<?php
require_once __DIR__ . '/auth.php';
require_login();

$posts = load_posts();
usort($posts, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

$deleted = isset($_GET['deleted']);
$saved = isset($_GET['saved']);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel del Blog | CP Magusa</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-topbar">
    <span class="brand">Panel del Blog — CP Magusa</span>
    <span>
      <a href="../blog.php" target="_blank" rel="noopener">Ver blog público</a>
      &nbsp;·&nbsp;
      <a href="logout.php">Cerrar sesión</a>
    </span>
  </div>

  <div class="admin-wrap">
    <?php if ($saved): ?>
      <div class="alert alert-success">Publicación guardada correctamente.</div>
    <?php endif; ?>
    <?php if ($deleted): ?>
      <div class="alert alert-success">Publicación eliminada.</div>
    <?php endif; ?>

    <div class="top-actions">
      <h1>Publicaciones</h1>
      <a href="post-form.php" class="btn btn-primary">+ Nueva publicación</a>
    </div>

    <div class="admin-card">
      <?php if (empty($posts)): ?>
        <div class="empty-state">Todavía no hay publicaciones. Crea la primera con el botón de arriba.</div>
      <?php else: ?>
        <ul class="post-list">
          <?php foreach ($posts as $post): ?>
            <?php
              $thumb = $post['images'][0] ?? null;
              $date = format_post_date($post['created_at'] ?? '');
            ?>
            <li class="post-row">
              <div class="thumb" style="<?= $thumb ? "background-image:url('../" . h($thumb) . "')" : '' ?>"></div>
              <div class="info">
                <h3><?= h($post['title'] ?? '(sin título)') ?></h3>
                <div class="meta"><?= h($date['day'] . ' ' . $date['mon'] . ' ' . $date['year']) ?><?= !empty($post['video']) ? ' · con video' : '' ?></div>
              </div>
              <div class="actions">
                <a class="btn btn-secondary" href="../blog-single.php?slug=<?= urlencode($post['slug'] ?? '') ?>" target="_blank" rel="noopener">Ver</a>
                <a class="btn btn-secondary" href="post-form.php?slug=<?= urlencode($post['slug'] ?? '') ?>">Editar</a>
                <form method="post" action="delete-post.php" onsubmit="return confirm('¿Eliminar esta publicación? Esta acción no se puede deshacer.');" style="margin:0;">
                  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= h($post['id'] ?? '') ?>">
                  <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
