<?PHP
/*
* xStreamCycling
* Copyright (c) 2009 Chad Auld (opensourcepenguin.net)
* Licensed under the MIT license.
*/

/**
* Use cURL to fetch some data and return the response
*/
function doCurl($url) {
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //Call URL and fetch response
    $pipesResponse = curl_exec($ch);

    //Close cURL resource, and free up system resources
    curl_close($ch);
    
    return $pipesResponse;
}

/**
* Check to see if we've recorded this stream before to help prevent duplicates
*/
function checkForDuplicate($streamID) {
    $matched = false;
    
    //This file can be cleared every so often to make parsing faster (@TODO)
    if (file_exists(CAPTURE_FILE) && is_readable(CAPTURE_FILE)) {
        $file = @fopen(CAPTURE_FILE, "r");
        while($line = fgets($file)) {
            if ($streamID == trim($line)) {
                $matched = true;
                break;
            }
        } fclose($file);
        
        return $matched;
    } else {
        die("Unable to read stream id's from " . CAPTURE_FILE . ".  Check permissions.");
    }
}

/**
* Keep track of the streams we've record to help prevent duplicates
*/
function recordStreamID($streamID) {
    // Let's make sure the file exists and is writable first.
    if (file_exists(CAPTURE_FILE) && is_writable(CAPTURE_FILE)) {
        $file = @fopen(CAPTURE_FILE, 'a');
        
        //Content to write
        $content = $streamID . "\n";
        @fwrite($file, $content);
        fclose($file);
        
        trimCaptured(); //Keep future read performance snappy
    } else {
        die("Unable to write stream id to " . CAPTURE_FILE . ".  Check permissions.");
    }
}

/**
* Used to trim the captured file to keep things fast and tidy
*/
function trimCaptured() {
    if (is_writable(CAPTURE_FILE)) {
        $lines = file(CAPTURE_FILE);
        
        //When the file reaches 250 lines we chop out the first 50
        if (!empty($lines) && count($lines) >= 250) {
            $file = @fopen(CAPTURE_FILE, 'w');
            fwrite($file, implode('', array_slice($lines, 50)));
            fclose($file);
        }
    } else {
        die("Unable to trim " . CAPTURE_FILE . ".  Check permissions.");
    }
}
?>