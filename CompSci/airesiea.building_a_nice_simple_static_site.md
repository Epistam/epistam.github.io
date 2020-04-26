# Building a nice, simple static website for Air ESIEA (WiP)
###### 26/04/2020

## Context and needs
As an ever-growing student association, Air ESIEA actually finds itself needing more and
more effort put towards communication, be it towards students or school staff, companies
and potential sponsors. Now, given the fact that there were only 3 actual members 2 years
ago, there have been some... corners cut. One of these being the lack of an actual
website. 

Now, most of us might be techies, however, most us also actually enjoy getting stuff done.
And by stuff, I mean "getting work done on projects", not spending days to setup and
maintain communication tools just for the sake of it.

Which brings me to the point : we need a website. Its main purposes would be :
- provide some basic introduction of the association and its members ; basically a
  portfolio
- provide access to older projects, association history, maybe a sponsors page...
- enable the members to share updates on their work with the world in an efficient and
  fast manner, without necessarily being CS inclined.

Obviously, I kept the best bit last : this last requirement will probably play the biggest
role in shaping what this website will be like, since it is what will differenciate it
from a simple pile of HTML files (which, one might argue, is the closest definition of a
good amount of teachers and professors websites...).

In particular, I really like the idea of writing articles, pages and such in Markdown and
have some kind of templating engine wrapping it up together into some kind of eye-candy.
Designing really isn't my cup of tea, and I'm usually glad to have that part taken care of
by someone or something else.

## A short state of the art
As we said before, a few options are on the table here :
- a website fully developed in-house, with more or less features, ranging to the
  aforementionned pile of HTML files to an actual website requiring a LAMP / LEMP stack to
  run properly
- a CMS-based website, making use of... well, CMSes, like Joomla, WP, Drupal, or a simpler
  Grav or Pico, maybe Anchor : this would allow for complex features and services, but
  also introduce a significant amount of setup time and complexity
- static site generators.

The latter actually features a good amount of options, ranging from Jekyll (which is
powered this very blog), to generators like Nikola... and Hugo. 

Hugo specifically retains my attention as a worthy alternative, since it looks
well-polished, mature, and is probably the most used static site generator in its domain.
It powers a good amount of personal blogs, but also bigger scale websites.

## Testing Hugo : [insert smart statement]
In a nutshell, Hugo is a website generator written in Go. I had no trouble installing it,
since it was already in my distro's repositories (btw I use Gentoo).
