// 	SmoothScroll.js (Page) - https://github.com/gblazex/smoothscroll-for-websites

// Description :::::::::::::::::::::::::::::::::::::::::::::::
//	ページを慣性スクロールさせるためのライブラリ
//	SmoothScroll.js の設定ファイル
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

SmoothScroll({
	frameRate: 150,
	animationTime : 700,
	stepSize         : 150,
	accelerationDelta : 0,  // 加速度 - 推奨0
	accelerationMax   : 0,   // 加速度最高値 - 推奨0
})