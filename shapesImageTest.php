<?php

header("Content-type: image/png");

$img_width = 3508;
$img_height = 2480;

$offset = 5;

$image = imagecreatetruecolor($img_width, $img_height);

imagesetthickness($image, 5);

$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$red   = imagecolorallocate($image, 255, 0, 0);
$green = imagecolorallocate($image, 0, 255, 0);
$blue  = imagecolorallocate($image, 0, 0, 255);
$orange = imagecolorallocate($image, 255, 200, 0);

imagefill($image, 0, 0, $white);

//determines the number of shapes
$offset = 7;

//determines the location of the shapes around the circle based on the angle in radians
$rad = (2 * pi())/$offset;

//sets the radius of the entire circle, ending at the center of the word circles
$radius = 600;

//start at 0 radians
$currentRad = 0;

//determine the origin of the image
$middleX = $img_width/2;
$middleY = $img_height/2;

//set the initial x and y values to the origin
$currentX = $img_width/2;
$currentY = $img_height/2;

//placeholders used for drawing the lines between circles
$lastX = 0;
$lastY = 0;

//first, a for loop to draw the lines so they are in back of the circles
for($i = 1; $i <= $offset + 1; $i++){

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

// Set the environment variable for GD
putenv('GDFONTPATH=' . realpath('.'));

// Name the font to be used (note the lack of the .ttf extension)
$font = 'arial.ttf';
$fontsize = 48;
$fontangle = 0;

//a for loop to draw the shapes the words will be in, with similar logic to the lines
for($i = 1; $i <= $offset; $i++){

    $currentRad = 0 + ($i * $rad);

    $currentX = $middleX + ($radius * cos($currentRad));
    $currentY = $middleY + ($radius * sin($currentRad));

    //draw a larger circle in back of a smaller circle to act as a border, then add the words to it in front
    imagefilledellipse($image, $currentX, $currentY, ($radius/2) + 5, ($radius/2) + 5, $black);
    imagefilledellipse($image, $currentX, $currentY, $radius/2, $radius/2, $red);
    imagestring($image, 5, $currentX - ($radius/2)/4, $currentY, "circle number ".$i, $black);
    

    $lastX = $currentX;
    $lastY = $currentY;
}

imagepng($image);

?>