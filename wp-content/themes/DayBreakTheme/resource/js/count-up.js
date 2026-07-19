/**
 * `is-on` の付与をトリガーに、桁ごとのドラムを回してカウントアップさせるユーティリティ。
 *
 * 使い方:
 *   <span data-js-scroll-target="once" data-js-count-up data-duration="1600">
 *     <span class="c-deco-counter-number">99</span>
 *   </span>
 *
 * 属性仕様:
 *   data-js-count-up: 付与するだけで対象になる。
 *                     値に CSS セレクタを渡すと、その要素を数字カラムに置き換える
 *                     (既定: `.c-deco-counter-number`、無ければ要素自身)
 *   data-digits:      整数部の最小桁数 (既定 0 = 指定なし)
 *                     2 なら 00 から始まり 01,02... と 0 埋めしたまま回る。
 *                     3 なら 001,002...010、値が指定桁を超える場合は値の桁数が優先される
 *   data-duration:    アニメーション時間 (ms / 既定 1600)
 *
 * 見た目:
 *   数字は 1 桁ずつ縦カラム (0-9 + 折り返し用の 0 = 11 コマ) になっていて、
 *   カウントアップに合わせて下から新しい数字が上がり、古い数字が上へ抜けていく。
 *   最下位桁だけが連続回転し、上位桁は繰り上がる直前にだけ 1 コマ回る (独立型)。
 *   これにより上位桁が常時ぼやけず、停止時も必ず整数コマで止まる。
 *
 * 実装方針:
 *   - scroll-target.js には手を入れず、MutationObserver で class 属性を監視して発火する
 *     (ユーティリティ同士を独立させ、どちらか単体でも成立させるため)
 *   - init 時点で既に `is-on` が付いている要素も拾う (監視開始前に付与された場合の取りこぼし防止)
 *   - `once` でない場合、`is-on` が外れると 0 へ巻き戻る。方向が変わるたびに
 *     「現在値 → 新しい目標値」でトゥイーンを張り直すため、巻き戻し途中で再進入しても
 *     その位置から滑らかに繋がり、かつ必ず即座に動き出す
 *     (進行度を時間で往復させる方式だと easeOutExpo の平坦部で数字が動かなく見える)
 *   - 目標値は PHP が出力した textContent から読む。JS 無効時はその値がそのまま表示される
 *     (Progressive Enhancement)。目標値を退避してからカラムを構築する順序を守ること
 *   - 桁区切り (,) と小数点 (.) は回転しない固定セルとして描画する
 *   - 整数部の先頭ゼロは opacity で隠す (要素は残すのでレイアウトはズレない)。
 *     ただし data-digits で 0 埋めを指定した桁は隠さずに 0 のまま回す
 *   - strip の transform に CSS transition を掛けてはいけない。9→0 の折り返しは
 *     末尾の複製 0 コマからの 1 フレームジャンプで成立しており、transition があると
 *     そのジャンプが逆再生されて 9→0 が巻き戻って見える。滑らかさは rAF だけで作る
 *   - prefers-reduced-motion: reduce では最終値のまま何もしない
 *
 * @param {ParentNode} [root=document]
 * @returns {() => void} cleanup 関数
 */
export function initCountUp(root = document) {
  const targets = root.querySelectorAll('[data-js-count-up]');
  if (targets.length === 0) return () => { };

  if (
    typeof matchMedia === 'function' &&
    matchMedia('(prefers-reduced-motion: reduce)').matches
  ) {
    return () => { };
  }

  const DEFAULT_DURATION = 1600;
  const NUMBER_SELECTOR = '.c-deco-counter-number';

  // 0-9 の後ろに折り返し用の 0 を 1 つ足したコマ数。
  const CELL_COUNT = 11;

  // 上位桁が回り始める位置。0.9 = 繰り上がり直前の 10% だけで 1 コマ回る。
  const CARRY_THRESHOLD = 0.9;

  // 短い距離のトゥイーンでも最低これだけの時間比率は確保する (duration に対する割合)。
  const MIN_DURATION_RATIO = 0.35;

  // easeOutExpo: 序盤に一気に回り、終盤でゆっくり収束する
  const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

  /**
   * @typedef {{ strip: HTMLElement, cell: HTMLElement, place: number,
   *             position: number | null, blank: boolean }} Digit
   * @typedef {{ el: Element, output: Element | null, digits: Digit[],
   *             seps: Array<{ el: HTMLElement, place: number, blank: boolean }>,
   *             targetScaled: number, decimals: number, padPlaces: number, duration: number,
   *             value: number, from: number, to: number,
   *             startTime: number, tweenDuration: number,
   *             rafId: number | null }} Item
   */

  /** @type {Item[]} */
  const items = [];

  targets.forEach((el) => {
    const selector = el.getAttribute('data-js-count-up');
    const output =
      (selector && el.querySelector(selector)) ||
      el.querySelector(NUMBER_SELECTOR) ||
      el;

    // 目標値はカラムを組み立てる前に必ず退避する
    const raw = (output.textContent || '').trim();
    const target = Number(raw.replace(/,/g, ''));
    if (!Number.isFinite(target)) return;

    const dotIndex = raw.indexOf('.');
    const decimals = dotIndex === -1 ? 0 : raw.length - dotIndex - 1;
    const grouped = raw.includes(',');
    const duration = Number(el.getAttribute('data-duration')) || DEFAULT_DURATION;
    // 整数部の最小桁数。2 なら 00,01,02... と先頭を 0 埋めしたまま回す
    const minDigits = Math.max(0, Number(el.getAttribute('data-digits')) || 0);

    // 小数を扱うため、以降は「10^decimals 倍した整数」の桁として一律に処理する
    const targetScaled = Math.round(target * Math.pow(10, decimals));
    const item = {
      el,
      output: null, // build() で確定する
      digits: [],
      seps: [],
      targetScaled,
      decimals,
      // 0 埋めで常に表示する桁の範囲 (place がこの値未満なら先頭ゼロを隠さない)
      padPlaces: minDigits === 0 ? 0 : minDigits + decimals,
      duration,
      value: 0,
      from: 0,
      to: 0,
      startTime: 0,
      tweenDuration: duration,
      rafId: null,
    };

    build(item, output, grouped);
    if (item.digits.length === 0) return;

    items.push(item);
    render(item, 0);
  });

  if (items.length === 0) return () => { };

  /**
   * 数字カラムの DOM を組み立てて output の中身を差し替える。
   * @param {Item} item
   * @param {Element} output
   * @param {boolean} grouped
   */
  function build(item, output, grouped) {
    const digitCount = Math.max(String(item.targetScaled).length, item.padPlaces);
    const fragment = document.createDocumentFragment();

    // 左 (上位桁) から右 (下位桁) へ。place は 10 の何乗かを表す
    for (let place = digitCount - 1; place >= 0; place--) {
      const digit = document.createElement('span');
      digit.className = 'c-deco-counter-digit';

      const strip = document.createElement('span');
      strip.className = 'c-deco-counter-digit-strip';

      for (let n = 0; n < CELL_COUNT; n++) {
        const cell = document.createElement('span');
        cell.className = 'c-deco-counter-digit-cell';
        cell.textContent = String(n % 10);
        strip.appendChild(cell);
      }

      digit.appendChild(strip);
      fragment.appendChild(digit);
      // position / blank は前フレームの値。同じ値なら DOM に書かない
      item.digits.push({ strip, cell: digit, place, position: null, blank: false });

      // 小数点
      if (item.decimals > 0 && place === item.decimals) {
        fragment.appendChild(appendSep(item, '.', place));
      }

      // 桁区切り
      if (grouped && place > item.decimals && (place - item.decimals) % 3 === 0) {
        fragment.appendChild(appendSep(item, ',', place));
      }
    }

    output.textContent = '';
    output.appendChild(fragment);
    output.classList.add('is-columns');

    // will-change の付け外しはここに対して行う。
    // item.el 側に class を足すと自前の MutationObserver が反応してしまう
    item.output = output;
  }

  /**
   * 回転しない固定セル (小数点 / 桁区切り) を作る。
   * place は「この記号のすぐ左にある桁」= 先頭ゼロで一緒に隠すための基準。
   */
  function appendSep(item, char, place) {
    const sep = document.createElement('span');
    sep.className = 'c-deco-counter-sep';
    sep.textContent = char;
    item.seps.push({ el: sep, place, blank: false });
    return sep;
  }

  /**
   * scaled 値 (整数換算) を各カラムの位置に反映する。
   * @param {Item} item
   * @param {number} valueScaled
   * @param {boolean} [snap] 停止フレーム。必ず整数コマに揃える
   */
  function render(item, valueScaled, snap = false) {
    for (const digit of item.digits) {
      const unit = Math.pow(10, digit.place);
      const raw = valueScaled / unit;
      const base = Math.floor(raw) % 10;
      const frac = raw - Math.floor(raw);

      let position;

      if (snap) {
        position = Math.floor(item.targetScaled / unit) % 10;
      } else if (digit.place === 0) {
        // 最下位桁だけは連続回転
        position = base + frac;
      } else {
        // 上位桁は繰り上がり直前だけ回す
        position =
          base + Math.max(0, (frac - CARRY_THRESHOLD) / (1 - CARRY_THRESHOLD));
      }

      // 値が変わっていない桁 (上位桁は大半のフレームで不変) には書き込まない。
      // 同じ値でも style / class への代入はスタイル再計算を誘発するため
      if (digit.position !== position) {
        digit.strip.style.transform = `translateY(-${(position / CELL_COUNT) * 100}%)`;
        digit.position = position;
      }

      const blank = isBlankPlace(item, valueScaled, digit.place);
      if (digit.blank !== blank) {
        digit.cell.classList.toggle('is-blank', blank);
        digit.blank = blank;
      }
    }

    for (const sep of item.seps) {
      const blank = isBlankPlace(item, valueScaled, sep.place);
      if (sep.blank !== blank) {
        sep.el.classList.toggle('is-blank', blank);
        sep.blank = blank;
      }
    }
  }

  /**
   * その桁を先頭ゼロとして隠すか。
   * 0 埋め指定 (padPlaces) の範囲内は常に表示し、最小の整数桁も常に表示する。
   * @param {Item} item
   * @param {number} valueScaled
   * @param {number} place
   */
  function isBlankPlace(item, valueScaled, place) {
    if (place < item.padPlaces) return false;
    if (place <= item.decimals) return false;
    return Math.floor(valueScaled / Math.pow(10, place)) === 0;
  }

  /**
   * 現在値から to へ向かうトゥイーンを張り直す。
   *
   * 「進行度を時間で往復させる」方式だと、easeOutExpo は progress 0.3 の時点で
   * 既に値の 87% に達しているため、巻き戻しの前半で数字がほとんど動かない
   * (= 出入りを繰り返すと動いていないように見える)。
   * そこで方向が変わるたびに現在値を起点として張り直し、常に即座に動き出すようにする。
   *
   * @param {Item} item
   * @param {number} to 目標値 (scaled)
   */
  function playTo(item, to) {
    if (item.value === to) {
      if (item.rafId !== null) {
        cancelAnimationFrame(item.rafId);
        item.rafId = null;
        item.output.classList.remove('is-animating');
      }
      return;
    }

    item.from = item.value;
    item.to = to;
    item.startTime = performance.now();

    // 移動距離に応じて時間を縮める。短い出入りを繰り返しても間延びしない
    const ratio =
      item.targetScaled === 0 ? 1 : Math.abs(to - item.from) / item.targetScaled;
    item.tweenDuration = item.duration * Math.max(MIN_DURATION_RATIO, ratio);

    if (item.rafId !== null) return; // 走行中のループが新しい from/to を拾う

    const tick = (now) => {
      const progress = Math.min((now - item.startTime) / item.tweenDuration, 1);

      if (progress < 1) {
        item.value = item.from + (item.to - item.from) * easeOutExpo(progress);
        render(item, item.value);
        item.rafId = requestAnimationFrame(tick);
      } else {
        item.value = item.to;
        render(item, item.value, item.to === item.targetScaled);
        item.rafId = null;
        // 停止中はレイヤーを手放す
        item.output.classList.remove('is-animating');
      }
    };

    // アニメ中だけ will-change を有効にする
    item.output.classList.add('is-animating');
    item.rafId = requestAnimationFrame(tick);
  }

  // class 属性の変化を監視し、`is-on` の付け外しで再生 / 巻き戻しする。
  const observer = new MutationObserver((records) => {
    for (const record of records) {
      const item = items.find((i) => i.el === record.target);
      if (!item) continue;
      playTo(item, record.target.classList.contains('is-on') ? item.targetScaled : 0);
    }
  });

  items.forEach((item) => {
    observer.observe(item.el, { attributes: true, attributeFilter: ['class'] });
    // 監視開始前に付与されていた場合の取りこぼしを防ぐ
    if (item.el.classList.contains('is-on')) playTo(item, item.targetScaled);
  });

  return () => {
    observer.disconnect();
    items.forEach((item) => {
      if (item.rafId !== null) cancelAnimationFrame(item.rafId);
    });
  };
}
