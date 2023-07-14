<?php if(!empty($categories) ) { ?>

    <?php foreach($categories as $category) { ?> <li><?php echo $category['slug']; ?></li><?php } ?>

<?php } else { ?>

    <span>-</span>

<?php } ?>



<p>Pages:</p>
<?php if(!empty($pages) ) { ?>
    <ul class="categoriesRead">
        <?php foreach($pages as $page) { ?> 

            <li><?php echo $page['title']; ?></li>

        <?php } ?>
    </ul>
<?php } else { ?>

    <span>-</span>

<?php } ?>

<p>Categories:</p>
<?php if(!empty($pages) ) { ?>
    <ul class="categoriesRead">
        <?php foreach($categories as $category) { ?> 

            <li><?php echo $category['title']; ?></li>

        <?php } ?>
    </ul>
<?php } else { ?>

    <span>-</span>

<?php } ?>

