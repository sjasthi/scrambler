<?php
	// set the current page to one of the main buttons
	$nav_selected = "SHAPESPUZZLE";

	// make the left menu buttons visible; options: YES, NO
	$left_buttons = "NO";
	
	// set the left menu button selected; options will change based on the main selection
	$left_selected = "";

	include("../includes/innerNav.php");
	require("Shapes.php");
	require(ROOT_PATH."indic-wp/word_processor.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$title = $_POST["title"];
		$subtitle = $_POST["subtitle"];

		// If variables are not set redirect to index page with empty error message
		if(isset($_POST["wordInput"])) {
			$puzzleType = 'shapes';
			// $puzzleType = $_POST["puzzletype"];
			$wordInput = $_POST["wordInput"];

			// If input is blank redirect to Index with empty error message
			if(trim($wordInput) === ''){
				redirect("emptyinput");
			}
		}
		else{
			redirect("emptyinput");
		}



		// Parse through input and generate a word list
		$wordList = generateWordList($wordInput);

		// If only one word was passed in redirect with count error message
		if(count($wordList) == 1){
			redirect("count");
		}

		// Create shapes puzzle
		$shapes = new Shapes($wordList);

		//If there was an error with input redirect with invalid input message
		if($shapes->getErrorStatus() == true){
			print_r("asdfa:");
			redirect("invalidinput");
		}
		//else{
			// Get lists and puzzles
			//$letterList = $shapes->getLetterList();
			$wordList = $shapes->getWordList();
			$shapesPuzzle = $shapes->getShapesPuzzle();
			$wordPuzzle = $shapes->getWordPuzzle();
			$letterPuzzle = $shapes->getLetterPuzzle();
			$characterList = $shapes->getCharacterList();
		//}

	}
	else{
		redirect(" ");
	}

	/*
	 * Redirects user to index page with Get error code if there is an issue with input
	 */
	function redirect($error){
		if($error != " "){
			$url = "../index.php?error=".$error;
		}
		else{
			$url = "../index.php";
		}

		header("Location: ".$url);
		exit;
	}

	/*
	 * Generates a word list based on input
	 * Splits input by line breaks, trims each line, and then puts each line into an array
	 */
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

	/*** Word Processor Functions ***/
	function getWordLength($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLength();
	}

	function splitWord($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLogicalChars();
	}
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
</head>
<body>
    <div class="container-fluid">
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
							<div class="main">
								<div class="shapesArea">
									<?php

									$numWords = count($wordList);
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
									
									$firstX = $middleX + ($radius * cos(pi()/2 + ($rad))) + $hypotenuse;
									$firstY = $middleY + ($radius * sin(pi()/2 + ($rad))) + $hypotenuse;

									for($i = 1; $i <= $numWords; $i++){

										$currentRad = pi()/2 + ($i * $rad);

										$currentX = $middleX + ($radius * cos($currentRad));
										$currentY = $middleY + ($radius * sin($currentRad));

										$xyCoords[$i - 1] = [$currentX, $currentY]; 
									}

									echo '<svg height="750" width="750" style="position:relative">';
									echo '<polyline points ="';
									for($i = 0; $i < count($xyCoords); $i++) {
										$x = $xyCoords[$i][0] + $hypotenuse;
										$y = $xyCoords[$i][1] + $hypotenuse;
										echo ''.$x.','.$y.' ';
									}
									echo ''.$firstX.','.$firstY.'" " style="fill:none;stroke:black;stroke-width:3" /></svg>';
									?> 
								</div>
								</div>
                                

							<?php //START OF WORDS PUZZLE *******************?>
								<div class="wordPuzzle word">
									<div class="row"> <h3> Step Down </h3> </div>
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
																					<h3>Letters</h3>
																			</div>
																			<div align="left" >
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Letter Square Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='letterSquareColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Letter Color</label>
	                                            </div>
	                                            <div class="col-sm-6" >
	                                                <input type="text" class='letterColorLetters'/>
	                                            </div>
	                                        </div>
	                                        <br>
	                                        <div class="row">
	                                            <div class="col-sm-6" >
	                                                <label>Line Color</label>
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
<!-- 										<br>
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control" id="puzzlelettertype" name="puzzlelettertype" onchange="lettersChange()">
													<option value="rectangle">Rectangle</option>
													<option value="pyramid" >Pyramid</option>
													<option value="stepup" >Step Up</option>
													<option value="stepdown" >Step Down</option>
												</select>
											</div>
										<h4>Letters</h4>
										</div>
 -->
<!-- 										<br>
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control" id="puzzletype" name="puzzletype" onchange="puzzleChange()">
													<option value="pyramid" <?php if($puzzleType == "pyramid"){echo('selected="selected"');} ?>>Pyramid</option>
													<option value="stepup" <?php if($puzzleType == "stepup"){echo('selected="selected"');} ?>>Step Up</option>
													<option value="stepdown" <?php if($puzzleType == "stepdown"){echo('selected="selected"');} ?>>Step Down</option>
												</select>
											</div>
										<h4>Words</h4>
										</div>
 -->                                    </div>
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
										<div class="row"> <h3> Words </h3> </div>
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
			$(".letters table.puzzle tr td.filled").css("background-color", color.toHexString());
						$(".letters .rectangle .inside").css("background-color", color.toHexString());
			$(".letters .rectangle .left").css("background-color", color.toHexString());
			$(".letters .rectangle .right").css("background-color", color.toHexString());
			$(".letters .rectangle .top").css("background-color", color.toHexString());
			$(".letters .rectangle .topRight").css("background-color", color.toHexString());
			$(".letters .rectangle .bottom").css("background-color", color.toHexString());
			$(".letters .rectangle .bottomRight").css("background-color", color.toHexString());
		}
	});

	$(".letterColorLetters").spectrum({
		color: "#000000",
		change: function(color) {
			$(".letters table.puzzle tr td.filled").css("color", color.toHexString());
						$(".letters .rectangle .cell").css("color", color.toHexString());

			$(".letters table.puzzle tr td.filled").css("color", color.toHexString());
						$(".letters .rectangle .inside").css("color", color.toHexString());
			$(".letters .rectangle .left").css("color", color.toHexString());
			$(".letters .rectangle .right").css("color", color.toHexString());
			$(".letters .rectangle .top").css("color", color.toHexString());
			$(".letters .rectangle .topRight").css("color", color.toHexString());
			$(".letters .rectangle .bottom").css("color", color.toHexString());
			$(".letters .rectangle .bottomRight").css("color", color.toHexString());
		}
	});

	$(".lineColorLetters").spectrum({
		color: "#000000",
		change: function(color) {
			$(".letters table.puzzle tr td.filled").css("border", "2px solid " + color.toHexString());
						$(".letters .rectangle .cell").css("border", "2px solid " + color.toHexString());

			$(".letters table.puzzle tr td.filled").css("border-color", color.toHexString());
						$(".letters .rectangle .inside").css("border-color", color.toHexString());
			$(".letters .rectangle .left").css("border-color", color.toHexString());
			$(".letters .rectangle .right").css("border-color", color.toHexString());
			$(".letters .rectangle .top").css("border-color", color.toHexString());
			$(".letters .rectangle .topRight").css("border-color", color.toHexString());
			$(".letters .rectangle .bottom").css("border-color", color.toHexString());
			$(".letters .rectangle .bottomRight").css("border-color", color.toHexString());
		}
	});

	<?php
		// // Hide/Show starting puzzles/solutions based off input from Index page
		// if($puzzleType == "stepup"){
		// 	echo('$(".pyramidPuzzle").hide();');
		// 	echo('$(".stepupPuzzle").show();');
		// 	echo('$(".stepdownPuzzle").hide();');

		// 	echo('$(".pyramidSolution").hide();');
		// 	echo('$(".stepupSolution").show();');
		// 	echo('$(".stepdownSolution").hide();');
		// }
		// else if($puzzleType == "stepdown"){
		// 	echo('$(".pyramidPuzzle").hide();');
		// 	echo('$(".stepupPuzzle").hide();');
		// 	echo('$(".stepdownPuzzle").show();');

		// 	echo('$(".pyramidSolution").hide();');
		// 	echo('$(".stepupSolution").hide();');
		// 	echo('$(".stepdownSolution").show();');
		// }
		// else{
		// 	echo('$(".rectanglePuzzle").hide();');
		// 	echo('$(".pyramidPuzzle").hide();');
		// 	echo('$(".stepupPuzzle").show();');
		// 	echo('$(".stepdownPuzzle").hide();');

		// 	echo('$(".rectangleSolution").hide();');
		// 	echo('$(".pyramidSolution").show();');
		// 	echo('$(".stepupSolution").show();');
		// 	echo('$(".stepdownSolution").hide();');
		// }
	?>

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
/*	function puzzleChange(){
		if($('#puzzletype').val() == "rectangle"){
			$(".rectanglePuzzle").show();
			$(".stepupPuzzle").hide();
			$(".stepdownPuzzle").hide();

			$(".rectangleSolution").show();
			$(".stepupSolution").hide();
			$(".stepdownSolution").hide();
		}
		else if($('#puzzletype').val() == "stepup"){
			$(".rectanglePuzzle").hide();
			$(".stepupPuzzle").show();
			$(".stepdownPuzzle").hide();

			$(".rectangleSolution").hide();
			$(".stepupSolution").show();
			$(".stepdownSolution").hide();
		}
		else{
			$(".rectanglePuzzle").hide();
			$(".stepupPuzzle").hide();
			$(".stepdownPuzzle").show();

			$(".rectangleSolution").hide();
			$(".stepupSolution").hide();
			$(".stepdownSolution").show();
		}
	}
*/
	// 	Shows/hides letters when puzzle type is changed (not needed for scrambler)
/*	function lettersChange(){
			if($('#puzzlelettertype').val() == "rectangle"){
				$(".rectangleLettersPuzzle").show();
				$(".rectangleLettersPuzzle").show();
				$(".stepupLettersPuzzle").hide();
				$(".stepdownLettersPuzzle").hide();

			}
			else if($('#puzzlelettertype').val() == "pyramid"){
				$(".rectangleLettersPuzzle").hide();
				$(".rectangleLettersPuzzle").show();
				$(".stepupLettersPuzzle").hide();
				$(".stepdownLettersPuzzle").hide();

			}
			else if($('#puzzlelettertype').val() == "stepup"){
				$(".rectangleLettersPuzzle").hide();
				$(".rectangleLettersPuzzle").hide();
				$(".stepupLettersPuzzle").show();
				$(".stepdownLettersPuzzle").hide();

			}
			else{
				$(".rectangleLettersPuzzle").hide();
				$(".rectangleLettersPuzzle").hide();
				$(".stepupLettersPuzzle").hide();
				$(".stepdownLettersPuzzle").show();

			}
		}*/
</script>
	<script type="text/javascript">
        var xyCoords = <?php echo json_encode($xyCoords) ?>;
		
        var wordList = <?php echo json_encode($wordList) ?>;
		
        var wordPuzzle = <?php echo json_encode($wordPuzzle) ?>;
		
        var letterPuzzle = <?php echo json_encode($letterPuzzle) ?>;
		
        var shapesPuzzle = <?php echo json_encode($shapesPuzzle) ?>;
    </script>
    <script type="text/javascript" src="../js/shapes.js"></script>
</html>
