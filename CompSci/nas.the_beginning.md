# Homemade NAS series : the beginning
###### 07/09/2019

## Context 
After a bit of a hiatus here, I figured it would be time to actively fight
laziness, and get to work. Since I have a few projects on the way, I figured
now might as good a time as any. So here I am !

One thing that bothered me for years is that I have terabytes of data scattered
across my disks, both where I'm studying and at my parents'. Most of this data
is stored without any kind of redundancy or encryptipn. Paradoxically, data
loss seems like a horrible prospect to me (and to anyone who has experienced
it, really). 

More so, I dislike inefficient setups, and it really seems to me as though
having a SSD and a HDD on every PC is one of these cases.
Two reasons here : 
- Why not centralize mass storage ? Maybe use a HDD as a buffer if manipulating
  big datasets or files is needed, but a system "disk", on any system, even
  with games and such, would totally accommodate itself to, say, a 500GB SSD ; 
- In most consumer-grade HDDs, the bandwidth offered by the disk would probably
  be around 150-200 MB/s, in sequential R/W. In practice, this would fall to
  lower values around 100 MB/s. Well, it turns out the maximum theoritical
  bandwidth on an Gigabyte Ethernet link is around 125 MB/s. All in all, it
  sounds like the network bottlenecking, if it even exists, shouldn't be much
  of a concern, and probably doesn't justify the use of SATA over a centralized
  Networked Attached Storage. 

## The project and its requirements
For a year or two now, I've been eyeing on a project that, to me, would fix
these issues almost entirely : a centralized, redundant, encrypted NAS to store
and access my data on from any terminal.  Now, I have a few requirements to
satisfy : 
- I need to move : I'm going back to my parents' often, and that's a 3 hours
  train ride. I will also spend one semester abroad to study, and I intended to
  plan more of this. That means the NAS is going to have a low form factor, to
  basically fit in a handbag in a plane cabin ; 
- It will (obviously) need to fit all my data and offer a decent level or
  redundancy. The setup I've chosen goes as follows : 6x1 TB disks in RAID6,
  allowing in the end for 4 TB of usable space. One thing I like with this
  setup is that it allows, albeit at a significant cost, for an upgrade
  (basically switching all the disks to 2TB) ;
- It will need to accomodate poor power grid conditions and shutdown cleanly on
  prolonged brownouts or power loss. That all points to one thing : a UPS. The
  small form factor is both a curse and a blessing here. According to the
  datasheet, the disks I'm planning to use barely eat up 1.5W during read/write
  operations, which means the battery will be small enough fit the form factor
  without a problem. But since there aren't, to my knowledge, many UPSes, if at
  all, featuring a small form factor, this requirement will probably result in
  a home-cooked solution ;
- I want the data to be encrypted, and accessible over the network. Now, the
  usual options in terms of encryption involve the decryption of the volume
  locally, but that seems pretty idiotic to me since the NAS will always be
  running the volume constantly mounted in an accessible state. We'll see how I
  address this later ;
- The processing power will have to be up to the task, e.g. managing read/write
  operations on a soft RAID system and encryption, all the while providing a
  decent bandwidth ;
- The cooling system will have to be mostly passive, and in any case very
  silent. 

Now, let's get into a bit more in details.

## The hardware
Given the size of the NAS, I will have to use a SoC as the heart of the machine. This SoC will also have to comply with the following elements : 
- Provide enough processing power ;
- Allow a minimum of 6 disks.

Now, that last one is a bit of a problem, since most SoC chips usually provide a maximum of 4 SATA ports, and that also 

excludes usual suspects : RPI (altho, link to blog), banana pi, orange pi

Given the above requirements, 
SoC : odroid or armada
extension card pcie2.0 x4
ram ? 
disks !

## Design

cooling

2 parts ? 

case with foam or cloth

## Network accessibility and encryption

## Protecting oneself against electrical UPSies 

online / line interactive / offline 

proection against : 
overcurrent
undercurrent
autonomy
out TTL signal for power state
signal rectification


DATASHEET disks 
datasheets armada github + pdfs sur leur site

indexation inside image file

separate hearders

## Room for expansion
I also mentionned upgrades : to me, there are two ways to proceed.

The first one is somewhat sequential and probably takes a bit of time : replace a 1TB disk by a 2TB one, rebuild the volume on the new disk. Do this for every disk and then simply expand the RAID array and then the sparse file. 

The second one would probably be faster, since it doesn't imply calculations for every last bit of data available. It would, however, require the use of another computer with at least 6 SATA ports. The idea is to move the 1TB disks into the "auxiliary" machine, mount the RAID volume there (software RAID makes things considerably easier here), then put the new disks into the NAS, build a new array and simply copy the sparse file from one machine to the other. 

Now, I couldn't fail to mention the one drawback of this method : it leaves you with 6 used 1TB disks. Now, it all depends if you need this room or not. These could probably be recycled inside various machines, family, or even put into a new, smaller NAS, with or without RAID. 

## Moar redundancy 

offsite backups


[here](https://jekyllrb.com/tutorials/navigation/#scenario-1-basic-list)
