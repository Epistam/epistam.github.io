# Designing a reliable flight computer (WiP)
###### 26/02/2020

## Context
Astute followers may have noticed I have not been publishing a lot over the course of the
last year. Quite a few things kept me busy during this time, one of which discovering Air
ESIEA, my school's aerospace student association, where I found myself surrounded by
interesting tech savvy people : basically, hackers in a hackerspace. 

Being quite enthralled by the whole experience, I have decided to invest myself in it
quite a bit, and ended up gradually taking responsability, taking over the roles of
secretary-general, vice-president and soon president in a few weeks.  During this time, I
have seen and worked on a few projects. 

This year's biggest project (which I also happen to lead) was named "Janus". Basically, it
is a two-stage ~2m long experimental rocket, which we will hopefully launch at the end of
July, during the C'Space international launch campaign, organized for the CNES (basically
the French agency for space research).

This article will mostly focus on the physical / general electronics design of the flight
computer(s).

## The flight computer
Naturally, a project this size embeds quite a few subsystems... namely : 
- telemetry
- IMUs / real-time trajectography
- GPS for recovery
- GSM communication to transmit the GSM position data
- ... and more 

This commands the use of multiple boards, both to make the programming easier and to have
a physical segregation between subsystems (which increases the whole system's resilience
and makes the cabling and operation easier). However, this results in a whole lot of
boards in both the upper and lower stage. Thus, we have decided to make actual electronics
racks, so that the boards are neatly set up, and easily maintanable during both the
development phase and while on the launch-pad. 

## A central bus
Seing we had adopted a physical architecture based on racks, I figured we might as well
use a main bus for each rack, so as to make future expansion easier (this is actually a
two-year project where more experiments are supposed to be included after the first
launch).

architecture image

Now, that prompts what question : what bus technology should we use ? 

### Analogic bus
This is the first option I thought about : it's basically about having a faisceau de
câbles blablablabla

### I2C

### SPI
