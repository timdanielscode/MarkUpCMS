<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>
<?php use parts\Pagination; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="con">

    <?php if(parts\Session::exists("updated")) { ?>
        <div class="margin-t-50"><?php echo parts\Alert::display("success", "updated"); ?></div>
    <?php parts\Session::delete('updated');  } ?>

    <?php if(Session::exists("registered")) { ?>
        <div class="margin-t-50"><?php echo Alert::display("warning", "registered"); ?></div>
    <?php Session::delete('registered'); } ?>

    <a class="button margin-t-50" href="/admin/posts/create">Create new post</a>

    <table class="margin-t-50">
        
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($posts as $post) { ?>
                    <tr>
                        <td>
                            <?php echo $post['id']; ?>
                        </td>
                        <td>
                            <?php echo $post['title']; ?>
                        </td>
                        <td>
                            <a href="/admin/posts/<?php echo $post['id']; ?>/edit">edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
</div>

<?php 
    $this->include('footer');
?>