<?php
require_once __DIR__ . '/auth.php';
require_login();

$slug = trim((string) ($_GET['slug'] ?? ''));
$post = null;
if ($slug !== '') {
    $post = find_post_by_slug(load_posts(), $slug);
    if (!$post) {
        header('Location: index.php');
        exit;
    }
}

$isEdit = $post !== null;
$title = $post['title'] ?? '';
$body = $post['body'] ?? '';
$videoUrl = $isEdit ? ($post['video_source_url'] ?? '') : '';
$images = $post['images'] ?? [];

$flash = get_and_clear_flash();
$formError = $flash['error'];
if ($flash['old']) {
    $title = $flash['old']['title'] ?? $title;
    $body = $flash['old']['body'] ?? $body;
    $videoUrl = $flash['old']['video_url'] ?? $videoUrl;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $isEdit ? 'Editar publicación' : 'Nueva publicación' ?> | Blog CP Magusa</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-topbar">
    <span class="brand">Panel del Blog — CP Magusa</span>
    <span><a href="index.php">&larr; Volver al listado</a></span>
  </div>

  <div class="admin-wrap">
    <h1><?= $isEdit ? 'Editar publicación' : 'Nueva publicación' ?></h1>

    <?php if ($formError !== ''): ?>
      <div class="alert alert-error"><?= h($formError) ?></div>
    <?php endif; ?>

    <form class="admin-card" method="post" action="save-post.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= h($post['id']) ?>">
      <?php endif; ?>

      <div class="form-group">
        <label for="title">Título</label>
        <input type="text" id="title" name="title" value="<?= h($title) ?>" required maxlength="150">
      </div>

      <div class="form-group">
        <label for="body">Contenido</label>
        <textarea id="body" name="body" rows="10" required><?= h($body) ?></textarea>
        <p class="help-text">Solo texto plano; los saltos de línea se respetan al publicarse.</p>
      </div>

      <div class="form-group">
        <label for="video_url">Video corto (opcional)</label>
        <input type="url" id="video_url" name="video_url" value="<?= h($videoUrl) ?>" placeholder="https://www.youtube.com/shorts/... o https://www.instagram.com/reel/...">
        <p class="help-text">Pega un enlace de YouTube (incluye Shorts) o de un Reel/publicación de Instagram.<?= $isEdit && !empty($post['video']) ? ' Ya hay un video guardado — déjalo vacío para conservarlo, o pega uno nuevo para reemplazarlo.' : '' ?></p>
      </div>

      <div class="form-group">
        <label for="images">Agregar imágenes</label>
        <input type="file" id="images" name="images[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple>
        <p class="help-text">Hasta 5MB por imagen (JPG, PNG, WEBP o GIF). Puedes seleccionar varias a la vez.</p>
      </div>

      <?php if ($isEdit && !empty($images)): ?>
        <div class="form-group">
          <label>Imágenes actuales</label>
          <div class="thumbs-grid">
            <?php foreach ($images as $i => $img): ?>
              <div class="thumb-item">
                <img src="../<?= h($img) ?>" alt="">
                <label><input type="checkbox" name="remove_images[]" value="<?= h($img) ?>"> Quitar</label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Guardar cambios' : 'Publicar' ?></button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</body>
</html>
