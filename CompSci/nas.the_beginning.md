# Homemade NAS series : the beginning (WiP)
###### 07/09/2019

## Context 
After a bit of a hiatus here, I figured it would be time to actively fight
laziness, and get back to work. Since I have a few projects on the way, I
figured now might as good a time as any. So here I am !

One thing that bothered me for years is that I have terabytes of data scattered
across my disks, both where I'm studying and at my parents'. Most of this data
is stored without any kind of redundancy or encryption. Paradoxically, data
loss seems like a horrible prospect to me (and to anyone who has experienced
it, really). 

More so, I dislike inefficient setups, and it really seems to me as though
having a SSD and a HDD on every PC is one of these cases.
Two reasons here : 
- Why not centralize mass storage ? Maybe use a HDD as a buffer if manipulating
  big datasets or files is needed, but a system SSD, on any system, even
  with games and such, would totally accommodate itself to, say, a 500GB SSD ; 
- In most consumer-grade HDDs, the bandwidth offered by the disk would probably
  be around 150-200 MB/s, in sequential R/W. In practice, this would fall to
  lower values around 100 MB/s. Well, it turns out the maximum theoritical
  bandwidth on a Gigabyte Ethernet link is around 125 MB/s. All in all, it
  sounds like the network bottlenecking, if it even exists, shouldn't be much
  of a concern, and probably doesn't justify the use of SATA over a centralized
  Networked Attached Storage. 

## The project and its requirements
For a year or two now, I've been eyeing on a project that, to me, would fix
these issues almost entirely : a centralized, redundant, encrypted NAS to store
and access my data on from any terminal.  Now, I have a few requirements to
satisfy : 
- I need to move : I'm going back to my parents' often, and that's a 3 hours
  train ride. I will also spend one semester abroad to study, and I intend to
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
  all, featuring a small form factor, so this requirement will probably result
  in a home-cooked solution ;
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

Now, let's get into the actual details.

## The hardware
Given the size of the NAS, I will have to use a SoC as the heart of the
machine. This SoC will also have to comply with the following elements : 
- Provide enough processing power ;
- Allow a minimum of 6 disks.

Now, that last one is a bit of a problem, since most SoC chips usually provide a
maximum of 4 SATA ports, and that also excluses the usual suspects when talking
about DIY embedded projects : the Banana Pi, the Orange Pi, and the Raspberry Pi
(although...  some madmen tried and actually succeeded in salvaging a PCIe 2.0
interface, but I don't think I am nearly proficient enough to achieve 
[this](http://archive.is/OODbO) level of hacking).

I have identified two SoCs which I think might fit these requirements : 
- the Marvell Armada 38x series (datasheet [here](https://github.com/MarvellEmbeddedProcessors/main/wiki/Armada-38x))
- the infamous Odroid H2 (datasheet [here](https://wiki.odroid.com/odroid-h2/start#odroid-h2_schematic_and_full_intel_j4105_datasheets))

In all honesty, this choice is not done yet. I see two major upsides to the
second option : 
- It features a x86 CPU ;
- It probably is easier to acquire since Marvell sellers usually sell their
  products to NAS manufacturers rather than to students wanting to make a NAS on
  their own.

But the main factor is the presence of (vanilla) PCIe 2.0 x4 ports, which
allows the addition of a SATA extension card, thus fitting my "min. 6 disks"
requirements (most embedded SoCs don't feature more than 4 SATA ports). 

One factor that plays in favor of the Marvell chip, though, is power
consumption. 

ram ? 
disks !

## Design
Since one of the hard requirements of the build is its size, the design of the
case is quite an important prospect. 

cooling

2 parts ? 

case with foam or cloth

## Network accessibility and encryption : LUKS over NFS
As I said previously, encryption on a NAS is only effective if the user has to
actively decrypt the data each time it accesses it, which a regular LUKS volume
doesn't allow. 

However, I've found recently LUKS provides another feature in the form of
sparse files. The idea is to contain a whole volume into a regular file, which
can then be accessed locally or via Samba, NFS, or SSHFS. 

Then again, my choice isn't quite done right now, and the matter is especially
one of encryption. SSHFS provides encryption on top of the LUKS volume, but
makes use of FUSE, which causes significant latency and bottlenecking in the
handling of the file system (not to mention the encryption computational
overhead).

(https://www.usenix.org/system/files/conference/fast17/fast17-vangoor.pdf)

## Protecting oneself against electrical UPSies 
Of course, if we're talking about sensitive data, I think anybody even remotely
sane would care about the way the infrastructure is actually powered. In terms
of short term damage control, the main requirement is over-voltage and
over-current control, since both of those would, given enough power, cause
durable damage to the hardware, even potentially including the disks.

But there are also multiple other features I would like the UPS to possess : 
- A small form factor (yet again, refer to the Design part for the numbers) ;
- Over / under current / voltage protection ;
- Enough autonomy to live through a power outage, or at least to allow clean
  shutdown ;
- Power state signalling to the NAS, to allow the software side to act when
  shutdown is needed ;
- Signal rectification (mostly aimed to remote locations abroad and "dirty"
  electricity.



online / line interactive / offline 

DATASHEET disks 
datasheets armada github + pdfs sur leur site

indexation inside image file

separate hearders

## Room for expansion
I also mentionned upgrades : to me, there are two ways to proceed.

The first one is somewhat sequential and probably takes a bit of time : replace
a 1TB disk by a 2TB one, rebuild the volume on the new disk. Do this for every
disk and then simply expand the RAID array and then the sparse file. 

The second one would probably be faster, since it doesn't imply calculations for
every last bit of data available. It would, however, require the use of another
computer with at least 6 SATA ports. The idea is to move the 1TB disks into the
"auxiliary" machine, mount the RAID volume there (software RAID makes things
considerably easier here), then put the new disks into the NAS, build a new
array and simply copy the sparse file from one machine to the other. 

Now, I couldn't fail to mention the one drawback of this method : it leaves you
with 6 used 1TB disks. Now, it all depends if you need this room or not. These
could probably be recycled inside various machines, family, or even put into a
new, smaller NAS, with or without RAID. 

## Moar redundancy 
If you have worked with backups at some point, chances are you have heard about
the 3-2-1 rule. The gist of the idea is as follows ; 
- your data is supposed to be stored 3 in copies ;
- one being in production, and 2 others as backup ;
- and among these backups, have one of them stored offsite in case of disaster
  (say, your building getting leveled in a fire, for example).

Attentive readers will notice this rule is not quite respected : the failure
tolerance allowed by RAID acts as some kind of first backup, but there is no
offsite copy. Yet.

What I intend to do in the long term is to setup an archiving server, hosted by
a well known provider in a datacenter. 

Now there are two ways to proceed : the "trivial" one would consist in sending
the whole LUKS file at every backup. This would be inefficient, and would
totally disregard the existence of incremental backups. However, it would have
the upside of being secure from the beginning to the end.

The other plan is to use another LUKS volume on the server, and mount both the
production and the distant volume, and make an incremental backup from one to
the other. The problem here being that, since we intended not to decrypt the
data directly on the NAS, we need a client machine, possibly the one the user
usually uses (sic), to mount both volumes and make the incremental transfer.
Depending on the previous changes and bandwidth available, this operation could
potentially take time and be a bit of a hassle to the end user. Then again, no
choice made yet.

The option I plan to use is to use another LUKS file on the server, and 
offsite backups



$ NAS : should be cheap af to replace easily in case of breakdwon

