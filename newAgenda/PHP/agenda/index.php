<!doctype html>
<html lang="en">
<head>
    <!-- META TAGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- FAVICON -->
    <link rel="icon" type="image/x-icon" href="../../IMG/notebook.png">

    <!-- TITLE -->
    <title>Agenda</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../../CSS/coloris.min.css">
    <link rel="stylesheet" href="../../CSS/root.css">

    <link rel="stylesheet" href="../../CSS/agenda/header.css">
    <link rel="stylesheet" href="../../CSS/agenda/agenda.css">
    <link rel="stylesheet" href="../../CSS/agenda/filter.css">

    <!-- Javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../JS/agenda/coloris.min.js"></script>

    <script src="../../JS/agenda/agenda.js" defer></script>
    <script src="../../JS/agenda/accordion.js" defer></script>
</head>

<body>
    <?php include '../forceLogin.php' ?>
    <?php include '../connectDatabase.php'; ?>
    <?php include 'addToAgenda.php'; ?>
    
    <header class="header">
        <div class="control-date">
            <div class="header-current-week">
                <?php displayWeekDateHeader(); ?>
            </div>

            <form method="post" class="week-button-wrapper">
                <div class="change-week-wrapper">
                    <input type="submit" name="prev_week" value="<" class="change-week-button">
                    <input type="submit" name="next_week" value=">" class="change-week-button">
                </div>
                <input type="submit" name="this_week" value="Vandaag" class="this-week">
            </form>

            <!-- change week --> 
            <?php include 'changeWeek.php'; ?>
        </div>

        <div class="agenda-header">
            <?php
            $daysOfWeek = ["Ma", "Di", "Wo", "Do", "Vr", "Za", "Zo"];
            $weekStart = strtotime($_SESSION['week_start']);
            
            for ($i = 0; $i < 7; $i++) {
                $currentDate = date('jS M', $weekStart + $i * 86400); // Calculate the current date
                $dayName = $daysOfWeek[date('N', $weekStart + $i * 86400) - 1]; // Get the day name (Mon, Tue, etc.)
                
                //if it is today, add the 'current-day' class DONT ASK ME HOW THIS WORKS
                $currentDayClass = (date('Y-m-d', $weekStart + $i * 86400) == date('Y-m-d') ? 'current-day' : '');
            
                echo "<div class='agenda-day $currentDayClass'><label style='font-size:1vw'>$currentDate $dayName</label></div>";
            }
            ?>
        </div>
    </header>

   <main>

   <section class="filter-wrapper">
            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Functie Toevoegen</h4> 
                </div>
                <div class="accordion-content">
                    <form method="POST" action="index.php">
                        <label for="new-functie">Nieuwe functie:</label>
                        <input type="text" id="new-functie" name="new-functie" placeholder="Naam functie"><br>

                        <label for="new-color">Nieuwe kleur:</label>
                        <input type="text" data-coloris class="coloris instance1" id="new-color" name="new-color" value="#77077d"><br>

                        <input type="hidden" name="userID" value="<?=  $userID ?>">

                        <input type="submit" name="new-color-submit" value="Toevoegen" class="new-color-submit"><br><br>
                    </form>                
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Functie Verwijderen</h4> 
                </div>
                <div class="accordion-content">
                    <form method="POST" action="">
                        <input type="hidden" name="userID" value="<?=  $userID ?>">

                        <label for="remove-functie">Verwijder functie:</label><br>
                        <select name="remove-color-select" id="remove-color-select">
                            <?php getColors($userID, $connection); ?>
                        </select><br>
                        <input type="submit" name="remove-color-submit" value="Verwijderen" class="remove-color-submit">
                    </form>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Filter</h4>
                </div>
                <div class="accordion-content">
                    <form method="POST" action="">
                        <select name="filter-functie" id="filter-functie">
                            <option value="0">Geen filter</option>
                            <?php getColors($userID, $connection) ?>
                        </select><br>
                    </form>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Deel</h4>
                </div>
                <div class="accordion-content">
                    <form method="POST" action="">
                        <input type="text" name="share-input" placeholder="Gebruikersnaam" class="share-input">
                        <input type="submit" name="share-submit" value="Deel" class="share-submit">
                    </form>

                    <form method="POST" action="">
                        <input type="submit" value="Google Agenda" name="GoogleAgenda">
                    </form>

                </div>
            </div>

            <div class="agenda-hide-wrapper">
                
        <?php 
            
            if(isset($_POST['GoogleAgenda'])){
                //copy the link to the clipboard
                $link = "http://".$_SERVER['SERVER_NAME']."/PHP/GoogleCalender/".$_SESSION['userID'].".ics";
                $link = str_replace('/PHP', '', $link);

                echo "<script> 
                    let link = '$link';
                    navigator.clipboard.writeText(link);
                </script>";                

                echo "<script>alert('je wordt door gestuurnd naar google agenda, plak daar de link er in.');</script>";
                echo "<script>window.open('https://calendar.google.com/calendar/u/0/r/settings/addbyurl', '_blank')</script>";
            }   
        
        ?>
            </div>
        </section>


        <?php 
            //if color is deleted
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
        ?>

        

        <section class="main-agenda-wrapper">
            <div class="agenda-wrapper">
                <div class='agenda-line-wrapper'>
                    <?php
                        for($i = 1; $i <= 7; $i++){
                            echo "<div class='agenda-day-line'></div>";
                        }
                    ?>
                </div>
            
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
                            
                    <?php require_once 'displayAgendaItems.php'; ?>
                </div>
            </div>
        </section>
    </main>

</body>
</html>

<script>
    const agendaGrid = document.querySelector('.agenda-grid-wrapper')
    
    let isTimeInverted = false;
    let row_amount = 24 * 4;
    let colom_amount = 7;

    let isMouseDown = false;

    let weekStart;
    let dayOffset;

    let startRow = 0;
    let startTime = 0;

    let startDate;

    let endRow = 0;
    let endTime = 0;

    let colom = 0;

    agendaGrid.addEventListener('mousedown', function(event) {
        if (event.target === agendaGrid) {
            //mouse is pressed on the agenda
            isMouseDown = true;
            startRow = get_row(event)[0] -1;
            startTime = get_row(event)[1];

            colom = get_colom(event)[0] + 1;

            weekStart = document.getElementById('week_start').value;
            weekStart = new Date(weekStart);

            startDate = new Date(weekStart);
            startDate.setDate(weekStart.getDate() + get_colom(event)[1]);

            startDate = startDate.toISOString().substring(0, 10)

            //remove all the temp agenda items
            let temp_items = document.querySelectorAll('.agenda-item-temp');
            temp_items.forEach(function (item) {
                item.remove();
            });
        }

        console.log(event);
        console.log(get_row(event));

    });

    agendaGrid.addEventListener('mousemove', function(event) {
        if(isMouseDown === true){
            //mouse is moving on the agenda and is pressed
            endRow = get_row(event)[0];
            endTime = get_row(event)[1];

            //remove all the temp agenda items
            let temp_items = document.querySelectorAll('.agenda-item-temp');
            temp_items.forEach(function(item) {
                item.remove();
            });


            let agenda_item_temp = document.createElement('div');
            agendaGrid.appendChild(agenda_item_temp);
           
            agenda_item_temp.classList.add('agenda-item-temp');


            agenda_item_temp.style.gridRowStart = startRow;
            agenda_item_temp.style.gridRowEnd = endRow;

            agenda_item_temp.style.gridColumn = colom;

            agenda_item_temp.style.backgroundColor = '#22007c';
            agenda_item_temp.style.border = '1px solid whites';

            if (startTime > endTime){
                let temp = startTime;
                startTime = endTime;
                endTime = temp;

                isTimeInverted = true;
            }



            document.getElementsByClassName('agenda-item-temp')[0].innerHTML = `
            <form method="POST" action="" autocomplete="off" id="add-to-agenda-form">
                <div>
                    <input type="text" name="agenda-naam" placeholder="Titel" value="" id="agenda-naam" required oninvalid="this.setCustomValidity('Vul een titel in')" onchange="this.setCustomValidity('')"><br>

                    <input type="text" name="agenda-omschrijving" placeholder="Omschrijving" id="agenda-omschrijving">

                    <p id="end-start-time">` + startTime + ` - ` + endTime + `</p>


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
                    <input type="time" name="agenda-start-tijd" placeholder="AgendaStartTijd" value="` + startTime + `" id="agenda-start-time" hidden><br>
                    <input type="time" name="agenda-eind-tijd" placeholder="AgendaEindTijd" value="` + endTime + `" id="agenda-eind-time" hidden><br>

                </div>
                <button type="submit" name="agenda-submit" id="agenda-submit">Voeg to aan de Agenda</button>
            </form>
             `;


            if(isTimeInverted === true){
                let temp = startTime;
                startTime = endTime;
                endTime = temp;

                isTimeInverted = false;
            }

            document.getElementById('agenda-naam').addEventListener('input', function(event){
                let input = event.target.value;
                input = input.charAt(0).toUpperCase() + input.slice(1);
                document.getElementById('agenda-naam').value = input;
            });

            document.getElementById('agenda-omschrijving').addEventListener('input', function(event){
                let input = event.target.value;
                input = input.charAt(0).toUpperCase() + input.slice(1);
                document.getElementById('agenda-omschrijving').value = input;
            });
        }


    });


    agendaGrid.addEventListener('mouseup', function() {
        //mouse is not pressed on the agenda anymore
        isMouseDown = false;

        document.getElementById('agenda-functie').addEventListener('input', function(event){
            document.getElementsByClassName('agenda-item-temp')[0].style.backgroundColor = event.target.value;
            document.getElementById('agenda-kleur').value = event.target.value;
        });

        document.getElementsByClassName('agenda-item-temp')[0].style.backgroundColor = document.getElementById('agenda-functie').value;
        document.getElementById('agenda-kleur').value = document.getElementById('agenda-functie').value;

        document.getElementById('agenda-start-date').value = startDate;
    });

    
    function get_row(event){
        let rect = agendaGrid.getBoundingClientRect();
        let y = event.clientY - rect.top;
        let row_height = rect.height / row_amount;

        //calucate the time that corresponds to the row (1 row = 15 minutes)
        let time = Math.ceil(y / row_height) * 15;
        let hours = Math.floor(time / 60);
        let minutes = time % 60;

        // format the time value so it can be used in the input field
        let formatted_time = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');

        return [(Math.ceil(y / row_height) + 1), formatted_time];
    }

    function get_colom(event){
        let clickX = event.offsetX;

        let rect = agendaGrid.getBoundingClientRect();
        let x = event.clientX - rect.left;
        let colom_width = agendaGrid.offsetWidth / colom_amount;

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

        //get the month in text
        $month = date('m', strtotime($week_start));
       
        $year = date('Y', strtotime($week_start));

        echo "<h3>$month, $year, week: $week_number</h3>";
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