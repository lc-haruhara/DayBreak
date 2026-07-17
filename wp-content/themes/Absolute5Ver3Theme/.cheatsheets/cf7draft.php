<div class="c-input-item">
  <label for="your-name" class="c-input-label">
    名前
  </label>
  <div class="c-input-item-input">
    [text* your-name id:your-name autocomplete:name placeholder " "]
    <span id="your-name-help" class="c-input-placeholder">
      <span class="c-input-placeholder-body">
        山田太郎
      </span>
  </div>
</div>

<div class="c-input-item">
  <label for="your-email" class="c-input-label">
    メールアドレス
  </label>
  <div class="c-input-item-input">
    [email* your-email id:your-email autocomplete:email placeholder " "]
    <span id="your-email-help" class="c-input-placeholder">
      <span class="c-input-placeholder-body">
        example@example.com
      </span>
  </div>
</div>

<div class="c-input-item">
  <label for="your-email-confirm" class="c-input-label">
    メールアドレス (確認用)
  </label>
  <div class="c-input-item-input">
    [email* your-email-confirm id:your-email-confirm autocomplete:off placeholder " "]
    <span id="your-email-confirm-help" class="c-input-placeholder">
      <span class="c-input-placeholder-body">
        同一のメールアドレスを入力してください
      </span>
  </div>
</div>

<div class="c-input-item">
  <label for="your-tel" class="c-input-label _any">
    電話番号
  </label>
  <div class="c-input-item-input">
    [tel your-tel id:your-tel autocomplete:tel placeholder " "]
    <span id="your-tel-help" class="c-input-placeholder">
      <span class="c-input-placeholder-body">
        090-9999-9999
      </span>
  </div>
</div>


<div class="c-input-item">
  <label for="your-select" class="c-input-label _any">
    セレクトボックス
  </label>
  <div class="c-input-item-input">
    [select* your-select id:your-select first_as_label "選択してください" "選択肢 1" "選択肢 2" "選択肢 3"]
  </div>
</div>

<fieldset class="c-input-radio">
  <legend class="c-input-label">
    ラジオボタン質問
  </legend>
  [radio your-example-radio use_label_element default:1 "選択肢 1" "選択肢 2" "選択肢 3"]
</fieldset>

<fieldset class="c-input-radio">
  <legend class="c-input-label">
    チェックボックス質問
  </legend>
  [checkbox your-example-checkbox use_label_element "選択肢 1" "選択肢 2" "選択肢 3"]
</fieldset>

<div class="c-input-item">
  <label for="your-contents" class="c-input-label _any">
    お問い合わせ内容
  </label>
  <div class="c-input-item-input">
    [textarea* your-contents id:your-contents autocomplete:contents placeholder " "]
    <span id="your-contents-help" class="c-input-placeholder">
      <span class="c-input-placeholder-body">
        お問い合わせ内容を入力してください
      </span>
  </div>
</div>


<div class="c-input-pp-confirm">

  <p class="c-input-pp-confirm-paragraph">
    <button class="c-btn-link _icon-right" type="button" data-modal-open="pp" aria-controls="modal-pp" aria-expanded="false" aria-label="プライバシーポリシーを確認する">
      プライバシーポリシー
    </button>
    の内容をご確認いただきご同意のうえ、送信ボタンを押してください。
  </p>

  <div class="c-input-radio">
    <label for="your-pp-confirm" class="c-input-radio-item">
      <span class="c-input-radio-item-body">
        <input type="checkbox" name="your-pp-confirm" id="your-pp-confirm">
        <span class="c-input-radio-item-elm"><span></span></span>
        <span class="c-input-radio-item-text">プライバシーポリシーに同意する</span>
      </span>
    </label>
  </div>

</div>

[submit id:submit]