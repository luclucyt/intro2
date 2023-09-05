<script>
    if(window.history.replaceState){
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- CSS -->
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php 
        include 'database.php';
        include 'newItem.php';
    ?>

<main>
    <h1>New Item:</h1>
    <form class="new-item-form" method="POST" action="">
        <input type="text" name="itemName" placeholder="Item Name" class="new-item-input">

        <input type="submit" value="Add Item" name="newItem" class="new-item-submit">
    </form>


    <div class="display-todo">
        <?php
            $sql = "SELECT * FROM Todo WHERE status = 'incomplete'";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                echo "<div class='todo-item'>";
                    echo "<h3>".$row['name']."</h3>";
                    echo "<form method='POST' action='' class='item-form incomplete-form'>";
                        echo "<input type='hidden' name='id' value='".$row['id']."' hidden>";
                        echo "<input type='submit' name='complete' value='Complete'>";
                        echo "<input type='submit' name='delete' value='Delete'>";
                    echo "</form>";
                echo "</div>";
            }
        ?>
    </div>

    <div class="display-complete">
        <?php
            $sql = "SELECT * FROM Todo WHERE status = 'complete'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) != 0){
                echo "<h1>Completed:</h1>";
            }
            while($row = mysqli_fetch_assoc($result)){
                echo "<div class='todo-item'>";
                    echo "<h3>".$row['name']."</h3>";
                    echo "<form method='POST' action='' class='item-form complete-form'>";
                        echo "<input type='hidden' name='id' value='".$row['id']."'>";
                        echo "<input type='submit' name='incomplete' value='Incomplete'>";
                        echo "<input type='submit' name='delete' value='Delete'>";
                    echo "</form>";
                echo "</div>";
            }
        ?>
    </div>
    </main>
</body>
</html>