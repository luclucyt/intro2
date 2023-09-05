<?php
if(isset($_POST['newItem'])){
    $name = $_POST['itemName'];

    //insert item name AND status into database
    $sql = "INSERT INTO Todo (name, status) VALUES ('$name', 'incomplete')";
    $result = mysqli_query($conn, $sql);
}

if(isset($_POST['complete'])){
    $id = $_POST['id'];

    //update status to complete
    $sql = "UPDATE Todo SET status = 'complete' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
}

if(isset($_POST['incomplete'])){
    $id = $_POST['id'];

    //update status to incomplete
    $sql = "UPDATE Todo SET status = 'incomplete' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
}

if(isset($_POST['delete'])){
    $id = $_POST['id'];

    //delete item from database
    $sql = "DELETE FROM Todo WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
}