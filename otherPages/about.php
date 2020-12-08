<?php
	
  // set the current page; 
  $nav_selected = "ABOUT";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "NO";

  // set the left menu button selected; options will change based on the main selection
  $left_selected = "";

  include("../includes/innerNav.php");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles/custom_nav.css" type="text/css">
    <title>Build Summary</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet" />

</head>

<body class="body_background">

<h3 style = "color: #01B0F1;">About Scrambler Puzzles</h3>

<p>The scrambler puzzles are designed to challenge students to parse 
  broken apart words mixed together to employ critical thinking
  and improve language skills
</p>

<h3>Home</h3>
	<p>
		"Home" button displays all the puzzles available in the system.
    <br> You can play the puzzle by clicking any puzzle.
    <br> You can drag and drop any character from left to right to solve the puzzle.
    <br> You can also double click on any character on the right to push the character to the first available space on the top right.
  </p>

<h3>Lines</h3>
	<p>
		"Lines" scrambles each word separately. 
    Words can be of any length.
  </p>
  

  <h3>Dabble</h3>
	<p>
		"Dabble" combines all the words and scrambles all the characters.
   <br> Words should be of sequential length (2,3,4,5.. and so on).
   <br> You can start the word at any length. And you can stop the word at any length.

    <br> <br> Once the puzzle is generated, you can select different styles (pyramic, step up, step down) to display the puzzle.

  </p>

  <h3>Dabble+</h3>
	<p>
		"Dabble+" is similar to "Dabble". 
    <br>However, if you can't find the words of required length, you can input smaller words separated by comma. 
    <br>For example, a 10 character word can be - 4 character word and 6 character word separated by comma. 
    <br>Words should be of sequential length (2,3,4,5.. and so on).
    <br>You can start the word at any length. And you can stop the word at any length.
  </p>


  <h3>Squares</h3>
	<p>
   "Squares" combines all the words and scrambles all the characters.
   <br>Words should be of the same length.
   <br>Characters are picked up from anywhere to form the words.
  </p>

  <h3>SwimLanes</h3>
	<p>
   In "SwimLanes", all words should be of the same length. There are two flavors of this puzzle.
   <br> <br> <h4> Fixed: </h4>

   In fixed mode, the position of the character in the word and the position of the character in the generated word are same.
   <br> For example, all the characters in the 1st position of each word are taken. These are scrambled and are placed in the 1st column of the puzzle.
   <br> Similarly, all the characters in 2nd position of each word are scrambled and are placed in 2nd column of the puzzle.
   <br> So, characters are picked up from each column and are arranged in the same order to find the words.

   <br> <br> <h4> Scrambled: </h4>

   In scrambled mode, the characters can appear in any column in the generated puzzle. However, two characters of the word will not appear in the same column.
   <br> So, characters are picked up from each column and the selected characters need to be arranged in the correct order to find the words.
   </p>

  <h3>Stacks</h3>
	<p>
   "Stacks" is similar to "Dabble". However, there are no length restrictions.
   <br>The input words can be of any length.
   <br>And the generated puzzle honors the order of the words given.
   </p>

  <h3>Shapes</h3>
	<p>
   "Shapes" removes one random character from each word and places it in a shape (circle or square).
   <br> Words must be the same length (with a maximum length of 5 letters).
   <br> The input words are restricted to a maximum of 5 words.
   <br> Puzzle is solved by selecting a letter from each shape and arranging those letters in the correct order.
   </p>

   <h3>Random</h3>
	<p>
   You can play a random puzzle by clicking "Random".
   </p>

	<h3>Team</h3>
	<p>
		<ul>
      <li>Siva Jasthi (siva.jasthi@metrostate.edu) </li>
      <li>Joseph Marden (dg3614kh@metrostate.edu) </li>
      
		</ul>
  </p>
  


</body>

</html>