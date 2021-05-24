<?php

	/* Created by Stephen Schneider
	 * Class for creating the Stacks puzzle
	 * Takes in at least two words that go in sequential length order to generate step up, step down, and pyramid puzzles
	 * Input is ordered by word length, checked for validation, then characters are obtained from the words and shuffled
	 * letterList servers as the list of letters for the puzzles while the puzzles serve as solutions
	 * Puzzles themselves are just blank solutions displayed on ScramblerPuzzle page
	 * Scrambler will stop if there is an error detected with user input and raise the errorStatus flag
	 */
class Stacks{
	// Removed the global as max columns need to change with scrambler
	// private $MAX_COLUMNS = 5;
	private $wordList = [];
	private $puzzleList = [];
	private $characterList = [];
	private $letterList = [];
	private $characterListNoSpaces = [];

	private $rectanglePuzzle = [];
	private $stacksPyramidPuzzle = [];
	private $stacksStepUpPuzzle = [];
	private $stacksStepDownPuzzle = [];
	private $stacksPuzzle = [];

	private $rectangleLetterPuzzle =[];
	private $stacksStepDownLetterPuzzle = [];
	private $stacksStepUpLetterPuzzle = [];
	private $stacksPyramidLetterPuzzle =[];
	private $stacksLetterPuzzle = [];

	// Added max Columns for scrambler
	private $maxColumns;
	private $maxLength;
	private $wordCount;


	private $wordProcessor;
	//private $errorStatus;


	public function __construct($wordList){
		$this->wordProcessor = new wordProcessor(" ", "Telugu");
		$this->wordList = $wordList;



		//$this->orderWords();

		//if($this->validateInput()){
		$this->maxLength = 0;
		
		//find the maximum word length, which is also the maximum number of columns
		foreach($this->wordList as $word) {
			if ($this->getWordLengthNoSpaces($word) > $this->maxLength) {
				$this->maxLength = $this->getWordLengthNoSpaces($word);
			}
		}
			//$this->maxLength = $this->getWordLength($this->wordList[(count($this->wordList) - 1)]);
			$this->wordCount = count($wordList);

			// Only need the count of the first element for scrambler as they have to all be the same length
			$this->maxColumns = $this->maxLength;

			$this->generateLetterList();

			$this->generatePuzzles();
		/**}
		else{
			$this->errorStatus = true;
		}**/
	}

	/*
	 * Orders input words by word length (not needed for stacks)
	 */
	private function orderWords(){
	 	usort($this->wordList, function($a, $b) {
	 		return $this->getWordLength($a) - $this->getWordLength($b);
	 	});
	 }

	/*
	 * Generates the puzzle list of letters in grid format with columns equal to maxColumns variable
	 * Takes all words from input, split into characters, shuffle the list, then save in letterList in grid format
	 */
	//THIS FUNCTION HAS NOT BEEN TOUCHED
	private function generateLetterList(){


		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);

			foreach($chars as $char){
				array_push($this->characterList, $char);
			}
		}

		shuffle($this->characterList);

		$this->characterListNoSpaces = $this->characterList;

		for($i = 0; $i < count($this->characterListNoSpaces);){
			if($this->characterListNoSpaces[$i] == ' ') {
				array_splice($this->characterListNoSpaces, $i, 1);
			} else {
				$i++;
			}
		}

		$charCount = count($this->characterListNoSpaces);

		$cols = $this->maxColumns;
		$rows = $charCount / $cols;
		$rows = ceil($rows);

		$this->letterList = array_fill(0, $rows, array_fill(0, $cols, 0));

		$k = 0;

		for($i = 0; $i < $rows; $i++){
			for($j = 0; $j < $cols; $j++){

				if(isset($this->characterListNoSpaces[$k])){
					$this->letterList[$i][$j] = $this->characterListNoSpaces[$k];
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
		$this->stacksPyramidPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stacksStepUpPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stacksStepDownPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));

		$this->generateRectanglePuzzle();
		$this->generateStacksPyramidPuzzle();
		$this->generateStacksStepUpPuzzle();
		$this->generateStacksStepDownPuzzle();

		$this->rectangleLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stacksPyramidLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stacksStepUpLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));
		$this->stacksStepDownLetterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));

		$this->generateRectangleLetterPuzzle();
		$this->generateStacksPyramidLetterPuzzle();
		$this->generateStacksStepDownLetterPuzzle();
		$this->generateStacksStepUpLetterPuzzle();


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
				if($char == ' '){}
				else {
					$this->rectanglePuzzle[$row][$col] = $char;

					$col++;
				}
			}

			for($i = 0; $i < count($this->rectanglePuzzle[$row]); $i++){
				if($this->rectanglePuzzle[$row][$i] == ' ') {
					array_splice($this->rectanglePuzzle[$row], $i, 1);
					array_push($this->rectanglePuzzle[$row], '0');
				} else{
					// $i++;
				}
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
				if($char == ' '){}
				else {
					$this->rectangleLetterPuzzle[$row][$col] = $this->characterListNoSpaces[$count++];

					$col++;
				}
			}

			for($i = 0; $i < count($this->rectangleLetterPuzzle[$row]); $i++){
				if($this->rectangleLetterPuzzle[$row][$i] == ' ') {
					array_splice($this->rectangleLetterPuzzle[$row], $i, 1);
					//array_push($this->rectangleLetterPuzzle[$row], '0');
				} else{
					// $i++;
				}
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
	private function generateStacksPyramidPuzzle(){
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				if($char == ' '){}
				else {
					$this->stacksPyramidPuzzle[$row][$col] = $char;

					$col++;
				}
			}

			for($i = 0; $i < count($this->stacksPyramidPuzzle[$row]); $i++){
				if($this->stacksPyramidPuzzle[$row][$i] == ' ') {
					array_splice($this->stacksPyramidPuzzle[$row], $i, 1);
					array_push($this->stacksPyramidPuzzle[$row], '0');
				} else{
					// $i++;
				}

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
	private function generateStacksPyramidLetterPuzzle(){
		$col = 0;
		$row = 0;
		$count=0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				if($char == ' '){}
				else {
					$this->stacksPyramidLetterPuzzle[$row][$col] = $this->characterListNoSpaces[$count++];

					$col++;
				}
			}

			for($i = 0; $i < count($this->stacksPyramidLetterPuzzle[$row]); $i++){
				if($this->stacksPyramidLetterPuzzle[$row][$i] == ' ') {
					array_splice($this->stacksPyramidLetterPuzzle[$row], $i, 1);
					array_push($this->stacksPyramidLetterPuzzle[$row], '0');
				} else{
					// $i++;
				}
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

	private function generateStacksStepUpPuzzle(){
		$maxColumn = $this->maxLength;
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$wordLength = $this->getWordLengthNoSpaces($word);

			$col = $maxColumn - $wordLength;

			foreach($chars as $char){
				if($char == ' '){}
				else {
					$this->stacksStepUpPuzzle[$row][$col] = $char;

					$col++;
				}
			}

			for($i = 0; $i < count($this->stacksStepUpPuzzle[$row]);){
				if($this->stacksStepUpPuzzle[$row][$i] == ' ') {
					array_splice($this->stacksStepUpPuzzle[$row], $i, 1);
					array_unshift($this->stacksStepUpPuzzle[$row], '0');
				} else{
					$i++;
				}
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
	private function generateStacksStepUpLetterPuzzle(){
		$maxColumn = $this->maxLength;
		$col = 0;
		$row = 0;
		$count = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$wordLength = $this->getWordLengthNoSpaces($word);

			$col = $maxColumn - $wordLength;

			foreach($chars as $char){
				if($char == ' '){}
				else {
					$this->stacksStepUpLetterPuzzle[$row][$col] = $this->characterListNoSpaces[$count++];

					$col++;
				}
			}

			for($i = 0; $i < count($this->stacksStepUpLetterPuzzle[$row]);){
				if($this->stacksStepUpLetterPuzzle[$row][$i] == ' ') {
					array_splice($this->stacksStepUpLetterPuzzle[$row], $i, 1);
					array_unshift($this->stacksStepUpLetterPuzzle[$row], '0');
				} else{
					$i++;
				}
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
	private function generateStacksStepDownPuzzle(){
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				if($char == ' '){}
				else {
					$this->stacksStepDownPuzzle[$row][$col] = $char;

					$col++;
				}
			}

			for($i = 0; $i < count($this->stacksStepDownPuzzle[$row]);){
				if($this->stacksStepDownPuzzle[$row][$i] == ' ') {
					array_splice($this->stacksStepDownPuzzle[$row], $i, 1);
					array_push($this->stacksStepDownPuzzle[$row], '0');
				} else{
					$i++;
				}
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
	private function generateStacksStepDownLetterPuzzle(){
		$col = 0;
		$row = 0;
		$count = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				if($char == ' ' ){}
				else {
					$this->stacksStepDownLetterPuzzle[$row][$col] = $this->characterListNoSpaces[$count++];

					$col++;
				}
			}

			for($i = 0; $i < count($this->stacksStepDownLetterPuzzle[$row]);){
				if($this->stacksStepDownLetterPuzzle[$row][$i] == ' ') {
					array_splice($this->stacksStepDownLetterPuzzle[$row], $i, 1);
					array_push($this->stacksStepDownLetterPuzzle[$row], '0');
				} else{
					$i++;
				}
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
		return $this->rectangleLetterPuzzle;
	}

	public function getStacksPyramidPuzzle(){
		return $this->stacksPyramidPuzzle;
	}

	public function getStacksPyramidLetterPuzzle(){
		return $this->stacksPyramidLetterPuzzle;
	}

	public function getStacksStepUpPuzzle(){
		return $this->stacksStepUpPuzzle;
	}

	public function getStacksStepUpLetterPuzzle(){
		return $this->stacksStepUpLetterPuzzle;
	}

	public function getStacksStepDownPuzzle(){
		return $this->stacksStepDownPuzzle;
	}

	public function getStacksStepDownLetterPuzzle(){
		return $this->stacksStepDownLetterPuzzle;
	}

	public function getErrorStatus(){
		return $this->errorStatus;
	}

	public function getCharacterList(){
		return $this->characterList;
	}

	public function getCharacterListNoSpaces(){
		return $this->characterListNoSpaces;
	}

	/*** Word Processor Functions ***/
	private function getWordLength($word){
		$this->wordProcessor->setWord($word);

		return $this->wordProcessor->getLength();
	}

	private function getWordLengthNoSpaces($word){
		$this->wordProcessor->setWord($word);

		return $this->wordProcessor->getLengthNoSpaces($word);
	}

	private function splitWord($word){
		$this->wordProcessor->setWord($word);

		return $this->wordProcessor->getLogicalChars();
	}
}
?>
