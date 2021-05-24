<?php
require_once '../functions/session_start.php';
ob_start();

/*
* Redirects user to index page with Get error code if there is an issue with input
*/
function redirect($error){
	$_SESSION['lastpage'] = 'swim';
	if($error != " "){
		$url = "swimIndex.php?error=".$error;
	}
	else{
		$url = "swimIndex.php";
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
	$nav_selected = "SWIMPUZZLE";

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

	echo $_SESSION['logged_in'];

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
	require("Swim.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$title = $_POST["title"];
		$subtitle = $_POST["subtitle"];

		// If variables are not set redirect to index page with empty error message
		if(isset($_POST["wordInput"])) {
			
			$puzzleType = $_POST["puzzletype"];
			$scrambled = $_POST["scrambled"];
			$wordInput = $_POST["wordInput"];
			$_SESSION['userInput'] = $_POST["wordInput"];
			$_SESSION['puzzletype'] = $_POST["puzzletype"];
			$_SESSION['scrambled'] = $_POST["scrambled"];
			$_SESSION['title'] = $title;
			$_SESSION['subtitle'] = $subtitle;
			$_SESSION['type'] = 'swimlanes';

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

		
		$_SESSION['wordList'] = $wordList;

		// Create swimlanes puzzle
		$swim = new Swim($wordList);

		//If there was an error with input redirect with invalid input message
		if($swim->getErrorStatus() == true){
			redirect("invalidinput");
		}
		//else{
			// Get lists and puzzles
			$fullWords = $swim->getFullWords();
			$letterList = $swim->getLetterList();
			$wordList = $swim->getWordList();
			$sparseWords = $swim->getSparseWords();
			$scrambledFullWords = $swim->getScrambledFullWords();
			$scrambledSparseWords = $swim->getScrambledSparseWords();

			$pyramidPuzzle = $swim->getPyramidPuzzle();
			$stepUpPuzzle = $swim->getStepUpPuzzle();
			$stepDownPuzzle = $swim->getStepDownPuzzle();
			$swimPuzzle = $swim->getSwimlanesPuzzle();

			$pyramidLetterPuzzle = $swim->getPyramidLetterPuzzle();
			$stepUpLetterPuzzle = $swim->getStepUpLetterPuzzle();
			$stepDownLetterPuzzle = $swim->getStepDownLetterPuzzle();
			$swimLetterPuzzle = $swim->getSwimlanesLetterPuzzle();

			$characterList = $swim->getCharacterList();

			$_SESSION['puzzle'] = $scrambledFullWords;

	} else if ($_SESSION['lastpage'] == 'saveWords') {
			
		$title = $_SESSION['title'];
		$subtitle = $_SESSION['subtitle'];
		$wordInput = $_SESSION['userInput'];
		$wordList = $_SESSION['wordList'];
		$puzzleType = $_SESSION['puzzletype'];
		$scrambled = $_SESSION['scrambled'];

		$swim = new Swim($wordList);

		$fullWords = $swim->getFullWords();
		$letterList = $swim->getLetterList();
		$wordList = $swim->getWordList();
		$sparseWords = $swim->getSparseWords();
		$scrambledFullWords = $swim->getScrambledFullWords();
		$scrambledSparseWords = $swim->getScrambledSparseWords();

		$pyramidPuzzle = $swim->getPyramidPuzzle();
		$stepUpPuzzle = $swim->getStepUpPuzzle();
		$stepDownPuzzle = $swim->getStepDownPuzzle();
		$swimPuzzle = $swim->getSwimlanesPuzzle();

		$pyramidLetterPuzzle = $swim->getPyramidLetterPuzzle();
		$stepUpLetterPuzzle = $swim->getStepUpLetterPuzzle();
		$stepDownLetterPuzzle = $swim->getStepDownLetterPuzzle();
		$swimLetterPuzzle = $swim->getSwimlanesLetterPuzzle();

		$characterList = $swim->getCharacterList();

		$_SESSION['letterPuzzle'] = $scrambledFullWords;	
		
	} else{
		redirect(" ");
	}
	
	$_SESSION['lastpage'] = 'swim';
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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">

	<title>SwimLanes Puzzle</title>
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
		<form method="post" action="../imageGeneration/puzzleImageGenerator.php"  onsubmit="return checkInput()">
			<button type="submit" value="Submit">Generate Image</button>
		</form>
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
                                <div align="center"><h2>SwimLanes Puzzle</h2></div>
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
                                <div class="row letters fullLetterPuzzle"> <h3>Letters</h3>
								<table class="puzzle">
                                <?php
									// THIS PRINTS THE FIXED FULL LETTERS FOR THE SWIMLANES PUZZLE
									foreach($fullWords as $row){
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
								<div class="letters sparseLetterPuzzle" style="display: none;">
									<div class="row"> <h3>Letters</h3></div>
									<div class="row">
										<table class="puzzle">
										<?php
														// THIS PRINTS THE FIXED SPARSE LETTERS FOR THE SWIMLANES PUZZLE
														foreach($sparseWords as $row){
															echo'<tr>';
															foreach($row as $letter){
																if($letter != "0"){
																	echo'<td class="filled">'.$letter.'</td>';
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
								<div class="row letters scrambledFullLetterPuzzle" style="display: none;"> <h3>Letters</h3>
								<table class="puzzle">
                                <?php
									// THIS PRINTS THE SCRAMBLED FULL LETTERS FOR THE SWIMLANES PUZZLE
									foreach($scrambledFullWords as $row){
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
								<div class="letters scrambledSparseLetterPuzzle" style="display: none;">
									<div class="row"> <h3>Letters</h3></div>
									<div class="row">
										<table class="puzzle">
										<?php
														// THIS PRINTS THE SCRAMBLED SPARSE LETTERS FOR THE SWIMLANES PUZZLE
														foreach($scrambledSparseWords as $row){
															echo'<tr>';
															foreach($row as $letter){
																if($letter != "0"){
																	echo'<td class="filled">'.$letter.'</td>';
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

							<?php //START OF WORDS PUZZLE *******************?>
              <div class="col-sm-6">
								<div class="stepupPuzzle word">
									<div class="row"> <h3>Words</h3></div>
									<div class="row">
										<table class="puzzle">
											<?php
												// THIS WILL PRINT THE BLANK SQUARES FOR THE USER FILL
												foreach($swimPuzzle as $row){
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
								<div class="stepdownPuzzle word">
									<div class="row"> <h3> Step Down </h3> </div>
									<div class="row">
										<table class="puzzle">
											<?php
												// Prints blank step down puzzle

												foreach($stepDownPuzzle as $row){
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
                                <div align="center"><h2>SwimLanes Options</h2></div>
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

 										<br>
										<div class="row">
										<div class="col-sm-6">
												<label>Fixed or Scrambled</label>
											</div>
											<div class="col-sm-6">
                                    			<select class="form-control" id="scrambled" name="scrambled" onchange="puzzleChange();">
													<?php
													if($scrambled == 'fixed'){
														echo '<option value="fixed" selected="selected">Fixed</option>
														<option value="scrambled" >Scrambled</option>';
													} else if($scrambled == 'scrambled'){
														echo '<option value="fixed">Fixed</option>
														<option value="scrambled" selected="selected">Scrambled</option>';
													}
													?>   
												</select>
                                			</div>
                           				</div>
										<br>
										<div class="row">
											<div class="col-sm-6">
												<label>Full or Sparse</label>
											</div>
											<div class="col-sm-6">
                                    			<select class="form-control" id="puzzletype" name="puzzletype" onchange="puzzleChange();">
													<?php
													if($puzzleType == 'full') {
														echo'<option value="full" selected="selected">Full</option>
														<option value="sparse" >Sparse</option>';
													} else if($puzzleType == 'sparse') {
														echo'<option value="full">Full</option>
														<option value="sparse" selected="selected">Sparse</option>';
													}
													?>
                                   				</select>
                                			</div>
											
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
									<div align="center"><h2>SwimLanes Solution</h2></div>
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
									<div class="row letters fullLetterPuzzle"> <h3>Letters</h3>
										<table class="puzzle">
																		<?php
											// Prints a grid with 5 square width with puzzle letters
											foreach($fullWords as $row){
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
										<div class="letters sparseLetterPuzzle" style="display: none;">
											<div class="row"> <h3>Letters</h3></div>
											<div class="row">
												<table class="puzzle">
													<?php
														// Prints blank step up puzzle
														foreach($sparseWords as $row){
															echo'<tr>';
															foreach($row as $letter){
																if($letter != "0"){
																	echo'<td class="filled">'.$letter.'</td>';
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
										<div class="row letters scrambledFullLetterPuzzle" style="display: none;"> <h3>Letters</h3>
								<table class="puzzle">
                                <?php
									// THIS PRINTS THE SCRAMBLED LETTERS FOR THE SWIMLANES PUZZLE
									foreach($scrambledFullWords as $row){
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
								<div class="letters scrambledSparseLetterPuzzle" style="display: none;">
									<div class="row"> <h3>Letters</h3></div>
									<div class="row">
										<table class="puzzle">
										<?php
														// Prints blank step up puzzle
														foreach($scrambledSparseWords as $row){
															echo'<tr>';
															foreach($row as $letter){
																if($letter != "0"){
																	echo'<td class="filled">'.$letter.'</td>';
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
								<div class="col-sm-6">
									<div class="stepupSolution word">
										<div class="row"> <h3>Words</h3> </div>
										<div class="row">
											<table class="puzzle">
												<?php
													// THIS WILL PRINT THE SOLUTION FOR THE WHOLE PUZZLE
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

		// Hide/Show starting puzzles/solutions based off input from Index page
		if($scrambled == 'fixed'){
			if($puzzleType == "sparse"){
				echo('$(".sparseLetterPuzzle").show();');
				echo('$(".fullLetterPuzzle").hide();');
				echo('$(".scrambledSparseLetterPuzzle").hide();');
				echo('$(".scrambledFullLetterPuzzle").hide();');
			}
			else if($puzzleType == "full"){
				echo('$(".sparseLetterPuzzle").hide();');
				echo('$(".fullLetterPuzzle").show();');
				echo('$(".scrambledSparseLetterPuzzle").hide();');
				echo('$(".scrambledFullLetterPuzzle").hide();');
			}
		} else if($scrambled == 'scrambled'){
			if($puzzleType == "sparse"){
				echo('$(".sparseLetterPuzzle").hide();');
				echo('$(".fullLetterPuzzle").hide();');
				echo('$(".scrambledSparseLetterPuzzle").show();');
				echo('$(".scrambledFullLetterPuzzle").hide();');
			}
			else if($puzzleType == "full"){
				echo('$(".sparseLetterPuzzle").hide();');
				echo('$(".fullLetterPuzzle").hide();');
				echo('$(".scrambledSparseLetterPuzzle").hide();');
				echo('$(".scrambledFullLetterPuzzle").show();');
			}
		}
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
	
	function puzzleChange(){
		if($("#scrambled").val() == 'fixed'){
			if($('#puzzletype').val() == "sparse"){
				$(".sparseLetterPuzzle").show();
				$(".fullLetterPuzzle").hide();
				$(".scrambledSparseLetterPuzzle").hide();
				$(".scrambledFullLetterPuzzle").hide();
			}
			else if($('#puzzletype').val() == "full"){
				$(".sparseLetterPuzzle").hide();
				$(".fullLetterPuzzle").show();
				$(".scrambledSparseLetterPuzzle").hide();
				$(".scrambledFullLetterPuzzle").hide();
			}
		} else if($("#scrambled").val() == 'scrambled'){
			if($('#puzzletype').val() == "sparse"){
				$(".sparseLetterPuzzle").hide();
				$(".fullLetterPuzzle").hide();
				$(".scrambledSparseLetterPuzzle").show();
				$(".scrambledFullLetterPuzzle").hide();
			}
			else if($('#puzzletype').val() == "full"){
				$(".sparseLetterPuzzle").hide();
				$(".fullLetterPuzzle").hide();
				$(".scrambledSparseLetterPuzzle").hide();
				$(".scrambledFullLetterPuzzle").show();
			}
		}
	}
</script>
</html>
<?php } ?>
