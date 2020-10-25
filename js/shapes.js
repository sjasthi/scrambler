            var stringArray = new Array(wordList.length);
            generateTableStrings();
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
                var circleChild = document.createElement('div');
                
                var rectangleChild = document.createElement('div');
                circleChild.className = 'circleShape';
                rectangleChild.className = 'rectangleShape';
                var circleCanvas = document.getElementsByClassName('circlesPuzzle');
                
                var rectangleCanvas = document.getElementsByClassName('rectanglesPuzzle');

                circleChild.style.position = 'absolute';
                circleChild.style.left = (currentX/7.5) +'%';
                circleChild.style.top = (currentY/7.5) + '%';
                circleChild.innerHTML = tableString;
                circleCanvas[0].appendChild(circleChild);

                rectangleChild.style.position = 'absolute';
                rectangleChild.style.left = (currentX/7.5) +'%';
                rectangleChild.style.top = (currentY/7.5) + '%';
                rectangleChild.innerHTML = tableString;
                rectangleCanvas[0].appendChild(rectangleChild);
            }

                function generateTableStrings(){
                    for(i = 0; i < wordList[0].length; i++){
                        var tableString = '<table class="puzzle">';
                        tableString = tableString + '<tr>';
                        for(j = 0; j < wordList.length; j++){
                            tableString = tableString + '<td class="empty">' + shapesPuzzle[j][i] + '</td>';
                        }
                        tableString = tableString + '</tr>';
                        tableString = tableString + '</table>';
                        stringArray[i] = tableString;
                    }
                    tableString = tableString + '</table>';
                    stringArray[i] = tableString;
                }