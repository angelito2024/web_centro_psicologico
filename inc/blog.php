<?php
// Funciones compartidas del blog (panel de administración + páginas públicas).

define('BLOG_DATA_DIR', __DIR__ . '/../blog-data');
define('BLOG_POSTS_FILE', BLOG_DATA_DIR . '/posts.json');
define('BLOG_UPLOADS_DIR', __DIR__ . '/../uploads/blog');
define('BLOG_UPLOADS_URL', 'uploads/blog');

const BLOG_MAX_IMAGE_BYTES = 5 * 1024 * 1024; // 5MB
const BLOG_ALLOWED_IMAGE_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
const BLOG_MONTHS_ES = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function nlbr_h(?string $value): string
{
    return nl2br(h($value));
}

/** @return array<int, array<string, mixed>> */
function load_posts(): array
{
    if (!is_file(BLOG_POSTS_FILE)) {
        return [];
    }
    $raw = file_get_contents(BLOG_POSTS_FILE);
    $data = json_decode((string) $raw, true);
    return is_array($data) ? $data : [];
}

function save_posts(array $posts): bool
{
    if (!is_dir(BLOG_DATA_DIR)) {
        mkdir(BLOG_DATA_DIR, 0755, true);
    }
    $json = json_encode(array_values($posts), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    if ($json === false) {
        return false;
    }
    $fp = fopen(BLOG_POSTS_FILE, 'c+');
    if (!$fp) {
        return false;
    }
    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, $json);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

function find_post_by_slug(array $posts, string $slug): ?array
{
    foreach ($posts as $post) {
        if (($post['slug'] ?? '') === $slug) {
            return $post;
        }
    }
    return null;
}

function slugify(string $text): string
{
    $map = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
        'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u', 'Ü' => 'u', 'Ñ' => 'n',
    ];
    $text = strtr($text, $map);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');
    return $text === '' ? 'post' : $text;
}

function unique_slug(string $base, array $posts, ?string $excludeId = null): string
{
    $slug = slugify($base);
    $original = $slug;
    $i = 2;
    while (true) {
        $collision = false;
        foreach ($posts as $post) {
            if (($post['slug'] ?? '') === $slug && ($post['id'] ?? null) !== $excludeId) {
                $collision = true;
                break;
            }
        }
        if (!$collision) {
            return $slug;
        }
        $slug = $original . '-' . $i;
        $i++;
    }
}

/**
 * Reconoce un enlace de YouTube o Instagram y devuelve datos para incrustarlo.
 * Nunca se acepta una URL de iframe arbitraria: siempre se reconstruye desde el ID detectado.
 */
function extract_video_embed(string $url): ?array
{
    $url = trim($url);
    if ($url === '') {
        return null;
    }

    // YouTube: youtu.be/ID, watch?v=ID, shorts/ID
    if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $m)
        || preg_match('~youtube\.com/(?:watch\?v=|shorts/|embed/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
        return [
            'type' => 'youtube',
            'embed_url' => 'https://www.youtube.com/embed/' . $m[1],
        ];
    }

    // Instagram: instagram.com/reel/CODE/ o /p/CODE/
    if (preg_match('~instagram\.com/(?:reel|p)/([A-Za-z0-9_-]+)~', $url, $m)) {
        return [
            'type' => 'instagram',
            'embed_url' => 'https://www.instagram.com/p/' . $m[1] . '/embed',
        ];
    }

    return null;
}

function render_video_embed_html(?array $video): string
{
    if (!$video || empty($video['embed_url'])) {
        return '';
    }
    $src = h($video['embed_url']);
    return '<div class="blog-video-wrap"><iframe src="' . $src . '" title="Video del post" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe></div>';
}

/**
 * Valida un archivo de $_FILES antes de guardarlo.
 * @return array{ok: bool, error?: string, ext?: string}
 */
function validate_uploaded_image(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'error' => 'no_file'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'upload_error'];
    }
    if ($file['size'] <= 0 || $file['size'] > BLOG_MAX_IMAGE_BYTES) {
        return ['ok' => false, 'error' => 'too_large'];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, BLOG_ALLOWED_IMAGE_EXT, true)) {
        return ['ok' => false, 'error' => 'bad_extension'];
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        return ['ok' => false, 'error' => 'not_uploaded'];
    }
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        return ['ok' => false, 'error' => 'not_an_image'];
    }
    return ['ok' => true, 'ext' => $ext === 'jpeg' ? 'jpg' : $ext];
}

function save_uploaded_image(array $file, string $slug): string|false
{
    $check = validate_uploaded_image($file);
    if (!$check['ok']) {
        return false;
    }
    if (!is_dir(BLOG_UPLOADS_DIR)) {
        mkdir(BLOG_UPLOADS_DIR, 0755, true);
    }
    $name = $slug . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $check['ext'];
    $dest = BLOG_UPLOADS_DIR . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return false;
    }
    return BLOG_UPLOADS_URL . '/' . $name;
}

function delete_uploaded_image(string $relativePath): void
{
    $full = __DIR__ . '/../' . ltrim($relativePath, '/');
    $real = realpath($full);
    $uploadsReal = realpath(BLOG_UPLOADS_DIR);
    if ($real && $uploadsReal && str_starts_with($real, $uploadsReal) && is_file($real)) {
        @unlink($real);
    }
}

function format_post_date(string $createdAt): array
{
    $ts = strtotime($createdAt) ?: time();
    return [
        'day' => date('d', $ts),
        'mon' => BLOG_MONTHS_ES[(int) date('n', $ts)],
        'year' => date('Y', $ts),
    ];
}

function excerpt_from_body(string $body, int $length = 140): string
{
    $plain = trim(preg_replace('/\s+/', ' ', $body) ?? '');
    $hasMb = function_exists('mb_strlen');
    $len = $hasMb ? mb_strlen($plain) : strlen($plain);
    if ($len <= $length) {
        return $plain;
    }
    $cut = $hasMb ? mb_substr($plain, 0, $length) : substr($plain, 0, $length);
    return rtrim($cut) . '…';
}
