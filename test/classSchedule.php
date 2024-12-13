<?php
$eol = "<br/>\n";
$classes = array();
$specialsBlock = 8;
$specialsIndex = 0 ;

$specials = array("Phys. Ed.", "Music", "Art", "Phys. Ed.", "Library");

// Initialize the specials scheduling array.
$spSched = array();
foreach ($specials as $specialClass) {$spSched[$specialClass] = array();}
echo "Number of Specials: " . strval(count($specials)) . $eol;



// Process through the grades, and the classrooms within each grade.
for ($grade = 0; $grade <= 3; $grade++) {

	// Arrange reading and lunch around specials...
	$readingBlock = (inRange($specialsBlock,8,9) ? 10 : 9) ;
	$lunchBlock = (inRange($specialsBlock, 10,11) ? 12 : 11) ;
	if ($specialsBlock == $readingBlock) {$specialsBlock++;}
	if ($specialsBlock == $lunchBlock) {$specialsBlock++;}
	
	echo "<hr/>\n" ;
	echo "Specials Block: " .strval($specialsBlock) . $eol;
	echo "Reading Block: " .strval($readingBlock) . $eol;
	echo "Lunch Block: " .strval($lunchBlock) . $eol;

	// Process through the classrooms.
	for ($room = 1; $room <= 3; $room++) {
		$classId = ($grade==0 ? "K" : strval($grade));
		$classId .= "-" . chr(96 + $room) ;
#		echo $classId . ", ";
#		$classes[$classId] = "";
		printClassSchedule($classId);
		// For each new classroom, shift the starting specials index.
		$specialsIndex++;		// Start each new classroom on a new index.
	}
	$specialsBlock++;
	if ($specialsBlock == 15) {$specialsBlock = 8;}
}

// Print out the specials schedules...
printSpecialsSchedule();


function inRange($value, $begin, $end) {
	if (($value >= $begin) AND ($value <= $end)) {return true;}
	return false;
}


// Print the schedule with specials for each classroom.
function printClassSchedule($classId){
	global $specials, $specialsBlock, $readingBlock, $lunchBlock, $specialsIndex, $spSched, $eol;

	// Make sure we're in the range of specials in the list.
	if (($specialsIndex)==count($specials)) {$specialsIndex = 0;}

	$startIndex = $specialsIndex ;
	$endOfSpecials = false;
	
	echo "<h1>Classroom Schedule: {$classId}</h1>\n";
	echo "Specials Block: " . strval($specialsBlock) ;
	echo ", Index: " . strval($specialsIndex) ;
	echo ", Starting with " . strval($startIndex) . $eol;


	$header =  <<<HEADER
	<table border = "1">
	<tr>
	<th>Hour</th>
	<th>Day 1</th>
	<th>Day 2</th>
	<th>Day 3</th>
	<th>Day 4</th>
	<th>Day 5</th>
	</tr>
HEADER;
	echo $header . $eol;

	for ($block = 8; $block <= 14; $block++) {
		echo "<tr><td>" . strval($block) . ":00</td>\n";
		for ($day = 1; $day <= 5 ; $day++) {
			echo "<td>\n" ;
			switch ($block) {
			case $readingBlock : 
				echo "Reading";
				break;
			case $lunchBlock :
				echo "Lunch/Recess";
				break;
			case $specialsBlock:
				if (!$endOfSpecials){
					$specialClass = $specials[$specialsIndex];
					echo $specialClass . strval($specialsIndex);
					$specialsIndex++;
					
					// Save to the specials schedule...
					$spSched[$specialClass][$block][$day] = $classId;
				}
#				echo "Index: " . strval($specialsIndex);
				if (($specialsIndex)==count($specials)) {$specialsIndex = 0;}
				if ($specialsIndex == $startIndex){
					$endOfSpecials = true;
#					$specialsBlock++;
				}
			}
			echo "</td>\n" ;
		}
		echo "</tr>\n";
	}
	echo "</table>" . $eol ;

}


// Print the schedule for each of the special classes.
function printSpecialsSchedule(){
	global $specials, $specialsBlock, $readingBlock, $lunchBlock, $specialsIndex, $spSched, $eol;

	// Make sure we're in the range of specials in the list.
	if (($specialsIndex)==count($specials)) {$specialsIndex = 0;}

	$startIndex = $specialsIndex ;
	$endOfSpecials = false;

	$header =  <<<HEADER
	<table border = "1">
	<tr>
	<th>Hour</th>
	<th>Day 1</th>
	<th>Day 2</th>
	<th>Day 3</th>
	<th>Day 4</th>
	<th>Day 5</th>
	</tr>
HEADER;

	foreach ($specials as $special){
		echo "<hr/>\n" ;
		echo "<h1>Specials Schedule: {$special}</h1>\n";
		echo $header . $eol;

		for ($block = 8; $block <= 14; $block++) {
			echo "<tr><td>" . strval($block) . ":00</td>\n";
			for ($day = 1; $day <= 5 ; $day++) {
				echo "<td>\n" ;
				echo $spSched[$special][$block][$day];
				echo "</td>\n" ;
			}
			echo "</tr>\n";
		}
	echo "</table>" . $eol ;
	}
}