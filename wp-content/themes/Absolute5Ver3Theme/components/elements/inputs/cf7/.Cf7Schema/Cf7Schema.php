<!--::::::::::::::::::::::::::::::::::::::::::::

  Contact Form Schema

  このファイルは、Contact Form 7 と同期するための
  「フォーム定義ファイル」です。

  管理画面の CF7 Sync から同期すると、この配列をもとに
  以下の内容が Contact Form 7 側へ反映されます。

  - フォームHTML
  - 受信メール設定
  - 自動返信メール設定
  - Additional Settings

  このファイル自体は「正本」で、
  Contact Form 7 の管理画面側は「同期先」という扱いです。

  ---------------------------------------------
  ■ 基本の使い方
  ---------------------------------------------

  1. fields に入力項目を定義する
  2. mail にメール関連の設定を書く
  3. 管理画面の「CF7 Sync」からこのフォームを同期する
  4. Contact Form 7 側へ反映された内容を確認する

  項目を変更した場合は、
  この schema ファイルを編集したあとに
  必ず CF7 Sync から再同期します。

  ---------------------------------------------
  ■ 各キーの意味
  ---------------------------------------------

  key
    フォームを識別するための一意なキーです。
    例: contact / recruit / reservation

  title
    CF7 Sync画面で表示する管理用タイトルです。

  cf7_id
    Contact Form 7 の投稿IDです。
    ※ ショートコードの文字列IDではなく、
       管理画面URLの post=数字 の数字を指定します。

    例:
    /wp-admin/admin.php?page=wpcf7&post=9&action=edit
    の場合は
    'cf7_id' => 9

  mail
    メール送信に関する設定です。

    admin_recipient
      管理者宛メールの送信先

    admin_intro
      受信メールの冒頭文

    reply_intro
      自動返信メールの冒頭文

    reply_footer
      自動返信メール末尾の補足文
      ※ 未設定時は components/utilities/schema.json から
         会社情報を読み取って出力します

  additional_settings
    CF7 の Additional Settings に同期する設定です。

  submit
    送信ボタンのラベルや id を設定します。

  fields
    各入力項目の定義です。
    component / name / label / type / required などを指定します。

  ---------------------------------------------
  ■ fields の見方
  ---------------------------------------------

  component
    使用する入力コンポーネント名です。
    例: InputFieldText / InputFieldEmail / InputFieldSelect / InputFieldChoice

  name
    CF7 のフォームタグ名です。
    メール本文でも [name] の形で参照されます。

  label
    画面表示用のラベルです。

  type
    CF7 の入力タイプです。
    例: text / email / tel / select / radio / checkbox / textarea

  required
    true で必須項目になります。

  autocomplete
    input の autocomplete 属性に使用します。

  placeholder
    CF7タグ内の placeholder 値です。
    デザイン都合で " " を入れる場合もあります。

  hint
    入力欄の補助テキスト表示に使います。

  mail
    true の場合、受信メール / 自動返信メール本文に出力します。
    確認用メールアドレスなど、本文に不要な項目は false にします。

  reply_to
    true の場合、管理者宛メールの Reply-To に使用します。
    通常はメールアドレス項目にのみ付けます。

  confirm_for
    確認用フィールドが、どの項目と一致すべきかを指定します。
    例: 'confirm_for' => 'your-email'

  options
    select / radio / checkbox の選択肢です。

  default
    radio の初期選択値です。

  first_option_label
    select の先頭案内文です。

  ---------------------------------------------
  ■ 会社情報について
  ---------------------------------------------

  自動返信メール末尾の会社情報は、
  components/utilities/schema.json を参照して自動生成します。

  参照する主な値:
    - organization.name
    - organization.address.postalCode
    - organization.address.addressLocality
    - organization.address.streetAddress
    - organization.contactPoint.telephone

  これらの値が空の場合は、その項目は出力しません。
  たとえば telephone が空なら、TEL行は出力されません。

  会社情報を schema.json から自動で出したくない場合は、
  mail.reply_footer を schema 側に明示的に設定してください。

  ---------------------------------------------
  ■ 複数コンタクトフォームがある場合
  ---------------------------------------------

  複数フォームがある場合は、
  1フォームにつき1つの schema ファイルを作成します。

  例:
    contact.php
    recruit.php
    reservation.php

  それぞれで
    - key
    - title
    - cf7_id
    - mail
    - fields
  を個別に持たせます。

  例:
    contact.php    -> cf7_id = 9
    recruit.php    -> cf7_id = 12
    reserve.php    -> cf7_id = 15

  CF7 Sync画面には schema ごとに同期ボタンが表示されるため、
  フォーム単位で個別に同期できます。

  つまり、
  「1つの schema で複数フォームを管理する」のではなく、
  「1フォーム = 1schema」で管理するのが基本です。

  ---------------------------------------------
  ■ 運用上の注意
  ---------------------------------------------

  - Contact Form 7 管理画面で直接編集すると、
    この schema ファイルとの内容がズレる可能性があります
  - 項目を変更したら、schema を編集したあとに
    CF7 Sync から再同期してください
  - cf7_id は必ず対象フォームの投稿IDを設定してください
  - 確認用項目や同意項目など、メール本文に不要なものは
    mail => false にしてください

::::::::::::::::::::::::::::::::::::::::::::-->

<?php

return [
  // フォーム識別用キー
  'key'   => 'contact',

  // CF7 Sync画面などで表示するタイトル
  'title' => 'Contact',

  // Contact Form 7 の投稿ID
  'cf7_id' => 9,

  // メール設定
  'mail' => [
    'admin_recipient' => 'kato@launchcraft.jp',
    // 管理者宛メール件名
    'admin_subject'   => '【[_site_title]】お問い合わせを受信しました。',
    // 受信メールの冒頭の一文
    'admin_intro'     => '[_site_title] のコンタクトフォームから以下の内容でお問合せが届きました。',
    // 自動返信メール件名
    'reply_subject'   => '【[_site_title]】お問い合わせありがとうございます。',
    // 自動返信メールの冒頭の一文
    'reply_intro'     => 'この度はお問合せありがとうございます。',
  ],

  // CF7 の Additional Settings に入れる値
  'additional_settings' => [
    'acceptance_as_validation: on',
  ],

  // 送信ボタン設定
  'submit' => [
    'label' => '送信',
    'id'    => 'submit',
  ],

  // フィールド定義
  'fields' => [
    [
      // 使用するコンポーネント名
      'component'    => 'InputFieldText',
      // CF7 の name
      'name'         => 'your-name',
      // ラベル表示
      'label'        => '名前',
      // CF7 の入力タイプ
      'type'         => 'text',
      // 必須かどうか
      'required'     => true,
      // input の autocomplete 属性
      'autocomplete' => 'name',
      // CF7タグ内の placeholder 値
      'placeholder'  => ' ',
      // 補助テキスト表示用
      'hint'         => '山田太郎',
      // メール本文に含めるかどうか
      'mail'         => true,
    ],
    [
      'component'    => 'InputFieldEmail',
      'name'         => 'your-email',
      'label'        => 'メールアドレス',
      'type'         => 'email',
      'required'     => true,
      'autocomplete' => 'email',
      'placeholder'  => ' ',
      'hint'         => 'example@example.com',
      'mail'         => true,
      // 管理者メールの Reply-To に使う項目
      'reply_to'     => true,
    ],
    [
      'component'    => 'InputFieldEmail',
      'name'         => 'your-email-confirm',
      'label'        => 'メールアドレス (確認用)',
      'type'         => 'email',
      'required'     => true,
      'autocomplete' => 'off',
      'placeholder'  => ' ',
      'hint'         => '同一のメールアドレスを入力してください',
      // 確認用なのでメール本文には出さない
      'mail'         => false,
      // どの項目と一致チェックするか
      'confirm_for'  => 'your-email',
    ],
    [
      // tel でも Text コンポーネントを使う
      'component'    => 'InputFieldText',
      'name'         => 'your-tel',
      'label'        => '電話番号',
      'type'         => 'tel',
      'required'     => false,
      'autocomplete' => 'tel',
      'placeholder'  => ' ',
      'hint'         => '090-9999-9999',
      'mail'         => true,
    ],
    [
      'component'          => 'InputFieldSelect',
      'name'               => 'your-select',
      'label'              => 'セレクトボックス',
      'type'               => 'select',
      'required'           => true,
      // 先頭の案内文
      'first_option_label' => '選択してください',
      // 選択肢
      'options'            => ['選択肢 1', '選択肢 2', '選択肢 3'],
      'mail'               => true,
    ],
    [
      // radio / checkbox 共通コンポーネント
      'component' => 'InputFieldChoice',
      'name'      => 'your-example-radio',
      'label'     => 'ラジオボタン質問',
      'type'      => 'radio',
      'required'  => false,
      'options'   => ['選択肢 1', '選択肢 2', '選択肢 3'],
      // 初期選択
      'default'   => '選択肢 1',
      'mail'      => true,
    ],
    [
      'component' => 'InputFieldChoice',
      'name'      => 'your-example-checkbox',
      'label'     => 'チェックボックス質問',
      'type'      => 'checkbox',
      'required'  => true,
      'options'   => ['選択肢 1', '選択肢 2', '選択肢 3'],
      'mail'      => true,
    ],
    [
      'component'    => 'InputFieldTextarea',
      'name'         => 'your-contents',
      'label'        => 'お問い合わせ内容',
      'type'         => 'textarea',
      'required'     => true,
      'autocomplete' => 'contents',
      'placeholder'  => ' ',
      'hint'         => 'お問い合わせ内容を入力してください',
      'mail'         => true,
    ],
    [
      'component' => 'FormPrivacyConsent',
      'name'      => 'your-pp-confirm',
      'label'     => 'プライバシーポリシーに同意する',
      'type'      => 'privacy-consent',
      'mail'      => false,
    ],
  ],
];
