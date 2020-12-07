<?php
	
    if(session_id() == '' || !isset($_SESSION)){
        session_start();
    }

	if(isset($_GET["error"])){
		$error = $_GET["error"];
		
		// Check to see if an error code was passed in
		// Error get variable is passed through ScramblerPuzzle.php after an issue has been detected
		// Here the message will get displayed and prompt the user to try again
		switch($error){
			case "emptyinput":
				$errorMessage = "No input was provided.  Enter words with identical word lengths.";
				break;
			case "count":
				$errorMessage = "Only one word was entered.  Please enter more than one word.";
				break;
			/**case "invalidinput":
				$errorMessage = "Input was invalid. Enter words with identical word lengths.";
				break;**/
            case "nonalpha":
                $errorMessage = "Input cannot contain numbers or special characters.";
                break;
            case 'duplicate':
                $errorMessage = "Input cannot contain duplicate words. Please make each word unique.";
                break;
			default:
				$errorMessage = "Unknown error - try again";
		}
		
    }
    
    // set the current page to one of the main buttons
    $nav_selected = "STACKS";

    // make the left menu buttons visible; options: YES, NO
    $left_buttons = "NO";

    // set the left menu button selected; options will change based on the main selection
    $left_selected = "";

    include("../includes/innerNav.php");

    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
      ?>
      
      
        <div class="right-content">
          <div class="container">
    
          <h3 style = "color: red;">Please log in to view this page</h3>
    
          </div>
        </div>
      
      <?php
    } else if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
      ?>
      
      
        <div class="right-content">
          <div class="container">
    
          <h3 style = "color: red;">Admin privileges are required to view this page</h3>
    
          </div>
        </div>
      
      <?php
    } else {
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/puzzleStyle.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">
    <title>Stacks Puzzle</title>
	
</head>
<body>
    <br>
        <div class="form-group">
			<div class="col-sm-1"></div>
			<div class="col-sm-10">
				<label class="charLabel" style="color:red;font-size:14px;" name="charName" value="">
				<?php
					// If there is a warning message after input validation display message to user
					if(isset($errorMessage)){
						echo($errorMessage);
					}
				?>
				</label>
			</div>
		</div>
    <br>
    <form action="stacksPuzzle.php" method="post" name="shapesForm" class="form-horizontal">
        <div class="container-fluid">
            <div class="panel">
                <div class="panel-group">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div align="center"><h2>Stacks Puzzle Maker</h2></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Title</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="title" name="title" <?php
                                        if(isset($_SESSION['title']) && isset($_SESSION['lastpage']) &&
                                        $_SESSION['lastpage'] == 'stacks'){
                                            $title = $_SESSION['title'];
                                            echo "value='".$title."'";
                                        } else {
                                            echo 'value="Title"';
                                        }?>>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Subtitle</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="subtitle" name="subtitle" <?php
                                        if(isset($_SESSION['subtitle']) && isset($_SESSION['lastpage']) && 
                                        $_SESSION['lastpage'] == 'stacks'){
                                            $subtitle = $_SESSION['subtitle'];
                                            echo "value='".$subtitle."'";
                                        } else {
                                            echo 'value="Subtitle"';
                                        }?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Puzzle Mode</label>
                                
                                <div class="col-sm-3">
                                    <select class="form-control" id="puzzletype" name="puzzletype" onchange="sizeChange(this.value);">
                                        <?php
                                            if(isset($_SESSION['puzzletype']) && isset($_SESSION['lastpage']) &&
                                            $_SESSION['lastpage'] == 'stacks'){
                                                if($_SESSION['puzzletype'] == 'pyramid'){
                                                    echo '<option value="pyramid" selected="selected">Center Justified</option>
                                                    <option value="stepup" >Right Justified</option>
                                                    <option value="stepdown" >Left Justified</option>';
                                                } else if($_SESSION['puzzletype'] == 'stepup'){
                                                    echo '<option value="pyramid" >Center Justified</option>
                                                    <option value="stepup" selected="selected">Right Justified</option>
                                                    <option value="stepdown" >Left Justified</option>';
                                                } else {
                                                    echo '<option value="pyramid" >Center Justified</option>
                                                    <option value="stepup" >Right Justified</option>
                                                    <option value="stepdown" selected="selected">Left Justified</option>';
                                                }
                                            }
                                            else{
                                                echo '<option value="pyramid" selected="selected">Center Justified</option>
                                                <option value="stepup" >Right Justified</option>
                                                <option value="stepdown">Left Justified</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-9" style="text-align: left;">Enter multiple words each on a new line.
                                <br>Words can be any variety of lengths.
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="10" id="input" name="wordInput"><?php
                                            if(isset($_SESSION['userInput']) && isset($errorMessage)){
                                                echo $_SESSION['userInput'];
                                            }
                                        ?></textarea>
                                </div>
                            </div> 
							<!-- <div class="form-group">
								<div class="col-sm-1"></div>
								<div class="col-sm-10">
									<label class="charLabel" style="color:red;font-size:14px;" name="charName" value="">
									<?php
										// If there is a warning message after input validation display message to user
										if(isset($errorMessage)){
											echo($errorMessage);
										}
									?>
									</label>
								</div>
							</div> -->
							<div class="row">
								<div class="text-center">
									<input type="submit" name="submit" class="btn btn-primary btn-lg" value="Generate">
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
<?php $_SESSION['lastpage'] = 'stacksIndex';?>

    <script type="text/javascript">

        function checkform() {
            var inputString = document.forms["shapesForm"]["wordInput"].value;
            var wordList = inputString.split("\n");
            console.log(wordList);
            var duplicates = new Array(0);
            var noDuplicates = true;
            var errorString = 'You cannot have duplicate words in your input. Please resolve.\n\nDuplicates found are:\n';
                
            for(i = 0; i < wordList.length; i++) {
                for(j = 0; j < wordList[i].length; j++) {
                    if(!isNaN(parseInt(wordList[i].charAt(j), 10))) {
                        alert('Numbers are not permitted in the input. Please resolve.');
                        console.log('word ' + i + ' character ' + j + ' letter is ' + wordList[i].charAt(j));
                        return false;
                    } else if(j == i){}
                    else if (wordList[i] == wordList[j]) { 
                        if(!duplicates.includes(wordList[j])) {
                            duplicates.push(wordList[j]);
                        }
                        noDuplicates = false;
                    }
                }
            }
            if(!noDuplicates) {
                for (i = 0; i < duplicates.length; i++) {
                    if(duplicates[i] == null) {
                        break;
                    }
                    errorString += duplicates[i] + '\n';
                }
                alert(errorString);
            }

            return noDuplicates;
        }

    </script>
</html>
    <?php } ?>