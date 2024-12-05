<?php
require_once 'vendor/autoload.php';
use FontLib\Font;

$cacheFile = 'font-cache.json';
$cacheTime = 86400; // Cache for 1 day

if (isset($_GET['refresh'])) {
    // Force refresh, delete cache file
    unlink($cacheFile);
}

if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTime) {
    $fontNames = json_decode(file_get_contents($cacheFile), true);
} else {

    $fontFolder = 'fonts/';
    $fontFiles = array_diff(scandir($fontFolder), array('..', '.'));
    $fontNames = [];

    foreach ($fontFiles as $file) {
        if (preg_match('/\.(ttf|otf)$/', $file)) {
            try {
                $font = Font::load($fontFolder . $file);
                $font->parse();
                $fontNames[] = [
                    'file' => $file,
                    'postscript_name' => $font->getFontPostscriptName()
                ];
            } catch (Exception $e) {
                $fontNames[] = ['file' => $file, 'error' => $e->getMessage()];
            }
        }
    }

    // Save to cache
    file_put_contents($cacheFile, json_encode($fontNames));
}

header('Content-Type: application/json');
echo json_encode($fontNames);
