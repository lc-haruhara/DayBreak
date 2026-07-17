<?php

//==================================================
// Contact Form 7 自動 <p> 無効化
//==================================================
add_filter('wpcf7_autop_or_not', '__return_false');


//==================================================
// Radio / Checkbox 構造を再構築
//==================================================
add_filter('wpcf7_form_elements', function ($content) {

  if (
    strpos($content, 'wpcf7-radio') === false &&
    strpos($content, 'wpcf7-checkbox') === false
  ) {
    return $content;
  }

  libxml_use_internal_errors(true);

  $dom = new DOMDocument('1.0', 'UTF-8');
  $dom->loadHTML('<?xml encoding="UTF-8">' . $content);
  $xpath = new DOMXPath($dom);

  $groups = $xpath->query("
    //span[contains(@class,'wpcf7-radio')]
    |
    //span[contains(@class,'wpcf7-checkbox')]
  ");

  foreach ($groups as $group) {

    $items = $xpath->query(".//span[contains(@class,'wpcf7-list-item')]", $group);
    if (!$items) continue;

    $index = 1;

    foreach ($items as $item) {

      $inputNode = $xpath->query(".//input", $item);
      if (!$inputNode || $inputNode->length === 0) continue;

      $input = $inputNode->item(0);
      if (!($input instanceof DOMElement)) continue;

      $type = $input->getAttribute('type');
      if ($type !== 'radio' && $type !== 'checkbox') continue;

      $name  = $input->getAttribute('name');
      $value = $input->getAttribute('value');
      if (!$name) continue;

      $cleanName = preg_replace('/[\[\]]+/', '', $name);
      $id = $cleanName . '-' . $index;
      $input->setAttribute('id', $id);

      $labelText = trim($item->textContent);
      if (!$labelText) $labelText = $value;

      $newLabel = $dom->createElement('label');
      $newLabel->setAttribute('for', $id);
      $newLabel->setAttribute('class', 'c-input-radio-item');

      $body = $dom->createElement('span');
      $body->setAttribute('class', 'c-input-radio-item-body');

      $elm = $dom->createElement('span');
      $elm->setAttribute('class', 'c-input-radio-item-elm');
      $elm->appendChild($dom->createElement('span'));

      $text = $dom->createElement('span');
      $text->setAttribute('class', 'c-input-radio-item-text');
      $text->appendChild($dom->createTextNode($labelText));

      $body->appendChild($input->cloneNode(true));
      $body->appendChild($elm);
      $body->appendChild($text);

      $newLabel->appendChild($body);

      $group->replaceChild($newLabel, $item);

      $index++;
    }
  }

  $html = $dom->saveHTML();
  $html = preg_replace('/^<!DOCTYPE.+?>/', '', $html);
  $html = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $html);

  return $html;
}, 20);


//==================================================
// select の空 option を disabled selected に
//==================================================
add_filter('wpcf7_form_elements', function ($content) {

  return preg_replace_callback(
    '/<select[^>]*>.*?<\/select>/s',
    function ($matches) {
      return preg_replace(
        '/<option\s+value=""/',
        '<option value="" disabled selected',
        $matches[0],
        1
      );
    },
    $content
  );
}, 30);


//==================================================
// input / select / textarea を c-input-item-input-body で囲む
//==================================================
add_filter('wpcf7_form_elements', function ($content) {

  libxml_use_internal_errors(true);

  $dom = new DOMDocument('1.0', 'UTF-8');
  $dom->loadHTML('<?xml encoding="UTF-8">' . $content);
  $xpath = new DOMXPath($dom);

  $nodes = $xpath->query("
    //span[contains(@class,'wpcf7-form-control-wrap')]/*
    [self::input or self::textarea or self::select]
  ");

  $exclude = ['radio', 'checkbox', 'hidden', 'submit', 'button', 'file', 'reset'];

  foreach ($nodes as $node) {

    if (!($node instanceof DOMElement)) continue;

    if ($node->tagName === 'input') {
      $type = strtolower($node->getAttribute('type'));
      if (in_array($type, $exclude, true)) {
        continue;
      }
    }

    if (
      $node->parentNode instanceof DOMElement &&
      strpos($node->parentNode->getAttribute('class'), 'c-input-item-input-body') !== false
    ) {
      continue;
    }

    $wrapper = $dom->createElement('span');
    $wrapper->setAttribute('class', 'c-input-item-input-body');

    $node->parentNode->insertBefore($wrapper, $node);
    $wrapper->appendChild($node);
  }

  $html = $dom->saveHTML();
  $html = preg_replace('/^<!DOCTYPE.+?>/', '', $html);
  $html = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $html);

  return $html;
}, 60);


//==================================================
// aria-describedby 自動付与
//==================================================
add_filter('wpcf7_form_elements', function ($content) {

  $exclude = ['radio', 'checkbox', 'hidden', 'submit', 'button', 'file', 'reset'];

  return preg_replace_callback(
    '/<(input|textarea|select)\b[^>]*>/i',
    function ($matches) use ($exclude) {

      $tag = $matches[0];

      if (preg_match('/type="([^"]+)"/i', $tag, $typeMatch)) {
        if (in_array(strtolower($typeMatch[1]), $exclude, true)) {
          return $tag;
        }
      }

      if (!preg_match('/name="([^"]+)"/i', $tag, $nameMatch)) {
        return $tag;
      }

      if (strpos($tag, 'aria-describedby=') !== false) {
        return $tag;
      }

      $name = $nameMatch[1];

      return str_replace(
        '>',
        ' aria-describedby="' . esc_attr($name) . '-help">',
        $tag
      );
    },
    $content
  );
}, 100);


//==================================================
// 確認用メールアドレス バリデーション
//==================================================
function wpcf7_custom_email_validation_filter($result, $tag)
{
  if ('your-email-confirm' === $tag->name) {

    $email   = isset($_POST['your-email']) ? trim($_POST['your-email']) : '';
    $confirm = isset($_POST['your-email-confirm']) ? trim($_POST['your-email-confirm']) : '';

    if ($email !== $confirm) {
      $result->invalidate($tag, "メールアドレスが一致しません");
    }
  }

  return $result;
}

add_filter('wpcf7_validate_email',  'wpcf7_custom_email_validation_filter', 20, 2);
add_filter('wpcf7_validate_email*', 'wpcf7_custom_email_validation_filter', 20, 2);
