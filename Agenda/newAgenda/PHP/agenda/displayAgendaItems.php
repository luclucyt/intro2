<?php
// Query the database for all agenda items within the current week range
$sqlAgenda = "SELECT * FROM agenda WHERE ((startDatum >= '$week_start' AND eindDatum <= '$week_end') OR repeatItem = 'week' ) AND userID = '$userID'";
$result = mysqli_query($connection, $sqlAgenda);
$resultCheck = mysqli_num_rows($result);

while ($row = mysqli_fetch_assoc($result)) {
    if($row['repeatItem'] != 'week'){
        //calculate the difference in days between the start date and the week start date
        $start = new DateTime($row['startDatum']);
        $end = new DateTime($week_start);
        
        $dayDifference = $start->diff($end)->format("%a");
    }else{
        //calculate the difference in days between the start date and the week start date
        $eventDate = $row['startDatum'];
        $eventDateTime = new DateTime($eventDate);

        $dayOfWeek = $eventDateTime->format("N");
        $firstDayOfWeek = date("Y-m-d", strtotime($eventDate . "- {$dayOfWeek} days + 1 day"));

        $eventDate = new DateTime($eventDate);
        $firstDayOfWeek = new DateTime($firstDayOfWeek);

        $dayDifference = $eventDate->diff($firstDayOfWeek)->format("%a");
    }

    //remove the seconds from the start time and end time
    $row['startTijd'] = substr($row['startTijd'], 0, -3);
    $row['eindTijd'] = substr($row['eindTijd'], 0, -3);

    //calculate the gird row start and end position of the event based on the start and end time when every 15 minutes is 1 row (there are 96 rows in the grid)
    $startRow = floor(((strtotime($row['startTijd']) - strtotime('00:00')) / 900) + 1);
    $endRow = floor(((strtotime($row['eindTijd']) - strtotime('00:00')) / 900) + 1);
    

    $agenda_item_id1 = $row['id'];
    $agenda_item_naam = $row['naam'];
    $agenda_item_omschrijving = $row['omschrijving'];
    $agenda_item_startDatum = $row['startDatum'];
    $agenda_item_startTijd = $row['startTijd'];
    $agenda_item_eindTijd = $row['eindTijd'];
    $agenda_item_functie = $row['functie'];
    $agenda_item_kleur = $row['kleur'];

    echo "<div class='agenda-item agenda-date{$dayDifference} {$agenda_item_functie} userID{$userID}' id='agendaID{$agenda_item_id1} ' style='background-color:{$agenda_item_functie}; grid-row-start:{$startRow};grid-row-end:{$endRow};'>";
        echo "<form method='POST' action='' class='agenda-form'>";
            echo "<input type='hidden' name='id' value='{$agenda_item_id1}'>";
            echo "<input type='text' name='agenda-item-naam' value='{$agenda_item_naam}' class='agenda-item-naam' placeholder='Title'>";
            echo "<textarea name='agenda-item-omschrijving' class='agenda-item-omschrijving' placeholder='Description'>{$agenda_item_omschrijving}</textarea>";

            echo "<input type='time' name='agenda-item-start-tijd' value='{$agenda_item_startTijd}' class='agenda-item-start-tijd' hidden> ";
            echo "<input type='time' name='agenda-item-eind-tijd' value='{$agenda_item_eindTijd}' class='agenda-item-eind-tijd' hidden>";            
            
            echo "<div class='agenda-item-tijd'>{$agenda_item_startTijd} - {$agenda_item_eindTijd}</div>";
            
            echo "<div class='change-time-item change-time-item-top'></div>";
            echo "<div class='change-time-item change-time-item-bottom'></div>";

            echo "<div class='agenda-form-wrapper'>";

                echo "<form method='POST' action='index.php'>";
                    echo "<input type='hidden' name='id' value='{$agenda_item_id1}'>";
                    echo "<button type='submit' name='agenda-delete' class='agenda-delete'>Delete</button>";
                echo "</form>";
            echo "</div>";
        echo "</form>";

    //close the agenda item
    echo "</div>";
}