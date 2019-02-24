# Responsive design on Github Pages
###### 03/02/2018

## A neccessary doctrine
As my non-existent viewer base has probably noticed, this blog and all its pages are now accessible on mobile with a nice new shiny menu.
But all of this is the result of a few hours of article reading and CSS tinkering. 

During my few years out of the IT world, I missed a fundamental paradigm change in web developpement. When I left, HTML5 was a new and pretty unpopular standard, at least compared to the good old rock solid XHTML Strict and consorts.
The massive invasion of the mobile platform, has changed it all. HTML5 is now a pillar of Webdev as what seemed new and unreliable before is nothing compared to the ever-changing ecosystem of mobile browsers and standards. 
One simply cannot, being either a huge company or a small casual developper, the mobile audience. 

## GP Themes
Fortunately, Github Pages theme developpers are most of the time professionnal developpers who are not as ignorant as I am on the topic, and thus make quality content.
The theme I'm using, Midnight, was already responsive... mostly working as I intended it to. 
This theme features a header which, on the example configurations is used to link a Github pages. I figured I'd use it as a menu, but the developper apparently didn't consider this possibility. 
I can't really blame him, though, as I don't even know if it is intended or not and the theme in itself is flawless otherwise. 
But the fact of the matter is, the header simply vanished once the window was less than 480 pixels wide. 
If this header is going to be a menu, I'd rather have it displayed on every window size. 

## Overriding (once again) the theme
Obviously, the only solution was to indeed override the existing theme (in this case the CSS). 
The procedure is mentionned in the page I linked in my first article on Github Pages. 

What now ? 
Well, we first need to understand the code we want to override before overriding it. In my case, the end of the CSS file looks like this : 

	@media print, screen and (max-width: 480px) {

	  #header {
		margin-top: -20px;
	  }

	  section {
		margin-top: 40px;
	  }
	  nav {
		display: none;
	  }
	}

This brings us to one of the main tools of responsive design : media queries. This part basically acts as a conditional statement in CSS, determining whether or not we need to apply the "mobile style".
There is no way, however, to directly identify a mobile client, and the CSS thus relies on screen width to adapt accordingly.
As we can see, the "mobile style" as the developper thought it in the beginning consists in removing the "nav" tag from view and shifting the "menu background" up. 
The final step is to redefine the top margin of the "title + subtitle" block. As a side note, the use of the "nav" tag hints that, perhaps the developper indeed thought of this bar as an actual menu.
Either way, we want to get rid of that, so we will just copy / past this block into our override CSS file and set the modified values to what they are defined to originally, between this bloc in the default file.

But we can't simply let the code like that, as having full length colliding buttons would look amazingly stupid on a phone. 
The solution I found is to use this "double design" principle more in depth : for each button, I inserted images right next to the text and surrounded the latter with *<span></span>* tags.
The idea is to use the *display:* property to react accordingly to each case.
I also wrote various size and alignment modifications to the buttons, and centered the menu in both cases. 

The result can be seen [here](https://github.com/Epistam/epistam.github.io/blob/master/assets/css/style.scss).
