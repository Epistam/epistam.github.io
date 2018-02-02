# Gentooing from afar
###### 02/02/2018

## Who likes mainstream penguins anyway ?

As a Gentoo user, I should be quite used to its peculiar -yet extraordinary- way to handle packages. 

Portage -Gentoo's package manager- is indeed quite remarkable in its "compile everything" philosophy, which is not much in other distros (and there are probably good reasons for that).
Yes, you read it right : every package (well, not every as there's still a way to install popular precompiled binaries) you install is compiled from source.
Adopting such an methodoly allows for more thorough optimisation and extensive configuration and customisation. 
The performance edge is often minimal, but I personnally feel that pushed out optimisation combined to the "free hands" feeling such a system gives you justifies its use in itself. 
But there are of course some drawbacks, the main one being that compilation is not an immediate process and it wholy depends on the hardware capabilities of a given machine, although technology tends to fill that gap pretty quickly. 
It is thus crucial to assess your needs before choosing your distro. A high end right or a supercalculator wouldn't mind the extra time spent compiling every update ; a Single Board Computer, though, would spent hours and days to compile packages, which might not only damage it in the long term as 100% CPU usage tends to create some heat, but also damage its owner's mental health. 

Another aspect to consider is the pedagogic value of such a distribution. The wiki and the community in general tend to push newcomers to install Gentoo the old-fashioned way, that is configuring and compiling the kernel themselves.
In general, administrating a Linux box efficiently requires some kind of in depth knowledge of its architecture, and learning how to make your way through Gentoo is a significant contribution.
The wiki also regroups copious yet religiously organised amounts of information, and the distro's developement and packaging is methodic and efficient, preventing most of malfunctions, especially during updates.

## Gentoo on a server

According to what I've written earlier, servers look like a good fit for Gentoo... and they are. If you don't mind the little bit of extra administration required by the "hands in the hood" approach. 
It really is nothing spectacular though as one just has to pay attention once in a while to instructions on update notes. 
Gentoo is indeed really stable and cautious in terms of developement and global workings (extensively testing software before removing the "unstable" flag for a given architecture), meaning extensive changes in the core of the distro are very rare and very well documented.
It is not rare for example to witness 1000 days uptime production servers.
Also, you don't care if your server compiles during a whole night.

image

My area of concern today is, as you probably guessed it, one of these updates. As a matter of fact towards what I just wrote, GCC 6.4.0 has been recently marked stable on regular architectures, which in turn triggered a few changes regarding [user profiles](https://wiki.gentoo.org/wiki/Profile_(Portage)).
The change was however well documented and the switch is painless. As for the speed, however, things are different. This update requires you to recompile all your packages with GCC 6.4.0, which, depending on your configuration, might take some time. 
My server, in this case, is indeed not really powerful, this promissed to take some time. As I am quite busy with studies during the day, I couldn't monitor the whole process with on my PC as I would usually do. 
What solution could I find ? 

## The mighty Termux

Then came memories from a few days back. Memories of my former self astonished by the phone application I had just discovered. 
This app is Termux. It's basically a terminal "emulator" for Android. Sounds like nothing too crazy since Android runs on a Linux base, but it is really well integrated, apparently has a pretty big community and wiki, even though I didn't check in detail, and provides pretty intuitive keyboard emulation combinations.
It also has a pretty nice package manager, which, fortunately, allows you to install Openssh. This is where the fun begins. 

Now Openssh is installed, I could confortably connect to my server. But I couldn't simply launch the updates and follow them from there : any mobile network timeout would immediately stop all progress, which can be pretty annoying when compiling a package such as GCC which takes more than an hour to compile. 
The solution comes from a free software I've used in the past : GNU Screen. Quite simply, it allows you to create a virtual terminal which you can attach and detach at (un)will.
It then becomes really simple : 

	ssh user@server.net
	screen -S update_screen

And there you are, in your virtual terminal. From now, you can just type in your update command. You can then detach from it by pressing *Ctrl + A + D*, or just get disconnected because your mobile internet provider isn't the best out there. You can also just type the following to reattach when reconnected to your server : 

	screen -R update_screen

As you can see, nothing really rocket sciencey. 
I just had to keep my phone sitting on the table, screen opened, to follow the updates my server was undertaking.
Which, by the way, looks pretty classy. I never encompassed how good looked a black terminal on a phone screen. 



	


