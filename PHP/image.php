
<?php

/*

$filename = basename($_GET['file'] ?? '');
$filepath = __DIR__ . '/../uploads/' . $filename;

if (!preg_match('/^[\w.-]+\.(jpg|jpeg|png|gif)$/i', $filename)) {
    http_response_code(400);
    exit('Invalid file name.');
}
if (!file_exists($filepath)) {
    http_response_code(404);
    exit('File not found.');
}
header('Content-Type: ' . mime_content_type($filepath));
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
*/