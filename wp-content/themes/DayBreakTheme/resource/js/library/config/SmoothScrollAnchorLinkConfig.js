// smooth-scroll (AnchorLink) - https://github.com/cferdinandi/smooth-scroll
// - Easing - https://github.com/cferdinandi/smooth-scroll?tab=readme-ov-file#easing-options

// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	アンカーリンクをスムーススクロールさせるためのライブラリ
//	smooth-scroll の設定ファイル
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// SmoothScroll 本体（cferdinandi/smooth-scroll）が未読み込みの場合は何もしない。
// 未定義のまま new すると ReferenceError で app.js の後続処理（scroll-target 等）が止まるため、
// 読み込み時のみ初期化する。使う場合は EnqueueResources.php でライブラリを有効化すること。
if (typeof SmoothScroll !== 'undefined') {
	var scroll = new SmoothScroll('a[href*="#"]', {
		// Speed & Duration
		speed: 100, // Integer. Amount of time in milliseconds it should take to scroll 1000px
		speedAsDuration: true,
		// Easing - https://github.com/cferdinandi/smooth-scroll?tab=readme-ov-file#easing-options
		easing: 'easeInOutCubic', // Easing pattern to use
	});
}