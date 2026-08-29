<?php
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = trim((string) ($_POST['id'] ?? ''));
$title = trim((string) ($_POST['title'] ?? ''));
$body = trim((string) ($_POST['body'] ?? ''));
$videoUrlInput = trim((string) ($_POST['video_url'] ?? ''));
$isEdit = $id !== '';

$posts = load_posts();
$existing = null;
$existingIndex = null;
if ($isEdit) {
    foreach ($posts as $i => $p) {
        if (($p['id'] ?? '') === $id) {
            $existing = $p;
            $existingIndex = $i;
            break;
        }
    }
    if ($existing === null) {
        header('Location: index.php');
        exit;
    }
}

$backTarget = 'post-form.php' . ($isEdit ? '?slug=' . urlencode($existing['slug'] ?? '') : '');

if (!csrf_check($_POST['csrf_token'] ?? null)) {
    set_flash_old(['title' => $title, 'body' => $body, 'video_url' => $videoUrlInput], 'La sesión del formulario expiró, intenta de nuevo.');
    header('Location: ' . $backTarget);
    exit;
}

if ($title === '' || $body === '') {
    set_flash_old(['title' => $title, 'body' => $body, 'video_url' => $videoUrlInput], 'El título y el contenido son obligatorios.');
    header('Location: ' . $backTarget);
    exit;
}

$video = $existing['video'] ?? null;
$videoSourceUrl = $existing['video_source_url'] ?? '';
if ($videoUrlInput !== '') {
    $embed = extract_video_embed($videoUrlInput);
    if ($embed === null) {
        set_flash_old(['title' => $title, 'body' => $body, 'video_url' => $videoUrlInput], 'No reconozco ese enlace de video. Usa un link de YouTube (incluye Shorts) o de un Reel/publicación de Instagram.');
        header('Location: ' . $backTarget);
        exit;
    }
    $video = $embed;
    $videoSourceUrl = $videoUrlInput;
}

$slug = $isEdit ? $existing['slug'] : unique_slug($title, $posts);

$images = $existing['images'] ?? [];

// Quitar imágenes marcadas para eliminar
$toRemove = $_POST['remove_images'] ?? [];
if (is_array($toRemove)) {
    foreach ($toRemove as $rel) {
        $rel = (string) $rel;
        $idx = array_search($rel, $images, true);
        if ($idx !== false) {
            delete_uploaded_image($rel);
            unset($images[$idx]);
        }
    }
    $images = array_values($images);
}

// Subir nuevas imágenes
if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
    $count = count($_FILES['images']['name']);
    for ($i = 0; $i < $count; $i++) {
        if (($_FILES['images']['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        $file = [
            'name' => $_FILES['images']['name'][$i],
            'type' => $_FILES['images']['type'][$i],
            'tmp_name' => $_FILES['images']['tmp_name'][$i],
            'error' => $_FILES['images']['error'][$i],
            'size' => $_FILES['images']['size'][$i],
        ];
        $saved = save_uploaded_image($file, $slug);
        if ($saved !== false) {
            $images[] = $saved;
        }
    }
}

$now = date('Y-m-d H:i:s');

if ($isEdit) {
    $posts[$existingIndex] = [
        'id' => $existing['id'],
        'slug' => $slug,
        'title' => $title,
        'body' => $body,
        'excerpt' => excerpt_from_body($body),
        'images' => $images,
        'video' => $video,
        'video_source_url' => $videoSourceUrl,
        'created_at' => $existing['created_at'] ?? $now,
        'updated_at' => $now,
    ];
} else {
    $posts[] = [
        'id' => bin2hex(random_bytes(8)),
        'slug' => $slug,
        'title' => $title,
        'body' => $body,
        'excerpt' => excerpt_from_body($body),
        'images' => $images,
        'video' => $video,
        'video_source_url' => $videoSourceUrl,
        'created_at' => $now,
        'updated_at' => $now,
    ];
}

if (!save_posts($posts)) {
    set_flash_old(['title' => $title, 'body' => $body, 'video_url' => $videoUrlInput], 'No se pudo guardar la publicación (error al escribir el archivo de datos). Intenta de nuevo.');
    header('Location: ' . $backTarget);
    exit;
}

header('Location: index.php?saved=1');
exit;
