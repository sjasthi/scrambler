<?php
	if(session_id() == '' || !isset($_SESSION)){
		session_start();
	}

	// set the current page to one of the main buttons
	$nav_selected = "LUCKY";

	// make the left menu buttons visible; options: YES, NO
	$left_buttons = "NO";

	// set the left menu button selected; options will change based on the main selection
    $left_selected = "";
    
    include("../includes/innerNav.php");
	require("../scramblerPuzzle/Scrambler.php");
	require("../linesPuzzle/Lines.php");
	require("../shapesPuzzle/Shapes.php");
	require("../stackPuzzle/Stacks.php");
	require("../swimLanesPuzzle/Swim.php");
    require("../dabblePuzzle/Dabble.php");
    require("../dabblePlusPuzzle/DabblePlus.php");
    require("../indic-wp/word_processor.php");
    
    $words = [];
    $wordInput = '';
    
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
    } else if (isset($_POST['ident'])) {
        $id = $_POST['ident'];
    } else {
        $setquery = "SELECT set_id FROM word_sets_meta ORDER BY RAND() LIMIT 1";
        //$setquery = "SELECT MAX(set_id) FROM word_sets_meta";
        $setresult = mysqli_query($db, $setquery);
        $data = mysqli_fetch_assoc($setresult);
        $id = $data['set_id'];
    }

    $wordsquery = "SELECT * FROM word_sets_meta WHERE set_id = $id";
    $wordsresult = mysqli_query($db, $wordsquery);

    $count = 0;
    $type = null;

    if ($wordsresult->num_rows > 0) {
        while ($row = $wordsresult->fetch_assoc()) {
            if($type == null){
                $type = $row['type'];
                $title = $row['title'];
                $subtitle = $row['subtitle'];
            }
            $sql = 'SELECT word FROM word_sets WHERE word_id = "'.$row['word_id'].'"';
            $data = mysqli_query($db, $sql);
            $raw_word = $data->fetch_assoc();
            $words[$count] = $raw_word['word'];
            $count++;
        }
    }

    for($i = 0; $i < count($words); $i++) {
        if($i < count($words) - 1) {
            $wordInput = $wordInput.$words[$i].'
            ';
        } else {
            $wordInput = $wordInput.$words[$i];
        }
    }

    $puzzleName = '';

    switch($type) {
        case 'dabble':
            $puzzleName = 'Dabble Puzzle';
            break;
        case 'dabbleplus':
            $puzzleName = 'Dabble Plus Puzzle';
            break;
        case 'stacks':
            $puzzleName = 'Stacks Puzzle';
            break;
        case 'scrambler':
            $puzzleName = 'Squares Puzzle';
            break;
        case 'lines':
            $puzzleName = 'Lines Puzzle';
            break;
        case 'swimlanes':
            $puzzleName = 'Swimlanes Puzzle';
            break;
        case 'shapes':
            $puzzleName = 'Shapes Puzzle';
            break;
        default:
            $puzzleName = 'This text should not appear';
    }

    $word_array = [];
    $puzzle = [];
    $letterlist = [];

    // Parse through input and generate a word list
	$wordList = generateWordList($wordInput);
?>
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
    <?php
    if(strcmp($type, 'stacks') == 0){?>
	<link rel="stylesheet" type="text/css" href="../css/stacksStyle.css">
    <?php } else { ?>
	<link rel="stylesheet" type="text/css" href="../css/puzzleStyle.css">
    <?php } ?>
    <?php
    if(strcmp($type, 'shapes') == 0){
	    echo '<link rel="stylesheet" type="text/css" href="../css/shapesStyle.css">';
    } ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">

    <title>Stacks Puzzle</title>
</head>
<style>
	form {
		display: inline;
	}
	
	.puzzleLetter{}
	.guess{}
</style>
<body>
        <?php
            if(strcmp($type, 'dabble') == 0 || strcmp($type, 'dabbleplus') == 0){
                ?>
                <script type="text/javascript">
                var puzzleType = 'pyramid';
                </script>
                <?php
            } else if (strcmp($type, 'lines') == 0) {
                ?>
                <script type="text/javascript">
                var puzzleType = 'lines';
                </script>
                <?php
            } else /*if (strcmp($type, 'scrambler') == 0 || strcmp($type, 'stacks') == 0)*/ {
                ?>
                <script type="text/javascript">
                var puzzleType = 'stepdown';
                </script>
                <?php
            }
        ?>
	</script>
<div class="container-fluid">
    <div class="panel">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12">
                            <div align="center"><h2><?php echo $puzzleName; ?></h2></div>
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

<?php
    if(strcmp($type, 'dabble') == 0) {
        // Create dabble puzzle
        $dabble = new Dabble($wordList);
        $letterList = $dabble->getLetterList();
        $wordList = $dabble->getWordList();
        $pyramidPuzzle = $dabble->getPyramidPuzzle();
        $pyramidLetterPuzzle = $dabble->getPyramidLetterPuzzle();
        $characterListNoSpaces = $dabble->getCharacterListNoSpaces();
        ?>
        <div class="col-sm-6">    
            <div class="letters pyramidLettersPuzzle">
                <div class="row"> <h3>Pyramid Letters</h3> </div>
                <div class="pyramid">
                    <?php
                        // Prints blank pyramid puzzle
                        // Cells must be printed with correct styling
                        // Top cell, then final top right cell, left cells, inside cells, right cells,
                        // bottom cells, then final right cell
                        $wordCount = count($wordList);
                        $count = 0;
                        for($i = 0; $i < $wordCount; $i++){
                            $word = $wordList[$i];
                            $charList = splitWord($word);
                            $length = getLengthNoSpaces($word);

                            echo'<div class="row">';

                            for($j = 0; $j < $length; $j++){
                                if($i == 0){
                                    if($j < $length - 1){
                                        echo'<div id="row'.$i.'column'.$j.'" class="top filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                    else{
                                        echo'<div id="row'.$i.'column'.$j.'" class="topRight filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                }
                                else if($i < $wordCount - 1){
                                    if($j == 0){
                                        echo'<div id="row'.$i.'column'.$j.'" class="left filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                    else if($j < ($length - 1)){
                                        echo'<div id="row'.$i.'column'.$j.'" class="inside filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                    else{
                                        echo'<div id="row'.$i.'column'.$j.'" class="right filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                }
                                else{
                                    if($j < ($length - 1)){
                                        echo'<div id="row'.$i.'column'.$j.'" class="bottom filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                    else{
                                        echo'<div id="row'.$i.'column'.$j.'" class="bottomRight filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                }
                            }
                            echo'</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="pyramidPuzzle word">
                <div class="row"> <h3> Pyramid </h3> </div>
                <div class="pyramid">
                    <?php
                        // Prints blank pyramid puzzle
                        // Cells must be printed with correct styling
                        // Top cell, then final top right cell, left cells, inside cells, right cells,
                        // bottom cells, then final right cell
                        $wordCount = count($wordList);

                        for($i = 0; $i < $wordCount; $i++){
                            $word = $wordList[$i];
                            $charList = splitWord($word);
                            $length = getLengthNoSpaces($word);

                            echo'<div class="row">';

                            for($j = 0; $j < $length; $j++){
                                if($i == 0){
                                    if($j < $length - 1){
                                        echo'<div id="row'.$i.'column'.$j.'" class="top filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                    }
                                    else{
                                        echo'<div id="row'.$i.'column'.$j.'" class="topRight filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                    }
                                }
                                else if($i < $wordCount - 1){
                                    if($j == 0){
                                        echo'<div id="row'.$i.'column'.$j.'" class="left filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                    }
                                    else if($j < ($length - 1)){
                                        echo'<div id="row'.$i.'column'.$j.'" class="inside filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                    }
                                    else{
                                        echo'<div id="row'.$i.'column'.$j.'" class="right filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                    }
                                }
                                else{
									if($j < ($length - 1)){
										echo'<div id="row'.$i.'column'.$j.'" class="bottom filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
									}
									else{
										echo'<div id="row'.$i.'column'.$j.'" class="bottomRight filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
									}
								}
							}
							echo'</div>';
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
        <script type="text/javascript">

            var answerArray = <?php echo json_encode($pyramidPuzzle) ?>;
            
            var lettersArray = <?php echo json_encode($pyramidLetterPuzzle) ?>;
            
            var wordList = <?php echo json_encode($wordList) ?>;
            
            var letterList = <?php echo json_encode($letterList) ?>;

        </script>
        <?php
    } else if (strcmp($type, 'dabbleplus') == 0) {
		// Create dabble puzzle
		$dabble = new DabblePlus($wordList);
        $letterList = $dabble->getLetterList();
        $wordList = $dabble->getWordList();
        $pyramidPuzzle = $dabble->getPyramidPuzzle();
        $pyramidLetterPuzzle = $dabble->getPyramidLetterPuzzle();
        $characterListNoSpaces = $dabble->getCharacterListNoSpaces();
        
        $wordColors = [];
        array_push($wordColors, "#FF0000");
        array_push($wordColors, "#0000FF");
        array_push($wordColors, "#00FF00");
        $currentColor = 0;
        ?>
        <div class="col-sm-6">
            <div class="letters pyramidLettersPuzzle">
                <div class="row"> <h3>Pyramid Letters</h3> </div>
                <div class="pyramid">
                    <?php
                        // Prints blank pyramid puzzle
                        // Cells must be printed with correct styling
                        // Top cell, then final top right cell, left cells, inside cells, right cells,
                        // bottom cells, then final right cell
                        $wordCount = count($wordList);
                        $count = 0;

                        for($i = 0; $i < $wordCount; $i++){
                            $word = $wordList[$i];
                            $length = getLengthNoSpaces($word);

                            echo'<div class="row">';

                            for($j = 0; $j < $length; $j++){
                                if ($characterListNoSpaces[$count] == ',') {
                                    $count++;
                                    $j--;	
                                } else if ($i == 0){
                                    if ($j < $length - 1){
                                        echo'<div id="row'.$i.'column'.$j.'" class="top filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                    else {
                                        echo'<div id="row'.$i.'column'.$j.'" class="topRight filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                } else if ($i < $wordCount - 1){
                                    if ($j == 0){
                                        echo'<div id="row'.$i.'column'.$j.'" class="left filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';
                                    }
                                    else if ($j < ($length - 1)){
                                        echo'<div id="row'.$i.'column'.$j.'" class="inside filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';

                                    }
                                    else {
                                        echo'<div id="row'.$i.'column'.$j.'" class="right filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';

                                    }
                                } else {
                                    if($j < ($length - 1)){
                                        echo'<div id="row'.$i.'column'.$j.'" class="bottom filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';

                                    }
                                    else{
                                        echo'<div id="row'.$i.'column'.$j.'" class="bottomRight filled puzzleLetter" draggable="true">'.$characterListNoSpaces[$count++].'</div>';

                                    }
                                }
                            }
                            echo'</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="pyramidPuzzle word">
                <div class="row"> <h3> Pyramid </h3> </div>
                <div class="pyramid">
                    <?php
                        // Prints blank pyramid puzzle
                        // Cells must be printed with correct styling
                        // Top cell, then final top right cell, left cells, inside cells, right cells,
                        // bottom cells, then final right cell
                            // print_r($pyramidPuzzle);
                            // echo '<br>';
                        $wordCount = count($wordList);

                        for($i = 0; $i < $wordCount; $i++){
                            $word = $wordList[$i];
                            $charList = splitWord($word);
                            $length = getWordLength($word);

                            echo'<div class="row">';
                            $count = 0;
                            for($j = 0; $j < $length; $j++){
                                    if(in_array(',', $charList)){
                                        if($charList[$j] == '0' || $charList[$j] == ' ') {}
                                        else if($charList[$j] == ','){
                                                $currentColor = ($currentColor + 1) % count($wordColors);															
                                        }
                                        else if($i == 0){
                                            if($j < $length - 1){
                                                echo'<div id="row'.$i.'column'.$count.'" class="top filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else{
                                                echo'<div id="row'.$i.'column'.$count.'" class="topRight filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $count++;
                                        }
                                        else if($i < $wordCount - 1){
                                            if($j == 0){
                                                echo'<div id="row'.$i.'column'.$count.'" class="left filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else if($j < ($length - 1)){
                                                echo'<div id="row'.$i.'column'.$count.'" class="inside filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else{
                                                echo'<div id="row'.$i.'column'.$count.'" class="right filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $count++;
                                        }
                                        else{
                                            if($j < ($length - 1)){
                                                echo'<div id="row'.$i.'column'.$count.'" class="bottom filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else{
                                                echo'<div id="row'.$i.'column'.$count.'" class="bottomRight filled guess" draggable="true" style="color:white; background-color:'.$wordColors[$currentColor].'">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $count++;
                                        }
                                    } else {
                                        if($charList[$j] == '0' || $charList[$j] == ' ') {}
                                        else if($i == 0){
                                            if($j < $length - 1){
                                                echo'<div id="row'.$i.'column'.$count.'" class="top filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else{
                                                echo'<div id="row'.$i.'column'.$count.'" class="topRight filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $count++;
                                        }
                                        else if($i < $wordCount - 1){
                                            if($j == 0){
                                                echo'<div id="row'.$i.'column'.$count.'" class="left filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else if($j < ($length - 1)){
                                                echo'<div id="row'.$i.'column'.$count.'" class="inside filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else{
                                                echo'<div id="row'.$i.'column'.$count.'" class="right filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $count++;
                                        }
                                        else{
                                            if($j < ($length - 1)){
                                                echo'<div id="row'.$i.'column'.$count.'" class="bottom filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            else{
                                                echo'<div id="row'.$i.'column'.$j.'" class="bottomRight filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $count++;
                                        }
                                    }
                                }
                                echo'</div>';
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
        <?php
        ?>
        <script type="text/javascript">

            var answerArray = <?php echo json_encode($pyramidPuzzle) ?>;
            
            var lettersArray = <?php echo json_encode($pyramidLetterPuzzle) ?>;
            
            var wordList = <?php echo json_encode($wordList) ?>;
            
            var letterList = <?php echo json_encode($letterList) ?>;

        </script>

        <?php
    } else if (strcmp($type, 'stacks') == 0) {
		// Create stacks puzzle
        $stacks = new Stacks($wordList);
        $letterList = $stacks->getLetterList();
        $wordList = $stacks->getWordList();
        $stepDownPuzzle = $stacks->getStacksStepDownPuzzle();
        $stepDownLetterPuzzle = $stacks->getStacksStepDownLetterPuzzle();
        $characterList = $stacks->getCharacterList();
        $characterListNoSpaces = $stacks->getCharacterListNoSpaces();
        ?>
        <div class="col-sm-6">
            <div class="letters stepdownLettersPuzzle">
                <div class="row"> <h3>Letters</h3> </div>
                <div class="row">
                    <table class="puzzle">
                        <?php
                            // Prints blank step down puzzle
                            foreach($stepDownLetterPuzzle as $row){
                                echo'<tr>';
                                foreach($row as $letter){
                                    if($letter != "0"){
                                        echo'<td class="filled puzzleLetter" draggable="true">'.$letter.'</td>';
                                    }
                                    else{
                                        echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
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
            <div class="stepdownPuzzle word">
                <div class="row"> <h3> Words </h3> </div>
                    <div class="row">
                        <table class="puzzle">
                            <?php
                                // Prints blank step down puzzle
                                foreach($stepDownPuzzle as $row){
                                    echo'<tr>';
                                    foreach($row as $letter){
                                        if($letter != "0"){
                                            echo'<td class="filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
        <script type="text/javascript">

            var answerArray = <?php echo json_encode($stepDownPuzzle) ?>;
            
            var lettersArray = <?php echo json_encode($stepDownLetterPuzzle) ?>;
            
            var wordList = <?php echo json_encode($wordList) ?>;
            
            var letterList = <?php echo json_encode($letterList) ?>;

        </script>
        <?php

    } else if (strcmp($type, 'scrambler') == 0) {
		// Create scrambler puzzle
		$scrambler = new Scrambler($wordList);
        $letterList = $scrambler->getLetterList();
        $wordList = $scrambler->getWordList();
        $stepUpPuzzle = $scrambler->getStepUpPuzzle();
        $stepUpLetterPuzzle = $scrambler->getStepUpLetterPuzzle();
        $characterList = $scrambler->getCharacterList();
        ?>
        <div class="col-sm-6">
            <div class="row letters rectangleLettersPuzzle"> <h3>Letters</h3>
				<table class="puzzle">
                    <?php
						// Prints a grid with 5 square width with puzzle letters
						foreach($letterList as $row){
							echo'<tr>';
							foreach($row as $letter){
								if($letter != "0"){
									echo'<td class="filled puzzleLetter" draggable="true">'.$letter.'</td>
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

		<?php //START OF WORDS PUZZLE *******************?>
        <div class="col-sm-6">
			<div class="stepupPuzzle word">
				<div class="row"> <h3>Words</h3></div>
				<div class="row">
					<table class="puzzle">
						<?php
							// Prints blank step up puzzle

							foreach($stepUpPuzzle as $row){
								echo'<tr>';
								foreach($row as $letter){
									if($letter != "0"){
										echo'<td class="filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
        <script type="text/javascript">

            var answerArray = <?php echo json_encode($stepUpPuzzle) ?>;

            var lettersArray = <?php echo json_encode($stepUpLetterPuzzle) ?>;

            var wordList = <?php echo json_encode($wordList) ?>;

            var letterList = <?php echo json_encode($letterList) ?>;

        </script>
        <?php
    } else if (strcmp($type, 'swimlanes') == 0) {
		// Create swimlanes puzzle
		$swim = new Swim($wordList);
        $fullWords = $swim->getFullWords();
        $letterList = $swim->getLetterList();
        $wordList = $swim->getWordList();
        $characterList = $swim->getCharacterList();
        $swimPuzzle = $swim->getSwimlanesPuzzle();
        $scrambledFullWords = $swim->getScrambledFullWords();
        ?>
        <div class="col-sm-6">
            <div class="row letters fullLetterPuzzle"> <h3>Letters</h3>
                <table class="puzzle">
                <?php
                    // THIS PRINTS THE FIXED FULL LETTERS FOR THE SWIMLANES PUZZLE
                    foreach($fullWords as $row){
                        echo'<tr>';
                        foreach($row as $letter){
                            if($letter != "0"){
                                echo'<td class="filled puzzleLetter" draggable="true">'.$letter.'</td>
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
                                        echo'<td class="filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
        <script type="text/javascript">

            var answerArray = <?php echo json_encode($letterList) ?>;

            var lettersArray = <?php echo json_encode($fullWords) ?>;

            var wordList = <?php echo json_encode($wordList) ?>;

            var letterList = <?php echo json_encode($letterList) ?>;

        </script>
        <?php
    } else if (strcmp($type, 'lines') == 0) {
        // Create lines puzzle
        $lines = new Lines($wordList);
        $letterList = $lines->getLetterList();
        $wordList = $lines->getWordList();
        $linesPuzzle = $lines->getLinesPuzzle();
        $linesLetterPuzzle = $lines->getLinesLetterPuzzle();
        ?>
        <div class="col-sm-6">
            <div class="letters linesLettersPuzzle">
                <div class="row"> <h3>Letters</h3> </div>
                <div class="row">
                    
                        <?php
                            // Prints blank lines puzzle
                            echo'<table class="puzzle">';
                            foreach($linesLetterPuzzle as $row){
                                //echo'<table class="puzzle">
                                echo '<tr>';
                                foreach($row as $letter){
                                    if($letter != "0"){
                                        echo'<td class="filled puzzleLetter" draggable="true">'.$letter.'</td>';
                                    }
                                    else{
                                        echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
                                    }
                                }
                                echo'</tr><tr>';
                                for($i = 0; $i < count($row); $i++) {
                                    echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
                                }
                                echo'</tr>';
                            }
                            echo '</table>';
                        ?>
                    
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="linesPuzzle word">
                <div class="row"> <h3> Words </h3> </div>
                    <div class="row">
                    
                        <?php
                                echo'<table class="puzzle">';
                            foreach($linesPuzzle as $row){
                                //echo'<table class="puzzle">
                                echo '<tr>';
                                foreach($row as $letter){
                                    if($letter != "0"){
                                        echo'<td class="filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                    }
                                    else{
                                        echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
                                    }
                                }
                                echo'</tr><tr>';
                                for($i = 0; $i < count($row); $i++) {
                                    echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
                                }
                                echo'</tr>';
                            }
                            echo '</table>';
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
        <script type="text/javascript">

            var answerArray = <?php echo json_encode($linesPuzzle) ?>;

            var lettersArray = <?php echo json_encode($linesLetterPuzzle) ?>;

            var wordList = <?php echo json_encode($wordList) ?>;

            var letterList = <?php echo json_encode($letterList) ?>;

        </script>
        <?php
    } else if (strcmp($type, 'shapes') == 0) {
        // Create shapes puzzle
        $shapes = new Shapes($wordList);
		$wordList = $shapes->getWordList();
		$shapesPuzzle = $shapes->getShapesPuzzle();
		$wordPuzzle = $shapes->getWordPuzzle();
		$letterPuzzle = $shapes->getLetterPuzzle();
        $characterList = $shapes->getCharacterList();
        ?>
        <div class="col-sm-6">
			<div class="main">
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
                                        echo'<td class="filled guess" draggable="true">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                                    }
                                    else{
                                        echo'<td class="empty"> &nbsp;&nbsp;&nbsp;&nbsp; </td>';
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
        <script type="text/javascript">
            
            var wordList = <?php echo json_encode($wordList) ?>;
            console.log(wordList);

            var answerArray = <?php echo json_encode($wordList) ?>;
            console.log(answerArray);

            var lettersArray = <?php echo json_encode($shapesPuzzle) ?>;
            console.log(lettersArray);

            var letterList = <?php echo json_encode($shapesPuzzle) ?>;
            console.log(letterList);

            var xyCoords = <?php echo json_encode($xyCoords) ?>;
            console.log(xyCoords);
            
            var wordPuzzle = <?php echo json_encode($wordList) ?>;
            console.log(wordPuzzle);
            
            var letterPuzzle = <?php echo json_encode($shapesPuzzle) ?>;
            console.log(letterPuzzle);
            
            var shapesPuzzle = <?php echo json_encode($shapesPuzzle) ?>;
            console.log(shapesPuzzle);
        </script>
        <?php
    }




    /*** Word Processor Functions ***/
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
    
	function getWordLength($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLength();
	}

	function getLengthNoSpaces($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLengthNoSpacesNoCommas($word);
	}
	function splitWord($word){
		$wordProcessor = new wordProcessor(" ", "telugu");
		$wordProcessor->setWord($word, "telugu");

		return $wordProcessor->getLogicalChars();
	}
?>

<script type="text/javascript" src="../js/shapesPlayable.js"></script>
<script type="text/javascript" src="../js/feelingLucky.js"></script>
    <script>
        function dragStart(event) {
            dropped = false;
            event.preventDefault();
            //event.dataTransfer.dropEffect("move");
            event.dataTransfer.setData("text", event.target.innerHTML);
        }

        function dragEnter(event) {
            event.preventDefault();
        }

        function dragLeave(event) {
            event.preventDefault();
        }

        function dragDrop(event) {
            event.preventDefault();
            var setHTML = event.dataTransfer.getData("text");
            event.target.innerHTML = setHTML;
        }
    </script>
