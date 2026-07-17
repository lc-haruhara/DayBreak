<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::

DialogCookie の Props 一覧
(components/parts/dialogs/DialogCookie/DialogCookie.php)

Cookie 同意バナーを出力します。
引数は不要です。

機能概要（インライン script で同梱）:
- localStorage に同意情報（granted/expires）を保存
- 同意済み・未同意・期限切れを自動判定
- 同意後に Google Analytics (GA4) を動的ロード
- GA4 Consent Mode v2 に対応
- 180 日後に再表示
- デバッグモード（DEBUG_MODE = true）で毎回表示

設定箇所:
- DialogCookie.php 内の GA_MEASUREMENT_ID を実際の GA ID に変更してください
  例: const GA_MEASUREMENT_ID = 'G-XXXXXXXXXX'; → 'G-ABCDEF1234'

ボタン:
- 「拒否する」（id="cookie-deny-btn"）
- 「同意」（id="cookie-accept-btn"）

依存関係:
- Link コンポーネント（プライバシーポリシーリンク用）
- ButtonLink コンポーネント（ボタン用）

通常は MountContentsCommon.php で呼び出されます。

::::::::::::::::::::::::::::::::::::::::::::
*/
?>

<!-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::
  基本：引数なし
::::::::::::::::::::::::::::::::::::::::::::::::::::::: -->
<?php
C_Parts('DialogCookie');
?>
