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
    <?php include 'agendaItems.php'; ?>
    <?php include 'functions.php'; ?>
    
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
                <input type="submit" name="this_week" value="Today" class="this-week">
            </form>

            <!-- change week --> 
            <?php include 'changeWeek.php'; ?>
        </div>

        <div class="agenda-header">
            <?php
            $daysOfWeek = ["Mon", "Thu", "Wen", "Thu", "Fri", "Sat", "Sun"];
            $weekStart = strtotime($_SESSION['week_start']);
            
            for ($i = 0; $i < 7; $i++) {
                $currentDate = date('jS', $weekStart + $i * 86400); // Calculate the current date
                $dayName = $daysOfWeek[date('N', $weekStart + $i * 86400) - 1]; // Get the day name (Mon, Tue, etc.)
                
                //if it is today, add the 'current-day' class DONT ASK ME HOW THIS WORKS
                $currentDayClass = (date('Y-m-d', $weekStart + $i * 86400) == date('Y-m-d') ? 'current-day' : '');
            
                echo "<div class='agenda-day $currentDayClass'><label style='font-size:1vw'>$dayName $currentDate</label></div>";
            }
            ?>
        </div>
    </header>

    <main>
        <section class="filter-wrapper">
            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Add function</h4> 
                </div>
                <div class="accordion-content">
                    <form method="POST" action="index.php">
                        <label for="new-functie">Name:</label>
                        <input type="text" id="new-functie" name="new-functie" placeholder="Function name"><br>

                        <label for="new-color">Color:</label>
                        <input type="text" data-coloris class="coloris instance1" id="new-color" name="new-color" value="#d50000"><br>

                        <input type="hidden" name="userID" value="<?=  $userID ?>">

                        <input type="submit" name="new-color-submit" value="Toevoegen" class="new-color-submit"><br><br>
                    </form>                
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Remove function</h4> 
                </div>
                <div class="accordion-content">
                    <form method="POST" action="">
                        <input type="hidden" name="userID" value="<?=  $userID ?>">

                        <label for="remove-functie">Remove function:</label><br>
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
                            <option value="0">No Filter</option>
                            <?php getColors($userID, $connection) ?>
                        </select><br>
                    </form>
                </div>
            </div>

            <div class="accordion-item">
                <div class="accordion-item-header">
                    <h4>Share</h4>
                </div>
                <div class="accordion-content">
                
                    <form method="POST" action="">
                        <input type="submit" value="Google Agenda" name="GoogleAgenda">
                    </form>

                </div>
            </div>

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
        </section>


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
                        
                    <div class='current-time-line'></div>
                    
                    <?php require_once 'displayAgendaItems.php'; ?>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
<script>
    document.getElementsByClassName("agenda-wrapper")[0].scrollTop = 375;


    const agendaGrid = document.querySelector('.agenda-grid-wrapper')

    let todayColom = 0;
    let timeOffset = 0;

    //calculate the time offset
    let rect = agendaGrid.getBoundingClientRect();
    let row_height = rect.height / 96;

    let date = new Date();
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let minutesOffset = hours * 60 + minutes;

    timeOffset = minutesOffset * row_height / 15;

    //calculate the colom (it must be in a way that it only shows the current day) (not on an other week, month or year)
    let weekStart = document.getElementById('week_start').value;
    weekStart = new Date(weekStart);

    let Today = new Date();


    //loop over the week start, and add 1 day to it every time if it hits the same day as today
    for(let i = 0; i < 7; i++){
        if(weekStart.getDate() === Today.getDate()){
            todayColom = i;
        }

        weekStart.setDate(weekStart.getDate() + 1);
        
    }

    
   
    document.querySelector('.current-time-line').style.gridColumn = todayColom + 1;
    document.querySelector('.current-time-line').style.marginTop =  timeOffset + 'px';

    if(todayColom == 0){
        document.querySelector('.current-time-line').style.opacity = "0"
    }

    
    let isTimeInverted = false;
    let row_amount = 24 * 4;
    let colom_amount = 7;

    let isMouseDown = false;


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
            startRow = get_row(event)[0];
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
            document.documentElement.style.cursor = "pointer";
        }
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
                        getColors($userID, $connection);
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

        document.documentElement.style.cursor = "default";
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
        $month = date('F', strtotime($week_start));
        $monthNames = ["Jan", "Feb", "Maa", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"];
        $month = $monthNames[date('n', strtotime($week_start)) - 1];
        
        $year = date('Y', strtotime($week_start));

        echo "<h3>$month $year, week: $week_number</h3>";
    }
?>


<?php 
if (!empty($_POST['agenda-item-naam'])) {
    $agenda_item_id = $_POST['id'];
    $agenda_item_naam = $_POST['agenda-item-naam'];
    $agenda_item_omschrijving = $_POST['agenda-item-omschrijving'];
    $agenda_item_start_tijd = $_POST['agenda-item-start-tijd'];
    $agenda_item_eind_tijd = $_POST['agenda-item-eind-tijd'];

    $sql = "UPDATE agenda SET naam = '$agenda_item_naam', omschrijving = '$agenda_item_omschrijving', startTijd = '$agenda_item_start_tijd', eindTijd = '$agenda_item_eind_tijd' WHERE id = '$agenda_item_id'";
    $result = mysqli_query($connection, $sql);

    echo '<div class="test-wrapper">succesfull</div>';
}
