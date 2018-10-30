# Installing Windows 7 on a Ryzen 2xxx System by USB
###### 11/10/2018


## Who likes mainstream penguins anyway ?


![Every package has to be approved for each architecture](https://epistam.github.io/IT/img/misc.gentooing_from_afar-1.PNG)


I never thought it would be that much of a chore. And yet, here we are. 

## An official support problem

Here is the "normal" USB installation process : you're supposed to "burn" the ISO on your USB stick, plug it in, boot on it, install, done. But, that would be a little too simple for an article here, would it ? 

Besides the fact W7 doesn't officially support Ryzen anymore, there has also been a major change in the way USB interfaces are handled. You see, W7 is getting a bit old, and is using EHCI (Enhanced Host Controller Interface) to control USB ports. Only at this time, there were only USB2.0 ports. But time has passed since, and USB3.0 was released. That wasn't much of a problem at first, since most USB3.0 motherboards also had USB2.0 ports and controllers, allowing a user to install W7 through USB2.0 ports, and afterwards install the correct drivers for USB3.0. And then, more differences piled onto it : Skylake Intel CPUs started to drop hardware support for EHCI, considering xHCI (eXtended Host Controller Interface, the USB3.0 controller) could be used for USB2.0, and therefore EHCI was redundant. But W7 images were still using EHCI, and any concept of xHCI was unknown to them, leading them to prompt a magnificent "missing driver" screen at the beginning of the install process. Those using a non-USB DVD tray or any other media were fine, since they just had to plug in a PS/2 keyboard to get the job done. Those installing through a USB stick, though, were in a very incomfortable situation. And I am one of them. Thus began the quest for an almighty way to install W7 on my brand new computer.

image prompt missing drivers

## Ideas and procedures

 After a bit of research, I figured I would have a few ways to get the job done : 
- The easy way : install W7 on a VM, then transfer the virtual VM disk to my physical drive
- The "normal" way : try to provide the installer with the necessary drivers to keep going. 

I chose the latter, as I wanted to have a genuine installed with whatever hardware specific configuration the installer provided (maybe I'm inventing things and being a bit too Gentoo-y here...)

To do this, there were a few ways available : using the various motherboard manufacturers patching utilities (my motherboard was from AsRock, and a patcher which was mentioned everywhere was the Gigabyte one), or trying to patch the image manually.

I tried AsRock's one, which (unfortunately) uses the original iso, patches it and burns it on the USB stick (apparently they didn't realize maybe someone who  encountered this problem probably had the USB already... ready. Unfortunately, that just didn't work, and I wasn't too eager to try again for good measure, since I had already roasted a USB stick by rewriting repeatedly its MBR... 

I then tried the Gigabyte tool, which is more intelligent in its way too operate. It locates the necessary files on the key, and patches them on the spot. But I ran into another problem : my USB key was only 8 GB large, and my image had all the possible Windows distributions (EN x86, EN x64, RU x86... etc). And the tool was patching every. One. Of. Them. Since the stick was nicely filled already, it would run out of space, and I therefore stopped the process before it ended. 

I then figured I would have to tinker with the image "manually" either way. In these install ISOs, the 2 important files are ./sources/boot.wim et ./sources/install.wim.
These are apparently a mix of compressed Windows images and offline registry hives I didn't quite get to its full extent. But the thing is Windows has a tool to deal with them "dism". Unfortunely (again), most of its features are not available on W7, but on W10. I then ended-up downloading a software called "dism++", which was providing not only a full version of dism, but also a graphical interface.

I then proceeded to make a new install.wim image, with just the distribution I need, which cut its size by a considerable amount. Then, after copying it back on the USB drive, I started to patching process, which also took a lot less time, but still failed (I still had the message at boot).

I was pretty surprised since the Gigabyte tool seemed to provide the most drivers... Besides, I was running out ideas. I had also tried manual installation by this point (with dism), but it also failed to incorporate a few driver files inside the .wim files, for reasons which, thanks to Microsoft's tendency to have VERY self-explanatory logs (/s), will always remain unclear to me.

## The easy way is sometimes the best... Or is it ? 

At this point, I was pretty much out of options, and decided to go for the VM installation process.
The idea was to : 
1. Set up a VM on another computer
2. Install W7 on it 
3. Convert the virtual drive to an image file
4. Boot on a Linux live USB, with the image file attached on an other USB drive 
5. Write this image file directly to the SSD
6. Cross fingers
7. Reboot

And that worked... Surprisingly well actually. Except for the part where I... You know, ended up pressing the TAB key on a PS/2 keyboard for a dozen of hours.
Obviously, getting the system on the disk didn't solve the USB drivers problem. I had merely moved the problem to a slightly more favorable situation : one in which I could run installers. 

## The hunt for drivers

At this point, the system was working somewhat correctly, except for three major problems :
- No USB (2.0 or 3.0) support on any panel, which limited my use of the computer to a single keyboard connected in PS/2
- No Ethernet : I needed to install Realtek drivers to be able to download other drivers without going through the hassle of the process I'll describe in a minute
- Absolutely horrendous graphics : my 24" display had gigantic black bars, and the now diminished screen was diplayed in a resolution somewhere in between 1024x768 and 640x480. Which is, then again, pretty normal, Windows doesn't invent NVIDIA drivers out of thin air. 

To install these, I had to go under a pretty heavy process everytime : 
1. Download the driver on my other computer
2. Move it to a random USB stick
3. Boot on the LiveUSB
4. Mount the Windows partition, move the driver from the stick to Windows
5. Reboot, install

Needless to say, I did that once, to install the Realtek drivers. Well, continuing to install drivers was still a pain, since between inaccessible-by-keyboard GDPR notices and overlays, ads, etc, web pages were very obnoxious to browse, especially with such a low resolution. 

## The panacea

One thing I do like with NVIDIA and Intel, is that their way to organize drivers is mostly impeccable. You just type "Intel Chipset drivers" and end up on the right page, downloading the right thing. But with AMD... I mean, why the well would you name your "AMD B450 chipset driver" "Crimson ReLive


TL;DR
If you have the technicaly knowledge to use a VM, just go for it instead of using patcher whose reliability is yet to prove among a great number of configurations. 
It might take a while to set it up, but it is still shorter than trying the others and failing. 
