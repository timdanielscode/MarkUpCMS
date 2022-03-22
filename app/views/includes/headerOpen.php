<?php

echo '<!DOCTYPE html>
<html>
<head>';

$files = ["script.js","style.css"];
foreach($files as $file) {

    if (file_exists('assets/css/' . $file)) {
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/' . $file . '">';
    } else if (file_exists('assets/js/' . $file)) {
        echo '<script defer src="/assets/js/' . $file . '"></script>';
    }
}
