<?php
session_start();
include "../authentication.php";
include "../config.php";

$id = $_GET['id'];
$fsql = "SELECT * FROM category WHERE id='$id'";
$fquery = mysqli_query($link,$fsql);
$result = mysqli_fetch_assoc($fquery);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $usql = "UPDATE category SET name='$name' WHERE id='$id'";

    $uquery = mysqli_query($link,$usql);
    if($uquery){
        $log = getHostByName($_SERVER['HTTP_HOST']).' - '.date("F j, Y, g:i a").PHP_EOL.
            "Record updated_".time().PHP_EOL.
            "---------------------------------------".PHP_EOL;
        file_put_contents('../logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

        $_SESSION['success'] = "One record updated successfully";
        header('location:category_list.php');
    }else{
        $_SESSION['error'] = "Something is wrong, Record not updated";
        header('location:category_list.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h1 class="text-center">Edit Existing Category</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name"><strong>Category Name</strong></label>
            <input type="text" class="form-control" placeholder="Enter category name" name="name" value="<?php echo $result['name'] ?>" required>
        </div>
        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="category_list.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>