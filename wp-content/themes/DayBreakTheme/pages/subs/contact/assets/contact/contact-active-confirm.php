<?php
C_Elements('ButtonLink', [
  'text'  => '確認する',
  'icon'  => 'chevron_forward',
  'aria'  => [
    'controls' => 'modal-confirm',
    'expanded' => 'false',
    'label'    => '送信内容を確認する'
  ],
  'data' => [
    'modal-open' => 'confirm',
  ],
]);
?>

<!--::::::::::::::::::::::::::::::::::::::::::::

  Scripts

::::::::::::::::::::::::::::::::::::::::::::-->
<style>
  input[type="submit"] {
    display: none;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    function syncValue(inputSelector, outputId, type = 'text') {
      const inputs = document.querySelectorAll(inputSelector);
      const output = document.getElementById(outputId);
      if (!output) return;

      function update() {
        if (type === 'checkbox') {
          const values = Array.from(inputs)
            .filter(el => el.checked)
            .map(el => el.value);
          output.textContent = values.join(' / ');
        } else if (type === 'radio') {
          const checked = Array.from(inputs).find(el => el.checked);
          output.textContent = checked ? checked.value : '';
        } else {
          output.textContent = inputs[0].value;
        }
      }

      inputs.forEach(el => {
        el.addEventListener('input', update);
        el.addEventListener('change', update);
      });

      update(); // 初期表示用
    }

    syncValue('#your-name', 'confirm-name');
    syncValue('#your-email', 'confirm-email');
    syncValue('#your-tel', 'confirm-tel');
    syncValue('#your-select', 'confirm-select');
    syncValue('input[name="your-example-radio"]', 'confirm-radio', 'radio');
    syncValue('input[name="your-example-checkbox[]"]', 'confirm-checkbox', 'checkbox');
    syncValue('#your-contents', 'confirm-contents');

  });
</script>


<script>
  //::::::::::::::::::::::::::::::::
  // ButtonDisabled
  //::::::::::::::::::::::::::::::::
  document.addEventListener('DOMContentLoaded', function() {

    const checkbox = document.getElementById('your-pp-confirm');
    const confirmBtn = document.querySelector('[data-modal-open="confirm"]');
    const requiredFields = document.querySelectorAll('[aria-required="true"]');

    if (!checkbox || !confirmBtn) return;

    function isRequiredFilled() {
      return Array.from(requiredFields).every(el => el.value.trim() !== '');
    }

    function updateConfirmState() {

      const isChecked = checkbox.checked;
      const requiredFilled = isRequiredFilled();

      const enable = isChecked && requiredFilled;

      confirmBtn.setAttribute('aria-disabled', String(!enable));
      confirmBtn.disabled = !enable;
    }

    // 入力監視
    requiredFields.forEach(el => {
      el.addEventListener('input', updateConfirmState);
      el.addEventListener('change', updateConfirmState);
    });

    // チェック監視
    checkbox.addEventListener('change', updateConfirmState);

    // 初期状態
    updateConfirmState();

  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // モーダル内「送信する」ボタン → 隠れた #submit を叩いて送信し、モーダルを閉じる
    document.querySelectorAll('[data-submit-trigger]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = this.getAttribute('data-submit-trigger');
        var target = document.getElementById(id);
        if (target && typeof target.click === 'function') {
          target.click();
          if (typeof window.closeAllModals === 'function') {
            window.closeAllModals();
          }
        }
      });
    });
  });
</script>