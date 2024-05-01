<!-- 
    - to show preview of widget
--> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Widgets read page</title>
<?php if(!empty($metas) && $metas !== null) { foreach($metas as $meta) { echo htmlspecialchars_decode($meta['content']); } } ?>
<?php if(!empty($cssFiles) && $cssFiles !== null) { ?><?php foreach($cssFiles as $cssFile) { ?><link rel="stylesheet" href="/website/assets/css/<?php echo $cssFile['file_name'] . $cssFile['extension']; ?>"><?php } ?><?php } ?>
<?php if(!empty($jsFiles) && $jsFiles !== null) { ?><?php foreach($jsFiles as $jsFile) { ?><script type="text/javascript" src="/website/assets/js/<?php echo $jsFile['file_name'] . $jsFile['extension']; ?>" defer></script><?php } ?><?php } ?>
    </head>
<body>
    <?php echo htmlspecialchars_decode($widget["content"]); ?>
</body>
</html>