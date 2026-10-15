<?php

/**
 * Sinema Tegal - Production & Local Built-in Web Server Router
 * Emulates Apache/Nginx rewrite and delivers static files directly
 */

$publicPath = __DIR__ . '/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'
);

// Serve static assets directly if they exist on disk (css, js, images, etc.)
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

require_once $publicPath . '/index.php';
