            // var tableString = '<table class="puzzle">';
            // tableString = tableString + '<tr><td>&nbsp</td></tr>';
            // tableString = tableString + '<tr><td>&nbsp</td></tr>';
            // tableString = tableString + '<tr><td>&nbsp</td></tr>';
            // tableString = tableString + '<tr>';
            // tableString = tableString + '<td class="empty">T</td>';
            // tableString = tableString + '<td class="empty">E</td>';
            // tableString = tableString + '<td class="empty">S</td>';
            // tableString = tableString + '<td class="empty">T</td>';
            // tableString = tableString + '</tr>';
            // tableString = tableString + '</table>';

            console.log("shapesPuzzle");
            console.log(shapesPuzzle);
            console.log("wordList");
            console.log(wordList);
            console.log("wordPuzzle");
            console.log(wordPuzzle);
            console.log("letterPuzzle");
            console.log(letterPuzzle);

            var stringArray = new Array(5);
            generateTableStrings();
            console.log(stringArray);
            for(var i = 0; i < xyCoords.length; i++) {
                drawShape(xyCoords[i][0], xyCoords[i][1], stringArray[i]);
            }

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
            
            function drawShape(currentX, currentY, tableString) {
                console.log("in draw shape function");
                console.log(currentX);
                console.log(currentY);
                    var child = document.createElement('div');
                //if(shape == 'circle') {
                    child.className = 'circle';
                //} else if (shape == 'rectangle') {
                // child.className = 'rectangle';
                //}
                    var canvas = document.getElementsByClassName('shapesArea');
                    child.style.position = 'absolute';
                    child.style.left = (currentX/7.5) +'%';
                    child.style.top = (currentY/7.5) + '%';
                    child.innerHTML = tableString;
                    canvas[0].appendChild(child);
                }

                // for(var i = 0; i < xyCoords.length; i++) {
                //     drawShape(xyCoords[i][0], xyCoords[i][1], stringArray[i]);
                // }

                function generateTableStrings(){
                    for(i = 0; i < wordList.length; i++){
                        var tableString = '<table class="puzzle">';
                        tableString = tableString + '<tr><td>&nbsp</td></tr>';
                        tableString = tableString + '<tr><td>&nbsp</td></tr>';
                        tableString = tableString + '<tr><td>&nbsp</td></tr>';
                        tableString = tableString + '<tr>';
                        for(j = 0; j < wordList[0].length; j++){
                            tableString = tableString + '<td class="empty">' + shapesPuzzle[i][j] + '</td>';
                        }
                        tableString = tableString + '</tr>';
                        tableString = tableString + '</table>';
                        console.log(tableString)
                        stringArray[i] = tableString;
                    }
                    tableString = tableString + '</table>';
                    console.log(tableString)
                    stringArray[i] = tableString;
                }