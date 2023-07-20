<div class="readSlugContainer">
    <span><?php echo $categorySlug['slug']; ?></span><?php if(!empty($categories) ) { ?>
        <?php foreach($categories as $category) { ?> <span><?php echo $category['slug']; ?></span><?php } ?>
    <?php } ?>
</div>



<div class="row">

<div class="col6">

<span class="categories">Categories: </span>
<ul class="categoriesRead">

    
        <?php foreach($categories as $category) { ?> 

            <li><?php echo $category['title']; ?></li>

        <?php } ?>
    

</ul>
</div>
<div class="col6">

<span class="pages">Pages: </span>
<ul class="pagesRead">

    
        <?php foreach($pages as $page) { ?> 

            <li><?php echo $page['title']; ?></li>

        <?php } ?>
    

</ul>
</div>

</div>
<button id="EDITCLOSE">Close</button>



