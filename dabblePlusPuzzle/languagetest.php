<?php require_once '../includes/indic-wp.php';
//require_once 'indic-wp/word_procesor.php';
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Telugu WP Unit Tester</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script src="wp_tabs.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>
	<div id="everything">
	<ul class="tabs">
		<li><a href="#summaries" tabindex="1">Summary Results</a></li>
		<li><a href="#details" tabindex="2">Detailed Results</a></li>
	</ul>

<!-- leaving out the 'Test' button, since all it does is refresh and clutter the page
     remove this comment block to restore it	
<form>
<center><input type='submit' onclick="history.go(0)" value="Test"></center>
</form>
-->

<?PHP
	$wp = new wordProcessor('a');
	$language = 'Telugu';

	$unique_tests = array();
	$unique_good = array();
	echo "<table name='result_table'>";
	echo "<tr class='detail'><th>Test Name</th><th>Input</th><th>Output</th><th>Expected</th>";
	echo "<th>Status</th></tr>";

	testParseToLogicalChars("ఆనందమకరందము", 8, $language, $wp);
	testParseToLogicalChars("స్ట్రా", 1, $language, $wp);
	testParseToLogicalChars("program", 7, $language, $wp);

	testSetWord("ఆనందమకరందము", "ఆనందమకరందము", $wp);
	testSetWord("Björn", "Björn", $wp);

	testGetWord("ఆనందమకరందము", "ఆనందమకరందము", $wp);
	testGetWord("Björn", "Björn", $wp);

	testSetLogicalChars($wp, "ఆనందము", "ఆ", "నం", "ద", "ము");
	testSetLogicalChars($wp, "Björn", "B", "j", "ö", "r", "n");
	
	testIndexOf("ఆనందమకరందము", "మ", 3, $language, $wp);
	testIndexOf("ఆనందమకరందము", "ము", 7, $language, $wp);
	
	testGetLength("ఆనందమకరందము", 8, $language, $wp);
	testGetLength("స్ట్రా", 1, $language, $wp);
	testGetLength("Elephant", 8, $language, $wp);
	
	testGetCodePointLength("ఆనందమకరందము", 11, $language, $wp);
	testGetCodePointLength("స్ట్రా", 6, $language, $wp);
	testGetCodePointLength("parrot", 6, $language, $wp);
	
	testStartsWith("ఆనందమకరందము", "ఆనంద", true, $wp);
	testStartsWith("ఆనందమకరందము", "ఆనం", true, $wp);
	testStartsWith("ఆనందమకరందము", "నంద", false, $wp);
	testStartsWith("phpprogramming", "php", true, $wp);
	
	testEndsWith("ఆనందమకరందము", "రందము", true, $wp);
	testEndsWith("ఆనందమకరందము", "ము", true, $wp);
	testEndsWith("ఆనందమకరందము", "ఆ", false, $wp);
	
	testContainsString("ఆనందమకరందము", "రందము", true, $wp);
	testContainsString("ఆనందమకరందము", "ఆనంము", false, $wp);
	testContainsString("metrostateuniversity", "state", true, $wp);
	
	testContainsChar("యూనివర్సిటీ", "యూ", true, $language, $wp);
	testContainsChar("యూనివర్సిటీ", "x", false, $language, $wp);
	
	testContainsAllLogicalChars($wp, $language, "ఆనందము", true, "నం", "ము");
	testContainsAllLogicalChars($wp, $language, "ఆనందము", false, "నం", "ము", "x");

	testContainsLogicalCharSequence($wp, $language, "మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "పా", "లి", "ట", "న్");
	testContainsLogicalCharSequence($wp, $language, "మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "పా", "లి", "ట", "న్", " ");
	testContainsLogicalCharSequence($wp, $language, "మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", false, "పా", "లి", "ట", "న్", "x");

	testContainsSpace("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, $language, $wp);
	testContainsSpace("మెట్రోపాలిటన్", false, $language, $wp);
	
	testStripSpaces("మెట్రో పాలిటన్", "మెట్రోపాలిటన్", $language, $wp);
	testStripSpaces(" మెట్రో", "మెట్రో", $language, $wp);
	testStripSpaces(" మెట్రో ", "మెట్రో", $language, $wp);
	testStripSpaces("మెట్రో  ", "మెట్రో", $language, $wp);
	testStripSpaces(" మె ట్రో   మె ట్రో  మె ట్రో", "మెట్రోమెట్రోమెట్రో", $language, $wp);
	testStripSpaces("Mc Phail", "McPhail", $language, $wp);
	
	testIsPalindrome("కిటికి", true, $language, $wp);
	testIsPalindrome("కిటికికి", false, $language, $wp);
	testIsPalindrome("malayalam", true, $language, $wp);
	
	testIsAnagram($wp, $language, "కిటికి", true, "కికిటి");
	testIsAnagram($wp, $language, "కిటికి", true, "టికికి");
	testIsAnagram($wp, $language, "కిటికి", false, "టికిక");
	testIsAnagram($wp, $language, "రాత", true, "తరా");
	testIsAnagram($wp, $language, "రాత", false, "తార ");
	testIsAnagram($wp, $language, "మెట్రోపాలిటన్", true, "పా", "లి", "ట", "న్", "మె", "ట్రో");
	testIsAnagram($wp, $language, "మెట్రోపాలిటన్", false, "పా", "లి", "ట", "న్", "మె", "ట్రో", "ట్రో");
	testIsAnagram($wp, $language, "fishy smell", true, "slimy flesh");
	
	testReverse("యూనివర్సిటీ", "టీర్సివనియూ", $wp);
	testReverse("snaginanehs", "shenanigans", $wp);
	
	testAddCharacterAt("నివర్సి", 0, "యూ", "యూనివర్సి", $wp);
	testAddCharacterAt("నివర్సి", 3, "టీ", "నివర్సిటీ", $wp);
	testAddCharacterAt("sheanigans", 3, "n", "shenanigans", $wp);
	
	testAddCharacterAtEnd("యూనివర్సి", "టీ", "యూనివర్సిటీ", $wp);
	testAddCharacterAtEnd("shenanigan", "s", "shenanigans", $wp);
	
	testLogicalCharAt("స్ట్రా", 0, "స్ట్రా", $wp);
	testLogicalCharAt("యూనివర్సిటీ", 0, "యూ", $wp);
	testLogicalCharAt("యూనివర్సిటీ", 4, "టీ", $wp);
	testLogicalCharAt("యూనివర్సిటీ", 2, "వ", $wp);
	
	testCodePointAt("స్ట్రా", 0, 3128, $wp);
	testCodePointAt("స్ట్రా", 1, 3149, $wp);
	testCodePointAt("స్ట్రా", 5, 3134, $wp);
	
	testCompareTo("ఆనందము", "ఆనందము", 0, $wp);
	testCompareTo("ఆనందము", "అరక", 1, $wp);
	testCompareTo("ఆనందము", "కడవలు", -1, $wp);

	testCompareToIgnoreCase("Hello", "Hello", 0, $wp);
	testCompareToIgnoreCase("Hello", "eLLo", 1, $wp);
	testCompareToIgnoreCase("Bello", "Hello", -1, $wp);
	testCompareToIgnoreCase("ఆనందము", "ఆనందము", 0, $wp);
	testCompareToIgnoreCase("ఆనందము", "అరక", 1, $wp);
	testCompareToIgnoreCase("ఆనందము", "కడవలు", -1, $wp);

	testEquals("ఆనందము", "ఆనందము", true);
	testEquals("ఆనందము", "ఆముదనం", false);
	
	testReverseEquals("ఆనందము", "ముదనంఆ", true);
	testReverseEquals("ఆనందము", "ఆనందము", false);
	
	testCanMakeWord("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", "పాలి", true);
	testCanMakeWord("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", "స్టేట్", true);
	testCanMakeWord("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", "వనియూర్సిటీ", true);
	testCanMakeWord("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", "మ్మవనియూర్సిటీ", false);
	
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "మెట్రో");
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "మెట్రో", "ట్రోమె");	
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "మెట్రో", "ట్రోమె", "స్టేట్");
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "మెట్రో", "ట్రోమె", "స్టేట్", "యూనివర్సిటీ");
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "మెట్రో", "ట్రోమె", "స్టేట్", "యూనివర్సిటీ", "యూని");
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", true, "మెట్రో", "ట్రోమె", "స్టేట్", "యూనివర్సిటీ", "యూని", "మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ");
	testCanMakeAllWords("మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", false, "మెట్రో", "ట్రోమె", "స్టేట్", "యూనివర్సిటీ", "యూని", "మెట్రోపాలిటన్ స్టేట్ యూనివర్సిటీ", "మ్మ మెట్రో");

	testTrim(" మెట్రో", "మెట్రో");
	testTrim(" మెట్రో ", "మెట్రో");
	testTrim("మెట్రో  ", "మెట్రో");
	testTrim(" మె ట్రో  ", "మె ట్రో");

	testStripAllSymbols("!మెట్రో(పాలి)టన/స్టేటయూ^ని%వర్సిటీ\~'", "మెట్రోపాలిటనస్టేటయూనివర్సిటీ");
	testStripAllSymbols("1మెట్రో9", "1మెట్రో9");
	testStripAllSymbols("//మెట్రో\"", "మెట్రో");
	
	testReplace("యూనివర్సిటీ", "y", "x", "యూనివర్సిటీ");
	testReplace("యూనివర్సిటీ", "నియూ", "x", "యూనివర్సిటీ");
	testReplace("యూనివర్సిటీ", "యూనివర్సిటీ", "x", "x");
	testReplace("యూనివర్సిటీ", "వర్సిటీ", "యూని", "యూనియూని");
	testReplace("యూనివర్సిటీ", "నివర్సి", "x", "యూxటీ");
	
	testIsIntersecting("యూనివర్సిటీ", "యూనియన్", true);
	testIsIntersecting("యూనివర్సిటీ", "యన్", false);
	testIsIntersecting("యూనివర్సిటీ", "వ", true);
	
	testGetIntersectingRank("యూనివర్సిటీ", "యూనియన్", 2);
	testGetIntersectingRank("యూనివర్సిటీ", "యన్", 0);
	testGetIntersectingRank("యూనివర్సిటీ", "వ", 1);
	testGetIntersectingRank("యూనివర్సిటీ", "యూనివర్సిటీ", 5);
	
	testGetUniqueIntersectingRank("యూనివర్సిటీయూనివర్సిటీ", "యూనియన్", 2);
	testGetUniqueIntersectingRank("యూనివర్సిటీయూనివర్సిటీ", "యన్", 0);
	testGetUniqueIntersectingRank("యూనివర్సిటీయూనివర్సిటీ", "వ", 1);
	testGetUniqueIntersectingRank("యూనివర్సిటీయూనివర్సిటీ", "యూనివర్సిటీ", 5);
	testGetUniqueIntersectingRank("METRO METRO", "MAT", 2);
	
	testSplitWord("యూనివర్సిటీ", 2, 3);

	testGetWordStrength("యూనివర్సిటీ", 4, $language);
	testGetWordStrength("ఆస్ట్రేలియా", 6, $language);
	testGetWordStrength("స్ట్రా", 6, $language);
	testGetWordStrength("Farva", 5, $language);
	
	testGetWordWeight("యూనివర్సిటీ", 11);
	testGetWordWeight("ఆస్ట్రేలియా", 11);
	testGetWordWeight("స్ట్రా", 6);
	testGetWordWeight("metro", 5);
	
	testRandomize("యూనివర్సిటీయూనివర్సిటీ");
	testRandomize("randomize");

	reset($GLOBALS['unique_tests']);
	$total_tested = 0;
	$total_passed = 0;
	$summaries = "";
	do {
		$test =  key($GLOBALS['unique_tests']);
		$tests = current($GLOBALS['unique_tests']);
		$good = $GLOBALS['unique_good'][$test];
		$total_tested += $tests;
		$total_passed += $good;
		$summaries .= "<tr class='summary'><td colspan='2'>" . $test ."</td>";
		$summaries .= "<td>" . $tests ."</td>";
		$summaries .= "<td>" . $good ."</td>";
		$summaries .= "<td>" . get_status_text($tests == $good) ."</td></tr>";
	} while( next($GLOBALS['unique_tests']) );
	echo "<tr class='totals'><td colspan='5' class='totals'>Total Tests: " .$total_tested."</td></tr>";
	echo "<tr class='totals'><td colspan='5' class='totals'>Total Tests Passed: " .$total_passed."</td></tr>";
	echo "<tr class='totals'><td colspan='5' class='totals'>Unique CustomFunctions Tested: " .count($unique_tests) ."</td></tr>";
	echo "<tr class='summary'><th colspan='2'>Test Name</th><th>Tests</th><th>Passes</th><th>Status</th>";
	echo $summaries;
	echo "</table></div>";

function testParseToLogicalChars($word, $len, $language, $wp) {
	$wp->setWord($word);
	$parsed_word = $wp->parseToLogicalChars($word, $language);
	$len_r = count($parsed_word);
	$success = $len_r == $len;
	update_test("parseToLogicalChars()", $word, $len_r, $len, $success);
}


function testSetWord($word, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->getWord();
	$success = $output === $expected;
	update_test("setWord()", $word, $output, $expected, $success);
}

function testGetWord($word, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->getWord();
	$success = $word === $expected;
	update_test("getWord()", $word, $output, $expected, $success);	
}

function testSetLogicalChars($wp, ...$args) {
	$expected = $args[0];
	for($i=1; $i < count($args); $i++) $log_chars[] = $args[$i];
	$wp->setLogicalChars($log_chars);
	$output = $wp->getWord();
	$success = $output === $expected;
	update_test("setLogicalChars()", format_array($log_chars), $output, $expected, $success);
}

function testIndexOf($word, $fragment, $expected_index, $language, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$found = $wp->indexOf($fragment);
	update_test("indexOf()", "(".$fragment.") in (".$word.")", $found, $expected_index, $found == $expected_index);
}

function testGetLength($word, $expected, $language, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$len = $wp->getLength();
	$success = $len == $expected;
	update_test("getLength()", $word, $len, $expected, $success);
}

function testGetCodePointLength($word, $expected, $language, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$cpl = $wp->getCodePointLength();
	$success = $cpl == $expected;
	update_test("getCodePointLength()", $word, $cpl, $expected, $success);
}

function testStartsWith($word, $part, $expected, $wp) {
	$wp->setWord($word);
	$ret = $wp->startsWith($part);
	$success = $ret == $expected;
	update_test("startsWith()", "(".$part.") starts (".$word.")", tf($ret), tf($expected), $success);
}

function testEndsWith($word, $part, $expected, $wp) {
	$wp->setWord($word);
	$ret = $wp->endsWith($part);
	$success = $ret == $expected;
	update_test("endsWith()", "(".$part.") ends (".$word.")", tf($ret), tf($expected), $success);	
}

function testContainsString($word, $part, $expected, $wp) {
	$wp->setWord($word);
	$ret = $wp->containsString($part);
	$success = $ret == $expected;
	update_test("endsWith()", "(".$part.") ends (".$word.")", tf($ret), tf($expected), $success);
}

function testContainsChar($word, $part, $expected, $language, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$ret = $wp->containsChar($part);
	$success = $ret == $expected;
	update_test("containsChar()", "(".$word.") contains (".$part.")", tf($ret), tf($expected), $success);
}

function testContainsAllLogicalChars($wp, $language, ...$args) {
	$word = $args[0];
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$expected = $args[1];
	for($i=2; $i < count($args); $i++) $chars[] = $args[$i];
	$ret = $wp->containsAllLogicalChars($chars);
	$success = $ret == $expected;
	update_test("containsAllLogicalChars()", "(".$word.") contains ".format_array($chars), tf($ret), tf($expected), $success);	
}

function testContainsLogicalCharSequence($wp, $language, ...$args) {
	$word = $args[0];
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$expected = $args[1];
	for($i=2; $i < count($args); $i++) $sequence[] = $args[$i];
	$chars = implode($sequence);
	$ret = $wp->containsLogicalCharSequence($chars);
	$success = $ret == $expected;
	update_test("containsLogicalCharSequence()", "(".$word.") contains (".$chars.")", tf($ret), tf($expected), $success);	
	
}

function testContainsSpace($word, $expected, $language, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$status = $wp->containsSpace();
	$success = $status == $expected;
	update_test("containsSpace()", $word, tf($status), tf($status), $success);
}

function testStripSpaces($word, $expected, $language, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$wp->stripSpaces();
	$output = $wp->getWord();
	$success = $output == $expected;
	update_test("stripSpaces()", $word, $output, $expected, $success);
}

function testIsPalindrome($word, $language, $expected, $wp) {
	$wp->setWord($word);
	$wp->parseToLogicalChars($word, $language);
	$status = $wp->isPalindrome();
	$success = $status == $expected;
	update_test("isPalindrome()", $word, tf($status), tf($expected), $success);
}

function testIsAnagram($wp, $language, ...$args) {
	$word = $args[0];
	$wp->parseToLogicalChars($word, $language);
	$expected = $args[1];
	for($i=2; $i < count($args); $i++) $to_test[] = $args[$i];
	if( count($to_test) == 1 ) $to_test = $to_test[0];
	$status = $wp->isAnagram($to_test);
	$success = $status == $expected;
	$tested = is_array($to_test) ? format_array($to_test) : $to_test;
	update_test("isAnagram()", "(". $tested .") becomes (". $word .")", tf($status), tf($expected), $success);
}

function testReverse($word, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->reverse();
	$success = $output == $expected;
	update_test("reverse()", $word, $output, $expected, $success);
}

function testAddCharacterAt($word, $pos, $char, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->addCharacterAt($pos, $char);
	$success = $output == $expected;
	update_test("addCharacterAt()", "(".$char.") into (".$word.") at index ".$pos, $output, $expected, $success);
}

function testAddCharacterAtEnd($word, $char, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->addCharacterAtEnd($char);
	$success = $output == $expected;
	update_test("addCharacterAtEnd()", "(".$char.") at end of (".$word.")", $output, $expected, $success);
}

function testLogicalCharAt($word, $pos, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->logicalCharAt($pos);
	$success = $output == $expected;
	update_test("logicalCharAt()", "char ".$pos." of (".$word.")", $output, $expected, $success);	
}

function testCodePointAt($word, $pos, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->codePointAt($pos);
	$success = $output == $expected;
	update_test("codePointAt()", "point ".$pos." of (".$word.")", $output, $expected, $success);
}

function testCompareTo($word, $to_compare, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->compareTo($to_compare);
	$success = ($output < 0 && $expected < 0) ||
				($output == 0 && $expected == 0) ||
				($output > 0 && $expected > 0);
	update_test("compareTo()", "(".$word.") and (".$to_compare.")", ct_txt($output), ct_txt($expected), $success);
}

function testCompareToIgnoreCase($word, $to_compare, $expected, $wp) {
	$wp->setWord($word);
	$output = $wp->compareToIgnoreCase($to_compare);
	$success = ($output < 0 && $expected < 0) ||
				($output == 0 && $expected == 0) ||
				($output > 0 && $expected > 0);
	update_test("compareToIgnoreCase()", "(".$word.") and (".$to_compare.")", ct_txt($output), ct_txt($expected), $success);
}

function ct_txt($val) {
	return ($val== 0 ? "equal to" : ($val > 0 ? "greater than" : "less than"));
}

function testEquals($word, $to_test, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->equals($to_test);
	$success = $output == $expected;
	update_test("equals()", "(".$word.") equals (".$to_test.")", tf($output), tf($expected), $success);
}

function testReverseEquals($word, $to_test, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->reverseEquals($to_test);
	$success = $output == $expected;
	update_test("reverseEquals()", "(".$word.") is reversed (".$to_test.")", tf($output), tf($expected), $success);
}

function testCanMakeWord($word, $to_make, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->canMakeWord($to_make);
	$success = $output == $expected;
	update_test("canMakeWord()", "(".$word.") can make (".$to_make.")", tf($output), tf($expected), $success);
}

function testCanMakeAllWords() {
	$word = func_get_args()[0];
	$expected = func_get_args()[1];
	for($i=2; $i < count(func_get_args()); $i++) $to_make[] = func_get_args()[$i];	
	$wp = new wordProcessor($word);	
	$output = $wp->canMakeAllWords($to_make);
	$success = $output == $expected;
	$input_format = "(".$word.")<br>can make<br>";
	foreach($to_make as $tword) $input_format .= "<br>(".$tword.")";
	update_test("canMakeAllWords()", $input_format, tf($output), tf($expected), $success);
}

function testTrim($word, $expected) {
	$wp = new wordProcessor($word);	
	$wp->trim();
	$success = $wp->getWord() == $expected;
	update_test("trim()", "(".$word.")", "(".$wp->getWord().")", "(".$expected.")", $success);
}

function testStripAllSymbols($word, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->stripAllSymbols();
	$success = $output == $expected;
	update_test("stripAllSymbols()", $word, $output, $expected, $success);
}

function testReplace($word, $search, $replace, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->replace($search, $replace);
	$success = $output == $expected;
	update_test("replace()", "repl. (".$search.") with (".$replace.") in (".$word.")", $output, $expected, $success);
}

function testIsIntersecting($word, $intersect, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->isIntersecting($intersect);
	$success = $output == $expected;
	update_test("isIntersecting()", "(".$intersect.") intersects (".$word.")", tf($output), tf($expected), $success);
}

function testGetIntersectingRank($word, $intersect, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->getIntersectingRank($intersect);
	$success = $output == $expected;
	update_test("getIntersectingRank()", "(".$intersect.") intersects (".$word.")", $output, $expected, $success);
}

function testGetUniqueIntersectingRank($word, $intersect, $expected) {
	$wp = new wordProcessor($word);	
	$intersect_array = $wp->parseToLogicalCharacters($intersect);
	$output = $wp->getUniqueIntersectingRank($intersect_array);
	$success = $output == $expected;
	update_test("getUniqueIntersectingRank()", "(".$intersect.") intersects (".$word.")", $output, $expected, $success);
}

// this would be where I'd test getUniqueIntersectingChars(), but getUniqueIntersectingRank() would
// fail if the former fails, so if the latter works, both work.

function testSplitWord($word, $cols, $expected_rows) {
	$wp = new wordProcessor($word);	
	$split_array = $wp->splitWord($cols);
	$success = $expected_rows == count($split_array);
	$output_ar = count($split_array)." rows";
	update_test("splitWord()", "(".$word.") into ".$cols." columns", $output_ar, $expected_rows." rows", $success);
}

function testGetWordStrength($word, $expected, $language) {
	$wp = new wordProcessor($word);	
	$output = $wp->getWordStrength($language);
	$success = $output == $expected;
	update_test("getWordStrength()", $word, $output, $expected, $success);
}

function testGetWordWeight($word, $expected) {
	$wp = new wordProcessor($word);	
	$output = $wp->getWordWeight();
	$success = $output == $expected;
	update_test("getWordWeight()", $word, $output, $expected, $success);
}

function testRandomize($word) {
	$wp = new wordProcessor($word);
	$output = $wp->randomize($wp->parseToLogicalCharacters($word));
	$success = $wp->isAnagram($output);
	update_test("randomize()", $word, implode($output), "an anagram", $success);
}

function update_test($function, $input, $output, $expected, $status) {
	echo "<tr class='detail'><td>" . $function . "</td><td>".$input."</td><td>".$output."</td>";
	echo "<td>".$expected."</td><td>".get_status_text($status)."</td></tr>";
	if( !isset($GLOBALS['unique_tests'][$function]) ) { // if the unique_tests is unset, so is unique_good
		$GLOBALS['unique_tests'][$function] = 0;
		$GLOBALS['unique_good'][$function] = 0;
	}
	$GLOBALS['unique_tests'][$function]++;
	if($status) $GLOBALS['unique_good'][$function]++;
}

function get_status_text($success) {
	if($success) return "<span class='pass'>PASS</span>";
	else return "<span class='fail'>FAIL</span>";
}

function format_array($array_word) {
	$formatted = "";
	foreach($array_word as $char) $formatted .= "(".$char.")";
	return $formatted;
}

function tf($status) {
	if($status) return "true";
	else return "false";
}
?>
	</div>
</body>
</html>
