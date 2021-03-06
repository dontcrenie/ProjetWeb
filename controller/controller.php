<?php
//direction de base
$page ="home";

//script de connection et l'inscription
if(isset($_GET["ac"])){
    if($_GET["ac"]=="signin"){
        if(user_signin($_POST[ "pseudo"], $_POST["password"], $c, $encryption_key)){
            $page = "connection_success";
        }
        else{
            $page = "connection_failed";
        }
    }
    //incription et connection automatique
    if($_GET["ac"]=="signup"){
        if(user_signup($_POST["fname"], $_POST["lname"], $_POST["adressepostal"], $_POST["codepostal"], $_POST["ville"], $_POST["email"], $_POST["password"], $_POST["tel"], $c, $encryption_key)){
            user_signin($_POST["email"], $_POST["password"], $c, $encryption_key);

        }
        else {
            $page = "sub_failed";
        }
    }
    //test de la reservation
    if($_GET["ac"]=="reserv"){
        $date = implode('-',array_reverse(explode('/',$_POST["date"])));
        if(user_reserv($_SESSION['id'], $_POST['idformule'], $_POST['idsalle'], $_POST["fnameenfant"], $_POST["lnameenfant"], $_POST["age"], $date, $_POST["creneau"], $_POST["childnb"]+1, $_POST["adultnb"]+1, $_POST["drinknb"]+1, $_POST["cakenb"]+1, $_POST["total_cost"],  $c, $encryption_key)){
            $reservations = get_reserv_by_user_id($_SESSION['id'], $c);
            $user_infos = get_info_user_by_id($_SESSION['id'], $c);
            $page = "user_log";
        }
        else {
            $page = "reserv_failed";
        }
    }


}


// Vérification si le user est enregister

if(isset($_SESSION['stats']) and $page != "connection_failed" and $page != "sub_failed"){
    if(isset($_GET["user_log"])){
        $reservations = get_reserv_by_user_id($_SESSION['id'], $c);
        $user_infos = get_info_user_by_id($_SESSION['id'], $c);
        $page = "user_log";
    }
}
else {
    $_SESSION['stats'] = "new_user";
}

//formulaire d'incription
if(isset($_GET["subform"])){
    $page="user_sub";
}

// Page A propos

if(isset($_GET["propos"])){
    $page = "propos";
}

// Page Attraction

if(isset($_GET["attraction"])){
    $attractions=recup_all_attraction($c, $encryption_key);
    $page = "attraction";
}
//formulaire de reservation
if(isset($_GET["reservform"])){
    if($_GET["reservform"]=="reserv"){
        $formules = recup_formule($c, $encryption_key);
        $salle = recup_hall_by_id($_POST['hall-choice'], $c, $encryption_key);
        $reservs = recup_all_reserv($c, $encryption_key);
        $page="user_reserv";
    }
    if($_GET["reservform"]=="hall_select"){
        $salles=recup_all_hall($c, $encryption_key);
        $page="hall_select";
    }
}

//formulaire de modification d'information
if(isset($_GET["infoform"])){
    $page="update_info_form";
}




//déconnection
if(isset($_GET["logout"])){
    user_logout();
    header('location: index.php');
}



