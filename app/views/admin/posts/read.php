<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>MarkupCMS</title>
        <link rel="icon" type="image/x-icon" href="/assets/img/logo.png">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <?php if(!empty($post[0]['metaTitle']) && $post[0]['metaTitle'] !== null) { ?><title><?php echo $post[0]['metaTitle']; ?></title><?php } ?>
        
        <?php if(!empty($post[0]['metaDescription']) && $post[0]['metaDescription'] !== null) { ?><meta name="description" content="<?php echo $post[0]['metaDescription']; ?>"/><?php } ?>
    
        <?php if(!empty($post[0]['metaKeywords']) && $post[0]['metaKeywords'] !== null) { ?><meta name="keywords" content="<?php echo $post[0]['metaKeywords']; ?>"/><?php } ?>
<?php if(!empty($cssFiles) && $cssFiles !== null) { ?><?php foreach($cssFiles as $cssFile) { ?><link rel="stylesheet" href="/website/assets/css/<?php echo $cssFile['file_name'] . $cssFile['extension']; ?>"><?php } ?><?php } ?>
<?php if(!empty($jsFiles) && $jsFiles !== null) { ?><?php foreach($jsFiles as $jsFile) { ?><script type="text/javascript" src="/website/assets/js/<?php echo $jsFile['file_name'] . $jsFile['extension']; ?>"></script><?php } ?><?php } ?>
    </head>
    <body>
        <?php if(!empty($menusTop ) ) { ?>

            <?php foreach($menusTop as $menuTop) { ?>

                <?php echo html_entity_decode($menuTop['content']); ?> 
            <?php } ?>

        <?php } ?>

        <?php echo html_entity_decode($post["body"]); ?>
            
        <?php if(!empty($menusBottom ) ) { ?>

            <?php foreach($menusBottom as $menuBottom) { ?>

                <?php echo html_entity_decode($menuBottom['content']); ?>  
            <?php } ?>

        <?php } ?>
    </body>
</html>