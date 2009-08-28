Copyright (c) 2009 Chad Auld ([opensourcepenguin.net](http://opensourcepenguin.net))
Licensed under the MIT license
 
# xStreamCycling README #

## WHY ##
I'm a cyclist and a geek.  I want to watch more cycling events, but most events are either 
in Europe and/or are simply on at inconvenient times for me.  Also, thanks to Boxee and Plex 
I canceled cable long ago.  So I scripted up a little process that helps me identify and 
record live cycling streams as they happen.

xStreamCycling scans cyclingfans.com at some scheduled interval (you set with cron) to identify live 
cycling streams they have found on the web.  If that stream happens to be on FreeeTv (and it often is) 
then we proceed with trying to capture it for offline viewing at our convenience.  FreeeTv lacks a 
simple way to track down the time or type of broadcast for a channel (at least as far as I can see 
anyway).  So cyclingfans serves as our filter to the FreeeTv service.

I build it for my own needs, but though perhaps others may find it useful.

## HOW IT WORKS ##
1.  It uses a [Yahoo! Pipe](http://pipes.yahoo.com/pipes/pipe.edit?_id=a2a32c30c0e92ba5c5326afd2647f5cf) with some [Yahoo! Query Language (YQL)](http://developer.yahoo.com/yql/) magic 
    mixed to pickout the multimedia stream links.
2.  It silently fires up the [VLC Media Player](http://www.videolan.org/vlc/) and captures the multimedia 
    stream identified in step 1.
3.  It emails you when a new stream has been found and recording has started.

## INSTALLATION ##
1.  Verify you have PHP with cURL support
2.  Verify you have a recent version of VLC installed
3.  Update the fetcher.php config settings with your local information
4.  Schedule fetcher.php to run with a user that has access to read/write in the location you have 
    place the xStreamCycling source code (ex) 0,30 * * * * /usr/bin/php /path/to/xStreamCycling/fetcher.php
    
Note: Since the streams are live I schedule a check for new ones every 30 mins

## PLAYBACK ##
The output file is a .ogg which can easily be played with VLC.  Feel free to experiment with the VLC 
capture settings in the script to make the quality and/or output types meet your needs.