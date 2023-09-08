<?php
if(isset($_POST['agenda-submit'])) {
    //connect to the database
    //require_once "connectDatabase.php";

    //get the input data
    $agenda_naam = $_POST['agenda-naam'];
    $agenda_omschrijving = $_POST['agenda-omschrijving'];
    $agenda_start_datum = $_POST['agenda-start-datum'];
    $agenda_eind_datum = $_POST['agenda-start-datum'];
    $agenda_repeat_item = $_POST['agenda-repeat-item'];
    $agenda_start_tijd = $_POST['agenda-start-tijd'];
    $agenda_eind_tijd = $_POST['agenda-eind-tijd'];
    $agenda_functie = $_POST['agenda-functie'];
    $agenda_kleur = $_POST['agenda-kleur'];

    //round the start and end time to the nearest 15 minutes
    $agenda_start_tijd = date("H:i", round(strtotime($agenda_start_tijd) / 900) * 900);
    $agenda_eind_tijd = date("H:i", round(strtotime($agenda_eind_tijd) / 900) * 900);

    //if the end time is before the start time, invert the start and end time
    if ($agenda_start_tijd > $agenda_eind_tijd) {
        $agenda_start_tijd = $_POST['agenda-eind-tijd'];
        $agenda_eind_tijd = $_POST['agenda-start-tijd'];
    }

    //if the start time is the same as the end time, set the end time to 15 minutes later
    if ($agenda_start_tijd == $agenda_eind_datum) {
        $agenda_eindDatum = date("H:i", strtotime($agenda_eind_tijd) + 900);
    }

    //if the end time is 00:00, set the end time to 23:59
    if ($agenda_eind_tijd == "00:00") {
        $agenda_eind_tijd = "23:59";
    }

    if($agenda_repeat_item == 0){
        $agenda_repeat_item = 'false';
    }
    if($agenda_repeat_item == 1){
        $agenda_repeat_item = 'week';
    }else{
        $agenda_repeat_item = 'false';
    }

    //prevent sql injection
    $agenda_naam = mysqli_real_escape_string($connection, $agenda_naam);
    $agenda_omschrijving = mysqli_real_escape_string($connection, $agenda_omschrijving);
    $agenda_start_datum = mysqli_real_escape_string($connection, $agenda_start_datum);
    $agenda_eind_datum = mysqli_real_escape_string($connection, $agenda_eind_datum);
    $agenda_repeat_item = mysqli_real_escape_string($connection, $agenda_repeat_item);
    $agenda_start_tijd = mysqli_real_escape_string($connection, $agenda_start_tijd);
    $agenda_eind_tijd = mysqli_real_escape_string($connection, $agenda_eind_tijd);
    $agenda_functie = mysqli_real_escape_string($connection, $agenda_functie);
    $agenda_kleur = mysqli_real_escape_string($connection, $agenda_kleur);

    //insert the data into the database
    $sqlAgenda = "INSERT INTO agenda (id, userID, naam, omschrijving, startDatum, eindDatum, repeatItem, startTijd, eindTijd, taak, functie, kleur) VALUES ('', '$userID', '$agenda_naam' , '$agenda_omschrijving', '$agenda_start_datum', '$agenda_eind_datum', '$agenda_repeat_item' ,'$agenda_start_tijd', '$agenda_eind_tijd', 'false', '$agenda_functie', '$agenda_kleur')";

    //run the query in the database
    $result = mysqli_query($connection, $sqlAgenda);

    //get tha data from the database
    $sqlAgenda = "SELECT * FROM agenda WHERE userID = '$userID' AND naam = '$agenda_naam' AND omschrijving = '$agenda_omschrijving' AND startDatum = '$agenda_start_datum' AND eindDatum = '$agenda_eind_datum' AND startTijd = '$agenda_start_tijd' AND eindTijd = '$agenda_eind_tijd' AND functie = '$agenda_functie' AND kleur = '$agenda_kleur'";
    $result = mysqli_query($connection, $sqlAgenda);

    while ($row = mysqli_fetch_assoc($result)) {
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

    updateICS($connection);
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

    updateICS($connection);
}


function updateICS($connection){
    //rewrite the ICS file so we can add it if needed to google agenda
    $fileName = "GoogleCalender/".$_SESSION['userID'].".ics";
    $agendaFile = fopen($fileName, 'a');
    
    file_put_contents($fileName, "");

    fwrite($agendaFile, "BEGIN:VCALENDAR\n");
    fwrite($agendaFile, "VERSION:2.0\n\n");

    $sqlAgenda = "SELECT * FROM agenda WHERE userID = '$_SESSION[userID]'";
    $resultAgenda = mysqli_query($connection, $sqlAgenda);
    while ($row = mysqli_fetch_assoc($resultAgenda)) {
        fwrite($agendaFile, "BEGIN:VEVENT\n");
        fwrite($agendaFile, "DTSTART:". str_replace('-', '' , $row['startDatum'])."T". str_replace(':', '' , $row['startTijd'])."\n");
        fwrite($agendaFile, "DTEND:".str_replace('-', '' , $row['eindDatum'])."T".str_replace(':', '' , $row['eindTijd'])."\n");
        fwrite($agendaFile, "SUMMARY:".$row['naam']."\n");
        fwrite($agendaFile, "DESCRIPTION:".$row['omschrijving']."\n");
        fwrite($agendaFile, "END:VEVENT\n\n");
    }

    fwrite($agendaFile, "END:VCALENDAR\n");

    fclose($agendaFile);
}