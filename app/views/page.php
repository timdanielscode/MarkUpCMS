<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <?php if(!empty($post['metaTitle']) ) {echo "<title>".$post['metaTitle']."</title>"; } ?>
    <?php if(!empty($post['metaDescription']) ) {echo '<meta name="description" content="'.$post['metaDescription'].'">'; } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach($cssFiles as $cssFile) {
        if (file_exists('website/assets/css/' . $cssFile['file_name'].$cssFile['extension'])) {
            echo '<link rel="stylesheet" type="text/css" href="website/assets/css/' . $cssFile['file_name'].$cssFile['extension'] . '">';
        } 
    } ?>
</head>
<body>
    <?php echo html_entity_decode($post["body"]); ?>
</body>