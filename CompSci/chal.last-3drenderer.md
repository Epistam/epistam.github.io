# DTRE Challenge - The Last One (TM) - A 3D Terminal Renderer
###### 2019-02-24

So, after a long time of silence, here I am, yet again, for the final FlaminCode challenge. 
Well, a bit late, since this particular challenge was due for one or two weeks back.
Still, I deem it interesting enough to make a post on it, detailing my thought process and the inner workings of the "thing".
A well chosen term, really, since this abomination was made in... PHP. A choice I made for reasons I will not discuss here, but I'll just
go ahead and say it was one those "and why the hell not ?" things -after Stack Overflow addressed the "is it even possible question ?". 

## What's the idea, anyway ? 

Well, first of, the main idea was to use PHP to make an in-terminal 3D renderer. If one takes PHP as a purely web-based language called solely by Apache, Nginx, or whatever is your web server of choice... Questionning this choice is somewhat legitimate. 
But PHP comes first and foremost with a cli : php-cli. That's something you must have experienced if you're working with various frameworks or applications that come with a PHP-made install script. 

Enough with the chit-chat, let's explain the idea which itself is pretty simple : 
* Make a renderer taking any set of 3D points
* Apply a rotation, whatever it is, to it
* Display the result as a 2D representation on the terminal
* Control the angular position of the set of points using the keyboard

## Points taken

For flexibility reasons, I used a file in which I would store my set of points : this way, it is possible to change what you want to represent on the screen pretty fast, and even on the fly. The file itself is merely a CSV file with the fields being its coordinates, and the point color, such as detailed in the example that follows : 

```
#x,y,z,color
-10,-10,-10,42
10,10,10,44
-10,10,-10,44
10,-10,10,42
-10,-10,10,42
10,-10,-10,42
10,10,-10,44
-10,10,10,44
```

Alright, let's get started with the actual code :

```php
function read3D() {

	$file_lines = file('cube.3d'); 
	$punkt_array = array();
	
	foreach($fileLines as $line) {
		if($line[0] != '#') {
			$punkt = explode(",", $line);
			$punkt_array[] = array($punkt[0], $punkt[1], $punkt[2], $punkt[3]);
		}
	}

	return $punkt_array;
}
```

The idea here is to "import" this file into a two-dimensions array -or more straightforwardly, an array of points.
To this end, the program is parsing the file itself, ignoring any line that begins with '#', and "exploding" every other line in an array of three coordinates + 1 color, all integers, and promptly appending it to the array containing the different points.

## The actual "engine"

Now we have the actual points, the real work can begin.
First of all, it is necessary to decompose the problem : what are the actual transformations we will apply to this set of points ? 
Well, 2 rotations on two orthogonal axes (didn't really count the "roll" axis, for some reason). 

That might seem a little thin of a beginning ground, but I had exactly what I needed in my toolbelt to get this : Euler's angles, which I had covered in mechanics for engineering in preparatory school. 

The idea is to make a different frame for each rotation (let's call these angles alpha and beta), so we isolate everything that's happening in every rotation, and can project every frame in one another in an organized fashion. 

% eulers angle

One thing I would advise in cases like that is, regardless of your knowledge in mechanics or geometry, trying to isolate everything down its most basic operations and components. And then, trying to find your way using trigonometry and projections all around on every frame you encounter, and ALWAYS keep in mind the value you want to find so you don't get lost on the way. 

Anyway, in this case, we'll just project a random vector in the 3rd frame frame into the first frame, just like this : 

% latex vector expressions

Surprisingly, I got the calculations right first try, and ended up with the following expressions for the resulting vector :

% final vector 

## Flattening the ground

Remember, what we want in the end is to project everything on our screen, that is a helplessly flat and boringly 2D surface -real screens have curves, right ?
So what do we do ? Simple, just take the coordinates you want ! In this case, I want to project on the (0,x,z) plane, so I'll just express my vector as a linear combination of the two unit vectors corresponding to these axes. 

So, there, done ! We have a projection on a 2D plane that hopefully corresponds to the object we rotated, and everything. 
I then made a grid the size of the "window", of which each element is a "pixel" in my future drawing, and is 0 if nothing is drawn here, or the color of the point to be drawn if there is one.

This results in the following code : 

```php
function fillGrid2D($points3D, $alpha, $beta) {

	$grid = array();
	for($i = 0 ; $i < 36 ; $i++) $grid[] = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); // DISGUSTANG
	
	foreach($points3D as $point) {
		$x = $point[0]*cos($beta)*cos($alpha) - $point[1]*sin($alpha) + $point[2]*sin($beta)*cos($alpha);
		$z = -$point[0]*sin($beta) + $point[2]*cos($beta);
		$grid[(int)$x+18][(int)$z+18] = $point[3];
	}

	return $grid;
}

```

## A show of display

Because obviously, a two-dimensional array in the entrails of PHP is slightly hard to understand as a human, I feel like it doesn't quite qualify as a 3D engine yet.
For all the non math-savvy readers, the nightmare ends here, and it gets more technical again. 

```php
// Display the projected 2D grid
function displayGrid2D($grid2D) {
	$i = 0;
	$j = 0;

	for($i = 35 ; $i >= 0 ; $i--) {
		for($j = 0 ; $j < 36 ; $j++) {
			if($grid2D[$i][$j] == 0) printf("  ");
			else printf("\033[%dm  \033[49m",$grid2D[$i][$j]);
		}
		printf("\n");
	}
}
```

The idea here is pretty simple, but one problem arises, which is fairly well known by people dealing with the terminal often, especially to plot mathematical functions : a cell is twice as high as it is large. Which results in a drawing that's stretched apart in the vertical direction. Two solutions : either we mathematically contract the drawing on the vertical axis, either we define a pixel as a 2x1 rectangles square. I chose the latter, hence the double space in the program.

The idea, as I said, is pretty simple : go through every element of the grid, and color the text cell if there's something here -figured if we were going full pixellated, might as well not underdo it-, or just draw two black spaces if there's nothing, using printf.

One last thing to do remains : control the rotations applied to the set of points. 
I chose to go with a classic "architecture" that's basically the only answer to this kind of problems : an event loop.
However, toying with that, you'll soon realize the terminal, just like in C, is buffered : everything you type is stored inside a buffer sent to the program (bash, PHP...) only once the user presses ENTER. Which is kind of an unacceptable behavior if you ever want the slightest bit of interactivity. 

However, since the people who developped all this ecosystem are far from being dumb, there is a solution : the canonical mode. 
You want the terminal to switch to this mode instead of buffered mode, and there's a command doing just that -besides enabling any user to manage every setting of a tty : stty. 

So, really, it's down to a single line of code : 

```bash
system("stty -icanon");
```

Once this "contingency" is taken care of, we can move on to the event loop and the actual core of the program :

```php
$alpha = 65;
$beta = 55;

displayGrid2D(fillGrid2D($points, $alpha, $beta));

while($c = fread(STDIN,1)) { 
	if($c == 'q') $beta = ($beta + 5)%180;
	if($c == 'd') $beta = ($beta - 5)%180;
	if($c == 'z') $alpha = ($alpha + 5)%180;
	if($c == 's') $alpha = ($alpha - 5)%180;
	
	system("clear");
	printf("alpha = %d, beta = %d",$alpha, $beta);
	displayGrid2D(fillGrid2D($points, deg2rad($alpha), deg2rad($beta)));
}
```

Here, alpha is the azimuth angle, i.e. the horizontal orientation of the object, and beta is the elevation.
Every time the program goes through the loop, it listens for a new keypress, does the necessary adjustments on the angle variables, and proceeds to clean up the screen, and display the new object. 

## Conclusion

So this is it, we got this thing working and I can now rotate whatever phallic object my not-so-much-of-a-teenager-anymore mind dictates me in every direction. So we're done... Or are we ? 

Not quite. After thinking a bit about it (and since the project was a bit of a rushed not-so-serious piece of code), a few improvements came to mind : 
* Implementing the roll axis : I just didn't bother, but it should be done at some point ;
* Redo it in a decent, sane language that doesn't need you to literally stick your finger to the '$' key.

Moving on to real non-stupid improvents :
* Instead of using a grid as a virtual screen, which has the advantage of being easily understandable visually speaking, just do what I did with the 3D points : turn the grid into an array of 2D points with a color, so the loop doesn't have to go through N^2 iterations everytime ;
* The terminal has escape sequences for basically everything, as we could see when tinkering with colors. It also has ones to move the cursor. Using the aforementionned design, it becomes a real idea to just move the cursor around every time you need to draw something, instead of displaying an endless stream of spaces to plug the gaps. * 
* Use a rotation matrix : probably an idea some of you were internally shouting from the beginning. Although in terms of performance, assuming we're running a critically performance-bound application (so, not in PHP), matrix products are expensive as hell. In this case especially, we're not even using one of the three coordinates of the resulting vector, so part of the calculations are a net loss in terms of performance. Still simpler and cleaner to implement though. 
* Add perspective : as a friend suggested, simply use the y axis as a dilation / contraction factor applied to the other coordinates when displaying the point.

I guess now we're done. The full code is available in raw form [here](files/r3d.php).
