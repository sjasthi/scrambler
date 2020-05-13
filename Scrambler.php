<?php

	/* Created by Stephen Schneider
	 * Class for creating the three various Scrambler puzzles
	 * Takes in at least two words that go in sequential length order to generate step up, step down, and pyramid puzzles
	 * Input is ordered by word length, checked for validation, then characters are obtained from the words and shuffled
	 * letterList servers as the list of letters for the puzzles while the puzzles serve as solutions
	 * Puzzles themselves are just blank solutions displayed on ScramblerPuzzle page
	 * Scrambler will stop if there is an error detected with user input and raise the errorStatus flag
	 */
class Scrambler{
	// Removed the global as max columns need to change with scrambler
	// private $MAX_COLUMNS = 5;
	private $wordList = [];
	private $puzzleList = [];
	private $characterList = [];
	private $letterList = [];


	private $rectanglePuzzle = [];
	private $pyramidPuzzle = [];
	private $stepUpPuzzle = [];
	private $stepDownPuzzle = [];

	private $rectangleLetterPuzzle =[];
	private $stepDownLetterPuzzle = [];
	private $stepUpLetterPuzzle = [];
	private $pyramidLetterPuzzle =[];

	// Added max Columns for scrambler
	private $maxColumns;
	private $maxLength;
	private $wordCount;

	private $wordProcessor;
	private $errorStatus;


	public function __construct($wordList){
		$this->wordProcessor = new wordProcessor(" ", "telugu");
		$this->wordList = $wordList;



		$this->orderWords();

		if($this->validateInput()){
			$this->maxLength = $this->getWordLength($this->wordList[(count($this->wordList) - 1)]);
			$this->wordCount = count($wordList);

			// Only need the count of the first element for scrambler as they have to all be the same length
			$this->maxColumns = getWordLength($this->wordList[0]);

			$this->generateLetterList();

			$this->generatePuzzles();
		}
		else{
			$this->errorStatus = true;
		}
	}

	/*
	 * Orders input words by word length (not needed for scrambler)
	 */
	private function orderWords(){
	 	usort($this->wordList, function($a, $b) {
	 		return $this->getWordLength($a) - $this->getWordLength($b);
	 	});
	 }

	/*
	 * Validates the user input
	 * If words aren't in equal in length then return false
	 * Return true if no issue with input words
	 */
	private function validateInput(){
		$len = $this->getWordLength($this->wordList[0]);

		for($i = 1; $i < count($this->wordList); $i++){
			$nextLen = $this->getWordLength($this->wordList[$i]);

			if(($len) != $nextLen){
				return false;
			}

			// $len++;
		}

		return true;
	}

	/*
	 * Generates the puzzle list of letters in grid format with columns equal to maxColumns variable
	 * Takes all words from input, split into characters, shuffle the list, then save in letterList in grid format
	 */
	private function generateLetterList(){


		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);

			foreach($chars as $char){
				array_push($this->characterList, $char);
			}
		}

		shuffle($this->characterList);

		$charCount = count($this->characterList);

		$cols = $this->maxColumns;
		$rows = $charCount / $cols;
		$rows = ceil($rows);

		$this->letterList = array_fill(0, $rows, array_fill(0, $cols, 0));

		$k = 0;

		for($i = 0; $i < $rows; $i++){
			for($j = 0; $j < $cols; $j++){

				if(isset($this->characterList[$k])){
					$this->letterList[$i][$j] = $this->characterList[$k];
					$k++;
				}
			}
		}
	}

	/*
	 * Generates the 3 types of puzzles for Scrambler
	 * Fills each puzzle with blank values and then calls individual generation methods
	 */
	private function generatePuzzles(){
		$this->rectanglePuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->pyramidPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stepUpPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stepDownPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));

		$this->generateRectanglePuzzle();
		$this->generatePyramidPuzzle();
		$this->generateStepUpPuzzle();
		$this->generateStepDownPuzzle();

		$this->rectangleLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->pyramidLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stepUpLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stepDownLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));

		$this->generateRectangleLetterPuzzle();
		$this->generatePyramidLetterPuzzle();
		$this->generateStepDownLetterPuzzle();
		$this->generateStepUpLetterPuzzle();


	}

	/*
	 * Generates the rectangle puzzle
	 * Puts characters into a grid with one word per line
	 * Appears in this format, but display is corrected in ScramblerPuzzle.php based on $this->length
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generateRectanglePuzzle(){
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				$this->rectanglePuzzle[$row][$col] = $char;

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the rectangle puzzle
	 * Puts characters into a grid with one word per line
	 * Appears in this format, but display is corrected in ScramblerPuzzle.php based on $this->length
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generateRectangleLetterPuzzle(){
		$col = 0;
		$row = 0;
		$count=0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				$this->rectangleLetterPuzzle[$row][$col] = $this->characterList[$count++];

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the step up puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     0 0 0 a
	 *     0 0 a b
	 *     0 a b c
	 *     a b c d
	 */

	/*
	 * Generates the pyramid puzzle
	 * Puts characters into a grid with one word per line
	 * Appears in this format, but display is corrected in ScramblerPuzzle.php based on $this->length
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generatePyramidPuzzle(){
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				$this->pyramidPuzzle[$row][$col] = $char;

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the pyramid puzzle
	 * Puts characters into a grid with one word per line
	 * Appears in this format, but display is corrected in ScramblerPuzzle.php based on $this->length
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generatePyramidLetterPuzzle(){
		$col = 0;
		$row = 0;
		$count=0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				$this->pyramidLetterPuzzle[$row][$col] = $this->characterList[$count++];

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the step up puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     0 0 0 a
	 *     0 0 a b
	 *     0 a b c
	 *     a b c d
	 */

	private function generateStepUpPuzzle(){
		$maxColumn = $this->maxLength;
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$wordLength = $this->getWordLength($word);

			$col = $maxColumn - $wordLength;

			foreach($chars as $char){
				$this->stepUpPuzzle[$row][$col] = $char;

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the step up puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     0 0 0 a
	 *     0 0 a b
	 *     0 a b c
	 *     a b c d
	 */
	private function generateStepUpLetterPuzzle(){
		$maxColumn = $this->maxLength;
		$col = 0;
		$row = 0;
		$count = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$wordLength = $this->getWordLength($word);

			$col = $maxColumn - $wordLength;

			foreach($chars as $char){
				$this->stepUpLetterPuzzle[$row][$col] = $this->characterList[$count++];

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the step up puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generateStepDownPuzzle(){
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				$this->stepDownPuzzle[$row][$col] = $char;

				$col++;
			}

			$row++;
		}
	}

	/*
	 * Generates the step up puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generateStepDownLetterPuzzle(){
		$col = 0;
		$row = 0;
		$count = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				$this->stepDownLetterPuzzle[$row][$col] = $this->characterList[$count++];

				$col++;
			}

			$row++;
		}
	}

	/*** Getters ***/

	public function getLetterList(){
		return $this->letterList;
	}

	public function getWordList(){
		return $this->wordList;
	}

	public function getMaxLength(){
		return $this->maxLength;
	}

	public function getRectanglePuzzle(){
		return $this->rectanglePuzzle;
	}

	public function getRectangleLetterPuzzle(){
		return $this->rectanglePuzzle;
	}

	public function getPyramidPuzzle(){
		return $this->pyramidPuzzle;
	}

	public function getPyramidLetterPuzzle(){
		return $this->pyramidPuzzle;
	}

	public function getStepUpPuzzle(){
		return $this->stepUpPuzzle;
	}

	public function getStepUpLetterPuzzle(){
		return $this->stepUpLetterPuzzle;
	}

	public function getStepDownPuzzle(){
		return $this->stepDownPuzzle;
	}

	public function getStepDownLetterPuzzle(){
		return $this->stepDownLetterPuzzle;
	}

	public function getErrorStatus(){
		return $this->errorStatus;
	}

	public function getCharacterList(){
		return $this->characterList;
	}

	/*** Word Processor Functions ***/
	private function getWordLength($word){
		$this->wordProcessor->setWord($word, "telugu");

		return $this->wordProcessor->getLength();
	}

	private function splitWord($word){
		$this->wordProcessor->setWord($word, "telugu");

		return $this->wordProcessor->getLogicalChars();
	}
}
?>
