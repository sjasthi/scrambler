<?php
require_once '../functions/session_start.php';

$puzzle = $_SESSION['puzzle'];
$title = $_SESSION['title'];
$subtitle = $_SESSION['subtitle'];
$puzzleType = $_POST['puzzletype'];

$puzzleName = '';
$url = '';

//Check to see what page this was accessed from
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

// print_r($puzzle);
// echo '<br>';
// for($i = 0; $i < count($puzzle); $i++){
//     print_r($puzzle[$i]);
//     echo '<br>';
// }

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

$maxlen = max(array_map('count', $puzzle));
$numShapes = count($puzzle[0]);


 $startingX = $img_width - 300 - (140 * $maxlen);
 $startingY = 740;

imagettftext($image, 100, 0, $img_width - 845, $startingY - 160, $black, realpath('./arlrdbd.ttf'), 'Solution');

for($i = 0; $i < count($puzzle); $i++){
    for($j = 0; $j < count($puzzle[0]); $j++) {
        if($puzzle[$i][$j] != '0' && $puzzle[$i][$j] != ',' && $puzzle[$i][$j] != ' ') {
            imagefilledrectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $white);
            imagerectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $black);
        }
    }
}

$middleX = 900;
$middleY = 200 + ($img_height/2);

$rad = (2 * pi())/$numShapes;

$currentRad = 0;

$currentX = $middleX;
$currentY = $middleY;

$lastX = 0;
$lastY = 0;

$radius = 650;

imagettftext($image, 100, 0, 265, 580, $black, realpath('./arlrdbd.ttf'), 'Puzzle');


for($i = 1; $i <= $numShapes + 1; $i++){

    //move the angle 1/offset around the circle
    $currentRad = 0 + ($i * $rad);

    //determine the x and y coordinates of the end of this line
    $currentX = $middleX + ($radius * cos($currentRad));
    $currentY = $middleY + ($radius * sin($currentRad));

    //if this isn't the first time this loop has been run through, draw a line between the current x and y coords and the last ones determined
    if($i != 1) {
        imageline($image, $currentX, $currentY, $lastX, $lastY, $black);
    }

    //mark the current coordinates for the next line to be  drawn
    $lastX = $currentX;
    $lastY = $currentY;
}

for($i = 1; $i <= $numShapes; $i++){

    $currentRad = 0 + ($i * $rad);

    $currentX = $middleX + ($radius * cos($currentRad));
    $currentY = $middleY + ($radius * sin($currentRad));

    $string = '';
    if(strcmp($puzzleType, 'rectangles') == 0){
        //draw a larger rectangle in back of a smaller rectangle to act as a border, then add the words to it in front
        imagefilledrectangle($image, $currentX - $radius/4, $currentY - $radius/4, $currentX + $radius/4, $currentY + $radius/4, $black);
        imagefilledrectangle($image, $currentX - $radius/4 + 15, $currentY - $radius/4 + 15, $currentX + $radius/4 - 15, $currentY + $radius/4 - 15, $white);
    } else {
        //draw a larger circle in back of a smaller circle to act as a border, then add the words to it in front
        imagefilledellipse($image, $currentX, $currentY, ($radius/2) + 50, ($radius/2) + 50, $black);
        imagefilledellipse($image, $currentX, $currentY, $radius/2 + 35 , $radius/2 + 35, $white);
    }
    for($j = 0; $j < count($puzzle); $j++) {
        $string = $string.$puzzle[$j][$i - 1];
        if($j != count($puzzle) - 1) {
            $string = $string.' ';
        }
    }
    // imagestring($image, 5, $currentX - ($radius/2)/4, $currentY, "circle number ".$i, $black);
    if(true){
        $text_bound = imagettfbbox(60, 0, realpath('./NTR-Regular.ttf'), $string);

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
            
        $x_offset = $currentX - $text_width/2;
        $y_offset = $currentY + $text_height/2;

        imagettftext($image, 60, 0, $x_offset, $y_offset, $black, realpath('./NTR-Regular.ttf'), $string);
    }
    $lastX = $currentX;
    $lastY = $currentY;

    unset($string);
    
}

// for($i = 0; $i < count($puzzle); $i++){
    // for($j = 0; $j < count($puzzle[$i]); $j++) {
    //     if($puzzle[$i][$j] != '0' && $puzzle[$i][$j] != ',' && $puzzle[$i][$j] != ' ') {

    //         // $currentX1 = $startingX - 25 + (140 * $j);
    //         // $currentX2 = $startingX + 115 + (140 * $j);
    //         // $currentY1 = $startingY - 105 + (140 * $i);
    //         // $currentY2 = $startingY + 35 + (140 * $i);

    //         imagefilledrectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $white);
    //         imagerectangle($image, $currentX1, $currentY1, $currentX2, $currentY2, $black);
            
    //         $text_bound = imagettfbbox(60, 0, realpath('./NTR-Regular.ttf'), $puzzle[$i][$j]);

    //         $lower_left_x =  $text_bound[0]; 
    //         $lower_left_y =  $text_bound[1];
    //         $lower_right_x = $text_bound[2];
    //         $lower_right_y = $text_bound[3];
    //         $upper_right_x = $text_bound[4];
    //         $upper_right_y = $text_bound[5];
    //         $upper_left_x =  $text_bound[6];
    //         $upper_left_y =  $text_bound[7];
                
    //         // Get Text Width and text height
    //         $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
    //         $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y
                
    //         $x_offset = $currentX1 + ($currentX2 - $currentX1)/2 - $text_width/2;
    //         $y_offset = $currentY1 + ($currentY2 - $currentY1)/2 +10;

    //         imagettftext($image, 60, 0, $x_offset, $y_offset, $black, realpath('./NTR-Regular.ttf'), $puzzle[$i][$j]);
    //     }
    // }
// }

imagepng($image);
imagedestroy($image);

?>