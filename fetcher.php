<?PHP
/*
* xStreamCycling
* Copyright (c) 2009 Chad Auld (opensourcepenguin.net)
* Licensed under the MIT license.
*/

include "./functions.php";

define("VLC_LOCATION", "/Applications/VLC.app/Contents/MacOS/vlc"); //Where is yours?
define("MASTER_DIRECTORY", dirname($_SERVER['SCRIPT_FILENAME'])); //Directory this file lives in
define("STREAM_OUTPUT_LOCATION", MASTER_DIRECTORY . "/streams/"); //Where should we output the saved stream?
define("CAPTURE_FILE", MASTER_DIRECTORY . "/captured.txt");
define("CONTACT_EMAIL", "your email here"); //Who to notify when a stream has started recording

//Attempt to find live cycling mms:// stream urls
$freeetvStreams = array();
$freeetvResponse = doCurl("http://pipes.yahoo.com/pipes/pipe.run?_id=a2a32c30c0e92ba5c5326afd2647f5cf&_render=php"); //http://pipes.yahoo.com/chadauld/live_cycling_streams

if (!empty($freeetvResponse)) {
    $freeetvLinkObjects = unserialize($freeetvResponse);
    for($i=0; $i<count($freeetvLinkObjects["value"]["items"]); $i++) {
        $freeetvLinks[] = $freeetvLinkObjects["value"]["items"][$i]["mms_link"][0]["href"];
    }
    
    //For each stream url we detect fire up a VLC instance to record it and send a notification
    if (!empty($freeetvLinks)) {
        foreach($freeetvLinks as $freeetvLink) {
            //Built a unqiue stream id
            $streamID = md5($freeetvLink);
            
            //Verify this isn't a stream we've already captured
            if (checkForDuplicate($streamID) !== true) {
                recordStreamID($streamID);
                
                //Assemble the right VLC command
                $vlcCommand = VLC_LOCATION . ' -I dummy --quiet "' . 
                                $freeetvLink . 
                                '" --sout=\'#transcode{vcodec=mp4v,vb=1024,acodec=vorb,ab=128}:standard{mux=ogg,dst=' . STREAM_OUTPUT_LOCATION .  $streamID . '.ogg,access=file}\' vlc://quit';
                
                mail(CONTACT_EMAIL, "New xStreamCycling Capture Started", "Stream is being captured to a file called " . $streamID . ".ogg");
                exec($vlcCommand);
            }
        }
    }
}
?>