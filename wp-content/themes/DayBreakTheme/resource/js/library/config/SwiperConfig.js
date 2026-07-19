// Swiper.js DEMO - https://swiperjs.com/demos

// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	Swiper の設定ファイル
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//
//	Top - Store 縦方向ループスライダー
//	autoplay の delay を 0、transition を linear にすることで
//	止まらず一定速度で流れ続ける（マーキー）挙動にする。
//	data-js-store-slider="down" の列は逆方向（下向き）に流す。
//	セレクタ文字列で渡すと最初の 1 要素しか初期化されないため、列ごとに生成する。
//
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

const storeSliderOptions = (reverse) => ({
  loop: true, // ループ有効
  direction: 'vertical',
  slidesPerView: 2.5, // 一度に表示する枚数
  speed: 6000, // ループの時間
  allowTouchMove: false, // スワイプ無効
  spaceBetween: 40,
  autoplay: {
    delay: 0, // 途切れなくループ
    reverseDirection: reverse, // true で下向きに流す
  },
});

// 上向き
new Swiper('[data-js-store-slider="up"]', storeSliderOptions(false));

// 下向き
new Swiper('[data-js-store-slider="down"]', storeSliderOptions(true));
