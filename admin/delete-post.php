<?php
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf_token'] ?? null)) {
    header('Location: index.php');
    exit;
}

$id = trim((string) ($_POST['id'] ?? ''));
$posts = load_posts();
$kept = [];
foreach ($posts as $post) {
    if (($post['id'] ?? '') === $id) {
        foreach ($post['images'] ?? [] as $img) {
            delete_uploaded_image($img);
        }
        continue;
    }
    $kept[] = $post;
}

save_posts($kept);
header('Location: index.php?deleted=1');
exit;
