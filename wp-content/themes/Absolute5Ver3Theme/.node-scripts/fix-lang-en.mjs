/**
 * fix-lang-en.mjs
 *
 * 必要パッケージ:
 * pnpm i glob parse5
 *
 * 実行例:
 * node .node-scripts/fix-lang-en.mjs
 *
 * このファイルのあるディレクトリではなく、
 * コマンドを実行したカレントディレクトリ配下を対象に走査します。
 *
 * 対象拡張子:
 * - .php
 * - .html
 * - .astro
 *
 * 除外:
 * - すでに lang がある要素
 * - aria-hidden="true" の要素
 * - script/style/pre/code/textarea/svg など
 * - PHP / Astro / テンプレート式を含む箇所
 *
 * 補足:
 * - 親要素ではなく「直下に英語テキストを持つ要素」にだけ lang="en" を付与します
 * - 例:
 *   <div><span>Purpose</span></div>
 *   → <span lang="en">Purpose</span>
 */

import fs from "fs";
import { glob } from "glob";
import * as parse5 from "parse5";

console.log("🚀 Script started");

/**
 * 対象パターン
 * あなたの target="_blank" 修正スクリプトと同じ感覚で、
 * カレントディレクトリ配下を再帰的に走査します。
 */
const patterns = ["**/*.php", "**/*.html", "**/*.astro"];

/**
 * 無視するディレクトリ
 */
const ignore = [
  "node_modules/**",
  "dist/**",
  "build/**",
  ".next/**",
];

/**
 * lang を付けないタグ
 */
const EXCLUDED_TAGS = new Set([
  "html",
  "head",
  "body",
  "script",
  "style",
  "pre",
  "code",
  "textarea",
  "svg",
  "math",
]);

/**
 * テンプレート構文を一時的に隠すための文字
 */
const MASK_CHAR = "§";

let updatedCount = 0;

/**
 * parse5 のノードが通常の要素ノードか判定
 */
function isElementNode(node) {
  return node && typeof node.nodeName === "string" && node.nodeName[0] !== "#";
}

/**
 * すでに lang 属性が付いているか
 */
function hasLangAttribute(node) {
  return Array.isArray(node.attrs) && node.attrs.some((attr) => attr.name === "lang");
}

/**
 * aria-hidden="true" なら対象外
 */
function hasAriaHiddenTrue(node) {
  return Array.isArray(node.attrs) &&
    node.attrs.some((attr) => {
      return (
        attr.name === "aria-hidden" &&
        String(attr.value).trim().toLowerCase() === "true"
      );
    });
}

/**
 * 空白を正規化
 */
function normalizeWhitespace(text) {
  return text.replace(/\s+/g, " ").trim();
}

/**
 * マスク済み文字を含むか
 */
function containsMaskedContent(text) {
  return text.includes(MASK_CHAR);
}

/**
 * 「英語っぽいテキスト」か判定
 *
 * 許可:
 * - ラテン文字
 * - 数字
 * - 句読点
 * - 記号
 * - 空白
 *
 * 非許可:
 * - 日本語などラテン文字以外の文字
 * - テンプレート構文でマスクされた内容
 */
function isAllowedEnglishLikeText(text) {
  const normalized = normalizeWhitespace(text);

  if (!normalized) return false;
  if (containsMaskedContent(normalized)) return false;

  let hasLatinLetter = false;

  for (const char of normalized) {
    if (/\s/u.test(char)) continue;
    if (/\p{Number}/u.test(char)) continue;
    if (/\p{Punctuation}/u.test(char)) continue;
    if (/\p{Symbol}/u.test(char)) continue;

    if (/\p{Letter}/u.test(char)) {
      if (!/\p{Script=Latin}/u.test(char)) {
        return false;
      }
      hasLatinLetter = true;
      continue;
    }

    return false;
  }

  return hasLatinLetter;
}

/**
 * source の指定範囲を MASK_CHAR で隠す
 * 改行は保持
 */
function maskRange(chars, start, end) {
  for (let i = start; i < end; i++) {
    if (chars[i] !== "\n" && chars[i] !== "\r") {
      chars[i] = MASK_CHAR;
    }
  }
}

/**
 * 正規表現にマッチした範囲をマスク
 */
function maskWithRegex(source, chars, regex) {
  for (const match of source.matchAll(regex)) {
    maskRange(chars, match.index, match.index + match[0].length);
  }
}

/**
 * Astro frontmatter をマスク
 */
function maskAstroFrontmatter(source, chars) {
  if (!source.startsWith("---")) return;

  const frontmatterMatch = source.match(/^---\r?\n[\s\S]*?\r?\n---/);
  if (frontmatterMatch) {
    maskRange(chars, 0, frontmatterMatch[0].length);
  }
}

/**
 * { ... } をネスト込みでマスク
 * Astro / template expression 対策
 */
function maskBalancedBraces(source, chars) {
  const len = source.length;

  for (let i = 0; i < len; i++) {
    if (source[i] !== "{") continue;

    let depth = 1;
    let j = i + 1;
    let quote = null;
    let escaped = false;

    while (j < len && depth > 0) {
      const ch = source[j];

      if (quote) {
        if (escaped) {
          escaped = false;
        } else if (ch === "\\") {
          escaped = true;
        } else if (ch === quote) {
          quote = null;
        }
        j++;
        continue;
      }

      if (ch === '"' || ch === "'" || ch === "`") {
        quote = ch;
        j++;
        continue;
      }

      if (ch === "{") {
        depth++;
        j++;
        continue;
      }

      if (ch === "}") {
        depth--;
        j++;
        continue;
      }

      j++;
    }

    if (depth === 0) {
      maskRange(chars, i, j);
      i = j - 1;
    }
  }
}

/**
 * 元ソースを parse5 用にマスクした文字列へ変換
 */
function createMaskedSource(source) {
  const chars = Array.from(source);

  // Astro frontmatter
  maskAstroFrontmatter(source, chars);

  // PHP
  maskWithRegex(source, chars, /<\?(?:php|=)?[\s\S]*?\?>/g);

  // EJS / ERB 系
  maskWithRegex(source, chars, /<%[\s\S]*?%>/g);

  // { ... }
  maskBalancedBraces(source, chars);

  return chars.join("");
}

/**
 * 開始タグの > の直前位置を取得
 * ここに lang="en" を差し込む
 */
function getInsertOffset(node) {
  const loc = node.sourceCodeLocation;
  if (!loc?.startTag?.endOffset) return null;
  return loc.startTag.endOffset - 1;
}

/**
 * その要素の「直下テキスト」だけ取得
 * 子要素の中身は拾わない
 */
function collectDirectText(node) {
  let text = "";

  if (!node.childNodes) return text;

  for (const child of node.childNodes) {
    if (child.nodeName === "#text") {
      text += child.value;
    }
  }

  return text;
}

/**
 * lang="en" を付けるべきか判定
 *
 * ルール:
 * - 要素ノードである
 * - 除外タグではない
 * - すでに lang がない
 * - aria-hidden="true" ではない
 * - 直下テキストが英語っぽい
 */
function shouldAddLang(node) {
  if (!isElementNode(node)) return false;
  if (EXCLUDED_TAGS.has(node.tagName)) return false;
  if (hasLangAttribute(node)) return false;
  if (hasAriaHiddenTrue(node)) return false;

  const directText = collectDirectText(node);
  return isAllowedEnglishLikeText(directText);
}

/**
 * ツリーを再帰で走査
 */
function walk(node, visitor) {
  if (!node) return;

  if (isElementNode(node)) {
    visitor(node);
  }

  if (!node.childNodes) return;

  for (const child of node.childNodes) {
    walk(child, visitor);
  }
}

/**
 * 元ソースへ lang="en" を後ろから差し込む
 * 後ろからやることで offset がズレない
 */
function applyInsertions(source, offsets) {
  const sorted = [...new Set(offsets)].sort((a, b) => b - a);
  let result = source;

  for (const offset of sorted) {
    result = result.slice(0, offset) + ' lang="en"' + result.slice(offset);
  }

  return result;
}

/**
 * 1ファイル分の変換処理
 */
function addLangToMarkup(source) {
  const masked = createMaskedSource(source);

  const document = parse5.parseFragment(masked, {
    sourceCodeLocationInfo: true,
  });

  const insertOffsets = [];

  walk(document, (node) => {
    if (!shouldAddLang(node)) return;

    const offset = getInsertOffset(node);
    if (offset == null) return;

    insertOffsets.push(offset);
  });

  if (insertOffsets.length === 0) {
    return { result: source, changed: 0 };
  }

  const result = applyInsertions(source, insertOffsets);

  return {
    result,
    changed: new Set(insertOffsets).size,
  };
}

/**
 * メイン処理
 */
for (const pattern of patterns) {
  const files = await glob(pattern, { ignore });
  console.log(`🔍 Pattern "${pattern}" matched ${files.length} files`);

  for (const file of files) {
    const content = fs.readFileSync(file, "utf8");
    const { result, changed } = addLangToMarkup(content);

    if (changed > 0) {
      fs.writeFileSync(file, result, "utf8");
      console.log(`✅ Updated: ${file} (${changed})`);
      updatedCount++;
    }
  }
}

console.log(`🎉 Done! ${updatedCount} file(s) updated.`);