<!-- 
    - to preview of page
--> 

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?php if(!empty($page['metaTitle']) && $page['metaTitle'] !== null) { ?><title><?php echo $page['metaTitle']; ?></title><?php } ?>
        <?php if(!empty($page['metaDescription']) && $page['metaDescription'] !== null) { ?><meta name="description" content="<?php echo $page['metaDescription']; ?>"/><?php } ?>
        <?php if(!empty($page['metaKeywords']) && $page['metaKeywords'] !== null) { ?><meta name="keywords" content="<?php echo $page['metaKeywords']; ?>"/><?php } ?>
        <?php if(!empty($metas) && $metas !== null) { foreach($metas as $meta) { echo htmlspecialchars_decode($meta['content']); } } ?>
        <?php if(!empty($cssFiles) && $cssFiles !== null) { ?><?php foreach($cssFiles as $cssFile) { ?><link rel="stylesheet" href="/website/assets/css/<?php echo $cssFile['file_name'] . $cssFile['extension']; ?>"><?php } ?><?php } ?>
        <?php if(!empty($jsFiles) && $jsFiles !== null) { ?><?php foreach($jsFiles as $jsFile) { ?><script type="text/javascript" src="/website/assets/js/<?php echo $jsFile['file_name'] . $jsFile['extension']; ?>" defer></script><?php } ?><?php } ?>
    </head>
    <body>

        <?php if(!empty($menusTop ) ) { ?>
            <?php foreach($menusTop as $menuTop) { ?>
                <?php echo htmlspecialchars_decode($menuTop['content']); ?> 
            <?php } ?>
        <?php } ?>

        <?php echo htmlspecialchars_decode($page["body"]); ?>

        <?php if(!empty($menusBottom ) ) { ?>
            <?php foreach($menusBottom as $menuBottom) { ?>
                <?php echo htmlspecialchars_decode($menuBottom['content']); ?>  
            <?php } ?>
        <?php } ?>
    </body>
</html>