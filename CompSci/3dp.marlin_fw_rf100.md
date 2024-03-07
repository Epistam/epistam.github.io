# Renkforce RF100 : from Octoprint to Marlin customization... and some more things along the way
###### 27/02/2024

Sometimes, a seemingly simple project turns out to be deeper than originally bargained for. Be it additional complexities or simply opportunities for
improvements, discovery and endless technical distraction, personal projects have indeed this habit of eating up time in the most candid ways.

Imagine, for instace : frustrated by your lack of 3D printing capabilities, you would like to get that old printer to work... And yet, being in the
"business" of 3D printing for a while now (just not with your own stuff), 2016 standards just don't cut it anymore. A web interface, fan control, part
cooling, a decent printing surface... A few examples of the rather lackluster capabilities from that old lady. Surely, quite a few topics to tackle,
right ?

And yet, it's what we like.  So if you want to see how sheer stubbornness drives one to ignore any notion of cost-efficiency and time-saving,
buckle-in : we're in for a ride !

# A 3D printer from another time
The old lady in question is a Renkforce RF100 (first of its name) printer.  While it seems it an occidental export copy of a Chinese printer (the
WEISTEK Mini-Abox 3D Printer (WT560A)), it used to be sold along with its younger sisters by Conrad. I got it from a friend who wanted to move to an
Ender 3 a few years ago and... let's just say the price was attractive. Yet, as many of my projects, it ended up being used for a few months and
promptly forgotten in a corner of my room. Small printing volume, old and wet filament, other, better available printers and lackluster print quality
being some of the reasons.

Yet, a few years have passed, opportunities for easy printing aren't the same, and I figured I might actually put to good use this otherwise useless
occupied shelf volume.

PIC OF PRINTER

# Fan : cooling duct + control
One of the first major problems I encountered was *stringing*. Such a phenomenon consists in thin lines of filament bridging over unoccupied space,
usually as a result of nozzle oozing or unsufficient adhesion of the filament to its deposition surface (printing bed or previous layers).

In my case, it was probably a case of both : 
- the filament is *very* old and bubbles like it's carbonated water everytime it goes near the nozzle. This causes the nozzle to ooze filamenent
  everywhere and basically every time. I know what you're thinking : "won't using another filament help like, a lot ?". You're right. And I'm cheap.
  To each their own. But to wrongly quote a famous meme : "I paid for the whole reel ; I'm going to use the whole reel.".
- printer makers in 2016 apparently didn't think it was smart to have part cooling... At all. The printer ends up just having a fan for the controller
  and motor drivers, and an extruder fan.
We will mostly take interest in the latter.

IMAGE STRINGING

As with most things in the 3D printing community, people have encountered this problem already. And it bugged them so much, in fact, that they all
took to Thingiverse and the likes to design various parts to help with the problem. In this case, it means creating a [cooling
duct](https://www.thingiverse.com/thing:2413581) that partially redirects the flow of the extruder fan towards the extruder. Smart indeed.

IMAGE END RESULT ON PRINTER

# Controlling the fan
Upon installing that attachment, I actually had a different, kind of embarassing problem. It was cooling... but *too much*, so much that the extruder
wasn't even able to reach its instructed temperature. Now, the original interface allows one to modify the fan speed on a scale of 0 to 255... But
only when in the printing state.

I'll write here so it's settled : the original interface is crappy. Its riddled with knob accuracy issues, somewhat buggy and not intuitive at all.
And so, for a while, I ended up setting the fan speed to ~100 on every print. *EVERY PRINT*. Dozens of painful navigation sessions in the crappy menus
with that wretched reversed knob.

What if there was another way ? 

# Octoprint
That introduction was probably a bit of a giveaway to 3D-printing inclined people : it seemed to me Octoprint would help with a lot of problems.
For a start, it has intuitive controls accessible from a regular computer screen, allows one to upload their GCODE straight from their computer, and
can be tinkered with a bit to allow fan control. Again, from the warmth beneath the covers, in the confines of my bed. What's not to like ? 

Octoprint is an easy setup in which I won't delve much here, since the [official documentation](https://octoprint.org/download/) covers this pretty
nicely.

Now, I liked Octoprint, but it lacked the ability to adjust the part cooling fan (M106 / M107 in Gcode). But seing how elegant it is to implement, I
like it even more.  [Some people](https://community.octoprint.org/t/part-cooling-fan-speed-control/34015/3) have requested this feature for a while,
but adding fan control is actually quite simple : one just needs to edit the `~/.octoprint/config.yaml` and add a control section to it : 

```
controls:
- children:
  - command: M106 S%(speed)s
    input:
    - default: 255
      name: Speed (0-255)
      parameter: speed
    name: Enable Fan
    type: parametric_command
  - command: M107
    name: Disable Fan
    type: command
  layout: horizontal
  name: Fan
```

Piece of cake, and it seems to be standardized for just about any Gcode command. Yes, even that fancy RGB led strip for your timelapses... Well, if
the printer supports it anyway.

Does it work though ? Meh. 

# The end is never the end is never the end...

firmware doesn't support M106, figured might as well upgrade it and start tinkering with it

hardware problems : no transsitor wired blablbalbalblabla

how come the firmware actually controls the speed at least roughly ? no idea, i'll have to pull it apart to figure it out



# Updating the firmware
Commuity version

# Up... grading the firmware ?

[14:45, 2/27/2024] Roland Korg: Sometimes, a seemingly simple task turns into a significant adbentire, espdcizlly when talking about personal projects

Say, for exqmoel, that you wa'ted to install octoprint, but then fan, then firmware update and the'neditii'g
[14:48, 2/27/2024] Roland Korg: Do birke as heater
And blog for printer
[14:55, 2/27/2024] Roland Korg: Sometimes, a seemingly small project turns out to be a little bit more than we bargainednfornorigianllt
Imagine, for instance...

## Getting a grip on it
My adventure with Github Pages hasn't been a quiet river, at least in the
beginning. Okay, starting to look into it while doing other stuff at the same
time probably didn't help.  I've been working on it in 2 sessions, the first
one struggling to get stuff done because I wasn't fully focused, and the next
one at home, actually looking to achieve what I wanted to. 

I'll be quite succinct on the first steps as it's basically part of any Github Pages tutorial.
One just needs to understand what Markdown looks like, and how it works in concert with Jekyll to deliver well formated HTML pages.
And no, PHP is not a thing in GP, but I'll cover that later.
Creating a Github repository and selecting a theme (Midnight in my case, a dark background which my eyes liked very much) are the 2 main very first steps.




https://github.com/probonopd/RF100-Firmware

backup :
sudo avrdude -v -c stk500v2 -p m2560 -P /dev/ttyUSB0 -b 115200 -U flash:r:rf100_1.0.bin:i

flash new :
sudo avrdude -v -c stk500v2 -p m2560 -P /dev/ttyUSB0 -b 115200 -U flash:w:RF-100.ino.hex:i -D


firmware modification : RF-100/Configuration.h:315
#define HEATER_0_MINTEMP -1
#define HEATER_1_MINTEMP -1
#define HEATER_2_MINTEMP -1
#define HEATER_3_MINTEMP -1
#define HEATER_4_MINTEMP -1
#define BED_MINTEMP 0



other approach : mod thermistors_table_51.h (all thermistors id in thermistornames.h)


Configuration_adv.h:130
   2 /**
   1  * High Temperature Thermistor Support
132   *
   1  * Thermistors able to support high temperature tend to have a hard time getting
   2  * good readings at room and lower temperatures. This means HEATER_X_RAW_LO_TEMP
   3  * will probably be caught when the heating element first turns on during the
   4  * preheating process, which will trigger a min_temp_error as a safety measure
   5  * and force stop everything.
   6  * To circumvent this limitation, we allow for a preheat time (during which,
   7  * min_temp_error won't be triggered) and add a min_temp buffer to handle
   8  * aberrant readings.
   9  *
  10  * If you want to enable this feature for your hotend thermistor(s)
  11  * uncomment and set values > 0 in the constants below
  12  */
  13







extruder fan speed + sanity check





parler du plateau
