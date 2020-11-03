<?php
	if(isset($_GET["error"])){
		$error = $_GET["error"];
		
		// Check to see if an error code was passed in
		// Error get variable is passed through DabblePuzzle.php after an issue has been detected
		// Here the message will get displayed and prompt the user to try again
		switch($error){
			case "emptyinput":
				$errorMessage = "No input was provided.  Please enter words in sequential length order.";
				break;
			case "count":
				$errorMessage = "Only one word was entered.  Please enter more than one word.";
				break;
			case "invalidinput":
				$errorMessage = "Input was invalid. Enter words with sequential word lengths. Example: word with length 2, word with length 3, word with length 4, etc.";
				break;
			default:
				$errorMessage = "Unknown error - try again";
		}
		
    }
    
    // set the current page to one of the main buttons
    $nav_selected = "DABBLE";

    // make the left menu buttons visible; options: YES, NO
    $left_buttons = "NO";

    // set the left menu button selected; options will change based on the main selection
    $left_selected = "";

    include("../includes/innerNav.php");
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
    <title>Dabble Puzzle</title>
	
</head>
<body>
    <form action="DabblePuzzle.php" method="post" name="shapesForm" class="form-horizontal" onsubmit='return checkform()'>
        <div class="container-fluid">
            <div class="panel">
                <div class="panel-group">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div align="center"><h2>Dabble Puzzle Maker</h2></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Title</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="title" name="title" value="Title">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Subtitle</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="subtitle" name="subtitle" value="Subtitle">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-1" style="text-align: left;">Puzzle Mode</label>
                                
                                <div class="col-sm-3">
                                    <select class="form-control" id="puzzletype" name="puzzletype" onchange="sizeChange(this.value);">
                                        <option value="pyramid" selected="selected">Pyramid</option>
                                        <option value="stepup" >Step Up</option>
										<option value="stepdown" >Step Down</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label class="control-label col-sm-9" style="text-align: left;">Enter the pyramid words each on a new line.  Word lengths should be unique and have no gaps in numbers.<br>Example: 3 letter word, 4 letter word, 5 letter word, etc.
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="10" id="input" name="wordInput"></textarea>
                                </div>
                            </div> 
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
    <script type="text/javascript">

        function checkform() {
            var inputString = document.forms["shapesForm"]["wordInput"].value;
            if(inputString == '') {
                alert('Cannot have empty input. Please enter at least 2 words');
                return false;
            }
            var wordList = inputString.split("\n");
            if(wordList.length == 1) {
                alert('Cannot have input of less than 2 words. Please enter at least 2 words');
                return false;
            }
            var duplicates = new Array(0);
            var length = wordList[0].length;
            var noDuplicates = true;
            var errorString = 'You cannot have duplicate words in your input. Please resolve.\n\nDuplicates found are:\n';
                
            for(i = 0; i < wordList.length; i++) {
                if(wordList[i].length != length) {
                    alert('Words must increment in length by one character per line. Please resolve.')
                    return false;
                }
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
                length++;
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