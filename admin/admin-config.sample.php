<?php
// Copia este archivo como "admin-config.php" (misma carpeta) — ese SÍ no se sube a git.
//
// Para generar el hash de tu propia contraseña, ejecuta en una terminal con PHP instalado:
//   php -r "echo password_hash('TU_CONTRASEÑA_AQUI', PASSWORD_DEFAULT), PHP_EOL;"
// y pega el resultado abajo en 'password_hash'. Nunca guardes la contraseña en texto plano.

return [
    'password_hash' => '$2y$10$REEMPLAZA.CON.TU.PROPIO.HASH.GENERADO.ARRIBA',
];
