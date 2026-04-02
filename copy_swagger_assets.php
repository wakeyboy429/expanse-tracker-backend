<?php
// PHP Script to copy Swagger assets
$src = __DIR__ . '/vendor/swagger-api/swagger-ui/dist';
$dest = __DIR__ . '/public/vendor/l5-swagger';

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

try {
    if (!file_exists($src)) {
        die("Source directory $src does not exist.");
    }
    recurse_copy($src, $dest);
    echo "Success: Assets copied to $dest";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
