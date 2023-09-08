<?php
//if new color is submitted
if(isset($_POST['new-color-submit'])){
    $newFunction = $_POST['new-functie'];
    $newColor = $_POST['new-color'];

    echo "<script>alert('{$newFunction}');</script>";
    echo "<script>alert('{$newColor}');</script>";

    $sqlNewColor = "SELECT * FROM kleuren WHERE userID = '$userID' AND functie = '$newFunction'";
    $resultNewColor = mysqli_query($connection, $sqlNewColor);
    $resultCheckNewColor = mysqli_num_rows($resultNewColor);

    if($resultCheckNewColor > 0){
        //get the values from the custom database
        $sqlNewColor = "SELECT * FROM kleuren WHERE userID = '$userID' AND functie = '$newFunction'";
        $resultNewColor = mysqli_query($connection, $sqlNewColor);

        //remove the old color from the database
        while ($row = mysqli_fetch_assoc($resultNewColor)) {
            $oldColorID = $row['id'];
            $olduserID = $row['userID'];
            $oldColor = $row['kleur'];
            $oldFunction = $row['functie'];

        }

        $sqlNewColor = "UPDATE kleuren SET kleur = '$newColor' WHERE userID = '$userID' AND functie = '$newFunction'";
        $resultNewColor = mysqli_query($connection, $sqlNewColor);


        $resultNewColor = mysqli_query($connection, $sqlNewColor);

        //add the new color to the database
        $sqlNewColor = "SELECT * FROM kleuren WHERE userID = '$userID' AND functie = '$newFunction'";
        $resultNewColor = mysqli_query($connection, $sqlNewColor);

        while($row = mysqli_fetch_assoc($resultNewColor)) {
            $newColorID = $row['id'];
            $userID = $row['userID'];
            $newColor = $row['kleur'];
            $newFunction = $row['functie'];

        }

    } else {
        $sqlNewColor = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES (NULL, '$userID', '$newColor', '$newFunction')";
        $resultNewColor = mysqli_query($connection, $sqlNewColor);


        $sqlNewColor = "SELECT * FROM kleuren WHERE userID = '$userID' AND functie = '$newFunction'";
        $resultNewColor = mysqli_query($connection, $sqlNewColor);

        while($row = mysqli_fetch_assoc($resultNewColor)) {
            $newColorID = $row['id'];
            $userID = $row['userID'];
            $newColor = $row['kleur'];
            $newFunction = $row['functie'];

        }

    }
    //refresh the page
    echo "<script>window.location.href = 'index.php';</script>";
}

//if function is deleted
if(isset($_POST['remove-color-select'])){
    $id = $_POST['remove-color-select'];

    //get the data from the database
    $sql = "SELECT * FROM kleuren WHERE userID = '{$userID}' AND kleur = '{$id}'";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $colorID = $row['id'];
        $colorUserID = $row['userID'];
        $colorName = $row['kleur'];
        $colorFunction = $row['functie'];
    }

    $SQL = "DELETE FROM kleuren WHERE userID = '{$userID}' AND kleur = '{$id}'";

    $result = mysqli_query($connection, $SQL);

    echo "<script>window.location.href</script>";
    echo "<script>window.location.reload()</script>";
}

function getColors($userID, $connection){
    $removeColorQuery = "SELECT * FROM kleuren WHERE userID = $userID";
    $removeColorResult = mysqli_query($connection, $removeColorQuery);
    while($row = mysqli_fetch_assoc($removeColorResult)) {
        $id = $row['kleur'];
        $functie = $row['functie'];
        echo "<option value='$id'>$functie</option>";
    }
}