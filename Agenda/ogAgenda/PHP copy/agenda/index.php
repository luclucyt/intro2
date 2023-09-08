<!doctype html>
<html lang="en">
<head>
    <!-- META TAGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- FAVICON -->
    <link rel="icon" type="image/x-icon" href="../../CSS/notebook.png">

    <!-- TITLE -->
    <title>Agenda</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../../CSS/coloris.min.css">
    <link rel="stylesheet" href="../../CSS/root.css">

    <link rel="stylesheet" href="../../CSS/Agenda/agenda.css">
    <link rel="stylesheet" href="../../CSS/Agenda/header.css">
    <link rel="stylesheet" href="../../CSS/Agenda/agendaHeader.css">

    <!-- JAVA SCRIPT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../JS/agenda/coloris.min.js"></script>

    <script src="../../JS/agenda/agenda.js" defer></script>
</head>

<body>
    <?php include '../forceLogin.php' ?>
    <?php include '../connectDatabase.php'; ?>
    <?php include 'addToAgenda.php'; ?>
    

    
    <div class="main-main-agenda-wrapper">
       

   
      
        <div class='agenda-grid-wrapper'>
             
            <div class="agenda-times">
                <?php 
                    for($i = 0; $i <= 23; $i++){ 
                    echo '<div class="time-wrapper">
                            <div class="time-header"><label>'.$i.' uur</label></div>
                            <div class="time-line"></div>
                        </div> ';
                    }
                ?>
            </div>
                    
            
        </div>
    </div>
</body>
</html>


<script>
    let agenda_wrapper = document.getElementsByClassName('agenda-grid-wrapper')[0];
    let start_timeInverted = false;
    let row_amount = 96;
    let colom_amount = 7;

    let is_dragging = false;

    let week_start;
    let day_offset;

    let start_row = 0;
    let start_time = 0;

    let start_date;

    let end_row = 0;
    let end_time = 0;

    let colom = 0;

    agenda_wrapper.addEventListener('mousedown', function(event) {

        if (event.target === agenda_wrapper) {
            //mouse is pressed on the agenda
            is_dragging = true;
            start_row = get_row(event)[0];
            start_time = get_row(event)[1];

            colom = get_colom(event)[0] + 1;

            week_start = document.getElementById('week_start').value;
            week_start = new Date(week_start);

            start_date = new Date(week_start);
            start_date.setDate(week_start.getDate() + get_colom(event)[1]);

            start_date = start_date.toISOString().substring(0, 10)

            //remove all the temp agenda items
            let temp_items = document.querySelectorAll('.agenda-item-temp');
            temp_items.forEach(function (item) {
                item.remove();
            });
        }

    });

    agenda_wrapper.addEventListener('mousemove', function(event) {
        if(is_dragging === true){
            //mouse is moving on the agenda and is pressed
            end_row = get_row(event)[0];
            end_time = get_row(event)[1];

            //remove all the temp agenda items
            let temp_items = document.querySelectorAll('.agenda-item-temp');
            temp_items.forEach(function(item) {
                item.remove();
            });


            let agenda_item_temp = document.createElement('div');
            agenda_item_temp.classList.add('agenda-item-temp');

            agenda_wrapper.appendChild(agenda_item_temp);

            agenda_item_temp.style.gridRowStart = start_row;
            agenda_item_temp.style.gridRowEnd = end_row;

            agenda_item_temp.style.gridColumn = colom;

            agenda_item_temp.style.backgroundColor = '#22007c';
            agenda_item_temp.style.border = '1px solid whites';

            if (start_time > end_time){
                let temp = start_time;
                start_time = end_time;
                end_time = temp;

                start_timeInverted = true;
            }

            //if start/end time is bigger than 24:00 then change it to 23:45
            if (start_time >= 23.75){
                start_time = 23.75;
            }
            if (end_time >= 23.76){
                end_time = 23.75;
            }


            document.getElementsByClassName('agenda-item-temp')[0].innerHTML = `
            <form method="POST" action="" autocomplete="off" id="add-to-agenda-form">
                <div>
                    <input type="text" name="agenda-naam" placeholder="Titel" value="" id="agenda-naam" required oninvalid="this.setCustomValidity('Vul een titel in')" onchange="this.setCustomValidity('')"><br>

                    <input type="text" name="agenda-omschrijving" placeholder="Omschrijving" id="agenda-omschrijving">

                    <p id="end-start-time">` + start_time + ` - ` + end_time + `</p>


                    <label For="agenda-functie">Kies een functie: </label>
                    <select name="agenda-functie" id="agenda-functie">

                    <?php
                        //loop through the colors and echo them
                        $slqKleuren = "SELECT * FROM kleuren WHERE userID = '$userID'";
                        $result = mysqli_query($connection, $slqKleuren);
                        $resultCheck = mysqli_num_rows($result);

                        if($resultCheck > 0){
                            while ($row = mysqli_fetch_assoc($result)){
                                $functie = $row['functie'];
                                $kleur = $row['kleur'];
                                echo "<option value='$kleur'>$functie</option>";
                            }
                        }else{
                            echo "<option value='Geen functie' style='background-color: #22007c'>Geen functie</option>";
                        }
                    ?>

                    </select><br>

                    <input type="color" name="agenda-kleur" placeholder="AgendaKleur" id="agenda-kleur" value="#22007c" hidden><br>

                    <input type="date" name="agenda-start-datum" placeholder="AgendaDatum" id="agenda-start-date" hidden>
                    <input type="time" name="agenda-start-tijd" placeholder="AgendaStartTijd" value="` + start_time + `" id="agenda-start-time" hidden><br>
                    <input type="time" name="agenda-eind-tijd" placeholder="AgendaEindTijd" value="` + end_time + `" id="agenda-eind-time" hidden><br>

                </div>
                <button type="submit" name="agenda-submit" id="agenda-submit">Voeg to aan de Agenda</button>
            </form>
             `;

            if(start_timeInverted === true){
                let temp = start_time;
                start_time = end_time;
                end_time = temp;

                start_timeInverted = false;
            }

            document.getElementById('agenda-naam').addEventListener('input', function(event){
                let input = event.target.value;
                input = input.charAt(0).toUpperCase() + input.slice(1);
                console.log(input);
                document.getElementById('agenda-naam').value = input;
            });

            document.getElementById('agenda-omschrijving').addEventListener('input', function(event){
                let input = event.target.value;
                input = input.charAt(0).toUpperCase() + input.slice(1);
                console.log(input);
                document.getElementById('agenda-omschrijving').value = input;
            });
        }


    });


    agenda_wrapper.addEventListener('mouseup', function() {
        //mouse is not pressed on the agenda anymore
        is_dragging = false;

        document.getElementById('agenda-functie').addEventListener('input', function(event){
            document.getElementsByClassName('agenda-item-temp')[0].style.backgroundColor = event.target.value;
            document.getElementById('agenda-kleur').value = event.target.value;
        });

        document.getElementsByClassName('agenda-item-temp')[0].style.backgroundColor = document.getElementById('agenda-functie').value;
        document.getElementById('agenda-kleur').value = document.getElementById('agenda-functie').value;

        document.getElementById('agenda-start-date').value = start_date;
    });

    
    function get_row(event){
        let rect = agenda_wrapper.getBoundingClientRect();
        let y = event.clientY - rect.top;
        row_height = 14;

        //calucate the time that corresponds to the row (1 row = 15 minutes)
        let time = Math.floor(y / row_height) * 15;
        let hours = Math.floor(time / 60);
        let minutes = time % 60;

        // format the time value so it can be used in the input field
        let formatted_time = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');

       

        return [Math.floor(y / row_height), formatted_time];
    }

    function get_colom(event){
        let rect = agenda_wrapper.getBoundingClientRect();
        let x = event.clientX - rect.left;
        let colom_width = agenda_wrapper.clientWidth / colom_amount;

        //calucate the day ofset that corresponds to the colom
        day_offset = Math.floor(x / colom_width);

        return [Math.floor(x / colom_width), day_offset];
    }
</script>

<?php
    function displayWeekDateHeader() {
        //set the week start and end date in the session (if not already set)
        if(!isset($_SESSION['week_start']) || !isset($_SESSION['week_end'])) {
            $date = date('Y-m-d');
            $current_week_day = date('N', strtotime($date));
            $week_start = date('Y-m-d', strtotime('-' . ($current_week_day - 1) . ' days', strtotime($date)));
            $week_end = date('Y-m-d', strtotime('+' . (7 - $current_week_day) . ' days', strtotime($date)));
            $_SESSION['week_start'] = $week_start;
            $_SESSION['week_end'] = $week_end;
        }

        $week_start = $_SESSION['week_start'];

        //update the week if the user clicks on the buttons
        if(isset($_POST['this_week'])){
            $date = date('Y-m-d');
            $current_week_day = date('N', strtotime($date));
            $week_start = date('Y-m-d', strtotime('-' . ($current_week_day - 1) . ' days', strtotime($date)));
        }
        if(isset($_POST['prev_week'])){
            $week_start = date('Y-m-d', strtotime('-1 week', strtotime($week_start)));
        }
        if(isset($_POST['next_week'])){
            $week_start = date('Y-m-d', strtotime('+1 week', strtotime($week_start)));
        }

        $week_number = date('W', strtotime($week_start));

        $month = date('F', strtotime($week_start));

        if($month == "January"){  $month = "Januari"; }
        if($month == "February"){ $month = "Februari"; }
        if($month == "March"){    $month = "Maart"; }
        if($month == "May"){      $month = "Mei"; }
        if($month == "June"){     $month = "Juni"; }
        if($month == "July"){     $month = "Juli"; }
        if($month == "August"){   $month = "Augustus"; }
        if($month == "October"){  $month = "Oktober"; }

        $year = date('Y', strtotime($week_start));

        echo "<h3>$month $year, week: $week_number</h3>";
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
?>


<?php
//ISSETS
if (isset($_POST['logout'])) {
    session_destroy();
    echo "<script>window.location.href = '../login/login.php';</script>";
}

if(isset($_POST['share-from-submit'])){
    $shareUsername = $_POST['share-main-form'];
    $shareUserID = $_SESSION['userID'];

    $SQL = "SELECT * FROM login WHERE username = '$shareUsername'";
    $resultShare = mysqli_query($connection, $SQL);
    $resultShareCheck = mysqli_num_rows($resultShare);

    if ($resultShareCheck > 0) {
        while ($row = mysqli_fetch_assoc($resultShare)) {
            $shareUsername = $row['id'];
        }
    }

    $slqAlreadyShared = "SELECT * FROM access WHERE accesUserID = '$shareUsername' AND userID = '$userID'";
    $resultAlreadyShared = mysqli_query($connection, $slqAlreadyShared);
    $resultCheckAlreadyShared = mysqli_num_rows($resultAlreadyShared);

    //if the user is already shared with the user it wants to share with
    if(!$resultCheckAlreadyShared > 0){
        $SQL = "SELECT * FROM login WHERE id = '$shareUsername'";
        $resultShare = mysqli_query($connection, $SQL);

        $resultCheck = mysqli_num_rows($resultShare);

        if($resultCheck > 0){
            while($row = mysqli_fetch_assoc($resultShare)) {
                $shareUserID = $row['id'];
                
                $SqlAccess = "INSERT INTO access (id, userID, accesUserID) VALUES ('', '$shareUserID', '$userID')";
                
                $result = mysqli_query($connection, $SqlAccess);
                $resultCheck = mysqli_num_rows($result);


                //get the data from the database
                $sqlAgenda = "SELECT * FROM agenda WHERE userID = '$userID' AND accesUserID = '$shareUserID'";
                $resultAgenda = mysqli_query($connection, $sqlAgenda);

                while ($row = mysqli_fetch_assoc($resultAgenda)){
                    $id = $row['id'];
                    $userID = $row['userID'];
                    $accesUserID = $row['accesUserID'];

                    writeToCustomDB("access", $id, $userID);
                }                    

                if($result){
                    echo "<script>alert('De gebruiker heeft nu toegang tot jouw agenda')</script>";
                    echo "<script>window.location.href = 'index.php'</script>";
                }
            }
        }
    }
}

//delete button is pressed
if(isset($_POST['agenda-delete'])){
    $id = $_POST['id'];

    //get the data before deleting it
    $sqlAgenda = "SELECT * FROM agenda WHERE id = '$id'";
    $resultAgenda = mysqli_query($connection, $sqlAgenda);
    while ($row = mysqli_fetch_assoc($resultAgenda)) {
        $agendaItemID = $row['id'];
        $agendaItemUserID = $row['userID'];
        $agendaItemNaam = $row['naam'];
        $agendaItemOmschrijving = $row['omschrijving'];
        $agendaItemStartDatum = $row['startDatum'];
        $agendaItemEindDatum = $row['eindDatum'];
        $agendaItemStartTijd = $row['startTijd'];
        $agendaItemEindTijd = $row['eindTijd'];
        $agendaItemtaak = $row['taak'];
        $agendaItemFunctie = $row['functie'];
        $agendaItemKleur = $row['kleur'];

        removeFromCustomDB("agenda", $agendaItemID, $agendaItemUserID, $agendaItemNaam, $agendaItemOmschrijving, $agendaItemStartDatum, $agendaItemEindDatum, $agendaItemStartTijd, $agendaItemEindTijd, $agendaItemtaak, $agendaItemFunctie, $agendaItemKleur);
    }

    $sqlAgenda = "DELETE FROM agenda WHERE id = '$id'";
    $result = mysqli_query($connection, $sqlAgenda);

    //refresh the page
    echo "<script>window.location.href = 'index.php';</script>";
} 

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

            removeFromCustomDB("kleuren", $oldColorID, $olduserID, $oldColor, $oldFunction);
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

            writeToCustomDB("kleuren", $newColorID, $userID, $newColor, $newFunction);
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

            writeToCustomDB("kleuren", $newColorID, $userID, $newColor, $newFunction);
        }

    }
    //refresh the page
    echo "<script>window.location.href = 'index.php';</script>";
}