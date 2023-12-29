<nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
    <p class="navbar-brand">
        <?php echo isset($_SESSION['id']) ? 'Welcome '.$_SESSION['name'] : '' ?> |
    </p>
    <p class="nav-item text-white">
        <?php echo isset($_SESSION['login_at']) ? 'Login at: '.$_SESSION['login_at'] : '' ?> | &nbsp;&nbsp;
    </p>
    <p class="float-right mr-2">
        <a href="<?php echo BASE_URL ?>" class="btn btn-warning"><i class='fas fa-sign-out-alt'></i> Home</a>
    </p>
    <p class="float-right">
        <a href="<?php echo BASE_URL ?>/logout.php" class="btn btn-danger"><i class='fas fa-sign-out-alt'></i> Logout</a>
    </p>
    <p class="float-right ml-2">
        <a href="<?php echo BASE_URL ?>/blog/blog_list.php" class="btn btn-success"><i class='fas fa-sign-out-alt'></i> Blog</a>
    </p>
    <p class="float-right ml-2">
        <a href="<?php echo BASE_URL ?>/category/category_list.php" class="btn btn-info"><i class='fas fa-sign-out-alt'></i> Category</a>
    </p>
</nav>