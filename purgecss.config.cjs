// Configuración de PurgeCSS usada por scripts/build-css.cjs
// Lista las páginas a analizar y las clases que se añaden en tiempo de ejecución.
module.exports = {
  css: ['css/fonts.css', 'css/font-awesome.css', 'css/animate.css', 'css/flaticon.css', 'css/style.css'],
  content: ['*.html', '*.php', 'inc/**/*.php', 'js/**/*.js', 'blog-data/*.json'],
  safelist: {
    // Clases que añaden Bootstrap, los plugins jQuery o main.js en tiempo de ejecución
    standard: [/^show/, /^collaps/, /^fade/, /^active/, /^in$/, /^scrolled/, /^awake/, /^sleep/,
      /^ftco-animated/, /^item-animate/, /^fadeIn/, /^text-(success|danger)/, /^was-validated/, /^is-(in)?valid/,
      /^modal/, /^tooltip/, /^popover/, /^bs-/, /^dropdown/],
    greedy: [/mfp-/, /owl-/, /stellar/]
  },
  keyframes: true,
  // No purgar @font-face: PurgeCSS no ve las familias usadas vía 'font:' o var(--font-*)
  fontFace: false,
  variables: true
};
