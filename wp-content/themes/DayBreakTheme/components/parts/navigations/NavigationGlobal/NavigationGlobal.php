<nav class="c-navigation-global">
  <div class="c-navigation-global-body">

    <div class="c-navigation-global-logo">
      <?php
      $logo_link = (is_home() || is_front_page()) ? '#home' : home_url('/');
      ?>
      <a href="<?= esc_url($logo_link); ?>" class="">
        <?php
        C_Elements('LogoCommon', [
          'key' => 'value',
        ]);
        ?>
      </a>
    </div>

    <ul class="c-navigation-global-list">
      <?php C_Elements('ListMenu', [
        'pp' => false,
        'contact' => true,
        'english' => true,
      ]); ?>
    </ul>
  </div>
</nav>