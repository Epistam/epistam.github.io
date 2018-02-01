# Marking it down
###### 01/02/2018

## Getting the grip on it
My adventure with Github Pages hasn't been a quiet river, at least in the beginning. Okay, starting to look into it while doing other stuff at the same time probably didn't help.
I've been working on it in 2 sessions, the first one struggling to get stuff done because I wasn't fully focused, and the next one at home, actually looking to achieve what I wanted to. 

I'll be quite succinct on the first steps as it's basically part of any Github Pages tutorial.
One just needs to understand what Markdown looks like, and how it works in concert with Jekyll to deliver well formated HTML pages.
And no, PHP is not a thing in GP, but I'll cover that later.
Creating a Github repository ; selecting a theme (midnight in my case, a dark background was necessary for my poor eyes). These are the 2 main first steps.

## The theme
So now you've got your site up and running, with a reserved domain and a dedicated GH repository to edit.
You're free to toy around a little bit, but you'll soon find yourself a bit limited... by the theme itself. 
Basically, the header was nice and all, but I wanted a menu because a single page blog is kind of awkward to browse. 
That's the part I kind of struggled on for a bit of time : finding a way to override that god damned theme. 
The keywords are pretty important here, as I ended up spending a bit of time on Google basically biting my tail at every research. 
Turns out this function is natively implemented in Github, and you just have to fall on the right [page](https://help.github.com/articles/customizing-css-and-html-in-your-jekyll-theme/).

In my case, I mainly wanted to tinker with the header to make a single level menu, as I didn't want to bother editing the CSS to make a second level. So each topic has its own index page which in turn lists all the article I manually sort every time I write and add them. And perhaps a few elements I didn't want such as the theme author credit. And also change the title, because the name of the repository is nothing too attractive. 

Turns out (if you read the link) it's implemented natively : you just have to find the Github *page-themes* repository, locale your theme and get the *_layouts/default.html*. This *page-themes/THEME* folder is basically the default structure of the site that is hidden to you when you only add files through the repository. But if you bring one of these files with the same path and name as in this default hierarchy, you'll just override it. As simple as that. If you read the file though, you will notice something doesn't quite looks like HTML or CSS, generally between braces on each side :

	<html lang="{{ site.lang | default: "en-US" }}">
	
Remember about my remark on PHP ? Well, there we go.

## Liquid
You guessed it, GP doesn't have PHP, but it has some kind of similar language, which is called Liquid. 
It basically allows you to format the text, make dynamic menus just like PHP would. I've not delved into too much unfortunately, but I'm pretty sure it's somewhat inherently more limited compared to PHP, which is in my opinion is justified : Liquid's purpose is not to be a fully blown web developpement language, but more a local programming tool to make that blog a little smarter. 
One of its most liked uses is to make dynamic menus from a YML file as shown [here](https://jekyllrb.com/tutorials/navigation/#scenario-1-basic-list) : the hierarchy is stored in that file, a script stored in the header parses this file everytime and generates the menu.
Speaking of headers, Liquid also allows you to include various files, so headers and footers are fine. 
But since they are generally the same for all the pages, a way to circumvent Liquid is to just edit the *default.html* file according to your wishes. 

## Conclusion
In the end, I'm pretty happy with GP since I didn't have to invest much (configuration and / or developpement) time into it to get it to work.
Markdown also looks quite efficient on a more regular basis : it allows for flexible and fast formatting without suffering the torment of HTML or LaTeX (the latter is pretty unrelated to web publishing, I'm not aware of any LaTeX / HTML wrapper so far).

This [page](https://guides.github.com/features/mastering-markdown/) and this [one](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet) might also be useful reads if you're looking to get into Markdown.
