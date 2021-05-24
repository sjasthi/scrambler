<?php
require_once '../functions/session_start.php';
ob_start();

/*
* Redirects user to index page with Get error code if there is an issue with input
*/
function redirect($error){

	$_SESSION['lastpage'] = 'shapes';

	if($error != " "){
		$url = "shapesIndex.php?error=".$error;
	}
	else{
		$url = "shapesIndex.php?error=unknown";
	}

	header("Location: ".$url);
	exit;
}

/*** Word Processor Functions ***/
function getWordLength($word){
	$wordProcessor = new wordProcessor(" ", "Telugu");
	$wordProcessor->setWord($word);

	return $wordProcessor->getLength();
}

function splitWord($word){
	$wordProcessor = new wordProcessor(" ", "Telugu");
	$wordProcessor->setWord($word);

	return $wordProcessor->getLogicalChars();
}

	// set the current page to one of the main buttons
	$nav_selected = "SHAPESPUZZLE";

	// make the left menu buttons visible; options: YES, NO
	$left_buttons = "NO";
	
	// set the left menu button selected; options will change based on the main selection
	$left_selected = "";

	if(isset($_GET["saveResult"])){
		$saveResult = $_GET["saveResult"];
		
		switch($saveResult){
			case "success":
				$saveMessage = "Successfully saved words to database";
				break;
			default:
				$saveMessage = "Failed to save words to database";
		}
    }

	include("../includes/innerNav.php");

    if(!is_logged_in()) {
		?>
		
		
		  <div class="right-content">
			<div class="container">
	  
			<h3 style = "color: red;">Please log in to view this page</h3>
	  
			</div>
		  </div>
		
		<?php
	  } else if (!is_admin()) {
		?>
		
		
		  <div class="right-content">
			<div class="container">
	  
			<h3 style = "color: red;">Admin privileges are required to view this page</h3>
	  
			</div>
		  </div>
		
		<?php
	  } else {
	require("Shapes.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$title = $_POST["title"];
		$subtitle = $_POST["subtitle"];

		// If variables are not set redirect to index page with empty error message
		if(isset($_POST["wordInput"])) {
			$puzzleType = 'shapes';
			// $puzzleType = $_POST["puzzletype"];
			$wordInput = $_POST["wordInput"];
			$_SESSION['puzzletype'] = $_POST['puzzletype'];
			$_SESSION['userInput'] = $_POST["wordInput"];
			$_SESSION['title'] = $title;
			$_SESSION['subtitle'] = $subtitle;
			$_SESSION['type'] = 'shapes';

			// If input is blank redirect to Index with empty error message
			if(trim($wordInput) === ''){
				redirect("emptyinput");
			}
		}
		else{
			redirect("emptyinput");
		}

		if (preg_match('*[0-9]*', $wordInput) || preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $wordInput)){
			redirect("nonalpha");
		}

		// Parse through input and generate a word list
		$wordList = generateWordList($wordInput);

		// If only one word was passed in redirect with count error message
		if(count($wordList) == 1){
			redirect("count");
		}

		for($i = 0; $i < count($wordList); $i++){
			for($j = 0; $j < count($wordList); $j++){
				if($i == $j){}
				else if(strcmp($wordList[$i], $wordList[$j]) == 0){
					redirect('duplicate');
				}
			}
		}

		if(strlen($wordList[0]) == 1) {
			redirect('length1');
		}

		$_SESSION['wordList'] = $wordList;
		
		// Create shapes puzzle
		$shapes = new Shapes($wordList);

		//If there was an error with input redirect with invalid input message
		if($shapes->getErrorStatus() == true){
			redirect("invalidinput");
		}

		if(isset($_POST['puzzletype'])){
			$puzzleType = $_POST['puzzletype'];
			// print_r($puzzleType);
		} else {
			$puzzleType = 'circles';
		}

		$_SESSION['puzzletype'] = $puzzleType;

		//else{
			// Get lists and puzzles
			//$letterList = $shapes->getLetterList();
			$wordList = $shapes->getWordList();
			$shapesPuzzle = $shapes->getShapesPuzzle();
			$wordPuzzle = $shapes->getWordPuzzle();
			$letterPuzzle = $shapes->getLetterPuzzle();
			$characterList = $shapes->getCharacterList();
		//}

		$_SESSION['letterPuzzle'] = $letterPuzzle;
		$_SESSION['puzzle'] = $shapesPuzzle;

	} else if ($_SESSION['lastpage'] == 'saveWords') {
			
		$title = $_SESSION['title'];
		$subtitle = $_SESSION['subtitle'];
		$wordInput = $_SESSION['userInput'];
		$wordList = $_SESSION['wordList'];
		$puzzleType = $_SESSION['puzzletype'];

		$shapes = new Shapes($wordList);

		$wordList = $shapes->getWordList();
		$shapesPuzzle = $shapes->getShapesPuzzle();
		$wordPuzzle = $shapes->getWordPuzzle();
		$letterPuzzle = $shapes->getLetterPuzzle();
		$characterList = $shapes->getCharacterList();

		$_SESSION['letterPuzzle'] = $letterPuzzle;	
		
	} else{
		redirect(" ");
	}

	$_SESSION['lastpage'] = 'shapes';
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- Spectrum -->
    <link rel="stylesheet" type="text/css" href="../css/spectrum.css">
    <script type="text/javascript" src="../js/spectrum.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/puzzleStyle.css">
	<link rel="stylesheet" type="text/css" href="../css/shapesStyle.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">

	<title>Shapes Puzzle</title>
	<style>
		form {
			display: inline;
		}
	</style>
</head>
<body>
	<br>
        <div class="form-group">
			<div class="col-sm-1"></div>
			<div class="col-sm-10">
				<label class="charLabel" style="color:red;font-size:14px;" name="charName" value="">
				<?php
					if(isset($saveMessage)){
						echo($saveMessage);
					}
				?>
				</label>
			</div>
		</div>
    <br>
    <div class="container-fluid">
		<!-- <form method="post" action="../imageGeneration/shapesImageGenerator.php" onsubmit="return checkInput()"> -->
			<button type="submit" form="options" value="Submit">Generate Image</button>
		<!-- </form> -->
		<form method="post" action="../db/saveWords.php">
			<button type="submit" value="Submit">Save Words</button>
		</form>
		<button type="button" onclick="alert('To be implemented')">Save Puzzle</button>
		<button type="button" onclick="alert('To be implemented')">Play</button> 
		<br>
        <div class="panel">
            <div class="panel-group">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Shapes Puzzle</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div align="center">
                            <h3><?php echo($title);?></h3>
						</div>
						<div align="center">
							<h4><?php echo($subtitle);?></h4>
						</div>
						<div align="center">
								
							<div class="col-sm-6">
								<div class="main">
									<div class="shapesArea rectanglesPuzzle" style='display:none'>
										<?php
										// print_r(count($wordList[0]));
										$numWords = count($wordList[0]);
										//determines the location of the shapes around the circle based on the angle in radians
										$rad = ((2 * pi())/$numWords);

										//sets the radius of the entire circle, ending at the center of the word circles
										$radius = 200;

										//start at 0 radians
										$currentRad = 0;

										$xyCoords = [];

										$hypotenuse = 75 * sqrt(2);

										//determine the origin of the image
										$middleX = 375 - $hypotenuse;
										$middleY = 375 - $hypotenuse;

										//set the initial x and y values to the origin
										$currentX = 375;
										$currentY = 375;

										//placeholders used for drawing the lines between circles
										$lastX = 0;
										$lastY = 0;
										
										$firstX = $middleX + ($radius * cos(pi()/2 + ($rad))) + $hypotenuse - 10;
										$firstY = $middleY + ($radius * sin(pi()/2 + ($rad))) + $hypotenuse - 10;

										for($i = 1; $i <= count($wordList[0]); $i++){

											$currentRad = pi()/2 + ($i * $rad);

											$currentX = $middleX + ($radius * cos($currentRad));
											$currentY = $middleY + ($radius * sin($currentRad));

											$xyCoords[$i - 1] = [$currentX, $currentY]; 
										}

										echo '<svg height="740" width="740" style="position:relative">';
										echo '<polyline points ="';
										for($i = 0; $i < count($xyCoords); $i++) {
											$x = $xyCoords[$i][0] + $hypotenuse - 10;
											$y = $xyCoords[$i][1] + $hypotenuse - 10;
											echo ''.$x.','.$y.' ';
										}
										echo ''.$firstX.','.$firstY.'" " style="fill:none;stroke:black;stroke-width:3" /></svg>';
										?> 
									</div>
									<div class="shapesArea circlesPuzzle">
										<?php
										$numWords = count($wordList[0]);
										//determines the location of the shapes around the circle based on the angle in radians
										$rad = ((2 * pi())/$numWords);

										//sets the radius of the entire circle, ending at the center of the word circles
										$radius = 200;

										//start at 0 radians
										$currentRad = 0;

										$xyCoords = [];

										$hypotenuse = 75 * sqrt(2);

										//determine the origin of the image
										$middleX = 375 - $hypotenuse;
										$middleY = 375 - $hypotenuse;

										//set the initial x and y values to the origin
										$currentX = 375;
										$currentY = 375;

										//placeholders used for drawing the lines between circles
										$lastX = 0;
										$lastY = 0;
										
										$firstX = $middleX + ($radius * cos(pi()/2 + ($rad))) + $hypotenuse - 10;
										$firstY = $middleY + ($radius * sin(pi()/2 + ($rad))) + $hypotenuse - 10;

										for($i = 1; $i <= count($wordList[0]); $i++){

											$currentRad = pi()/2 + ($i * $rad);

											$currentX = $middleX + ($radius * cos($currentRad));
											$currentY = $middleY + ($radius * sin($currentRad));

											$xyCoords[$i - 1] = [$currentX, $currentY]; 
										}

										echo '<svg height="740" width="740" style="position:relative">';
										echo '<polyline points ="';
										for($i = 0; $i < count($xyCoords); $i++) {
											$x = $xyCoords[$i][0] + $hypotenuse - 10;
											$y = $xyCoords[$i][1] + $hypotenuse - 10;
											echo ''.$x.','.$y.' ';
										}
										echo ''.$firstX.','.$firstY.'" " style="fill:none;stroke:black;stroke-width:3" /></svg>';
										?> 
									</div>
								</div>
							</div>
									
								
							<?php //START OF WORDS PUZZLE *******************?>
								
							<div class="col-sm-6">
								<div class="wordPuzzle word">
									<div class="row"> <h3></h3> </div>
										<div class="row">
											<table class="puzzle">
												<?php
													// Prints blank step down puzzle

													foreach($wordPuzzle as $row){
														echo'<tr>';
														foreach($row as $letter){
															if($letter != "0"){
																echo'<td class="filled">&nbsp;&nbsp;&nbsp;&nbsp;</td>
																';
															}
															else{
																echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
																';
															}
														}
														echo'</tr>';
													}
												?>
											</table>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Shapes Options</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12" align="center">
															<?php // adding word options here **************************?>
															<div class="col-sm-4">
																	<div class="row">
																			<div class="col-sm-8">
																					<h3>Shapes</h3>
																			</div>
																			<div align="left" >
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Shape Background Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='letterSquareColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Shape Letter Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='letterColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Shape Border Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='lineColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                    </div>
																		</div>
																	</div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3>Puzzle Options</h3>
                                        </div>
                                    </div>

																		<div align="left">
                                        <div class="row">
                                            <div class="col-sm-12" >
                                                <input type="checkbox" class="showSolutionCheckbox" onchange="solutionCheckboxChange()" checked> Show Solution
                                            </div>
                                        </div>
										<?php //addition of letters options ************************* ?>
										<br>
										<div class="row">
											<div class="col-sm-6">
											<form method="post" id="options" action="../imageGeneration/shapesImageGenerator.php"  onsubmit="return checkInput()">
												<select class="form-control" id="puzzletype" name="puzzletype" onchange="puzzleChange()">
													<option value="circles" >Circles</option>
                                        			<option value="rectangles" >Rectangles</option>
												</select>
											</form>
											</div>
										<h4>Puzzle Type</h4>
										</div>
                                    </div>
                                </div>

																<?php // Words OPTIONS ********************************** ?>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h3>Words</h3>
                                        </div>
                                    </div>
                                    <div align="left" >
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Word Square Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='letterSquareColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Word Letter Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='letterColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Line Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='lineColor'/>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
					<div class="panel panel-primary solutionSection">
						<div class="panel-heading ">
							<div class="row">
								<div class="col-sm-12">
									<div align="center"><h2>Shapes Solution</h2></div>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div align="center">
								<h3><?php echo($title);?></h3>
							</div>
							<div align="center">
								<h4><?php echo($subtitle);?></h4>
							</div>
							<div align="center">
									<div class="letterSolution word">
										<div class="row"> <h3></h3> </div>
										<div class="row">
											<table class="puzzle">
												<?php
													// Prints solution for step down
													foreach($wordList as $row){
														echo'<tr>';
														foreach($row as $letter){
															if($letter != "0"){
																echo'<td class="filled">'.$letter.'</td>
																';
															}
															else{
																echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
																';
															}
														}
														echo'</tr>';
													}
												?>
											</table>
										</div>
									</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
	function checkInput() {
		var maxLength = 0;
		var numSpaces = 0;
		var array = <?php echo json_encode($_SESSION['puzzle']) ?>;
		for (var i = 0, length = array.length; i < length; i++) {
			numSpaces = 0;
			for(var j = 0; j < array[i].length; j++){
				if (array[i][j] == ' ') numSpaces++;
			}
			rowLength = array[i].length;
			rowLength -= numSpaces;
			if(rowLength > maxLength) {
				maxLength = rowLength;
			}
		};

		if(maxLength > 10) {
			alert("Cannot generate images with words longer than ten characters due to size limitations");
			return false;
		}

		if(array.length > 10)
			{
			alert("Cannot generate images with more than ten words due to size limitations");
			return false;
		}
	}
</script>
<script>
	// Set default spectrum elements
	$(".letterSquareColor").spectrum({
		color: "#EEEEEE",
		change: function(color) {
			$(".word table.puzzle tr td.filled").css("background-color", color.toHexString());
            $(".word .rectangle .inside").css("background-color", color.toHexString());
			$(".word .rectangle .left").css("background-color", color.toHexString());
			$(".word .rectangle .right").css("background-color", color.toHexString());
			$(".word .rectangle .top").css("background-color", color.toHexString());
			$(".word .rectangle .topRight").css("background-color", color.toHexString());
			$(".word .rectangle .bottom").css("background-color", color.toHexString());
			$(".word .rectangle .bottomRight").css("background-color", color.toHexString());
		}
	});

	$(".letterColor").spectrum({
		color: "#000000",
		change: function(color) {
			$(".word table.puzzle tr td.filled").css("color", color.toHexString());
            $(".word .rectangle .cell").css("color", color.toHexString());

			$(".word table.puzzle tr td.filled").css("color", color.toHexString());
            $(".word .rectangle .inside").css("color", color.toHexString());
			$(".word .rectangle .left").css("color", color.toHexString());
			$(".word .rectangle .right").css("color", color.toHexString());
			$(".word .rectangle .top").css("color", color.toHexString());
			$(".word .rectangle .topRight").css("color", color.toHexString());
			$(".word .rectangle .bottom").css("color", color.toHexString());
			$(".word .rectangle .bottomRight").css("color", color.toHexString());
		}
	});

	$(".lineColor").spectrum({
		color: "#000000",
		change: function(color) {
			$(".word table.puzzle tr td.filled").css("border", "2px solid " + color.toHexString());
            $(".word .rectangle .cell").css("border", "2px solid " + color.toHexString());

			$(".word table.puzzle tr td.filled").css("border-color", color.toHexString());
            $(".word .rectangle .inside").css("border-color", color.toHexString());
			$(".word .rectangle .left").css("border-color", color.toHexString());
			$(".word .rectangle .right").css("border-color", color.toHexString());
			$(".word .rectangle .top").css("border-color", color.toHexString());
			$(".word .rectangle .topRight").css("border-color", color.toHexString());
			$(".word .rectangle .bottom").css("border-color", color.toHexString());
			$(".word .rectangle .bottomRight").css("border-color", color.toHexString());
		}
	});

	$(".letterSquareColorLetters").spectrum({
		color: "#EEEEEE",
		change: function(color) {
			$(".circleShape").css("background-color", color.toHexString());
			$(".rectangleShape").css("background-color", color.toHexString());
		}
	});

	$(".letterColorLetters").spectrum({
		color: "#000000",
		change: function(color) {
			$(".circleShape table.puzzle tr td.empty").css("color", color.toHexString());
			$(".circleShape table.puzzle tr td.empty").css("color", color.toHexString());
			$(".rectangleShape table.puzzle tr td.empty").css("color", color.toHexString());
			$(".rectangleShape table.puzzle tr td.empty").css("color", color.toHexString());
		}
	});

	$(".lineColorLetters").spectrum({
		color: "#000000",
		change: function(color) {
			$(".circleShape").css("border-color", color.toHexString());
			$(".rectangleShape").css("border-color", color.toHexString());
		}
	});

	// Updates the solution section to hidden/visable on check box update
	function solutionCheckboxChange(){
		if($('.showSolutionCheckbox').is(":checked")){
			$(".solutionSection").show();
		}
		else{
			$(".solutionSection").hide();
		}
	}

	// Shows/hides puzzles and solutions when puzzle type is changed (not needed for scrambler)
function puzzleChange(){
		if($('#puzzletype').val() == "rectangles"){
			$(".rectanglesPuzzle").show();
			$(".circlesPuzzle").hide();
		}
		else if($('#puzzletype').val() == "circles"){
			$(".rectanglesPuzzle").hide();
			$(".circlesPuzzle").show();
		}
	}

</script>
	<script type="text/javascript">
        var xyCoords = <?php echo json_encode($xyCoords) ?>;
		
        var wordList = <?php echo json_encode($wordList) ?>;
		
        var wordPuzzle = <?php echo json_encode($wordPuzzle) ?>;
		
        var letterPuzzle = <?php echo json_encode($letterPuzzle) ?>;
		
		var shapesPuzzle = <?php echo json_encode($shapesPuzzle) ?>;
		
		var puzzleType = <?php echo "'".$puzzleType."'" ?>;
    </script>
    <script type="text/javascript" src="../js/shapes.js"></script>
</html>
<?php } ?>