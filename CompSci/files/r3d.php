<?php

function read3D() {

	$fileLines = file('cube.3d');
	$punktArray = array();
	
	// Go through individual lines
	foreach($fileLines as $line) {
		if($line[0] != '#') { // Checking for commented lines
			// Split the CSV into coordinates
			$punkten = explode(",", $line);
			// Append an array made of all of the points of the line to punktArray
			// C'est comme ça qu'on append en PHP... REEEEEE, même java c'est plus propre putain
			$punktArray[] = array($punkten[0], $punkten[1], $punkten[2], $punkten[3]); // array(x,y,z,color);
		}
	}

	return $punktArray;
}

function fillGrid2D($points3D, $alpha, $beta) {

	// Creating a new projected 2D grid
	$grid = array();
	// C'eeeest dééééégeulaaaaaaaasse
	for($i = 0 ; $i < 36 ; $i++) $grid[] = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); // 30 0s
	
	// Projecting on (0,x,z) plane (+ translating to match indexes and later cursor coords)
	foreach($points3D as $point) {
		$x = $point[0]*cos($beta)*cos($alpha) - $point[1]*sin($alpha) + $point[2]*sin($beta)*cos($alpha);
		$z = -$point[0]*sin($beta) + $point[2]*cos($beta);
		$grid[(int)$x+18][(int)$z+18] = $point[3];
	}

	return $grid;
}

// Display the projected 2D grid
function displayGrid2D($grid2D) {
	$i = 0;
	$j = 0;

	for($i = 35 ; $i >= 0 ; $i--) {
		printf("                               ");
		for($j = 0 ; $j < 36 ; $j++) { // mb +1 car 1st cursor coord is 1 iirc
			if($grid2D[$i][$j] == 0) printf("  "); 
			else printf("\033[%dm  \033[49m",$grid2D[$i][$j]);
		}
		printf("\n");
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

system("clear");
// Get all the drawn points from the file
$points = read3D();

// Since it's just a projection, Euler's angles are simplified ; basically going full azimutal.
$alpha = 65; // azimuth angle
$beta = 55; // elevation angle

displayGrid2D(fillGrid2D($points, $alpha, $beta));

// Switching tty in canonical mode
system("stty -icanon");
while($c = fread(STDIN,1)) { 
	if($c == 'q') $beta = ($beta + 5)%180;
	if($c == 'd') $beta = ($beta - 5)%180;
	if($c == 'z') $alpha = ($alpha + 5)%180;
	if($c == 's') $alpha = ($alpha - 5)%180;
	
	system("clear");
	printf("alpha = %d, beta = %d",$alpha, $beta);
	displayGrid2D(fillGrid2D($points, deg2rad($alpha), deg2rad($beta)));
}

?>
