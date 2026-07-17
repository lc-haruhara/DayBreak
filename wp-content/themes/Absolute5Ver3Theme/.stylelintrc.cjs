module.exports = {
  extends: [
    "stylelint-config-standard-scss",
    "stylelint-config-recess-order"
  ],
  plugins: ["stylelint-order"],
  customSyntax: "postcss-scss",

  rules: {
    "order/order": [
      "custom-properties",
      "declarations"
    ],

    "scss/dollar-variable-pattern": null,
    "scss/at-mixin-pattern": null,
    "keyframes-name-pattern": null,

    "property-no-vendor-prefix": null,
    "selector-no-vendor-prefix": null,

    "scss/comment-no-empty": null,
    "scss/double-slash-comment-whitespace-inside": null,

    "color-function-notation": null,
    "alpha-value-notation": null,
    "hue-degree-notation": null,

    "import-notation": null,

    "declaration-empty-line-before": null,
    "at-rule-empty-line-before": null,
    "rule-empty-line-before": null,

    "selector-attribute-quotes": null,
    "selector-class-pattern": null,

    "block-no-empty": null,
    "no-invalid-position-declaration": null,
    "font-family-no-missing-generic-family-keyword": null,
    "property-no-deprecated": null,

    "scss/double-slash-comment-empty-line-before": null,
    "comment-empty-line-before": null,
    "comment-whitespace-inside": null,

    "color-hex-length": null,
    "color-function-alias-notation": null,

    "value-keyword-case": null,
    "length-zero-no-unit": null,

    "declaration-block-no-redundant-longhand-properties": null,
    "shorthand-property-no-redundant-values": null,

    "scss/at-mixin-argumentless-call-parentheses": null,
    "function-url-quotes": null,

    "selector-id-pattern": null,
    "scss/at-extend-no-missing-placeholder": null,
    "scss/operator-no-unspaced": null
  },

  overrides: [
    {
      files: ["**/*.astro"],
      customSyntax: "postcss-html"
    }
  ]
};