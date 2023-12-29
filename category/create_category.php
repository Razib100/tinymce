<?php
session_start();
include "../authentication.php";
include "../config.php";

if(isset($_POST['submit'])){
    $name = $_POST['name'];

    if(!empty($name)){
        $sql = "INSERT INTO category (name) VALUES ('$name')";
    }
    $query = mysqli_query($link,$sql);
    if($query){
        $log = getHostByName($_SERVER['HTTP_HOST']).' - '.date("F j, Y, g:i a").PHP_EOL.
            "Record created_".time().PHP_EOL.
            "---------------------------------------".PHP_EOL;
        file_put_contents('../logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

        $_SESSION['success'] = "One record inserted successfully";
        header('location:../category/category_list.php');
    }else{
        $_SESSION['error'] = "Something is wrong, Record not inserted";
        header('location:index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php
include('../include/header.php');
?>
<div class="container">
    <h1 class="text-center">Category</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name"><strong>Category Name</strong></label>
            <input type="text" class="form-control" placeholder="Enter category name" name="name" required>
        </div>
        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>