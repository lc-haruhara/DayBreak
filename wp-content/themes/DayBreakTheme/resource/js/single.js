//single-page.js

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

  🍔 ページの読込み時

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
document.addEventListener("DOMContentLoaded", function () {

  /*---------------------------------------------------------------------
    ✅ 記事内にURLがあると自動でハイパーリンクに
  ---------------------------------------------------------------------*/
  // JavaScriptのクエリセレクタで要素を取得
  var elements = document.querySelectorAll("[data-js-auto-hyper-link]");

  // 各要素に対して処理を行う
  elements.forEach(function (elem) {
    // 要素のHTMLコンテンツを取得
    var str = elem.innerHTML;

    // URLを検出する正規表現パターン - aタグやiframeは除外
    var regexp_url = /((?<!href=["']|src=["'])\bhttps?:\/\/[a-zA-Z0-9.\-_@:/~?%&;=+#',()*!]+)\b/g;

    // 現在のドメインを取得（サブドメイン含む完全一致用）
    var currentHost = window.location.hostname;

    // ハイパーリンクを生成する関数
    function regexp_makeLink(url) {
      try {
        var urlObj = new URL(url);
        var isSameDomain = urlObj.hostname === currentHost;

        if (isSameDomain) {
          // 同一ドメイン → targetなし
          return '<a href="' + url + '" class="c-swe-link-text">' + url + '</a>';
        } else {
          // 別ドメイン → target="_blank" & rel="noopener noreferrer"
          return '<a href="' + url + '" target="_blank" rel="noopener noreferrer" class="c-swe-link-text">' + url + '</a>';
        }
      } catch (e) {
        // URLパース失敗時は元の文字列を返す
        return url;
      }
    }

    // 正規表現を使ってハイパーリンクを生成
    var textWithLink = str.replace(regexp_url, regexp_makeLink);

    // 要素のHTMLを更新
    elem.innerHTML = textWithLink;
  });


  /*---------------------------------------------------------------------
    ✅ 見出し (h2) が3つ以上ある場合は自動で目次を作成
  ---------------------------------------------------------------------*/
  let countId = 1;
  const targets = document.querySelectorAll("[data-js-table-of-contents-target]");

  targets.forEach(target => {
    const h2Elements = target.querySelectorAll("h2");

    if (h2Elements.length >= 3) { // 見出しが3つ以上の場合にメニューを生成

      // TOC（目次）のラッパーを作成
      const tocWrap = document.createElement("nav");
      tocWrap.className = "p-single-table-of-contents";

      // TOCの見出しを作成
      const tocHeading = document.createElement("div");
      tocHeading.className = "p-single-table-of-contents-heading";
      const tocHeadingBody = document.createElement("div");
      tocHeadingBody.className = "p-single-table-of-contents-heading-body";
      tocHeadingBody.textContent = "Table of Contents"; // 目次のタイトルを設定
      tocHeading.appendChild(tocHeadingBody);

      // TOCのリストを作成
      const list = document.createElement("ol");
      list.className = "p-single-table-of-contents-list-body";

      // 見出しをリストに追加
      h2Elements.forEach(h2 => {
        const headingText = h2.textContent;
        h2.id = `contents${countId++}`;

        const listItem = document.createElement("li");
        listItem.className = "p-single-table-of-contents-list-item";

        const link = document.createElement("a");
        link.className = "p-single-table-of-contents-list-item-link";
        link.href = `#${h2.id}`;

        const listItemBody = document.createElement("span");
        listItemBody.className = "p-single-table-of-contents-list-item-body";

        const linkNumber = document.createElement("span");
        linkNumber.className = "number";
        listItemBody.appendChild(linkNumber);

        const linkText = document.createElement("span");
        linkText.className = "text";
        linkText.textContent = headingText;
        listItemBody.appendChild(linkText);

        link.appendChild(listItemBody);
        listItem.appendChild(link);
        list.appendChild(listItem);
      });

      // 全ての要素をTOCラッパーに追加
      tocWrap.appendChild(tocHeading);
      tocWrap.appendChild(list);

      // 最初のh2要素の前にTOCを挿入
      target.insertBefore(tocWrap, h2Elements[0]);
    }
  });

});