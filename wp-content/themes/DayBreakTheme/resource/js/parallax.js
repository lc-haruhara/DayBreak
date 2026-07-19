/**
 * スクロール進捗に応じて transform / opacity を追従させる汎用パララックス。
 *
 * 使い方:
 *   <div data-js-parallax="translateY:0->30">                     // 縦パララックス (30% = 要素高さの 30%)
 *   <div data-js-parallax="translateY:0->200px">                  // px を使いたい場合は明示
 *   <div data-js-parallax="translateY:0->-20 scale:1->1.1">       // 複数プロパティ
 *   <div data-js-parallax="translateY:0->100" data-scrub="0"      // scrub 0 = 1:1 同期
 *        data-range="enter">                                       // 進入区間のみ
 *
 * 属性仕様:
 *   data-js-parallax:  "prop:from->to[unit]" を半角スペース区切りで列挙
 *                      対応 prop: translateX / translateY / scale / rotate / opacity
 *                      単位省略時の既定: translate 系 = % (要素自身のサイズ基準)
 *                                        rotate = deg / scale・opacity = 無単位
 *                      px を使う場合は明示する (例: translateY:0->200px)
 *   data-scrub:        0-1 の追従係数 (既定 0.08 = GSAP scrub:1 相当の慣性)
 *                      0 を指定すると lerp を無効化して scroll と 1:1 同期
 *   data-range:        cover (既定) / enter / exit / "<start>vh-><end>vh"
 *                      - cover: 要素が viewport を通り抜ける全区間で 0→1
 *                      - enter: 要素が完全に画面内に入るまでで 0→1
 *                      - exit:  要素が画面から抜け始めてから完全に抜けるまでで 0→1
 *                      - "<start>vh-><end>vh": 要素上端が viewport 上端から
 *                        start vh の位置にある時 0、end vh の位置に来た時 1
 *                        例: "100vh->30vh" = 下端から入ってきて上から 30vh で完了
 *
 * 実装方針:
 *   - 単一 IntersectionObserver + 単一 rAF ループで全要素を捌く (要素 N でも FPS 影響なし)
 *   - viewport 外に出た要素は rAF ループから除外し、CPU コストを 0 に戻す
 *   - layout 由来の値 (layoutTop / height) は scroll 非依存なのでキャッシュし、
 *     tick では scrollY / innerHeight を 1 回読むだけにする (毎フレームの offsetParent 走査を排除)
 *     再計測は init / resize / viewport 進入時 / fonts.ready (FOUT reflow 補正) の 4 契機のみ
 *   - viewport 内の要素にだけ will-change: transform を付与し、transform を GPU 合成に載せる
 *     (画面外では解放してコンポジタメモリを節約)
 *   - prefers-reduced-motion: reduce では全 no-op (transform 未書き込み)
 *   - GSAP + ScrollTrigger (~47KB gzip) を置換する目的で自前実装。追加バンドルは ~1KB gzip
 *
 * @param {ParentNode} [root=document]
 * @returns {() => void} cleanup
 */
export function initParallax(root = document) {
  const targets = root.querySelectorAll('[data-js-parallax]');
  if (targets.length === 0) return () => { };

  if (
    typeof matchMedia === 'function' &&
    matchMedia('(prefers-reduced-motion: reduce)').matches
  ) {
    return () => { };
  }

  /**
   * @typedef {{
   *   el: HTMLElement,
   *   tweens: Array<{ prop: string, from: number, to: number, unit: string }>,
   *   scrub: number,
   *   range: 'cover' | 'enter' | 'exit' | 'viewport',
   *   startVh: number,
   *   endVh: number,
   *   current: number,
   *   target: number,
   *   visible: boolean,
   *   layoutTop: number,
   *   height: number,
   * }} Entry
   */

  /** @type {Entry[]} */
  const entries = [];

  /** @param {string} spec */
  const parseSpec = (spec) => {
    const tweens = [];
    for (const token of spec.trim().split(/\s+/)) {
      const m = token.match(/^(translateX|translateY|scale|rotate|opacity):(-?\d*\.?\d+)->(-?\d*\.?\d+)([a-z%]*)$/i);
      if (!m) continue;
      const [, prop, fromStr, toStr, unitRaw] = m;
      // 単位省略時の既定: translate 系は % (要素自身のサイズ基準の CSS translate 相当)、
      // rotate は deg、scale / opacity は無単位。px を使いたい場合は明示する (例: translateY:0->200px)。
      const unit = unitRaw || (prop === 'rotate' ? 'deg' : prop.startsWith('translate') ? '%' : '');
      tweens.push({ prop, from: parseFloat(fromStr), to: parseFloat(toStr), unit });
    }
    return tweens;
  };

  for (const el of targets) {
    const tweens = parseSpec(el.getAttribute('data-js-parallax') || 'translateY:0->30%');
    if (tweens.length === 0) continue;
    const scrubAttr = el.getAttribute('data-scrub');
    const scrub = scrubAttr === null ? 0.08 : Math.max(0, Math.min(1, parseFloat(scrubAttr)));
    const rangeAttr = el.getAttribute('data-range') || 'cover';
    let range = /** @type {'cover' | 'enter' | 'exit' | 'viewport'} */ ('cover');
    let startVh = 0;
    let endVh = 0;
    const viewportMatch = rangeAttr.match(/^(-?\d*\.?\d+)vh->(-?\d*\.?\d+)vh$/i);
    if (viewportMatch) {
      range = 'viewport';
      startVh = parseFloat(viewportMatch[1]);
      endVh = parseFloat(viewportMatch[2]);
    } else if (rangeAttr === 'enter' || rangeAttr === 'exit') {
      range = rangeAttr;
    }
    entries.push({ el, tweens, scrub, range, startVh, endVh, current: 0, target: 0, visible: false, layoutTop: 0, height: 0 });
  }

  if (entries.length === 0) return () => { };

  // getBoundingClientRect() は transform を反映するため、自身が scale される要素だと
  // rect.top が視覚境界に引きずられて progress 計算が正フィードバックで振動する。
  // offsetTop / offsetHeight は layout ベース (transform 非依存) なので、それらを使う。
  /** @param {HTMLElement} el */
  const getLayoutTop = (el) => {
    let top = 0;
    /** @type {HTMLElement | null} */
    let cur = el;
    while (cur) {
      top += cur.offsetTop;
      cur = /** @type {HTMLElement | null} */ (cur.offsetParent);
    }
    return top;
  };

  // レイアウト由来の値 (layoutTop / height) は scroll では変化しないため、
  // 毎フレームではなく「初期化 / resize / viewport 進入時」だけ計測してキャッシュする。
  // これにより tick ループから offsetParent チェーン走査を排除する。
  /** @param {Entry} entry */
  const measure = (entry) => {
    entry.layoutTop = getLayoutTop(entry.el);
    entry.height = entry.el.offsetHeight;
  };

  // tick ループ中に共有する 1 フレーム分の scroll / viewport 値。毎エントリで読まない。
  let frameScrollY = 0;
  let frameVh = 0;

  // ビューポート高さの安定値。モバイルのアドレスバー開閉でスクロール中に innerHeight が
  // 変動し、progress が飛ぶ（画像がガクッとずれる）のを防ぐため、毎フレーム生の
  // innerHeight を読まずこの値を使う。更新は syncStableVh() 経由で、幅変化
  // （画面回転・実リサイズ）またはしきい値超の高さ変化があった時だけ行う。
  let stableVh = innerHeight;
  let lastWidth = innerWidth;
  // これ以下の高さ変化はアドレスバー開閉相当とみなして無視する。
  // 画面回転は幅が変わるため、しきい値に関係なく別途反映される。
  const VH_UPDATE_THRESHOLD = 150;

  /** @param {Entry} entry */
  const computeProgress = (entry) => {
    const vh = frameVh;
    const rectTop = entry.layoutTop - frameScrollY;
    const rectHeight = entry.height;
    let p;
    if (entry.range === 'enter') {
      p = rectHeight === 0 ? 0 : (vh - rectTop) / rectHeight;
    } else if (entry.range === 'exit') {
      p = rectHeight === 0 ? 0 : -rectTop / rectHeight;
    } else if (entry.range === 'viewport') {
      const startPx = (entry.startVh * vh) / 100;
      const endPx = (entry.endVh * vh) / 100;
      const denom = startPx - endPx;
      p = denom === 0 ? 0 : (startPx - rectTop) / denom;
    } else {
      p = (vh - rectTop) / (vh + rectHeight);
    }
    return Math.max(0, Math.min(1, p));
  };

  /** @param {Entry} entry @param {number} p */
  const render = (entry, p) => {
    // 指定されていない transform 関数を含めると CSS 側の transform を意図せず上書きするため、
    // tweens に登場した prop だけを順に組み立てる。
    /** @type {string[]} */
    const parts = [];
    let opacity = null;
    for (const { prop, from, to, unit } of entry.tweens) {
      const v = from + (to - from) * p;
      if (prop === 'translateX') parts.push(`translateX(${v}${unit})`);
      else if (prop === 'translateY') parts.push(`translateY(${v}${unit})`);
      else if (prop === 'scale') parts.push(`scale(${v})`);
      else if (prop === 'rotate') parts.push(`rotate(${v}${unit})`);
      else if (prop === 'opacity') opacity = v;
    }
    if (parts.length > 0) entry.el.style.transform = parts.join(' ');
    if (opacity !== null) entry.el.style.opacity = String(opacity);
  };

  // computeProgress が参照する 1 フレーム分の scroll / viewport 値を更新する。
  // scrollY はフレーム先頭で 1 回読む。ビューポート高さは生の innerHeight ではなく
  // 安定値 stableVh を使い、アドレスバー開閉による高さ変動の影響を受けないようにする。
  const refreshFrame = () => {
    frameScrollY = scrollY;
    frameVh = stableVh;
  };

  // 実際にビューポート基準を更新すべき変化か判定し、必要な時だけ stableVh を更新する。
  // 幅が変わる（画面回転・実リサイズ）か、しきい値を超える高さ変化のみ採用し、
  // アドレスバー開閉相当の小さな高さ変化は無視する。
  const syncStableVh = () => {
    if (
      innerWidth !== lastWidth ||
      Math.abs(innerHeight - stableVh) > VH_UPDATE_THRESHOLD
    ) {
      lastWidth = innerWidth;
      stableVh = innerHeight;
    }
  };

  let rafId = 0;
  let ticking = false;

  const tick = () => {
    refreshFrame();
    let stillAnimating = false;
    for (const entry of entries) {
      if (!entry.visible) continue;
      entry.target = computeProgress(entry);
      if (entry.scrub === 0) {
        entry.current = entry.target;
      } else {
        entry.current += (entry.target - entry.current) * entry.scrub;
        if (Math.abs(entry.target - entry.current) < 0.0005) entry.current = entry.target;
        if (entry.current !== entry.target) stillAnimating = true;
      }
      render(entry, entry.current);
    }
    if (stillAnimating) {
      rafId = requestAnimationFrame(tick);
    } else {
      ticking = false;
    }
  };

  const startTicking = () => {
    if (ticking) return;
    ticking = true;
    rafId = requestAnimationFrame(tick);
  };

  // scroll では layout は動かないので再計測は不要。tick を起こすだけ。
  const onScroll = () => startTicking();

  const io = new IntersectionObserver((records) => {
    refreshFrame();
    for (const record of records) {
      const entry = entries.find((e) => e.el === record.target);
      if (!entry) continue;
      entry.visible = record.isIntersecting;
      if (record.isIntersecting) {
        // viewport 進入時に layout 値を測り直し、キャッシュのズレを最小化する。
        // あわせて compositor layer へ昇格させ (will-change)、transform のペイントを GPU に載せる。
        measure(entry);
        entry.el.style.willChange = 'transform';
        entry.target = computeProgress(entry);
        entry.current = entry.target;
        render(entry, entry.current);
      } else {
        // 画面外では compositor layer を解放し、無駄なメモリ占有を避ける。
        entry.el.style.willChange = '';
      }
    }
    if (entries.some((e) => e.visible)) startTicking();
  });

  refreshFrame();
  for (const entry of entries) {
    // 初期位置を IO 発火を待たずに同期適用し、JS 起動と初回 render の間にスナップが見える現象を防ぐ。
    measure(entry);
    entry.target = computeProgress(entry);
    entry.current = entry.target;
    render(entry, entry.current);
    io.observe(entry.el);
  }

  // resize でのみ layout 値が変わりうるため、全エントリを測り直す。
  const onResize = () => {
    for (const entry of entries) measure(entry);
    startTicking();
  };

  addEventListener('scroll', onScroll, { passive: true });
  addEventListener('resize', onResize);

  // Web フォント読み込み (FOUT reflow) は表示中でも layoutTop を動かしうるが、
  // resize も IO 再進入も伴わないためキャッシュがずれる。fonts.ready で 1 回測り直す。
  let disposed = false;
  if (typeof document !== 'undefined' && document.fonts && document.fonts.ready) {
    document.fonts.ready.then(() => {
      if (disposed) return;
      for (const entry of entries) measure(entry);
      startTicking();
    });
  }

  return () => {
    disposed = true;
    io.disconnect();
    removeEventListener('scroll', onScroll);
    removeEventListener('resize', onResize);
    for (const entry of entries) entry.el.style.willChange = '';
    if (rafId) cancelAnimationFrame(rafId);
  };
}
