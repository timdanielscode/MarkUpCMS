<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/navbar.css");
    $this->stylesheet("/assets/css/index.css");
    $this->stylesheet("/assets/css/pagination.css");

    $this->include('headerClose');
    $this->include('navbar');
?>
<div class="index-container">

    <div class="headerAndButtonContainer">
        <h1>Users</h1>
        <a class="button " href="/admin/users/create">Add new</a>
    </div>

    <div class="countContainer">
        <span>All</span>
        <span>(<?php echo $count; ?>)</span>
    </div>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search" id="search">
        <input type="hidden" name="submit" value="search">
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
                            <a href="/admin/users/<?php echo $user["username"]; ?>/read">Read</a>
                        </td>
                        <td>
                            <a href="/admin/users/<?php echo $user["username"]; ?>/edit">Edit</a>
                        </td>   
                        <td>
                            <a href="/admin/users/<?php echo $user["username"]; ?>/delete" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if($numberOfPages !== null && count($numberOfPages) > 1) { ?>
        <nav class="pagination">
            <ul>
                <?php 
                    foreach($numberOfPages as $page) {
                        echo '<li class="page-item"><a href="/admin/users?page='.$page.'">'.$page.'</a></li>';
                    }  
                ?>
            </ul>
        </nav>
        <?php } ?>
</div>

<?php 
    $this->include('footer');
?>