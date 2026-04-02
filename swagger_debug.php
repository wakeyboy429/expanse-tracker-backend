<?php
// Manual Swagger generation script
require __DIR__ . '/vendor/autoload.php';

use OpenApi\Generator;

try {
    // Scan everything in app/ folder
    $openapi = Generator::scan(['app']);
    // Write out the result
    file_put_contents('storage/api-docs/api-docs.json', $openapi->toJson());
    // Also log success
    file_put_contents('swagger_status.txt', "Success: api-docs.json created at " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    // Log detailed error
    file_put_contents('swagger_status.txt', "Error: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
}
