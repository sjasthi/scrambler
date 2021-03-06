<?php
require_once '../functions/session_start.php';

	if(isset($_GET["error"])){
		$error = $_GET["error"];
		
		// Check to see if an error code was passed in
		// Error get variable is passed through swimPuzzle.php after an issue has been detected
		// Here the message will get displayed and prompt the user to try again
		switch($error){
			case "emptyinput":
				$errorMessage = "No input was provided.  Enter words with identical word lengths.";
				break;
			case "count":
				$errorMessage = "Only one word was entered.  Please enter more than one word.";
				break;
			case "invalidinput":
				$errorMessage = "Input was invalid. Enter words with identical word lengths.";
				break;
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
    $nav_selected = "SWIM";

    // make the left menu buttons visible; options: YES, NO
    $left_buttons = "NO";

    // set the left menu button selected; options will change based on the main selection
    $left_selected = "";

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
    <title>SwimLanes Puzzle</title>
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
    <form action="swimPuzzle.php" method="post" name="swimForm" class="form-horizontal">
        <div class="container-fluid">
            <div class="panel">
                <div class="panel-group">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div align="center"><h2>SwimLanes Puzzle Maker</h2></div>
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
                                        $_SESSION['lastpage'] == 'swim'){
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
                                        $_SESSION['lastpage'] == 'swim'){
                                            $subtitle = $_SESSION['subtitle'];
                                            echo "value='".$subtitle."'";
                                        } else {
                                            echo 'value="Subtitle"';
                                        }?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Fixed or Scrambled </label>

                                <div class="col-sm-3">
                                    <form action="swimPuzzle.php" method="post">
                                    <select class="form-control" id="scrambled" name="scrambled">
                                        <?php
                                            if(isset($_SESSION['scrambled']) && isset($_SESSION['lastpage']) && 
                                            $_SESSION['lastpage'] == 'swim'){
                                                if($_SESSION['scrambled'] == 'fixed'){
                                                    echo '<option value="fixed" selected="selected">Fixed</option>
                                                    <option value="scrambled">Scrambled</option>';
                                                } else if($_SESSION['scrambled'] == 'scrambled'){
                                                    echo '<option value="fixed">Fixed</option>
                                                    <option value="scrambled" selected="selected">Scrambled</option>';
                                                }
                                            }
                                            else{
                                                echo '<option value="fixed" selected="selected">Fixed</option>
                                                <option value="scrambled">Scrambled</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Full or Sparse </label>
                                
                                <div class="col-sm-3">
                                    <select class="form-control" id="puzzletype" name="puzzletype">
                                    <?php
                                            if(isset($_SESSION['puzzletype']) && isset($_SESSION['lastpage']) && 
                                            $_SESSION['lastpage'] == 'swim'){
                                                if($_SESSION['puzzletype'] == 'full'){
                                                    echo '<option value="full" selected="selected">Full</option>
                                                    <option value="sparse">Sparse</option>';
                                                } else if($_SESSION['puzzletype'] == 'sparse'){
                                                    echo '<option value="full" >Full</option>
                                                    <option value="sparse" selected="selected">Sparse</option>';
                                                }
                                            }
                                            else{
                                                echo '<option value="full" selected="selected">Full</option>
                                                <option value="sparse">Sparse</option>';
                                            }
                                    ?>
                                    </select>
                                    </form>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-9" style="text-align: left;">Enter multiple words each on a new line.
                                <br>All words should be equal in length.
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
<?php $_SESSION['lastpage'] = 'swimIndex';?>
    <script type="text/javascript">

        function checkform() {
            var inputString = document.forms["swimForm"]["wordInput"].value;
            if(inputString == '') {
                alert('Cannot have empty input. Please enter at least 2 words');
                return false;
            }
            var wordList = inputString.split("\n");
            if(wordList.length == 1) {
                alert('Cannot have input of less than 2 words. Please enter at least 2 words');
                return false;
            }
            var length = wordList[0].length;
            var duplicates = new Array(0);
            var noDuplicates = true;
            var errorString = 'You cannot have duplicate words in your input. Please resolve.\n\nDuplicates found are:\n';
            
                
            for(i = 0; i < wordList.length; i++) {
                if(wordList[i].length != length) {
                    alert('Words must be of the same length. Please resolve.')
                    return false;
                }
                for(j = 0; j < wordList[i].length; j++) {
                    if(!isNaN(parseInt(wordList[i].charAt(j), 10))) {
                        alert('Numbers are not permitted in the input. Please resolve.');
                        console.log('word ' + i + ' character ' + j + ' letter is ' + wordList[i].charAt(j));
                        return false;
                    } else if(j == i){}
                    else if(wordList[i] == wordList[j]) { 
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