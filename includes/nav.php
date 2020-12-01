<?php
  require_once('./functions/initialize.php');
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/main_style.css" type="text/css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="styles/custom_nav.css" type="text/css">
        <title>Scrambler Puzzles</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="./css/mainStyleSheet.css">
    </head>

<body class="body_background">
<div id="wrap">
    <div id="nav">
        <ul>
            <a href="index.php">
              <li class="horozontal-li-logo">
              <img src ="./images/silcLogo.png">
              <br/></li>
            </a>

            <a href="index.php">
              <li <?php if($nav_selected == "HOME"){ echo 'class="current-page"'; } ?>>
              <img src="./images/home.png">
              <br/>Home</li>
            </a>

            <a href="linesPuzzle/linesIndex.php">
              <li <?php if($nav_selected == "LINES"){ echo 'class="current-page"'; } ?>>
                <img src="./images/lines.png">
                <br/>Lines</li>
            </a>

            <a href="dabblePuzzle/dabbleIndex.php">
              <li <?php if($nav_selected == "DABBLE"){ echo 'class="current-page"'; } ?>>
                <img src="./images/dabble.png">
                <br/>Dabble</li>
            </a>

            <a href="dabblePlusPuzzle/dabblePlusIndex.php">
              <li <?php if($nav_selected == "DABBLEPLUS"){ echo 'class="current-page"'; } ?>>
                <img src="./images/pyramid.png">
                <br/>Dabble Plus</li>
            </a>

            <a href="scramblerPuzzle/scramblerIndex.php">
              <li <?php if($nav_selected == "SCRAMBLER"){ echo 'class="current-page"'; } ?>>
              <img src="./images/squares.png">
              <br/>Squares</li>
            </a>

            <a href="swimLanesPuzzle/swimIndex.php">
              <li <?php if($nav_selected == "SWIMLANES"){ echo 'class="current-page"'; } ?>>
                <img src="./images/swim.png">
                <br/>SwimLanes</li>
            </a>

            <a href="stackPuzzle/stacksIndex.php">
              <li <?php if($nav_selected == "STACKS"){ echo 'class="current-page"'; } ?>>
                <img src="./images/stack.png">
                <br/>Stacks</li>
            </a>

            <a href="shapesPuzzle/shapesIndex.php">
              <li <?php if($nav_selected == "SHAPES"){ echo 'class="current-page"'; } ?>>
                <img src="./images/shapes.png">
                <br/>Shapes</li>
            </a>

            <a href="otherPages/admin.php">
              <li <?php if($nav_selected == "ADMIN"){ echo 'class="current-page"'; } ?>>
                <img src="./images/admin.png">
                <br/>Admin</li>
            </a>

            <a href="otherPages/about.php">
              <li <?php if($nav_selected == "ABOUT"){ echo 'class="current-page"'; } ?>>
                <img src="./images/about.png">
                <br/>About</li>
            </a>

            <a href="otherPages/login.php">
              <li <?php if($nav_selected == "LOGIN"){ echo 'class="current-page"'; } ?>>
                <img src="./images/login.png">
                <br/>Login</li>
            </a>

            <a href="feelingLucky/feelingLucky.php">
              <li <?php if($nav_selected == "LUCKY"){ echo 'class="current-page"'; } ?>>
                <img src="./images/feelingLucky.png">
                <br/>Random</li>
            </a>
      </ul>
      
      <br />
    </div>


    <table style="width:1250px">
      <tr>
        <?php
            if ($left_buttons == "YES") {
        ?>

        <td style="width: 120px;" valign="top">
        <?php
            if ($nav_selected == "HOME") {
                include("../index.php");
            } elseif ($nav_selected == "DABBLE") {
                include("./left_menu_list.php");
            } elseif ($nav_selected == "SQUARES") {
                include("./left_menu_timeline.php");
            } elseif ($nav_selected == "SCRAMBLER") {
                include("./left_menu_reports.php");
            } elseif ($nav_selected == "SWIMLANES") {
                include("./left_menu_scanner.php");
            } elseif ($nav_selected == "STACKS") {
                include("./left_menu_history.php");
            } elseif ($nav_selected == "ADMIN") {
                include("../otherPages/left_menu_admin.php");
            } elseif ($nav_selected == "ABOUT") {
                include("./left_menu_about.php");
            } elseif ($nav_selected == "LOGIN") {
                include("./left_menu_help.php");
            } else {
                include("./left_menu.php");
            }
        ?>
        </td>
        <td style="width: 1100px;" valign="top">
        <?php
          } else {
        ?>
        <td style="width: 100%;" valign="top">
        <?php
          }
        ?>
