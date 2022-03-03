<?php

echo '<!DOCTYPE html>
<html>
<head>';

$files = ["script.js", "wysiwyg.js","style.css"];
foreach($files as $file) {

    if (file_exists('assets/css/' . $file)) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/' . $file . '">';
    } else if (file_exists('assets/js/' . $file)) {
        echo '<script src="/assets/js/' . $file . '"></script>';
    }
}
echo '</head><body>';