<?php
session_start();
include "../authentication.php";
include "../config.php";

$id = $_GET['id'];
$fsql = "SELECT * FROM blogs WHERE id=?";
$fquery = $link->prepare($fsql);
$fquery->bind_param("i", $id);
$fquery->execute();
$result = $fquery->get_result()->fetch_assoc();

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $category = $_POST['category'];
    $cat = $_POST['message'];

    $usql = "UPDATE blogs SET title=?, category=?, description=? WHERE id=?";
    $uquery = $link->prepare($usql);
    $uquery->bind_param("sssi", $title, $category, $cat, $id);

    if ($uquery->execute()) {
        $log = getHostByName($_SERVER['HTTP_HOST']) . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
            "Record updated_" . time() . PHP_EOL .
            "---------------------------------------" . PHP_EOL;
        file_put_contents('../logs/log_' . date("j-n-Y") . '.log', $log, FILE_APPEND);

        $_SESSION['success'] = "One record updated successfully";
        header('location:blog_list.php');
    } else {
        $_SESSION['error'] = "Something is wrong, Record not updated";
        header('location:blog_list.php');
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
    <title>Modify User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea#tinymce',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table paste codesample",
            ],
            toolbar:
                "undo redo | fontselect styleselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | codesample action section button | custom_button",
            content_css: [window.location.origin+"/assets/css/custom_css_tinymce.css"],
            font_formats:"Segoe UI=Segoe UI;",
            fontsize_formats: "8px 9px 10px 11px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px 42px 44px 46px 48px 50px 52px 54px 56px 58px 60px 62px 64px 66px 68px 70px 72px 74px 76px 78px 80px 82px 84px 86px 88px 90px 92px 94px 94px 96px",
            codesample_languages: [
                {text: 'HTML/XML', value: 'markup'},
                {text: 'JavaScript', value: 'javascript'},
                {text: 'CSS', value: 'css'},
                {text: 'PHP', value: 'php'},
                {text: 'Ruby', value: 'ruby'},
                {text: 'Python', value: 'python'},
                {text: 'Java', value: 'java'},
                {text: 'C', value: 'c'},
                {text: 'C#', value: 'csharp'},
                {text: 'C++', value: 'cpp'}
            ],
            height: 600,
            setup: function (editor) {
                editor.ui.registry.addButton('custom_button', {
                    text: 'Add Button',
                    onAction: function() {
                        // Open a Dialog
                        editor.windowManager.open({
                            title: 'Add custom button',
                            body: {
                                type: 'panel',
                                items: [{
                                    type: 'input',
                                    name: 'button_label',
                                    label: 'Button label',
                                    flex: true
                                },{
                                    type: 'input',
                                    name: 'button_href',
                                    label: 'Button href',
                                    flex: true
                                },{
                                    type: 'selectbox',
                                    name: 'button_target',
                                    label: 'Target',
                                    items: [
                                        {text: 'None', value: ''},
                                        {text: 'New window', value: '_blank'},
                                        {text: 'Self', value: '_self'},
                                        {text: 'Parent', value: '_parent'}
                                    ],
                                    flex: true
                                },{
                                    type: 'selectbox',
                                    name: 'button_rel',
                                    label: 'Rel',
                                    items: [
                                        {text: 'No value', value: ''},
                                        {text: 'Alternate', value: 'alternate'},
                                        {text: 'Help', value: 'help'},
                                        {text: 'Manifest', value: 'manifest'},
                                        {text: 'No follow', value: 'nofollow'},
                                        {text: 'No opener', value: 'noopener'},
                                        {text: 'No referrer', value: 'noreferrer'},
                                        {text: 'Opener', value: 'opener'}
                                    ],
                                    flex: true
                                },{
                                    type: 'selectbox',
                                    name: 'button_style',
                                    label: 'Style',
                                    items: [
                                        {text: 'Success', value: 'success'},
                                        {text: 'Info', value: 'info'},
                                        {text: 'Warning', value: 'warning'},
                                        {text: 'Error', value: 'error'}
                                    ],
                                    flex: true
                                }]
                            },
                            onSubmit: function (api) {

                                var html = '<a href="'+api.getData().button_href+'" class="btn btn-'+api.getData().button_style+'" rel="'+api.getData().button_rel+'" target="'+api.getData().button_target+'">'+api.getData().button_label+'</a>';

                                // insert markup
                                editor.insertContent(html);

                                // close the dialog
                                api.close();
                            },
                            buttons: [
                                {
                                    text: 'Close',
                                    type: 'cancel',
                                    onclick: 'close'
                                },
                                {
                                    text: 'Insert',
                                    type: 'submit',
                                    primary: true,
                                    enabled: false
                                }
                            ]
                        });
                    }
                });
            }
        });

        $(document).ready(function() {
            $('#save-content-form').on('submit', function(e) {
                e.preventDefault();

                var data = $('#save-content-form').serializeArray();
                data.push({name: 'content', value: tinyMCE.get('tinymce').getContent()});

                $.ajax({
                    type: 'POST',
                    url: 'upload.php',
                    data: data,
                    success: function (response, textStatus, xhr) {
                        console.log(response)
                    },
                    complete: function (xhr) {

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;

                    }
                });
            });
        });
    </script>
</head>
<body>
<?php
include('../include/header.php');
?>
<div class="container">
    <h1 class="text-center">Edit Existing Blog</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title"><strong>Title</strong></label>
            <input type="text" class="form-control" placeholder="Enter title" name="title" value="<?php echo $result['title'] ?>" required>
        </div>
        <div class="form-group">
            <label class="control-label" for="category">Category:</label>
            <select class="form-control" name="category">
                <?php
                // Loop through categories and populate the dropdown
                while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                    $selected = ($categoryRow['name'] == $result['category']) ? 'selected' : '';
                    echo '<option value="' . $categoryRow['name'] . '" ' . $selected . '>' . $categoryRow['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="category">Description:</label>
            <textarea name="message" id="tinymce"><?php echo $result['description'] ?></textarea><br>
        </div>
        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="blog_list.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>