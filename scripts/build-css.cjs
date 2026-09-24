// Genera css/style.min.css: fonts + font-awesome + animate + flaticon + style.css, con solo las reglas
// que usan las páginas y minificado, para servir un único CSS en lugar de tres.
// Uso: npm run build:css  (volver a ejecutarlo después de editar alguno de esos archivos)
// enhancements.css se sigue cargando aparte y se edita directamente.
const fs = require('fs');
const path = require('path');
const { PurgeCSS } = require('purgecss');
const csso = require('csso');

process.chdir(path.join(__dirname, '..'));

(async () => {
  const config = require('../purgecss.config.cjs');
  const resultados = await new PurgeCSS().purge(config);
  // PurgeCSS devuelve los archivos en el orden de config.css
  const unido = resultados.map((r) => r.css).join('\n');
  const minificado = csso.minify(unido).css;
  fs.writeFileSync('css/style.min.css', minificado);

  const kb = (bytes) => (bytes / 1024).toFixed(1) + ' KB';
  const original = config.css.reduce((total, f) => total + fs.statSync(f).size, 0);
  console.log('Fuentes (' + config.css.join(', ') + '): ' + kb(original));
  console.log('css/style.min.css: ' + kb(minificado.length));
})();
