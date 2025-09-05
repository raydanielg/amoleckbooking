<?php
// cPanel shim: if your document root points to the project root, this will forward to Laravel's public/index.php
// For best security, set your domain's Document Root to the /public directory instead of using this shim.

// Prevent direct access to sensitive paths
$blocked = ['/.env', '/storage', '/vendor', '/node_modules'];
$uri = $_SERVER['REQUEST_URI'] ?? '/';
foreach ($blocked as $frag) {
    if (stripos($uri, $frag) === 0) {
        http_response_code(403);
        exit('Forbidden');
    }
}

require __DIR__ . '/public/index.php';
