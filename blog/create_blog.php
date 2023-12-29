<?php
session_start();
include "../authentication.php";
include "../config.php";
//$server = 'localhost';
//$username = 'root';
//$password = 'root1234';
//$db = 'finalTiny';
//
//$link = mysqli_connect($server,$username,$password,$db);
//
//if(!$link){
//    die('Database connection error '.mysqli_connect_error());
//}
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $cat = $_POST['editor_content'];

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO blogs (title, category, description) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);

    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, 'sss', $title, $category, $cat);

        // Execute the statement
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            $log = getHostByName($_SERVER['HTTP_HOST']) . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
                "Record created_" . time() . PHP_EOL .
                "---------------------------------------" . PHP_EOL;
            file_put_contents('../logs/log_' . date("j-n-Y") . '.log', $log, FILE_APPEND);

            $_SESSION['success'] = "One record inserted successfully";
            header('location:../blog/blog_list.php');
        } else {
            $_SESSION['error'] = "Something is wrong, Record not inserted";
            header('location:index.php');
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the error if the statement couldn't be prepared
        echo "Error: " . mysqli_error($link);
    }
}
$categoryQuery = "SELECT * FROM category";
$categoryResult = mysqli_query($link, $categoryQuery);
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
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea#basic-example',
            height: 500,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'image' + 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
            images_upload_url: 'upload.php',
            automatic_uploads: false,

            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'upload.php');

                xhr.onload = function () {
                    var json;

                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.file_path != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    success(json.file_path);
                };

                formData = new FormData();
                formData.append('file', blobInfo.filename());

                xhr.send(formData);
            },
        });
    </script>
</head>
<body>
<?php
include('../include/header.php');
?>
<div class="container">
    <h1 class="text-center">Blogs</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title"><strong>Title</strong></label>
            <input type="text" class="form-control" placeholder="Enter title" name="title" required>
        </div>
        <div class="form-group">
            <label class="control-label" for="category">Category:</label>
            <select class="form-control" name="category">
                <?php
                // Loop through categories and populate the dropdown
                while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                    echo '<option value="' . $categoryRow['name'] . '">' . $categoryRow['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="category">Description:</label>
            <textarea name="editor_content" id="basic-example"></textarea><br>
        </div>
        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary" id="save_content_form" name="submit">Submit</button>
            <a href="blog_list.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>