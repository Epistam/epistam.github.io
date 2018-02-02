# Gentooing from afar
###### 02/02/2018

## Who likes mainstream penguins anyway ?

As a Gentoo user, I am quite used to its peculiar -yet extraordinary- way to handle packages. 
Portage -Gentoo's package manager- is indeed quite remarkable in its "compile everything" mindset, which is not much in other distros (and there are probably good reasons for that).
Yes, you read it right : every package (well, not every single one as there's still a way to install popular ones from precompiled binaries) you install is compiled from source.
Adopting such a philosophy allows for more thorough optimisation and extensive configuration and customization. 
The performance edge is often barely noticable though, but I personnally feel that pushing optimisation and user freedom to the maximum gives a feeling that painlessly justifies the use of such a system. It also makes it easier for developers to ship and release code.  
But there are of course some drawbacks, the main one being that compilation is not an immediate process and it wholly depends on the hardware capabilities of a given machine, although technology tends to fill that gap pretty quickly. 
It is thus crucial to assess your needs before choosing your distro. A high end rig or a supercalculator wouldn't mind the extra time spent compiling every update ; a Single Board Computer, however, would spent hours and days to compile packages, which might not only damage it in the long term as 100% CPU usage tends to create some heat, but also damage its owner's mental health. 

Another aspect to consider is the pedagogic value of such a distribution. The wiki and the community in general tend to push newcomers to install Gentoo the old-fashioned way, that is configuring and compiling the kernel themselves.
In general, administrating a Gentoo box builds some kind of in depth knowledge of its architecture up, and learning how to make your way through it definitely is a boon to the process.
The wiki also regroups copious yet religiously organised amounts of information, and the distro's developement and packaging is methodic and efficient. 

## Gentoo on a server

According to what I've written earlier, servers look like a good fit for Gentoo... and they are. If you don't mind the little bit of extra administration required by the "hands in the hood" approach to kickstart the server according to your needs. 
On a regular basis though, it's nothing spectacular as one just has to look out for -well documented- update notes, just like every sysadmin would do.
Gentoo is indeed really stable and cautious about its developement and shipping process (extensively testing software before removing the "unstable" flag for a given architecture), meaning extensive changes in the core of the distro are very rare and well thought in both the engineering and updating process.
It is not rare for example to witness 1000 days uptime production servers.
Compile times are not a problem either, as you generally don't care if your server compiles during a whole night in a well AC'd and monitored datacenter. 

image

My area of concern today is, as you probably guessed it, one of these updates. As if it were to confirm what I just wrote about package management, GCC 6.4.0 has been recently marked stable on regular architectures, which in turn triggered a few changes regarding [user profiles](https://wiki.gentoo.org/wiki/Profile_(Portage)).
The change was however well documented and the switch is painless on a procedural standpoint. As for the speed, however, things are different. This update requires you to recompile all your packages with GCC 6.4.0, which, depending on your configuration, might take some time. 
My server, in this case, is not exactly a powerhouse, and the update promissed to take some time. As I am quite busy with studies during the day, I couldn't monitor the whole process on my PC as I would usually do. 

## The mighty Termux

Then came memories from a few days back. Memories of my former self astonished by the phone app I had just discovered. 
This app is Termux. It's basically a terminal "emulator" for Android. Sounds like nothing too crazy since Android runs on a Linux base, but it is really well integrated, apparently has a pretty big community and wiki, even though I didn't explore in much detail, and provides pretty intuitive keyboard emulation combinations (once you know them).
It also has a pretty nice package manager, which, fortunately, allows you to install OpenSSH. This is where the fun begins. 

Now that OpenSSH is installed, I could confortably connect to my server. But I couldn't simply launch the updates and follow the process from there : any mobile network timeout would immediately kick me out of my SSH session and half all progress, which can be pretty annoying when compiling a package such as GCC takes more than 1 hour.  
The solution comes from a great piece of software I've used in the past : GNU Screen. Quite simply, it allows you to create a virtual terminal which you can attach and detach at (un)will.
It then becomes really simple : 

	ssh user@server.net
	screen -S update_screen

And there you are, in your virtual terminal. From now, you can just type in your update command(s). You can then detach from it by pressing *Ctrl + A + D*, or just get disconnected because your mobile internet provider isn't the best out there at this point in time. If either of these happen, you can also just type the following to reattach once reconnected to your server : 

	screen -R update_screen

As you can see, it's really not rocket science.
Once I got this all setup, I could just keep my phone horizontally sitting on a table, screen opened, to follow the updates my server was undertaking.
Which, by the way, looks pretty classy : I never encompassed how good and futuristic looked a black terminal on a phone screen. 



	


