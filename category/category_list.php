<?php
session_start();
include "../authentication.php";
include "../config.php";

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 3;
$offset = ($page-1)*$limit;
$sql = "SELECT * FROM category LIMIT $limit OFFSET $offset";
$query = mysqli_query($link,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Operation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        .fas{
            font-size: 20px;
        }
        .fa-edit:hover{
            color: green;
        }
        .fa-trash:hover{
            color: red;
        }
    </style>
</head>
<body>
<?php
include('../include/header.php');
?>
<div class="container">
    <h1 class="text-center">Category List</h1>

    <div class="text-right"><a href="create_category.php" class="btn btn-success mb-2"><i class='fas fa-plus'></i> Add Category</a></div>

    <?php if(isset($_SESSION['success'])){ ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php } ?>
    <?php if(isset($_SESSION['error'])){ ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>
    <?php if(isset($_SESSION['warning'])){ ?>
        <div class="alert alert-warning"><?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?></div>
    <?php } ?>

    <table class="table table-bordered table-striped table-hover">
        <thead class="bg-dark text-center text-white">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody class="text-center">
        <?php if(mysqli_num_rows($query) == 0){ ?>
            <tr><td colspan="7" class="text-center">No record found</td></tr>
        <?php }else{
            $psql = "SELECT * FROM category";
            $pquery = mysqli_query($link,$psql);
            $total_record = mysqli_num_rows($pquery);
            $total_page = ceil($total_record/$limit);
            ?>
            <?php while($row = mysqli_fetch_assoc($query)){ ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td>
                        <a href="category_update.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="category_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php }} ?>
        </tbody>
    </table>
    <ul class="pagination">
        <li class="page-item <?php echo ($page > 1) ? '' : 'disabled' ?>"><a class="page-link" href="category_list.php?page=<?php echo $page-1 ?>">Previous</a></li>
        <?php for($i=1;$i<=$total_page;$i++){ ?>
            <li class="page-item <?php echo ($page == $i) ? 'active' : '' ?>"><a class="page-link" href="category_list.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
        <?php } ?>
        <li class="page-item <?php echo ($total_page > $page) ? '' : 'disabled' ?>"><a class="page-link" href="category_list.php?page=<?php echo $page+1 ?>">Next</a></li>
    </ul>
</div>
</body>
</html>