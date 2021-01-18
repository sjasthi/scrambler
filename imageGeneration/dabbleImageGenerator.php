<?php
require_once '../functions/session_start.php';

$stepUpLetterPuzzle = $_SESSION['stepupletterpuzzle'];
$stepDownLetterPuzzle = $_SESSION['stepdownletterpuzzle'];
$pyramidLetterPuzzle = $_SESSION['pyramidletterpuzzle'];
$letterList = $_SESSION['letterlist'];

$letterPuzzleType = $_POST['puzzlelettertype'];
$wordsPuzzleType = $_POST['puzzletype'];

$title = $_SESSION['title'];
$subtitle = $_SESSION['subtitle'];

$puzzleName = '';
$url = '';

switch($_SESSION['lastpage']) {
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
    case 'swim':
        $puzzleName = 'Swimlanes Puzzle';
        break;
    case 'shapes':
        $puzzleName = 'Shapes Puzzle';
        break;
    default:
        $puzzleName = 'How did you get here?';
}

header("Content-type: image/png");

$img_width = 3508;
$img_height = 2480;

$image = imagecreatetruecolor($img_width, $img_height);

imagesetthickness($image, 5);

$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$red   = imagecolorallocate($image, 255, 0, 0);
$green = imagecolorallocate($image, 0, 255, 0);
$blue  = imagecolorallocate($image, 0, 0, 255);
$orange = imagecolorallocate($image, 255, 200, 0);

imagefill($image, 0, 0, $white);

$silcImage = imagecreatefrompng('../images/silcLogoLarge.png');

imagecopy($image, $silcImage, 125, 75, 0, 0, 303, 166);

if(true){
    $image_width = 3508;
    $image_height = 600;

    $text_bound = imagettfbbox(150, 0, realpath('./arlrdbd.ttf'), $puzzleName);

    //Get the text upper, lower, left and right corner bounds
    $lower_left_x =  $text_bound[0]; 
    $lower_left_y =  $text_bound[1];
    $lower_right_x = $text_bound[2];
    $lower_right_y = $text_bound[3];
    $upper_right_x = $text_bound[4];
    $upper_right_y = $text_bound[5];
    $upper_left_x =  $text_bound[6];
    $upper_left_y =  $text_bound[7];


    //Get Text Width and text height
    $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
    $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y

    // //Get the starting position for centering
    $x_offset = ($image_width - $text_width) / 2;
    $y_offset = ($image_height - $text_height) / 2;
    
    // Add text to image
    imagettftext($image, 150, 0, $x_offset, $y_offset, $black, realpath('./arlrdbd.ttf'), $puzzleName);
}
if(true){
    $image_width = 3508;
    $image_height = 900;

    $text_bound = imagettfbbox(100, 0, realpath('./arlrdbd.ttf'), $title);

    //Get the text upper, lower, left and right corner bounds
    $lower_left_x =  $text_bound[0]; 
    $lower_left_y =  $text_bound[1];
    $lower_right_x = $text_bound[2];
    $lower_right_y = $text_bound[3];
    $upper_right_x = $text_bound[4];
    $upper_right_y = $text_bound[5];
    $upper_left_x =  $text_bound[6];
    $upper_left_y =  $text_bound[7];


    //Get Text Width and text height
    $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
    $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y

    // //Get the starting position for centering
    $x_offset = ($image_width - $text_width) / 2;
    $y_offset = ($image_height - $text_height) / 2;
    
    // Add text to image
    imagettftext($image, 100, 0, $x_offset, $y_offset, $black, realpath('./arlrdbd.ttf'), $title);
}
if(true){
    $image_width = 3508;
    $image_height = 1150;

    $text_bound = imagettfbbox(75, 0, realpath('./arlrdbd.ttf'), $subtitle);

    //Get the text upper, lower, left and right corner bounds
    $lower_left_x =  $text_bound[0]; 
    $lower_left_y =  $text_bound[1];
    $lower_right_x = $text_bound[2];
    $lower_right_y = $text_bound[3];
    $upper_right_x = $text_bound[4];
    $upper_right_y = $text_bound[5];
    $upper_left_x =  $text_bound[6];
    $upper_left_y =  $text_bound[7];

    //Get Text Width and text height
    $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
    $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y

    // //Get the starting position for centering
    $x_offset = ($image_width - $text_width) / 2;
    $y_offset = ($image_height - $text_height) / 2;
    
    // Add text to image
    imagettftext($image, 75, 0, $x_offset, $y_offset, $black, realpath('./arlrdbd.ttf'), $subtitle);
}
// boundText(400, 100, $title, $black, realpath('./arlrdbd.ttf'));
// boundText(550, 75, $subtitle, $black, realpath('./arlrdbd.ttf'));

$maxlen = max(array_map('count', $stepDownLetterPuzzle));

$startingX = $img_width - 300 - (140 * $maxlen);
$startingY = 700;
$pyramidStartingX = 0;

imagettftext($image, 100, 0, $img_width - 845, $startingY - 120, $black, realpath('./arlrdbd.ttf'), 'Solution');

if($wordsPuzzleType == 'stepup') {
    for($i = 0; $i < count($stepUpLetterPuzzle); $i++){
        for($j = 0; $j < count($stepUpLetterPuzzle[$i]); $j++) {
            if($stepUpLetterPuzzle[$i][$j] != '0' && $stepUpLetterPuzzle[$i][$j] != ',' && $stepUpLetterPuzzle[$i][$j] != ' ') {
            imagefilledrectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $white);
            imagerectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $black);
            } else if($stepUpLetterPuzzle[$i][$j] == '0') {
                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            }
        }
    }
} else if ($wordsPuzzleType == 'stepdown'){
    for($i = 0; $i < count($stepDownLetterPuzzle); $i++){
        for($j = 0; $j < count($stepDownLetterPuzzle[$i]); $j++) {
            if($stepDownLetterPuzzle[$i][$j] != '0' && $stepDownLetterPuzzle[$i][$j] != ',' && $stepDownLetterPuzzle[$i][$j] != ' ') {
            imagefilledrectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $white);
            imagerectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $black);
            } else if($stepDownLetterPuzzle[$i][$j] == '0') {
                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            }
        }
    }
} else {
    $fullWidth = $startingX + (140 * $maxlen);
    $midPoint = $fullWidth - ((140 * $maxlen)/2);
    for($i = 0; $i < count($pyramidLetterPuzzle); $i++){
        $count = 0;
        for($j = 0; $j < count($pyramidLetterPuzzle[$i]); $j++) {
            if($pyramidLetterPuzzle[$i][$j] != '0' && $pyramidLetterPuzzle[$i][$j] != ',' && $pyramidLetterPuzzle[$i][$j] != ' '){
                $count++;
            }
        }
        $pyramidStartingX = $midPoint - (70 * $count);
        for($j = 0; $j < $count; $j++) {
            if($pyramidLetterPuzzle[$i][$j] != '0' && $pyramidLetterPuzzle[$i][$j] != ',' && $pyramidLetterPuzzle[$i][$j] != ' ') {
                imagefilledrectangle($image, $pyramidStartingX + (140 * $j), $startingY - 105 + (140 * $i), $pyramidStartingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $white);
                imagerectangle($image, $pyramidStartingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $pyramidStartingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $black);
            }
            // } else if($pyramidLetterPuzzle[$i][$j] == '0') {
            //     $currentX1 = $pyramidStartingX - 25 + (140 * $j);
            //     $currentX2 = $pyramidStartingX + 115 + (140 * $j);
            //     $currentY1 = $startingY - 105 + (140 * $i);
            //     $currentY2 = $startingY + 35 + (140 * $i);

            //     imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            // }
        }
    }
}

$startingX = 300;
$startingY = 700;
$pyramidStartingX = 0;

imagettftext($image, 100, 0, $startingX - 35, $startingY - 120, $black, realpath('./arlrdbd.ttf'), 'Puzzle');
if($letterPuzzleType == 'stepup') {
    for($i = 0; $i < count($stepUpLetterPuzzle); $i++){
        for($j = 0; $j < count($stepUpLetterPuzzle[$i]); $j++) {
            if($stepUpLetterPuzzle[$i][$j] != '0' && $stepUpLetterPuzzle[$i][$j] != ',' && $stepUpLetterPuzzle[$i][$j] != ' ') {

                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $white);
                imagerectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $black);
                
                $text_bound = imagettfbbox(60, 0, realpath('./NTR-Regular.ttf'), $stepUpLetterPuzzle[$i][$j]);

                $lower_left_x =  $text_bound[0]; 
                $lower_left_y =  $text_bound[1];
                $lower_right_x = $text_bound[2];
                $lower_right_y = $text_bound[3];
                $upper_right_x = $text_bound[4];
                $upper_right_y = $text_bound[5];
                $upper_left_x =  $text_bound[6];
                $upper_left_y =  $text_bound[7];
                    
                // Get Text Width and text height
                $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
                $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y
                    
                $x_offset = $currentX1 + ($currentX2 - $currentX1)/2 - $text_width/2;
                $y_offset = $currentY1 + ($currentY2 - $currentY1)/2 +10;

                imagettftext($image, 60, 0, $x_offset, $y_offset, $black, realpath('./NTR-Regular.ttf'), $stepUpLetterPuzzle[$i][$j]);
            } else if($stepUpLetterPuzzle[$i][$j] == '0') {
                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            }
        }
    }
} else if ($letterPuzzleType == 'stepdown') {
    for($i = 0; $i < count($stepDownLetterPuzzle); $i++){
        for($j = 0; $j < count($stepDownLetterPuzzle[$i]); $j++) {
            if($stepDownLetterPuzzle[$i][$j] != '0' && $stepDownLetterPuzzle[$i][$j] != ',' && $stepDownLetterPuzzle[$i][$j] != ' ') {

                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $white);
                imagerectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $black);
                
                $text_bound = imagettfbbox(60, 0, realpath('./NTR-Regular.ttf'), $stepDownLetterPuzzle[$i][$j]);

                $lower_left_x =  $text_bound[0]; 
                $lower_left_y =  $text_bound[1];
                $lower_right_x = $text_bound[2];
                $lower_right_y = $text_bound[3];
                $upper_right_x = $text_bound[4];
                $upper_right_y = $text_bound[5];
                $upper_left_x =  $text_bound[6];
                $upper_left_y =  $text_bound[7];
                    
                // Get Text Width and text height
                $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
                $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y
                    
                $x_offset = $currentX1 + ($currentX2 - $currentX1)/2 - $text_width/2;
                $y_offset = $currentY1 + ($currentY2 - $currentY1)/2 +10;

                imagettftext($image, 60, 0, $x_offset, $y_offset, $black, realpath('./NTR-Regular.ttf'), $stepDownLetterPuzzle[$i][$j]);
            } else if($stepDownLetterPuzzle[$i][$j] == '0') {
                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            }
        }
    }
} else if ($letterPuzzleType == 'rectangle') {
    for($i = 0; $i < count($letterList); $i++){
        for($j = 0; $j < count($letterList[$i]); $j++) {
            if($letterList[$i][$j] != '0' && $letterList[$i][$j] != ',' && $letterList[$i][$j] != ' ') {

                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $white);
                imagerectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $black);
                
                $text_bound = imagettfbbox(60, 0, realpath('./NTR-Regular.ttf'), $letterList[$i][$j]);

                $lower_left_x =  $text_bound[0]; 
                $lower_left_y =  $text_bound[1];
                $lower_right_x = $text_bound[2];
                $lower_right_y = $text_bound[3];
                $upper_right_x = $text_bound[4];
                $upper_right_y = $text_bound[5];
                $upper_left_x =  $text_bound[6];
                $upper_left_y =  $text_bound[7];
                    
                // Get Text Width and text height
                $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
                $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y
                    
                $x_offset = $currentX1 + ($currentX2 - $currentX1)/2 - $text_width/2;
                $y_offset = $currentY1 + ($currentY2 - $currentY1)/2 +10;

                imagettftext($image, 60, 0, $x_offset, $y_offset, $black, realpath('./NTR-Regular.ttf'), $letterList[$i][$j]);
            } else if($letterList[$i][$j] == '0') {
                $currentX1 = $startingX - 25 + (140 * $j);
                $currentX2 = $startingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            }
        }
    }
} else {
    $fullWidth = $startingX + (140 * $maxlen);
    $midPoint = $fullWidth - ((140 * $maxlen)/2);
    for($i = 0; $i < count($pyramidLetterPuzzle); $i++){
        $count = 0;
        for($j = 0; $j < count($pyramidLetterPuzzle[$i]); $j++) {
            if($pyramidLetterPuzzle[$i][$j] != '0' && $pyramidLetterPuzzle[$i][$j] != ',' && $pyramidLetterPuzzle[$i][$j] != ' '){
                $count++;
            }
        }
        $pyramidStartingX = $midPoint - (70 * $count);
        for($j = 0; $j < $count; $j++) {
            if($pyramidLetterPuzzle[$i][$j] != '0' && $pyramidLetterPuzzle[$i][$j] != ',' && $pyramidLetterPuzzle[$i][$j] != ' ') {

                $currentX1 = $pyramidStartingX - 25 + (140 * $j);
                $currentX2 = $pyramidStartingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $white);
                imagerectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $black);
                
                $text_bound = imagettfbbox(60, 0, realpath('./NTR-Regular.ttf'), $pyramidLetterPuzzle[$i][$j]);

                $lower_left_x =  $text_bound[0]; 
                $lower_left_y =  $text_bound[1];
                $lower_right_x = $text_bound[2];
                $lower_right_y = $text_bound[3];
                $upper_right_x = $text_bound[4];
                $upper_right_y = $text_bound[5];
                $upper_left_x =  $text_bound[6];
                $upper_left_y =  $text_bound[7];
                    
                // Get Text Width and text height
                $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
                $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y
                    
                $x_offset = $currentX1 + ($currentX2 - $currentX1)/2 - $text_width/2;
                $y_offset = $currentY1 + ($currentY2 - $currentY1)/2 +10;

                imagettftext($image, 60, 0, $x_offset, $y_offset, $black, realpath('./NTR-Regular.ttf'), $pyramidLetterPuzzle[$i][$j]);
            } else if($pyramidLetterPuzzle[$i][$j] == '0') {
                $currentX1 = $pyramidStartingX - 25 + (140 * $j);
                $currentX2 = $pyramidStartingX + 115 + (140 * $j);
                $currentY1 = $startingY - 105 + (140 * $i);
                $currentY2 = $startingY + 35 + (140 * $i);

                imagefilledrectangle($image, $currentX1 + 5, $currentY1 + 5, $currentX2 - 5, $currentY2 - 5, $white);
            }
        }
    }
}

imagepng($image);
imagedestroy($image);

?>