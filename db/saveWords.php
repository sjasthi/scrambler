<?php

    header("refresh:5; url=../index.php");

    if(session_id() == '' || !isset($_SESSION)){
        session_start();
    }

    // set the current page to one of the main buttons
	$nav_selected = "SAVEWORDS";

	// make the left menu buttons visible; options: YES, NO
	$left_buttons = "NO";
	
	// set the left menu button selected; options will change based on the main selection
	$left_selected = "";

	include("../includes/innerNav.php");

    //require_once('../includes/initialize.php');

    $wordList = $_SESSION['wordList'];
    $puzzle = $_SESSION['letterPuzzle'];
    $title = $_SESSION['title'];
    $subtitle = $_SESSION['subtitle'];
    $type = $_SESSION['type'];
    $set = get_next_set_id();
    $success = false;

    foreach($wordList as $word) {
        $success = insert_word($word, $set, $title, $subtitle, $type);
    }

    if ($success) {
        echo "Successfully added words to database!
        <br>
        <br>
        Returning to the home page.";
    }


    function get_next_set_id() {
        global $db;
    
        $sql = "SELECT MAX(set_id) FROM word_sets_meta";
        $result = mysqli_query($db, $sql);
    
        $next_id = mysqli_fetch_assoc($result);
        if($next_id['MAX(set_id)'] != null) {
            return $next_id['MAX(set_id)'] + 1;
        } else {
            return '1';
        }
        
    }
    
    function insert_word($word, $set, $title, $subtitle, $type) {
        global $db;
    
        $sql = "INSERT INTO word_sets ";
        $sql .= " (word) ";
        $sql .= "VALUES (";
        $sql .= "'" . $word . "')";
    
        $result = mysqli_query($db, $sql);
        
        if($result) {
            $sql = "SELECT MAX(word_id) FROM word_sets";
            $result = mysqli_query($db, $sql);
    
            confirm_result_set($result);
    
            $word_id = mysqli_fetch_assoc($result);
            if($word_id['MAX(word_id)'] != null) {}
            else {
                $word_id['MAX(word_id)'] = '1';
            }
    
            $sql = "INSERT INTO word_sets_meta ";
            $sql .= " (word_id, set_id, title, subtitle, type) ";
            $sql .= "VALUES (";
            $sql .= "'" . $word_id['MAX(word_id)'] . "',";
            $sql .= "'" . $set . "',";
            $sql .= "'" . $title . "',";
            $sql .= "'" . $subtitle . "',";
            $sql .= "'" . $type .  "'";
            $sql .= ")";
    
            $result = mysqli_query($db, $sql);
    
            if($result){
                return true;
            } else {
                //insert into word_sets_meta failed
                echo 'Insert into word_sets_meta error: ' . mysqli_error($db);
                exit;
            }
        } else {
            //insert into word_sets failed
            echo 'Insert into word_sets error: ' . mysqli_error($db);
            exit;
        }
    }
?>