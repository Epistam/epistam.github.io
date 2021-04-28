# ffmpeg over SSH : low-tech "distcc", but for encoding
###### 21/04/2021

## Context
One of the first boards I ever got my hands on was a Banana Pi. Funnily
enough, that coincided with my departure towards my very own flat, and the
absence of a proper sound system for my music listening needs. I however had in
my possession an old keyboard which I used to try and become a slightly less terrible
piano player™.

While it was coming straight from the 80s, that keyboard actually featured
decent connectivity, among which :
- stereo jacks both IN and OUT
- a MIDI connector

At the time, that was all begging for two projects : 
- first, a synthesizer (which I'll write on, one day, maybe)
- second, a media server !

Because what better cure to laziness than being able to start your music at the
other side of the room from your very own laptop when you're working (or doing
just about anything else, anyway).

Now, there are multiple ways to get music to feed your media server, and one of
these is to snatch the music straight from YouTube. You might have heard of an
extraordinary tool for this sort of use case : `youtube-dl`.

Obviously, given that this is a purely audio sort of thing, we want audio files
in order to optimize the space taken by our library.

Fortunately, `youtube-dl` has our back ; as stated by its manual :
```
Post-processing Options:
   -x, --extract-audio
		  Convert video files to audio-only files (requires ffmpeg or avconv and ffprobe or avprobe)

   --audio-format FORMAT
		  Specify audio format: "best", "aac", "flac", "mp3", "m4a", "opus", "vorbis", or "wav"; "best" by default; No effect without -x
```

This line, wrapped in a bash script, is what I used for quite a few years : 
```
youtube-dl -xv --audio-format mp3 $vid -o '%(id)s-%(title)s.%(ext)s' -f bestaudio --user-agent Chrome/72.0.3626.9`
```

## FASTER. MASTER !
Now, we have to remember the kind of hardware we're running this on. The
embedded conditions mean low processing power, and `ffmpeg` isn't exactly
lightweight when it comes to consuming it. In concrete terms, that low
processing power translates into painstakingly slow transcoding after the
download part. 

Being that "Gentoo guy", this reminds me of something. As you may or may not
know, installing packages in Gentoo means compiling them, which is quite a
ressource-intensive task too. In their infinite wisdom, the kind developers
around the whole C ecosystem have developed and made available `distcc`. As its
name suggests, it aims to distribute the compilation load over multiple remote
hosts over the network.

When it comes to encoding audio / video files, I do have multiple other
machines on my network better suited for this load than a Banana Pi. But how
can one distribute this load ? 

## Distributing the load : how it works
As usual, I intended to develop a low-tech elegant-ish solution. My first
thought was using SSH and pipes : given the Gigabit throughput, bandwidth is of
no concern and will result in almost no delay in the reencoding process.

My idea of an elegant "bash-y" process consists in sending the file through
`stdin` to the remote host, encoding it there and bringing in back through
`stdout`. Though this is technically possible, the limitation lies in `ffmpeg`
and its ability to process AAC files. As explained
[here](https://archive.is/Szori), the AAC format requires seeking throughout
the file, which simply isn't compatible with the sequential nature of Linux
pipes.

This resulted in a significantly less elegant solution : 
- upload the file to be encoded through SSH
- encode remotely and write locally `ffmpeg` standard output locally in the
  resulting file
- remove the temporary file created earlier on the remote host

This solution seemingly involves using three different SSH connections, which
seems impractical at best. With a bit of careful tinkering, it is possible to
overcome this difficulty, but I'll first show what that would all look like
with three separate commands, for understandability's sake.

## The script(s) themselves
My solution is actually made of two separate scripts, then again for
readability's sake. The first one looks like this :
```
	#! /bin/sh
	if [ $# -lt 1 ]; then
			echo "ddl.sh - Downloads, converts on a distant machine and plays a (music) video from YT"
			echo "sh ddl.sh [video URL]"
			exit 1
	fi

	ydl_path="/mnt/music/youtube-dl/youtube-dl"
	vid="$1"
	mp3ext=".mp3"
	id="$($ydl_path --get-id $vid)"
	title="$($ydl_path --get-title $vid)"
	prefix_b64=$(echo $id-$title | base64)
	filename="$id-$title$mp3ext"

	if [ ! -f "$filename" ]
	then
		echo "Doesn't exist, downloading and converting..."
		# Do not use -x --audio-format mp3
		$ydl_path -v $vid -o '%(id)s-%(title)s.%(ext)s' -f bestaudio --user-agent Chrome/72.0.3626.96

		# globbing like "$id".* when the rest of the filename has spaces is a pain
		# will fail if grep matches multiple files (good)
		mv "$(\ls | grep $id)" $prefix_b64
		
		sh ffmpeg_distribute.sh $prefix_b64
		rm $prefix_b64
		mv "$prefix_b64$mp3ext" "$filename"
		
	fi

	sh play.sh "$filename"
```

This simple script just allows us to download our video file with a predictable
file name and then calls the `ffmpeg_distribute.sh` script -which is where the
magic happens. One might notice the filename is converted to base64 by this
script : this additional "wrapping" step is to ensure no trouble happens when
the SSH connection(s) because of special characters or spaces.

```
	#!/bin/bash
	if [ $# -lt 1 ]; then
			echo "sh ffmpeg_distribute.sh [file_prefix]"
			exit 1
	fi

	remote=username@host
	remote_path=/tmp/ffmpeg_test

```

Now, for the actual remote encoding :
- uploading the file : `scp $1 $remote:$remote_path/`
- re-encode and recover the reencoded file through stdout : `ssh $remote ffmpeg -i $remote_path/$1 -f mp3 pipe:1 | cat > "$1".mp3`
- remove the temporary file on remote : `ssh $remote rm $remote_path/$1`

## Simplicity (but it actually looks more complex, sorry Dr. Lecter)
As you may notice, the previous solution creates a bit of a hassle : we need to
reconnect three times, so that means entering three times our key password...
Surely, there must be a way to do that with only one SSH connection, is there ?

Of course there is, we just need to figure out a way write the temporary file
through SSH itself, and we can take of the rest with the `&&` bash operator.
First, writing the temporary file : `cat`. According to its `man` entry :
```
NAME
       cat - concatenate files and print on the standard output

SYNOPSIS
       cat [OPTION]... [FILE]...

DESCRIPTION
       Concatenate FILE(s) to standard output.

       With no FILE, or when FILE is -, read standard input.
```
So... is it that we can send SSH our file through `stdin` and "get it back on
the other side" ? Of course : `cat $1 | ssh $remote "cat - > $remote_path/$1"`
does the trick : cat's output is just redirected towards a "hard copy" of the
file.

What about the rest ? Since we intend to catch the encoded file through SSH's
output, we need to make sure `ffmpeg` will be the sole command writing to
`stdout`. That is indeed the case : writing to a file creates no output, and
neither does deleting one (except in case of error, but that goes to... you
guessed it, `stderr`.

So there it is, our lovingly crafted and slightly overengineered command :
```
cat $1 | ssh $remote "cat - > $remote_path/$1 && ffmpeg -i $remote_path/$1 -f mp3 pipe:1 && rm $remote_path/$1" | cat > "$1".mp3
```

So there is our `ffmpeg_distribute.sh` script, in all its glory : 

```
	#!/bin/bash
	if [ $# -lt 1 ]; then
			echo "sh ffmpeg_distribute.sh [file_prefix]"
			exit 1
	fi

	# Wagner
	remote=username@host
	remote_path=/tmp/ffmpeg_test

	cat $1 | ssh $remote "cat - > $remote_path/$1 && ffmpeg -i $remote_path/$1 -f mp3 pipe:1 && rm $remote_path/$1" | cat > "$1".mp3

```

Job done ! This shall now be my solution for downloading and reencoding audio
straight from YouTube. Well, at least until I find another clear or faster way
to do so.
