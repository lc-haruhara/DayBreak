<?php
$analytics = $args['analytics'] ?? false;
$turnstile = $args['turnstile'] ?? false;
?>

<ul class="c-list-pp">

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">個人情報の利用目的</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトでは、お問い合わせなどの際に、お名前、メールアドレス、電話番号等の個人情報をご入力いただく場合があります。
      取得した個人情報は、以下の目的のために利用いたします。
    </p>
    <ul class="c-list-dot">
      <li class="c-list-dot-item">お問い合わせへの回答</li>
      <li class="c-list-dot-item">ご依頼いただいたサービスの提供およびご連絡</li>
      <li class="c-list-dot-item">必要な情報のご案内</li>
      <li class="c-list-dot-item">サービス向上のための分析</li>
    </ul>
  </li>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">個人情報の第三者提供</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトでは、次の場合を除いて、取得した個人情報を第三者に提供することはありません。
    </p>
    <ul class="c-list-dot">
      <li class="c-list-dot-item">本人の同意がある場合</li>
      <li class="c-list-dot-item">法令に基づき開示が必要となる場合</li>
    </ul>
  </li>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">個人情報の管理</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトは、個人情報への不正アクセス、紛失、漏えいなどを防止するため、適切な安全管理措置を講じます。
    </p>
  </li>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">Cookie（クッキー）について</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトでは、サービス向上や利便性の向上のため、Cookieを使用する場合があります。
      Cookieとは、サイトを利用した際にブラウザに保存される情報であり、個人を特定するものではありません。
      ブラウザの設定により、Cookieの使用を拒否することも可能です。
    </p>
  </li>

  <?php if ($analytics) : ?>
    <li class="c-list-pp-item">
      <h3 class="c-list-pp-item-heading">アクセス解析ツールについて</h3>
      <p class="c-list-pp-item-paragraph">
        当サイトでは、サイトの利用状況を把握するために Google が提供するアクセス解析ツール
        「Google Analytics」を利用しています。
      </p>
      <p class="c-list-pp-item-paragraph">
        Google Analytics はトラフィックデータ収集のために Cookie を使用しています。
        このトラフィックデータは匿名で収集されており、個人を特定するものではありません。
      </p>
      <p class="c-list-pp-item-paragraph">
        Google Analytics の利用規約については
        <?php
        C_Elements('Link', [
          'text' => 'こちら',
          'href' => 'https://marketingplatform.google.com/about/analytics/terms/jp/',
        ]);
        ?>
        をご確認ください。
      </p>
    </li>
  <?php endif; ?>

  <?php if ($turnstile) : ?>
    <li class="c-list-pp-item">
      <h3 class="c-list-pp-item-heading">スパム対策について</h3>
      <p class="c-list-pp-item-paragraph">
        当サイトでは、お問い合わせフォームのスパム対策のため Cloudflare が提供する「Turnstile」を利用しています。
        Turnstileは、不正なアクセスや自動化されたボットからサイトを保護するためのセキュリティサービスです。
        このサービスの利用により、IPアドレス等の情報が Cloudflare に送信される場合があります。
      </p>
      <p class="c-list-pp-item-paragraph">
        Cloudflareのプライバシーポリシーについては
        <?php
        C_Elements('Link', [
          'text' => 'こちら',
          'href' => 'https://www.cloudflare.com/privacypolicy/',
        ]);
        ?>
        をご確認ください。
      </p>
    </li>
  <?php endif; ?>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">SSL（HTTPS）による通信の暗号化</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトでは、SSL（Secure Socket Layer）による暗号化通信を使用しています。
      お問い合わせフォームなどで入力された個人情報は、SSLによって暗号化され、安全に送信されます。
    </p>
  </li>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">免責事項</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトに掲載している情報については、できる限り正確な内容を提供するよう努めておりますが、
      その正確性や安全性を保証するものではありません。
      当サイトの情報を利用することで生じたいかなる損害についても、一切の責任を負いかねます。
    </p>
    <p class="c-list-pp-item-paragraph">
      また、リンクやバナーなどにより他サイトへ移動した場合、移動先サイトで提供される情報やサービスについては責任を負いません。
    </p>
  </li>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">プライバシーポリシーの変更</h3>
    <p class="c-list-pp-item-paragraph">
      当サイトは、必要に応じて本ポリシーの内容を変更することがあります。
      変更後のプライバシーポリシーは、本ページに掲載した時点で効力を生じるものとします。
    </p>
  </li>

  <li class="c-list-pp-item">
    <h3 class="c-list-pp-item-heading">お問い合わせ先</h3>
    <p class="c-list-pp-item-paragraph">
      当プライバシーポリシーに関するお問い合わせ、苦情、相談につきましては、以下の窓口までご連絡ください。
      <span class="c-list-pp-item-paragraph-schema">
        <?php
        $schema_path = get_theme_file_path('/components/utilities/schema.json');
        $schema      = file_exists($schema_path) ? json_decode(file_get_contents($schema_path), true) : [];
        $org         = $schema['organization'] ?? [];
        $addr        = $org['address'] ?? [];
        $tel         = $org['contactPoint']['telephone'] ?? '';
        ?>
        <?php if (!empty($org['name'])) : ?>
          <?php echo esc_html($org['name']); ?><br>
        <?php endif; ?>
        <?php if ($addr) : ?>
          〒<?php echo esc_html($addr['postalCode'] ?? ''); ?>
          <?php echo esc_html(($addr['addressRegion'] ?? '') . ($addr['addressLocality'] ?? '') . ($addr['streetAddress'] ?? '')); ?><br>
        <?php endif; ?>
        <?php if ($tel) : ?>
          TEL: <?php echo esc_html($tel); ?><br>
        <?php endif; ?>
        <?php
        C_Elements('Link', [
          'text' => 'お問い合わせフォーム',
          'href' => '/contact/',
        ]);
        ?>
      </span>
    </p>
  </li>

  <?php
  $modified_date = date('Y年n月j日', filemtime(__FILE__));
  ?>
  <li class="c-list-pp-date-item">
    <p class="c-list-pp-date-item-paragraph">
      制定日：2026年4月12日<br>
      最終改定日：2026年4月13日
    </p>
  </li>

</ul>