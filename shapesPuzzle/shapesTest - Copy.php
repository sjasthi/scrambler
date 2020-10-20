

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/shapesStyle.css">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <!-- Spectrum -->
        <link rel="stylesheet" type="text/css" href="../css/spectrum.css">
        <script type="text/javascript" src="../js/spectrum.js"></script>

        <!-- CSS 
        <link rel="stylesheet" type="text/css" href="../css/puzzleStyle.css">-->

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale = 1">

        <script type="text/javascript" src="../js/shapes.js"></script>
        <script>
            var tableString = '<table class="puzzle">';
            tableString = tableString + '<tr><td>&nbsp</td></tr>';
            tableString = tableString + '<tr><td>&nbsp</td></tr>';
            tableString = tableString + '<tr><td>&nbsp</td></tr>';
            tableString = tableString + '<tr><td>&nbsp</td></tr>';
            tableString = tableString + '<tr>';
            tableString = tableString + '<td class="empty">T</td>';
            tableString = tableString + '<td class="empty">E</td>';
            tableString = tableString + '<td class="empty">S</td>';
            tableString = tableString + '<td class="empty">T</td>';
            tableString = tableString + '</tr>';
            tableString = tableString + '</table>';

            function drawLine(currentX, currentY, lastX, lastY) {
                var canvas = document.getElementsByClassName('shapesArea');
                if (shapesArea.getContext) {
                    var ctx = canvas.getContext('2d');
                        ctx.beginPath();
                        ctx.moveTo(lastX, lastY);
                        ctx.lineTo(currentX, currentY);
                        ctx.stroke();
                }
            }
            
            function drawShape(currentX, currentY) {
                console.log("in draw shape function");
                console.log(currentX);
                console.log(currentY);
                    var child = document.createElement('div');
                //if(shape == 'circle') {
                    child.className = 'circle';
                //} else if (shape == 'rectangle') {
                //    child.className = 'rectangle';
                //}
                    var canvas = document.getElementsByClassName('shapesArea');
                    canvas[0].appendChild(child);
                    child.style.position = 'absolute';
                    child.style.left = currentX +'px';
                    child.style.top = currentY + 'px';
                    child.innerHTML = tableString;
                }
            
            
        </script>
        
    </head>
    <body>
        <!-- <?php 
            if(isset($_POST['numWords'])) {
                //print_r('got value from user');
                $numWords = $_POST['numWords'];
            } else {
                
                //print_r('default');
                $numWords = 5;
            }
            $shape = 'rectangle';
        ?>
        <form action="shapestest.php" method="POST">
                <input type="number" name="numWords" min="2" max="5" value="5">
        </form> -->
        <div class="shapesArea">
            <!-- <div class="rectangle">testing</div> -->
            <?php

            $numWords = 5;
            //determines the location of the shapes around the circle based on the angle in radians
            $rad = ((2 * pi())/$numWords);

            //sets the radius of the entire circle, ending at the center of the word circles
            $radius = 200;

            //start at 0 radians
            $currentRad = 0;

            $polyline = [];

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
            
            $firstX = $middleX + ($radius * cos(pi()/2 + ($rad))) + $hypotenuse;
            $firstY = $middleY + ($radius * sin(pi()/2 + ($rad))) + $hypotenuse;

            for($i = 1; $i <= $numWords; $i++){

                $currentRad = pi()/2 + ($i * $rad);

                $currentX = $middleX + ($radius * cos($currentRad));
                $currentY = $middleY + ($radius * sin($currentRad));

                echo '<script type="text/javascript">', 
                'drawShape('.$currentX.', '.$currentY.');',
                '</script>';
                // $currentX += $hypotenuse;
                // $currentY += $hypotenuse;

                $polyline[$i - 1] = [$currentX, $currentY];
                // if($i != 1) {
                //     echo '<svg height="750" width="750">',
                //     '<polyline points ="'.$currentX.','.$currentY.' '.$lastX.','.$lastY.'" style="fill:none;stroke:black;stroke-width:3" />', 
                //     '</svg>';
                // }

                // $lastX = $currentX;
                // $lastY = $currentY;

                
            }

            echo '<svg height="750" width="750" style="position:absolute">';
            echo '<polyline points ="';
            for($i = 0; $i < count($polyline); $i++) {
                $x = $polyline[$i][0] + $hypotenuse;
                $y = $polyline[$i][1] + $hypotenuse;
                echo ''.$x.','.$y.' ';
            }
            echo ''.$firstX.','.$firstY.'" " style="fill:none;stroke:black;stroke-width:3" /></svg>';
            ?>
            
            
        </div>
        
    </body>
    
</html>