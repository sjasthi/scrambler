for(i = 0; i < answerArray.length; i++) {
    for(j = 0; j < answerArray[i].length; j++) {
        if(answerArray[i][j] == ' ' || answerArray[i][j] == ',' || answerArray[i][j] == '0') {
            console.log("removing " + answerArray[i][j] + " at index " + i +', ' + j + " from answer array");
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

var lettersPuzzleType = puzzleType;

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



// console.log("puzzles");

const puzzles = document.getElementsByClassName('puzzleLetter');

// console.log(puzzles.length);

for(var i =0; i < puzzles.length; i++) {
    console.log(puzzles[i].innerHTML);
}

// console.log("guesses");


const guesses = document.getElementsByClassName('guess');

// console.log(guesses.length);

for(var i =0; i < guesses.length; i++) {
    console.log(guesses[i].innerHTML);
}

for(var i = 0; i < guesses.length; i++) {
    guesses[i].addEventListener('dragover', dragOver);
    guesses[i].addEventListener('drop', dragDrop);
    guesses[i].addEventListener('dragstart', dragStart);
}

for(var i = 0; i < puzzles.length; i++) {
    puzzles[i].addEventListener('dragover', dragOver);
    puzzles[i].addEventListener('drop', dragDrop);
    puzzles[i].addEventListener('dragstart', dragStart);
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
    updateArrays(setHTML, placeholder, ev.target);
}

function dragOver(ev) {
    ev.preventDefault();
}

function updateArrays(setHTML, placeholder, target) {
    var update = new Array(2);
    
    if(target.getAttribute('class').includes('puzzleLetter')) {
        if(tdDragStart.getAttribute('class').includes('guess')) {
            if(puzzleType == "pyramid") {
                update = pyramidDetermineIndex(tdDragStart);
                updateAttemptIndex(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if (puzzleType == "stepdown" || lettersPuzzleType == "lines") {
                    updateAttemptIndex(update[0], update[1], placeholder);
                } else if (puzzleType == "stepup") {
                    updateAttemptIndex(update[0], update[1] - (lettersArray[update[0]].length - wordList[update[0]].length), placeholder);
                }
            }
        } else if (tdDragStart.getAttribute('class').includes('puzzleLetter')) {
            if(lettersPuzzleType == "pyramid") {
                update = pyramidDetermineIndex(tdDragStart);
                updatePyramidPuzzleArrayIndex(update[0], update[1], placeholder);
                //updateLetterListIndexFromLetters(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if(lettersPuzzleType == "stepup") {
                    updateStepUpPuzzleArrayIndex(update[0], update[1], placeholder);
                    //updateLetterListIndexFromLetters(update[0], update[1] - (lettersArray[update[0]].length - wordList[update[0]].length), placeholder)
                } else if (lettersPuzzleType == "stepdown" || lettersPuzzleType == "lines") {
                    updateStepDownPuzzleArrayIndex(update[0], update[1], placeholder);
                    //updateLetterListIndexFromLetters(update[0], update[1], placeholder);
                }
            }
        }

        if(puzzleType == "pyramid") {
            update = pyramidDetermineIndex(target);
            updatePyramidPuzzleArrayIndex(update[0], update[1], setHTML);
            //updateLetterListIndexFromLetters(update[0], update[1], setHTML);
        } else {
            update = genericDetermineIndex(target);
            if(puzzleType == "stepup") {
                updateStepUpPuzzleArrayIndex(update[0], update[1], setHTML);
                //updateLetterListIndexFromLetters(update[0], update[1] - (lettersArray[update[0]].length - wordList[update[0]].length), setHTML)
            } else if (puzzleType == "stepdown" || lettersPuzzleType == "lines") {
                updateStepDownPuzzleArrayIndex(update[0], update[1], setHTML);
                //updateLetterListIndexFromLetters(update[0], update[1], setHTML);
            }
        }

    } else if (target.getAttribute('class').includes('guess')) {
        if(tdDragStart.getAttribute('class').includes('guess')) {
            if(puzzleType == "pyramid") {
                update = pyramidDetermineIndex(tdDragStart);
                updateAttemptIndex(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if (puzzleType == "stepdown" || lettersPuzzleType == "lines") {
                    updateAttemptIndex(update[0], update[1], placeholder);
                } else if (puzzleType == "stepup") {
                    updateAttemptIndex(update[0], update[1] - (lettersArray[update[0]].length - wordList[update[0]].length), placeholder);
                }
            }
        } else if (tdDragStart.getAttribute('class').includes('puzzleLetter')) {
            if(puzzleType == "pyramid") {
                update = pyramidDetermineIndex(tdDragStart);
                updatePyramidPuzzleArrayIndex(update[0], update[1], placeholder);
                //updateLetterListIndexFromWords(update[0], update[1], placeholder);
            } else {
                update = genericDetermineIndex(tdDragStart);
                if(puzzleType == "stepup") {
                    updateStepUpPuzzleArrayIndex(update[0], update[1], placeholder);
                    //updateLetterListIndexFromWords(update[0], update[1] - (lettersArray[update[0]].length - wordList[update[0]].length), placeholder)
                } else if (puzzleType == "stepdown" || lettersPuzzleType == "lines") {
                    updateStepDownPuzzleArrayIndex(update[0], update[1], placeholder);
                    //updateLetterListIndexFromWords(update[0], update[1], placeholder);
                }
            }
        }

        if(puzzleType == "pyramid") {
            update = pyramidDetermineIndex(target);
            updateAttemptIndex(update[0], update[1], setHTML);
        } else {
            update = genericDetermineIndex(target);
            if (puzzleType == "stepdown" || lettersPuzzleType == "lines") {
                updateAttemptIndex(update[0], update[1], setHTML);
            } else if (puzzleType == "stepup") {
                updateAttemptIndex(update[0], update[1] - (lettersArray[update[0]].length - wordList[update[0]].length), setHTML);
            }
        }
    }
}

function pyramidDetermineIndex(targetCell) {
    for(var i=0; i < lettersArray.length; i++) {
        for(var j=0; j < lettersArray[i].length; j++) {
            if (targetCell.getAttribute('id') == ("row" + i + "column" + j)) {
                return [i, j];
            }
        }
    }
}

function genericDetermineIndex(targetCell) {
    var cellColumn = targetCell.cellIndex;
    var cellRow = targetCell.parentNode.rowIndex;
    console.log("cell row " + cellRow + " cell column " + cellColumn);
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

// function updateLetterListIndexFromLetters(row, column, letter) {
//     var letterRow = 0;
// 	var letterColumn = 0;
// 	var count = 0;
//     console.log("passing row " + row + " and column " + column);
//     if (puzzleType != "rectangle") {
//         if(row > 0) {
//             for (var i = 0; i < row; i++) {
//                 count = count + wordList[i].length;
//             }
//         }
// 		count += column;
// 		letterRow = Math.floor(count/letterList[0].length);
//         letterColumn = count % letterList[0].length;   
//     } else {
//         letterRow = row;
//         letterColumn = column;
//     }
//     console.log("row: " + letterRow + " column: " + letterColumn);
//     letterList[letterRow][letterColumn] = letter;
// }

// function updateLetterListIndexFromWords(row, column, letter) {
//     var letterRow;
// 	var letterColumn;
//     var count = 0;
//     console.log("passing row " + row + " and column " + column);
//     if(row > 0) {
//         for (var i = 0; i < row; i++) {
//             count = count + wordList[i].length;
//         }
//     }
// 	count += column;
// 	letterRow = Math.floor(count/letterList[0].length);
//     letterColumn = count % letterList[0].length;
//     console.log("row: " + letterRow + " column: " + letterColumn);
//     letterList[letterRow][letterColumn] = letter;
// }

function updateAttemptIndex(row, column, letter) {
    attempt[row][column] = letter;
    if(checkAnswer()) {alert("Congratulations!!!");}
}

function checkAnswer() {
    if (puzzleType != 'lines') {
        for(var i=0; i < attempt.length; i++) {
            for(var j=0; j < attempt[i].length; j++) {
                if(attempt[i][j] != answerArray[i][j]) {
                    return false;
                }
            }
        }
    } else {
        var k = 0;
        for(var i=0; i < attempt.length; i++) {
            if (i % 2 != 0) {}
            else {
                for(var j=0; j < attempt[i].length; j++) {
                    if(attempt[i][j] != answerArray[k][j]) {
                        return false;
                    }
                }
                k++;
            }
        }
    }
    return true;
}