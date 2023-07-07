<?php if(!empty($pages) ) { ?>
    <ul class="categoriesRead">
        <?php foreach($pages as $page) { ?> 

            <li><?php echo $page['title']; ?></li>

        <?php } ?>
    </ul>
<?php } else { ?>

    <span>-</span>

<?php } ?>