<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Short URL</title>

    <!-- CSS -->
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php
        include 'database.php';
    ?>
    <form action="" method="POST">
        <div class="form">
            <input type="text" name="url" placeholder="Enter URL">
            <input type="submit" value="Shorten" name="shortSubmit">
        </div>

        <div class="link">
            <?php
            if(isset($_POST['shortSubmit'])){
                $url = $_POST['url'];
                $code = substr(md5(uniqid(mt_rand(), true)), 0, 5);

                $sql = "INSERT INTO links (code, link) VALUES ('$code', '$url')";
                mysqli_query($conn, $sql);
                echo "Short URL: <a href='http://localhost:3000?link=$code'>http://localhost:3000?link=$code</a>";
            }
            ?>
        </div>
    </form> 
</body>
</html>

<?php 



if(isset($_GET['link'])){
    $code = $_GET['link'];
    $sql = "SELECT link FROM links WHERE code='$code'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $link = $row['link'];
    header("Location: $link");
}
?>