<?php

echo '<!DOCTYPE html>
<html>
<head>';

$files = ["script.js", "wysiwyg.js","style.css"];
foreach($files as $file) {

    if (file_exists('assets/css/' . $file)) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/' . $file . '">';
    } else if (file_exists('assets/js/' . $file)) {
        echo '<script defer src="/assets/js/' . $file . '"></script>';
    }
}
echo '<script src="https://cdn.tiny.cloud/1/yjrgki0oubi33qi9ebe57t1lz8lw9nbe3xbnfrv5893n4oqb/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script></head><body>';