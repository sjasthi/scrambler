for(i = 0; i < answerArray.length; i++) {
    for(j = 0; j < answerArray[i].length; j++) {
        if(answerArray[i][j] == ' ' || answerArray[i][j] == ',' || answerArray[i][j] == '0') {
            // console.log("removing " + answerArray[i][j] + " at index " + i +', ' + j + " from answer array");
            answerArray[i].splice(j, 1);
            j--;
        }
    }
}

for(i = 0; i < lettersArray.length; i++) {
    for(j = 0; j < lettersArray[i].length; j++) {
        if(lettersArray[i][j] == ' ' || lettersArray[i][j] == ',') {
            lettersArray[i].splice(j, 1);
        }
    }
}

for(i = 0; i < letterList.length; i++) {
    for(j = 0; j < letterList[i].length; j++) {
        if(letterList[i][j] == ' ' || letterList[i][j] == ',' || letterList[i][j] == '0') {
            letterList[i].splice(j, 1);
        }
    }
}

var word = '';
for(i = 0; i < wordList.length; i++) {
    word = '';
    for(j = 0; j < wordList[i].length; j++) {
        if(wordList[i][j] == ' ' || wordList[i][j] == ',' || wordList[i][j] == '0') {} 
        else {
            word += wordList[i][j];
        }
    }
    wordList[i] = word;
}

function printArrays() {
    console.log("answer array");
    console.log(answerArray);
    console.log("letters array");
    console.log(lettersArray);
    console.log("letter list");
    console.log(letterList);
    console.log("word list");
    console.log(wordList)
    console.log("attempt");
    console.log(attempt);
}

if(puzzleType != "lines") {
    var attempt = new Array(wordList.length);
    for(var i = 0; i < wordList.length; i++) {
        attempt[i] = new Array(wordList[i].length);
        for(var j = 0; j < wordList[i].length; j++) {
            attempt[i][j] = '';
        }
        if(attempt[i].length > answerArray[i].length) {
            attempt[i].splice(answerArray[i].length, attempt[i].length - answerArray[i].length);
        }
    }
} else {
    var attempt = new Array(wordList.length + (wordList.length - 1));
    var k = 0
    for(var i = 0; i < attempt.length; i++) {
        if(i % 2 == 0){
            attempt[i] = new Array(wordList[k].length);
            for(var j = 0; j < wordList[k].length; j++) {
                attempt[i][j] = '';
            }
            k++;
        } else {
            attempt[i] = new Array(wordList[k].length);
            for(var j = 0; j < attempt[i].length; j++) {
                attempt[i][j] = '0';
            }
        }
    }
}

// printArrays();

const puzzles = document.getElementsByClassName('puzzleLetter');

// if(puzzleType == 'shapes') {
//     console.log('in shapes loop');
//     for(i = 0; i < puzzles.length; i++) {
//         console.log(puzzles[i].id);
//     }
// }

const guesses = document.getElementsByClassName('guess');

for(var i = 0; i < guesses.length; i++) {
    guesses[i].addEventListener('dragover', dragOver);
    guesses[i].addEventListener('drop', dragDrop);
    guesses[i].addEventListener('dragstart', dragStart);
    guesses[i].addEventListener('dblclick', doubleClick);
}

for(var i = 0; i < puzzles.length; i++) {
    puzzles[i].addEventListener('dragover', dragOver);
    puzzles[i].addEventListener('drop', dragDrop);
    puzzles[i].addEventListener('dragstart', dragStart);
    puzzles[i].addEventListener('dblclick', doubleClick);
}

var tdDragStart;
var tdDragDrop;

function dragStart(ev) {
    ev.dataTransfer.setData("text", ev.target.innerHTML);
    tdDragStart = ev.target;
}

function dragEnter(ev) {
    ev.preventDefault();
}

function dragLeave(ev) {
    ev.preventDefault();
}

function dragDrop(ev) {
    ev.preventDefault();
    var target = ev.target;
    var setHTML = ev.dataTransfer.getData("text");
    var placeholder = target.innerHTML;
    target.innerHTML = setHTML;
    tdDragStart.innerHTML = placeholder;
    printArrays();
    dragUpdateArrays(setHTML, placeholder, ev.target);
}

function dragOver(ev) {
    ev.preventDefault();
}

function doubleClick(ev) {
    printArrays();
    ev.preventDefault();
    var placeholder = ev.target.innerHTML;
    var swapped = false;
    if(puzzleType != 'pyramid' && puzzleType != 'shapes') {
        if(ev.target.getAttribute('class').includes('puzzleLetter')) {
            var table = document.getElementById('wordsPuzzle');
            for(var i = 0; i < table.rows.length; i++) {
                for(var j = 0; j < table.rows[i].cells.length; j++) {
                    if (table.rows[i].cells[j].innerHTML != '&nbsp;&nbsp;&nbsp;&nbsp;') {}
                    else {
                        ev.target.innerHTML = table.rows[i].cells[j].innerHTML;
                        table.rows[i].cells[j].innerHTML = placeholder;
                        swapped = true;
                        dblClickUpdateArrays(ev.target.innerHTML, placeholder, i, j, ev.target);
                        break;
                    }
                }
                if(swapped) {break;}
            }
        } else if (ev.target.getAttribute('class').includes('guess')) {
            var table = document.getElementById('lettersPuzzle');
            for(var i = 0; i < table.rows.length; i++) {
                for(var j = 0; j < table.rows[i].cells.length; j++) {
                    if (table.rows[i].cells[j].innerHTML != '&nbsp;&nbsp;&nbsp;&nbsp;') {}
                    else {
                        ev.target.innerHTML = table.rows[i].cells[j].innerHTML;
                        table.rows[i].cells[j].innerHTML = placeholder;
                        swapped = true;
                        dblClickUpdateArrays(ev.target.innerHTML, placeholder, i, j, ev.target);
                        break;
                    }
                }
                if(swapped) {break;}
            }
        }
    } else if (puzzleType == 'pyramid') {
        if(ev.target.getAttribute('class').includes('puzzleLetter')) {
            for(var i = 0; i < lettersArray.length; i++) {
                for(var j = 0; j < lettersArray[i].length; j++) {
                    // console.log(" checking row " + i + " column " + j);
                    if(pyramidGetCell(i, j, 'guess') == undefined) {}
                    else {
                        ev.target.innerHTML = pyramidGetCell(i, j, 'guess').innerHTML;
                        // console.log("start changed to " + pyramidGetCell(i, j, 'guess').innerHTML);
                        pyramidGetCell(i, j, 'guess').innerHTML = placeholder;
                        swapped = true;
                        dblClickUpdateArrays(ev.target.innerHTML, placeholder, i, j, ev.target);
                        break;
                    }
                }
                if(swapped) {break;}
            }
        } else if (ev.target.getAttribute('class').includes('guess')) {
            for(var i = 0; i < lettersArray.length; i++) {
                for(var j = 0; j < lettersArray[i].length; j++) {
                    if(pyramidGetCell(i, j, 'puzzleLetter') == undefined) {}
                    else {
                        ev.target.innerHTML = pyramidGetCell(i, j, 'puzzleLetter').innerHTML;
                        // console.log("start changed to " + pyramidGetCell(i, j, 'puzzleLetter').innerHTML);
                        pyramidGetCell(i, j, 'puzzleLetter').innerHTML = placeholder;
                        swapped = true;
                        dblClickUpdateArrays(ev.target.innerHTML, placeholder, i, j, ev.target);
                        break;
                    }
                }
                if(swapped) {break;}
            }
        }
    } else if (puzzleType == 'shapes') {
        if(ev.target.getAttribute('class').includes('puzzleLetter')) {
            var table = document.getElementById('wordsPuzzle');
            for(var i = 0; i < lettersArray.length; i++) {
                for(var j = 0; j < lettersArray[i].length; j++) {
                    // console.log(" checking row " + i + " column " + j);
                    if(table.rows[i].cells[j].innerHTML != '&nbsp;&nbsp;&nbsp;&nbsp;') {}
                    else {
                        // console.log('changing guess array row ' + i + ' column ' + j);
                        ev.target.innerHTML = table.rows[i].cells[j].innerHTML;
                        // console.log("start changed to " + table.rows[i].cells[j].innerHTML);
                        table.rows[i].cells[j].innerHTML = placeholder;
                        swapped = true;
                        dblClickUpdateArrays(ev.target.innerHTML, placeholder, i, j, ev.target);
                        break;
                    }
                }
                if(swapped) {break;}
            }
        } else if(ev.target.getAttribute('class').includes('guess')) {
            for(var i = 0; i < lettersArray.length; i++) {
                for(var j = 0; j < lettersArray[i].length; j++) {
                    if(pyramidGetCell(i, j, 'puzzleLetter') == undefined) {}
                    else {
                        ev.target.innerHTML = pyramidGetCell(i, j, 'puzzleLetter').innerHTML;
                        // console.log("start changed to " + pyramidGetCell(i, j, 'puzzleLetter').innerHTML);
                        pyramidGetCell(i, j, 'puzzleLetter').innerHTML = placeholder;
                        swapped = true;
                        dblClickUpdateArrays(ev.target.innerHTML, placeholder, i, j, ev.target);
                        break;
                    }
                }
                if(swapped) {break;}
            }
        }
    }
}

function pyramidGetCell(row, cell, type) {
    var cells = document.getElementsByClassName(type);
    for(var i = 0; i < cells.length; i++) {
        if(cells[i].id.includes('row' + row + 'column' + cell)) {
            if(cells[i].innerHTML == '&nbsp;&nbsp;&nbsp;&nbsp;') {
                // console.log(cells[i].id);
                // console.log(cells[i].innerHTML);
                return cells[i];
            }
        }
    }
}

function dblClickUpdateArrays(setHTML, placeholder, row, cell, target) {
    if(target.getAttribute('class').includes('puzzleLetter')) {
        if(puzzleType == 'stepdown' || puzzleType == 'lines') {
            updateAttemptIndex(row, cell, placeholder);
            var indices = genericDetermineIndex(target);
            updateStepDownPuzzleArrayIndex(indices[0], indices[1], setHTML);
        } else if (puzzleType == 'pyramid') {
            var update = pyramidDetermineIndex(target);
            updateAttemptIndex(row, cell, placeholder);
            updatePyramidPuzzleArrayIndex(update[0], update[1], setHTML);
        } else if (puzzleType == 'shapes') {
            var update = pyramidDetermineIndex(target);
            updateAttemptIndex(row, cell, placeholder);
            updatePyramidPuzzleArrayIndex(update[0], update[1], setHTML);
            
        }
    } else if (target.getAttribute('class').includes('guess')) {
        if(puzzleType == 'stepdown' || puzzleType == 'lines') {
            var indices = genericDetermineIndex(target);
            updateAttemptIndex(indices[0], indices[1], setHTML);
            updateStepDownPuzzleArrayIndex(row, cell, placeholder);
        } else if (puzzleType == 'pyramid') {
            var update = pyramidDetermineIndex(target);
            updateAttemptIndex(update[0], update[1], setHTML);
            updatePyramidPuzzleArrayIndex(row, cell, placeholder);
        } else if (puzzleType == 'shapes') {
            var update = genericDetermineIndex(target);
            updateAttemptIndex(update[0], update[1], setHTML);
            updatePyramidPuzzleArrayIndex(row, cell, placeholder);
        }
    }
}

function dragUpdateArrays(setHTML, placeholder, target) {
    var update = new Array(2);
    
    if(target.getAttribute('class').includes('puzzleLetter')) {
        if(tdDragStart.getAttribute('class').includes('guess')) {
            if(puzzleType == "pyramid") {
                update = pyramidDetermineIndex(tdDragStart);
                updateAttemptIndex(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if (puzzleType == "stepdown" || puzzleType == "lines" || puzzleType == 'shapes') {
                    updateAttemptIndex(update[0], update[1], placeholder);
                }
            }
        } else if (tdDragStart.getAttribute('class').includes('puzzleLetter')) {
            if(puzzleType == "pyramid" || puzzleType == 'shapes') {
                update = pyramidDetermineIndex(tdDragStart);
                updatePyramidPuzzleArrayIndex(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if (puzzleType == "stepdown" || puzzleType == "lines") {
                    updateStepDownPuzzleArrayIndex(update[0], update[1], placeholder);
                }
            }
        }

        if(puzzleType == "pyramid" || puzzleType == 'shapes') {
            update = pyramidDetermineIndex(target);
            updatePyramidPuzzleArrayIndex(update[0], update[1], setHTML);
        } else {
            update = genericDetermineIndex(target);
            if (puzzleType == "stepdown" || puzzleType == "lines") {
                updateStepDownPuzzleArrayIndex(update[0], update[1], setHTML);
            }
        }

    } else if (target.getAttribute('class').includes('guess')) {
        if(tdDragStart.getAttribute('class').includes('guess')) {
            if(puzzleType == "pyramid") {
                update = pyramidDetermineIndex(tdDragStart);
                updateAttemptIndex(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if (puzzleType == "stepdown" || puzzleType == "lines" || puzzleType == 'shapes') {
                    updateAttemptIndex(update[0], update[1], placeholder);
                }
            }
        } else if (tdDragStart.getAttribute('class').includes('puzzleLetter')) {
            if(puzzleType == "pyramid" || puzzleType == 'shapes') {
                update = pyramidDetermineIndex(tdDragStart);
                updatePyramidPuzzleArrayIndex(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if (puzzleType == "stepdown" || puzzleType == "lines") {
                    updateStepDownPuzzleArrayIndex(update[0], update[1], placeholder);
                }
            }
        }

        if(puzzleType == "pyramid") {
            update = pyramidDetermineIndex(target);
            updateAttemptIndex(update[0], update[1], setHTML);
        } else {
            update = genericDetermineIndex(target);
            if (puzzleType == "stepdown" || puzzleType == "lines" || puzzleType == 'shapes') {
                updateAttemptIndex(update[0], update[1], setHTML);
            }
        }
    }
}

function pyramidDetermineIndex(targetCell) {
    for(var i=0; i < lettersArray.length; i++) {
        for(var j=0; j < lettersArray[i].length; j++) {
            if (targetCell.id == ("row" + i + "column" + j)) {
                // console.log("returning " + i + ", " + j);
                return [i, j];
            }
        }
    }
}

function genericDetermineIndex(targetCell) {
    var cellColumn = targetCell.cellIndex;
    var cellRow = targetCell.parentNode.rowIndex;
    // console.log("cell row " + cellRow + " cell column " + cellColumn);
    return [cellRow, cellColumn];
}

function updatePyramidPuzzleArrayIndex(row, column, letter) {
    lettersArray[row][column] = letter;
}

function updateStepUpPuzzleArrayIndex(row, column, letter) {
    lettersArray[row][column] = letter;
}

function updateStepDownPuzzleArrayIndex(row, column, letter) {
    if (puzzleType != 'lines') {
        lettersArray[row][column] = letter;
    } else {
        if(row % 2 == 0) {
            lettersArray[row/2][column] = letter;
        } else {
            lettersArray[(row/2) + 1][column] = letter;
        }
    }
}

function updateLettersArrayIndex(row, column, letter) {
    lettersArray[row][column] = letter;
}

function updateAttemptIndex(row, column, letter) {
    attempt[row][column] = letter;
    if(checkAnswer()) {alert("Congratulations!!!");}
}

function checkAnswer() {
    var attemptWords = new Array(attempt.length);
    var word = '';
    var s

    for(var i = 0; i < attempt.length; i++) {
        for(var j = 0; j < attempt[i].length; j++) {
            if(j == 0 && attempt[i][j] != '0') {
                word = attempt[i][j]; 
            } else if (attempt[i][j] != '0') {
                word = word + attempt[i][j];
            }
        }
        attemptWords[i] = word;
    }

    for(var i = 0; i < attemptWords.length; i++) {
        // console.log(attemptWords[i]);
        if(!wordList.includes(attemptWords[i])){
            return false;
        }
    }

    return true;
}