<?php

	/* Created by Stephen Schneider
	 * Class for creating the Stacks puzzle
	 * Takes in at least two words that go in sequential length order to generate step up, step down, and pyramid puzzles
	 * Input is ordered by word length, checked for validation, then characters are obtained from the words and shuffled
	 * letterList servers as the list of letters for the puzzles while the puzzles serve as solutions
	 * Puzzles themselves are just blank solutions displayed on ScramblerPuzzle page
	 * Scrambler will stop if there is an error detected with user input and raise the errorStatus flag
	 */
class Shapes{
	// Removed the global as max columns need to change with scrambler
	private $MAX_COLUMNS = 5;
	private $MAX_WORD_COUNT = 5;
	private $wordList = [];
	private $puzzleList = [];
	private $characterList = [];
	private $letterList = [];
	private $characterListNoSpaces = [];


	private $wordPuzzle = [];
	private $shapesPuzzle = [];

	
	private $letterPuzzle = [];

	// Added max Columns for swimlanes
	private $maxColumns;
	private $maxLength;
	private $wordCount;


	private $wordProcessor;
	private $errorStatus;


	public function __construct($wordList){
		$this->wordProcessor = new wordProcessor(" ", "Telugu");
		$this->wordList = $wordList;

		if($this->validateInput()){
		
		
			$this->maxLength = $this->getWordLengthNoSpaces($this->wordList[(count($this->wordList) - 1)]);
			$this->wordCount = count($wordList);

			$this->maxColumns = $this->maxLength;
			$this->generatePuzzles();
		}
		else{
			$this->errorStatus = true;
		}
	}

	private function validateInput(){
		if(count($this->wordList) > $this->MAX_WORD_COUNT) {
			return false;
		}
		$len = $this->getWordLengthNoSpaces($this->wordList[0]);

		for($i = 0; $i < count($this->wordList); $i++){
			$nextLen = $this->getWordLengthNoSpaces($this->wordList[$i]);

			if(($len) != $nextLen){
				return false;
			}
			
			if ($nextLen > $this->MAX_COLUMNS){
				return false;
			}
		}

		return true;
	}

	private function generateShapesPuzzle(){
		
		$i = 0;
		$indexShuffle = [];

		//For each word, put it in wordlist as an array
		foreach($this->wordList as $word) {
			$chars = $this->splitWord($word);

			$this->wordList[$i] = $chars;

			for($j = 0; $j < count($this->wordList[$i]);){
				if($this->wordList[$i][$j] == ' '){
					array_splice($this->wordList[$i], $j, 1);
				} else {
					$j++;
				}
			}

			array_push($this->shapesPuzzle, $this->wordList[$i]);

			$i = $i + 1;
		}

		for($i = 0; $i < count($this->shapesPuzzle); $i++){
			shuffle($this->shapesPuzzle[$i]);
		}
		for($i = 0; $i < $this->maxLength; $i++) {
			$indexShuffle = null;
			$indexShuffle = [];
			$testArray = null;
			$testArray = [];
			for($j = 0; $j < $this->wordCount; $j++) {
				array_push($indexShuffle, $this->shapesPuzzle[$j][$i]);				
			}

			shuffle($indexShuffle);

			for($j = 0; $j < $this->wordCount; $j++) {
				$this->shapesPuzzle[$j][$i] = $indexShuffle[$j];
			}
		}
	}
	
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
		
		$this->generateShapesPuzzle();

		$this->wordPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));

		$this->generateWordPuzzle();
	
		$this->letterPuzzle = array_fill(0, $this->wordCount, array_fill(0, $this->maxLength, 0));

		$this->generateLetterPuzzle();
	}

	/*
	 * Generates the word puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generateWordPuzzle(){
		$col = 0;
		$row = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				if($char == ''){}
				else{
					$this->wordPuzzle[$row][$col] = $char;

					$col++;
				}
			}
			
			for($i = 0; $i < count($this->wordPuzzle[$row]);){
				if($this->wordPuzzle[$row][$i] == ' ') {
					array_splice($this->wordPuzzle[$row], $i, 1);
					// array_push($this->wordPuzzle[$row], '0');
				} else{
					$i++;
				}

			}

			$row++;
		}
	}

	/*
	 * Generates the letter puzzle
	 * Puts characters into a grid with one word per line
	 * Should appear in this format:
	 *     a 0 0 0
	 *     a b 0 0
	 *     a b c 0
	 *     a b c d
	 */
	private function generateLetterPuzzle(){
		$col = 0;
		$row = 0;
		$count = 0;

		foreach($this->wordList as $word){
			$chars = $this->splitWord($word);
			$col = 0;

			foreach($chars as $char){
				
				if($char == ' '){}
				else{
					$this->letterPuzzle[$row][$col] = $char;

					$col++;
				}
			}

			for($i = 0; $i < count($this->letterPuzzle[$row]); $i++){
				if($this->letterPuzzle[$row][$i] == ' ') {
					array_splice($this->letterPuzzle[$row], $i, 1);
					array_push($this->letterPuzzle[$row], '0');
				} else{
					// $i++;
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

	public function getWordPuzzle(){
		return $this->wordPuzzle;
	}

	public function getLetterPuzzle(){
		return $this->letterPuzzle;
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

	public function getShapesPuzzle(){
		return $this->shapesPuzzle;
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
