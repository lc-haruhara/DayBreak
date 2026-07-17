// smooth-scroll (AnchorLink) - https://github.com/cferdinandi/smooth-scroll
// - Easing - https://github.com/cferdinandi/smooth-scroll?tab=readme-ov-file#easing-options

// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	アンカーリンクをスムーススクロールさせるためのライブラリ
//	smooth-scroll の設定ファイル
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

var scroll = new SmoothScroll('a[href*="#"]', {
	// Speed & Duration
	speed: 100, // Integer. Amount of time in milliseconds it should take to scroll 1000px
  speedAsDuration: true,
	// Easing - https://github.com/cferdinandi/smooth-scroll?tab=readme-ov-file#easing-options
	easing: 'easeInOutCubic', // Easing pattern to use
});