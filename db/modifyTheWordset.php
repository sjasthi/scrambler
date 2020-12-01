<?php

    if(session_id() == '' || !isset($_SESSION)){
      session_start();
    }

    $nav_selected = "ADMIN";
    $left_buttons = "NO";
    $left_selected = "WORDSETS";

    include("../includes/innerNav.php");
    require("../indic-wp/word_processor.php");

    function getLengthNoSpaces($word){
      $wordProcessor = new wordProcessor(" ", "telugu");
      $wordProcessor->setWord($word, "telugu");
  
      return $wordProcessor->getLengthNoSpacesNoCommas($word);
    }

    function generateWordList($wordInput){
      $wordList = [];
  
      $lines = explode("\n", $wordInput);
  
      foreach($lines as $line){
  
        $word = trim($line);
  
        if(!(empty($word))){
          array_push($wordList, $word);
        }
      }
  
      return $wordList;
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

    if (isset($_POST['setid'])){

      $set_id = mysqli_real_escape_string($db, $_POST['setid']);
      $title = mysqli_real_escape_string($db, $_POST['title']);
      $subtitle = mysqli_real_escape_string($db, $_POST['subtitle']);
      $type = mysqli_real_escape_string($db, $_POST['type']);
      $count = mysqli_real_escape_string($db, $_POST['count']);
      $wordInput = $_POST['wordinput'];
      $words = generateWordList($wordInput);
      $valid = true;
      for($i = 0; $i < count($words); $i++){
        if(strlen($words[$i]) == 0) {
          $valid = false;
        }
      }

      //VALIDATION FUNCTIONS FOR CHANGES TO WORDS
      if($type != 'dabbleplus') {
        for($i = 0; $i < $count; $i++){
          if(preg_match('/(,)/', $words[$i])) {
            $valid = false;
          }
        }
      }
      for($i = 0; $i < count($words); $i++){
        if (preg_match('*[0-9]*', $words[$i]) || preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>\.\?\\\]/', $words[$i])){
          $valid = false;
        }
      }
      if(strcmp($type, 'dabble') == 0 || strcmp($type, 'dabbleplus') == 0) {
        $len = getLengthNoSpaces($words[0]);
        for($i = 1; $i < count($words); $i++){
          $nextlen = getLengthNoSpaces($words[$i]);
          if($nextlen != ($len + 1)) {
            $valid = false;
          }
          $len = getLengthNoSpaces($words[$i]);
        }
      } else if (strcmp($type, 'scrambler') == 0 || strcmp($type, 'swimlanes') == 0) {
        $len = getLengthNoSpaces($words[0]);
        for($i = 1; $i < count($words); $i++){
          $nextlen = getLengthNoSpaces($words[$i]);
          if($nextlen != $len) {
            $valid = false;
          }
        }
      } else if (strcmp($type, 'shapes') == 0) {
        $len = getLengthNoSpaces($words[0]);
        if($len > 5) {
          $valid = false;
        }
        for($i = 1; $i < count($words); $i++){
          $nextlen = getLengthNoSpaces($words[$i]);
          if($nextlen != $len) {
            $valid = false;
          }
        }
      }
      for($i = 0; $i < count($words); $i++) {
        for($j = 0; $j < count($words); $j++) {
          if($i == $j) {}
          else if(strcmp($words[$i], $words[$j]) == 0) {
            $valid = false;
          }
        }
      }
      if($valid){
      //UPDATE TITLE AND SUBTITLE
      $sql = "UPDATE word_sets_meta 
              SET title = '$title',
              subtitle = '$subtitle'
              WHERE set_id = $set_id";
            
      mysqli_query($db, $sql);
      
      //UPDATE WORDS
      $sql = "SELECT word_id FROM word_sets_meta WHERE set_id = $set_id";
      $result = mysqli_query($db, $sql);
      if(count($words) != $result->num_rows) {
        $sql = "DELETE FROM word_sets_meta WHERE set_id = $set_id";
        mysqli_query($db, $sql);
        while($row = $result->fetch_assoc()){
          $wordID = $row['word_id'];
          $sql = "DELETE FROM word_sets WHERE word_id = '$wordID'";
          mysqli_query($db, $sql);
        }
        foreach($words as $word) {
          $success = insert_word($word, $set_id, $title, $subtitle, $type);
        }
      } else if ($result->num_rows > 0) {
        $count = 0;
        $string = $words[$count];
        while($row = $result->fetch_assoc()){
          $string = $words[$count];
          $id = $row['word_id'];
          $query = "UPDATE word_sets SET word = '$string' WHERE word_id = $id";
          mysqli_query($db, $query);
          $count++;
        }
      }
                   
      header('location: ../otherPages/admin_wordsets.php?edited=modified');
    }
    else {
      header('location: ../otherPages/admin_wordsets.php?edited=invalidinput');
    }
  }
?>