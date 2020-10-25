<?php


header("Content-type: image/png");
if(session_id() == '' || !isset($_SESSION)){
    session_start();
}

$wordList = $_SESSION['scramblerWordList'];
$puzzle = $_SESSION['scramblerLetterPuzzle'];
$title = $_SESSION['scramblerTitle'];
$subtitle = $_SESSION['scramblerSubtitle'];

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

// imagettftext($image, 150, 0, 1100, 200, $black, realpath('./arlrdbd.ttf'), 'Lines Puzzle');
// imagettftext($image, 100, 0, 1100, 400, $black, realpath('./arlrdbd.ttf'), $title);
// imagettftext($image, 75, 0, 1100, 550, $black, realpath('./arlrdbd.ttf'), $subtitle);
//boundText(200, 100, 'Lines Puzzle', $black, realpath('./arlrdbd.ttf'));
if(true){
    $image_width = 3508;
    $image_height = 600;

    $text_bound = imagettfbbox(150, 0, realpath('./arlrdbd.ttf'), 'Squares Puzzle');

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
    $start_x_offset = ($image_width - $text_width) / 2;
    $start_y_offset = ($image_height - $text_height) / 2;
    
    // Add text to image
    imagettftext($image, 150, 0, $start_x_offset, $start_y_offset, $black, realpath('./arlrdbd.ttf'), 'Squares Puzzle');
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
    $start_x_offset = ($image_width - $text_width) / 2;
    $start_y_offset = ($image_height - $text_height) / 2;
    
    // Add text to image
    imagettftext($image, 100, 0, $start_x_offset, $start_y_offset, $black, realpath('./arlrdbd.ttf'), $title);
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
    $start_x_offset = ($image_width - $text_width) / 2;
    $start_y_offset = ($image_height - $text_height) / 2;
    
    // Add text to image
    imagettftext($image, 75, 0, $start_x_offset, $start_y_offset, $black, realpath('./arlrdbd.ttf'), $subtitle);
}
// boundText(400, 100, $title, $black, realpath('./arlrdbd.ttf'));
// boundText(550, 75, $subtitle, $black, realpath('./arlrdbd.ttf'));

$maxlen = max(array_map('strlen', $wordList));

$startingX = $img_width - 300 - (140 * $maxlen);
$startingY = 700;


imagettftext($image, 100, 0, $img_width - 845, $startingY - 120, $black, realpath('./arlrdbd.ttf'), 'Solution');

$startingY += 40;

for($i = 0; $i < count($puzzle); $i++){
    //$startingY += 40;
    for($j = 0; $j < count($puzzle[$i]); $j++) {
        if(ctype_alpha($puzzle[$i][$j])) {
        imagefilledrectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $white);
        imagerectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $black);
        }
    }
}

$startingX = 300;
$startingY = 700;


imagettftext($image, 100, 0, $startingX - 35, $startingY - 120, $black, realpath('./arlrdbd.ttf'), 'Puzzle');

$startingY += 40;

for($i = 0; $i < count($puzzle); $i++){
    //$startingY += 40;
    for($j = 0; $j < count($puzzle[$i]); $j++) {
        if(ctype_alpha($puzzle[$i][$j])) {
        imagefilledrectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $white);
        imagerectangle($image, $startingX - 25 + (140 * $j), $startingY - 105 + (140 * $i), $startingX + 115 + (140 * $j), $startingY + 35 + (140 * $i), $black);
        imagettftext($image, 75, 0, $startingX + (140 * $j) + 5, $startingY + (140 * $i), $black, realpath('./arlrdbd.ttf'), $puzzle[$i][$j]);
            

            /* THIS WAS ME TRYING TO CENTER THE TEXT INSIDE THE TABLE CELLS.
            DIDN'T WORK LIKE I WANTED IT TO */
            
            // if(true){

            //     $image_width = 2 * ($startingX + (140 * $j));
            //     $image_height = 2 * ($startingY + (140 * $i)) + 140;

            //     $text_bound = imagettfbbox(100, 0, realpath('./arlrdbd.ttf'), $puzzle[$i][$j]);

            //     //Get the text upper, lower, left and right corner bounds
            //     $lower_left_x =  $startingX + 115 + (140 * $j); 
            //     $lower_left_y =  $startingY + 35 + (140 * $i);
            //     $lower_right_x = $startingX + 115 + (140 * $j);
            //     $lower_right_y = $startingY + 35 + (140 * $i);
            //     $upper_right_x = $startingX - 25 + (140 * $j);
            //     $upper_right_y = $startingY - 105 + (140 * $i);
            //     $upper_left_x =  $startingX - 25 + (140 * $j);
            //     $upper_left_y =  $startingY - 105 + (140 * $i);


            //     //Get Text Width and text height
            //     $text_width =  $lower_right_x - $lower_left_x; //or  $upper_right_x - $upper_left_x
            //     $text_height = $lower_right_y - $upper_right_y; //or  $lower_left_y - $upper_left_y

            //     // //Get the starting position for centering
            //     $start_x_offset = ($image_width - $text_width) / 2;
            //     $start_y_offset = ($image_height - $text_height) / 2;
                
            //     // Add text to image
            //     imagettftext($image, 100, 0, $start_x_offset, $start_y_offset, $black, realpath('./arlrdbd.ttf'), $puzzle[$i][$j]);
            // }
        }
    }
}

imagepng($image);



?>

<script type='text/javascript'>
    function consoleDisplay(){
        var textBoundArray = <?php echo json_encode($text_bound) ?>;
        console.log(<?php echo $start_x_offset ?>);
        console.log(<?php echo $start_y_offset ?>);
        for(i = 0; i < 7; i++){
            console.log(textBoundArray[i]);
        }
    }
</script> 