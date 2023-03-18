<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  
    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="con">

    <a class="button margin-t-50" href="/admin/users/create">Add user</a>

    <form action="" method="GET" class="margin-t-50">
        <div class="form-parts">
            <input type="text" name="search" class="search" placeholder="Search">
        </div>
    </form>
    <table>
        
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Show</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allUsers as $user) { ?>
                    <tr>
                        <td>
                            <?php echo $user['id']; ?>
                        </td>
                        <td>
                            <?php echo $user['username']; ?>
                        </td>
                        <td>
                            <?php echo $user['email']; ?>
                        </td>
                        <td>
                            <?php echo $user['name']; ?>
                        </td>
                        <td>
                            <a href="/admin/users/<?php echo $user["id"]; ?>/username/<?php echo $user["username"]; ?>">Read</a>
                        </td>
                        <td>
                            <a href="/admin/users/<?php echo $user["id"]; ?>/username/<?php echo $user["username"]; ?>/edit">Edit</a>
                        </td>   
                        <td>
                            <a href="/admin/users/<?php echo $user["id"]; ?>/username/<?php echo $user["username"]; ?>/delete" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="/admin/users?back=1">Previous</a></li>
                <?php 
                    foreach($numberOfPages as $page) {
                        echo '<li class="page-item"><a class="page-link" href="/admin/users?page='.$page.'">'.$page.'</a></li>';
                    }  
                ?>
                <li class="page-item"><a class="page-link" href="/admin/users?next=1">Next</a></li>
            </ul>
        </nav>
</div>

<?php 
    $this->include('footer');
?>