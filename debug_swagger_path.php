<?php
// Debugging L5-Swagger path resolution
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

echo "Config swagger_ui_assets_path: " . config('l5-swagger.documentations.default.paths.swagger_ui_assets_path') . "\n";
echo "Base Path: " . base_path() . "\n";

try {
    $path = swagger_ui_dist_path('default', 'swagger-ui.css');
    echo "Resolved Path: $path\n";
    echo "File Exists: " . (file_exists($path) ? "YES" : "NO") . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
