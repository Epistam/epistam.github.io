# Building a nice, simple static website for Air ESIEA
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

## Testing and tinkering with Hugo
In a nutshell, Hugo is a website generator written in Go. I had no trouble installing it,
since it was already in my distro's repositories (btw I use Gentoo). Now, my intention was
to actually tinker a bit with it a bit, and to figure out how things work.
[This page](https://gohugo.io/getting-started/quick-start/) will be useful as a first
reference guide.

Another note : I am trying to run this directly on a test server that is already running
Nginx. So I'll be skipping the "development" part in most guides, where they use Hugo's
own webserver to set things up. This will even cause some confusion afterwards, because
apparently most guides don't cover the "putting the website in production" part. 

So first of all : I needed to create a new site. Nothing really complicated so far, and
the syntax is pretty straightforward :
```
hugo new site [site_name]
```
This creates a new folder with all the basic contents needed to run a Hugo website. Now,
Hugo actually needs to use a theme to work : that's the part that turns your simple
Markdown files into a website that's bearable to the non IT-person eye. [This
page](https://themes.gohugo.io/) features what I believe to be an exhaustive list of Hugo
themes, which you are free to browse. There are even demo sites which you can explore to
find what you need. 

Personally, I chose "airspace-hugo" : it seemed like a nice looking theme for what would
become both a "website showcase" and a "blog"-type website. So, still following that
"quick-start guide" we found earlier, we can install the theme by cloning the related
repository at the right place, either "manually", or by creating a Git repository on the
website root and then adding the theme as a submodule (the former is straightforward, the
second is detailed in the guide). 

Now, for the part that is not necessarily obvious, but that is unfortunately not detailed
neither in the guide, nor in all theme repository READMEs : most "advanced" themes need
you to use their "exampleSite" template. So assuming you are at the website root, that
would look like :
```
cp -R themes/[theme_name]/exampleSite/* .
```

This is because these "advanced" themes actually have specific options in the main
configuration file, and even additional configuration that need to be created ; without
them, the theme won't ever load properly.

Now, a thing or two about how Hugo works more specifically : 
- most posts will be written in the `contents` directory in Markdown
- the static site will be generated in the `public` directory, so that is the one we will
  serve (that will prove important on the next step)
- more tricky : the `data` directory contains multiple YAML files (at least in my theme).
  These will be used to configure more specifically the specific pages built-in into the
  theme. We will come back to this more in detail in the "First impressions" section.
- even more tricky : `themes/[theme_name/assets` contains various CSS files, and this is
  the only way to edit the theme CSS and have it integrated into Hugo's site generation
  process. Again, we will come back to this later.
- the main configuration file for the whole site is `config.toml` (TOML is a strain of
  YAML that was engineered to be simpler and lighter in terms of its syntax).

Before running the default site for the first time, there is one thing left to do : edit
`config.toml` so that the base URL is the good one. Otherwise, every asset needed by the
site and hosted locally will fail to load and we will end up with a steaming pile of
garbage.

Depending on how the Web server was configured, this can be done a number of ways. In
this case, because I was experimenting with different themes, I decided to set the server
root on `/var/www/subdomain.domain_name/]`. In a production environment however, one might
be inclined to set the said root to `/var/www/subdomain.domain_name/public/`, since this
is where the generated static site will be located in the end.

Either way, this means the corresponding config line will have to look like that :
```
# Note the trailing '/'. Don't forget it, or the app will start looking for nonsensical
# URLs like http://domain.tld/path_to_public_dirimages/image.jpg
baseURL = "http://subdomain.domain_name/path_to_public_dir/"
```

On this is done, hopefully we can generate our very new website, by simply typing `hugo`
at the website root.

So, to sum it up, here is what the deployment steps look like :

1. Create the new site with `hugo new site [site_name] && cd [site_name]`
2. Install the theme by cloning it in `./themes/`
3. Copy the theme exampleSite to the current site root
4. Edit `config.toml` and set the correct base URL
5. Run `hugo` to generate the static website... and voilà !

## First impressions
On thing one needs to understand about Hugo themes, is that in the case of a "showcase
website", theme flexility depends entirely on the mindset of its developers. In terms of
structure, this theme is actually separated in two parts : 
- the blog part - straightforward, create the Markdown posts in `contents`, it just works
- the "showcase" part. Now here's the deal : every page that is not part of the blog is
  specifically engineered to look good. That means every type of page (About, Princing,
  Service, Homepage) has its specific YAML file to configure what sections will be in
  these pages.

Also, we will use [this website](https://themes.gohugo.io/theme/airspace-hugo/) as an
example for the following rambling.

On paper, this looks pretty nice overall : we have a pre-designed showcase website we
can arrange using simple YAML files instead of tinkering with the HTML/CSS part. In
practice, depending on how it's implemented, there can be a few restrictions.

Case in point : the homepage has an `about` block as follows :
```yaml
############################# About #################################
about:
  enable : true
  title : "ABOUT US"
  description : "sample text"
  content : "sample text 2"
  button:
    enable : true
    label : "Cool button"
    link : "about"
  image : "images/wrapper-img.png"
```

I like this block. It has an image on the right. I'd like to reuse that on the "About",
page. Tough luck, I can't : there is already another `about` block on the other page, and
it doesn't work the same way. It doesn't have the same YAML variables and displays
something else entirely (image is on the right for example).

Well, that's just an example, and this is case there is a name conflict. But most blocks
existing in a specific page simply don't exist in others : you can copy a block
from the homepage config file and paste it to the about page ; nothing will appear,
because this page simply doesn't know how to interpret it, it doesn't know it. In my
opinion, this really is wasted potential, because there was an opportunity here to make a
fully modular website, where as now we can just select pages and their block combinations,
and picking on that suits the best to our needs, without being able to re-adapt it exactly
how we want to, even though the tools exist on other pages.

Overall, it also seems themes in general don't have some sort of common framework /
similar practices in terms of how they are stuctured and how options are made available to
the user. This makes each theme heavily reliant on its own documentation, and customizing
different themes a specific experience everytime.

## Customizing a bit deeper
Unfortunately, it gets worse before getting better. Let's say you have a specific color
scheme in mind when making the website, and the default one just doesn't cut it. Some
themes allow you to configure a "base color" in the global config file. This one doesn't.

This means I have to locate the css files in the theme, directly in
`[theme_folder]/assets` as I wrote before and replace manually every color code in CSS
files. Thankfully, Linux tools come in handy : we can use find to... find the affected
files easily : 
```
find . -type f -print0 | xargs -0 grep -l "#655e7a"
```

This command will find every file that contains this specific string, so you can figure
out which CSS you need to edit in order to change a specific color. 

## Still a powerful tool
Yet, despite encountering these specific problems, one must reckon : Hugo is indeed a
really powerful tool, and I'm just scraping the surface here.

A good amount of options in `config.toml` are handled by Hugo itself, and are detailed in
the [documentation](https://gohugo.io/categories/content-management). Among these is
language handling.

Actually language management in Hugo is pretty impressive : one can run a site supporting
multiple languages without a problem. In the configuration file, it originally looks like
this : 
```
disableLanguages = []
# ...
################################ English Language ########################
[Languages.en]
languageName = "En"
languageCode = "en-us"
contentDir = "content/english"
weight = 1
home = "Home"
# copyright
copyright = "Copyright &copy; 2019 [Themefisher](https://themefisher.com) All Rights Reserved"

################################ France Language ########################
[Languages.fr]
languageName = "Fr"
languageCode = "fr-fr"
contentDir = "content/french"
weight = 2
home = "Accueil"
# copyright
copyright = "Copyright &copy; 2019 [Themefisher](https://themefisher.com) All Rights Reserved"
```

This is pretty self-explanatory, however I was looking to disable English altogether,
since this website in particular is targetting a purely French-speaking audience.
Naturally, my first thought is to edit the first line : 
```
disableLanguages = ["en"]
```

However, when building the site, Hugo complains that I can't disable the "main language".
Is Hugo so English-centered I can't even disable it ? Well, after a bit of
[RTFMing](https://gohugo.io/content-management/multilingual/), it turns out Hugo already
has an option to change to default languages : 
```
DefaultContentLanguage = "fr"
```

After rebuilding, it seems to work just fine ! Suddenly, I feel like Jeanne d'Arc, having
kicked the brits out of my website !

Jokes aside, Hugo seems to handle multilingual websites very well, and many options to
handle that.

Then again, one thing I think is a bit unfortunate : it's a bit of a shame the
configuration file is so barebones. The usual practice is to have a somewhat comprehensive
file, with most options commented, but a bit of an explanation on each one, and the
possibility to uncomment them with a flick of a finger on the "x" key (assuming we are
using vim...).

## Conclusion
This article is starting to be pretty long, so I will probably make another one focusing
more on how I intend to structure the site, considering the uses / goals I mentioned at
the beginning, and maybe deal with the stragglers when it comes to Hugo structure /
technical details.

For now, I will just say Hugo, despite some shortcomings, seems like a solid solution for
personal blogging based on Markdown, and even for making more fancy websites, provided one
is willing to invest a bit of time to get accustomed to its inner workings. 

It is fast (less than 1s to generate a static site from scratch), has a wide userbase, and
doesn't seem too structurally bloated. 

Sounds good to me.
