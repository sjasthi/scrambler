<?php

    if(session_id() == '' || !isset($_SESSION)){
    session_start();
    }

    $nav_selected = "ADMIN";
    $left_buttons = "NO";
    $left_selected = "WORDSETS";

    include("../includes/innerNav.php");

if (isset($_POST['setid'])){

  $set_id = mysqli_real_escape_string($db, $_POST['setid']);
  $title = mysqli_real_escape_string($db, $_POST['title']);
  $subtitle = mysqli_real_escape_string($db, $_POST['subtitle']);

  // $set_id = $_POST['setid'];
  // $title = $_POST['title'];
  // $subtitle = $_POST['subtitle'];
  
  $sql = "UPDATE word_sets_meta 
          SET title = '$title',
			    subtitle = '$subtitle'
          WHERE set_id = $set_id";
				 
  mysqli_query($db, $sql);
                
  header('location: ../otherPages/admin_wordsets.php?edited=modified');
}
?>