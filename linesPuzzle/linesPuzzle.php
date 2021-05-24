<?php
require_once '../functions/session_start.php';
	
	// set the current page to one of the main buttons
	$nav_selected = "LINESPUZZLE";

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
	require("../indic-wp/word_processor.php");
	require("Lines.php");

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


	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$title = $_POST["title"];
		$subtitle = $_POST["subtitle"];

		// If variables are not set redirect to index page with empty error message
		if(isset($_POST["wordInput"])) {
			$wordInput = $_POST["wordInput"];
			$_SESSION['userInput'] = $_POST["wordInput"];
			$_SESSION['title'] = $title;
			$_SESSION['subtitle'] = $subtitle;
			$_SESSION['type'] = 'lines';

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

			$lines = new Lines($wordList);

			$letterList = $lines->getLetterList();
			$wordList = $lines->getWordList();
			$linesPuzzle = $lines->getLinesPuzzle();
			$linesLetterPuzzle = $lines->getLinesLetterPuzzle();
			$shuffledWordList = $lines->getShuffledWordList();
			$characterList = $lines->getCharacterList();

		$_SESSION['wordList'] = $wordList;
		$_SESSION['puzzle'] = $linesLetterPuzzle;

	} else if ($_SESSION['lastpage'] == 'saveWords') {
			
		$title = $_SESSION['title'];
		$subtitle = $_SESSION['subtitle'];
		$wordInput = $_SESSION['userInput'];
		$wordList = $_SESSION['wordList'];

		$lines = new Lines($wordList);

		$letterList = $lines->getLetterList();
		$wordList = $lines->getWordList();
		$linesPuzzle = $lines->getLinesPuzzle();
		$linesLetterPuzzle = $lines->getLinesLetterPuzzle();
		$shuffledWordList = $lines->getShuffledWordList();
		$characterList = $lines->getCharacterList();	
		
	} else {
		redirect(" ");
	}

	/*
	 * Redirects user to index page with Get error code if there is an issue with input
	 */
	function redirect($error){
		
		$_SESSION['lastpage'] = 'lines';
		if($error != " "){
			$url = "linesIndex.php?error=".$error;
		}
		else{
			$url = "linesIndex.php";
		}

		header("Location: ".$url);
		exit;
	}

	/*** Word Processor Functions ***/
	function getWordLength($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLength();
	}

	function getLengthNoSpaces($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLengthNoSpaces($word);
	}

	function splitWord($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLogicalChars();
	}
	
	$_SESSION['lastpage'] = 'lines';
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

    <title>Lines Puzzle</title>
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
		<form method="post" action="../imageGeneration/puzzleImageGenerator.php" onsubmit="return checkInput()">
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
                                <div align="center"><h2>Lines Puzzle</h2></div>
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
								<div class="letters linesLettersPuzzle">
									<div class="row"> <h3>Letters</h3> </div>
									<div class="row">
										
											<?php
												// Prints blank lines puzzle
												foreach($linesLetterPuzzle as $row){
													echo'<table class="puzzle">
													<tr>';
													foreach($row as $letter){
														if($letter != "0"){
															echo'<td class="filled">'.$letter.'</td>';
														}
														else{
															echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
														}
													}
													echo'</tr>
													</table>
													<br>';
												}
											?>
										
									</div>
								</div>
							</div>

							<?php //START OF WORDS PUZZLE *******************?>
              				<div class="col-sm-6">
								<div class="linesPuzzle word">
									<div class="row"> <h3> Words </h3> </div>
										<div class="row">
										
											<?php
												// Prints blank step down puzzle

												foreach($linesPuzzle as $row){
													echo'<table class="puzzle">
													<tr>';
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
													echo'</tr>
													</table>
													<br>';
												}
											?>	
										</div>
									</div>
                            	</div>
                        	</div>
                    	</div>
						<div class="panel-heading">
							<div class="row">
								<div class="col-sm-12">
									<div align="center"><h2>Lines Options</h2></div>
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
									<div align="center"><h2>Lines Solution</h2></div>
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
									<div class="letters linesLettersPuzzle">
										<div class="row"> <h3>Letters</h3> </div>
										<div class="row">
										<?php
											// Prints blank step down puzzle
											foreach($linesLetterPuzzle as $row){
												echo'<table class="puzzle">
												<tr>';
												foreach($row as $letter){
													if($letter != "0"){
														echo'<td class="filled">'.$letter.'</td>';
													}
													else{
														echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
													}
												}
												echo'</tr>
												</table>
												<br>';
											}
										?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="linesSolution word">
										<div class="row"> <h3> Words </h3> </div>
										<div class="row">
											<?php
												// Prints solution for step down
												foreach($linesPuzzle as $row){
													echo'<table class="puzzle">
													<tr>';
													foreach($row as $letter){
														if($letter != "0"){
															echo'<td class="filled">'.$letter.'</td>';
														}
														else{
															echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
														}
													}
													echo'</tr>
													</table>
													<br>';
												}
											?>
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
			
			$(".word table.puzzle tr td.filled").css("background-color", color.toHexString());
            $(".word .pyramid .inside").css("background-color", color.toHexString());
			$(".word .pyramid .left").css("background-color", color.toHexString());
			$(".word .pyramid .right").css("background-color", color.toHexString());
			$(".word .pyramid .top").css("background-color", color.toHexString());
			$(".word .pyramid .topRight").css("background-color", color.toHexString());
			$(".word .pyramid .bottom").css("background-color", color.toHexString());
			$(".word .pyramid .bottomRight").css("background-color", color.toHexString());
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
			
			$(".word table.puzzle tr td.filled").css("color", color.toHexString());
            $(".word .pyramid .cell").css("color", color.toHexString());

			$(".word table.puzzle tr td.filled").css("color", color.toHexString());
            $(".word .pyramid .inside").css("color", color.toHexString());
			$(".word .pyramid .left").css("color", color.toHexString());
			$(".word .pyramid .right").css("color", color.toHexString());
			$(".word .pyramid .top").css("color", color.toHexString());
			$(".word .pyramid .topRight").css("color", color.toHexString());
			$(".word .pyramid .bottom").css("color", color.toHexString());
			$(".word .pyramid .bottomRight").css("color", color.toHexString());
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

			$(".word table.puzzle tr td.filled").css("border", "2px solid " + color.toHexString());
            $(".word .pyramid .cell").css("border", "2px solid " + color.toHexString());

			$(".word table.puzzle tr td.filled").css("border-color", color.toHexString());
            $(".word .pyramid .inside").css("border-color", color.toHexString());
			$(".word .pyramid .left").css("border-color", color.toHexString());
			$(".word .pyramid .right").css("border-color", color.toHexString());
			$(".word .pyramid .top").css("border-color", color.toHexString());
			$(".word .pyramid .topRight").css("border-color", color.toHexString());
			$(".word .pyramid .bottom").css("border-color", color.toHexString());
			$(".word .pyramid .bottomRight").css("border-color", color.toHexString());
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
			
			$(".letters table.puzzle tr td.filled").css("background-color", color.toHexString());
						$(".letters .pyramid .inside").css("background-color", color.toHexString());
			$(".letters .pyramid .left").css("background-color", color.toHexString());
			$(".letters .pyramid .right").css("background-color", color.toHexString());
			$(".letters .pyramid .top").css("background-color", color.toHexString());
			$(".letters .pyramid .topRight").css("background-color", color.toHexString());
			$(".letters .pyramid .bottom").css("background-color", color.toHexString());
			$(".letters .pyramid .bottomRight").css("background-color", color.toHexString());
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

			$(".letters table.puzzle tr td.filled").css("color", color.toHexString());
						$(".letters .pyramid .cell").css("color", color.toHexString());

			$(".letters table.puzzle tr td.filled").css("color", color.toHexString());
						$(".letters .pyramid .inside").css("color", color.toHexString());
			$(".letters .pyramid .left").css("color", color.toHexString());
			$(".letters .pyramid .right").css("color", color.toHexString());
			$(".letters .pyramid .top").css("color", color.toHexString());
			$(".letters .pyramid .topRight").css("color", color.toHexString());
			$(".letters .pyramid .bottom").css("color", color.toHexString());
			$(".letters .pyramid .bottomRight").css("color", color.toHexString());
		
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

			$(".letters table.puzzle tr td.filled").css("border", "2px solid " + color.toHexString());
						$(".letters .pyramid .cell").css("border", "2px solid " + color.toHexString());

			$(".letters table.puzzle tr td.filled").css("border-color", color.toHexString());
						$(".letters .pyramid .inside").css("border-color", color.toHexString());
			$(".letters .pyramid .left").css("border-color", color.toHexString());
			$(".letters .pyramid .right").css("border-color", color.toHexString());
			$(".letters .pyramid .top").css("border-color", color.toHexString());
			$(".letters .pyramid .topRight").css("border-color", color.toHexString());
			$(".letters .pyramid .bottom").css("border-color", color.toHexString());
			$(".letters .pyramid .bottomRight").css("border-color", color.toHexString());
		
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
</script>
</html>
<?php } ?>