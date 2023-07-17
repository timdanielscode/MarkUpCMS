<?php use validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use core\Session; ?>
<?php use extensions\Pagination; ?>

<?php 
    $this->include('headerOpen');  

    $this->stylesheet("/assets/css/style.css");
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
                </tr>
            </thead>
            <tbody>
                <?php foreach($allUsers as $user) { ?>
                    <tr>
                    <td>
                            <?php echo $user['id']; ?>
                        </td>
                    <td class="width-40">
                    <?php if($user['name'] === 'admin') { ?>
                        <a href="/admin/users/<?php echo $user['username']; ?>/read" class="font-weight-500"><?php echo $user['username']; ?></a>
                        
                    <?php } else { ?>

                        <a href="/admin/users/<?php echo $user['username']; ?>/edit" class="font-weight-500"><?php echo $user['username']; ?></a> |
                        <a href="/admin/users/<?php echo $user['username']; ?>/edit" class="font-weight-300">Edit</a> |     
                    <?php } ?>
                        
                    <?php if($user['name'] !== 'admin') { ?> <a href="/admin/users/<?php echo $user['username']; ?>/read" class="font-weight-300">Read</a> |

                            <a href="/admin/users/<?php echo $user['username']; ?>/delete" class="font-weight-300 color-red">Remove</a>
                        <?php } ?>
                    </td>

                        <td>
                            <?php echo $user['email']; ?>
                        </td>
                        <td>
                            <?php echo $user['name']; ?>
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