<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <?php if(!empty($post['metaTitle']) ) {echo "<title>".$post['metaTitle']."</title>"; } ?>
    <?php if(!empty($post['metaDescription']) ) {echo '<meta name="description" content="'.$post['metaDescription'].'">'; } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach($cssFiles as $cssFile) {
        if (file_exists('website/assets/css/' . $cssFile['file_name'].$cssFile['extension'])) {
            echo '<link rel="stylesheet" type="text/css" href="/website/assets/css/' . $cssFile['file_name'].$cssFile['extension'] . '">';
        } 
    } ?>
    <?php foreach($jsFiles as $jsFile) {
        if (file_exists('website/assets/js/' . $jsFile['file_name'].$jsFile['extension'])) {
            echo '<script src="/website/assets/js/' . $jsFile['file_name'].$jsFile['extension'] . '"></script>';
        } 
    } ?>
</head>
<body>
    <?php 
    
        if(!empty($menusTop ) ) { 

            foreach($menusTop as $menuTop) {
                echo html_entity_decode($menuTop['content']); 
            }

        }

        echo html_entity_decode($post["body"]); 
     
        if(!empty($menusBottom ) ) { 

            foreach($menusBottom as $menuBottom) {
                echo html_entity_decode($menuBottom['content']); 
            }

        }


    ?>

</body>