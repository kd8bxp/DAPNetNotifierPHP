#!/usr/local/bin/php -q

<?php

/* By LeRoy Miller, KD8BXP June 25, 2022 v0.1.0 init release
 This script is loosely based on DAPNETNotifier by N8ACL which in turn was based on
 DAPNET2APRS script by KR0SIV.
 https://github.com/KR0SIV/DAPNET2APRS
 https://github.com/n8acl/DAPNETNotifier/
 Both of which are python based, and honestly python just confuses the heck out of me, 
 needless to say I couldn't get either one to work reliably. N8ACL's worked once, but not with my pager id. 
Not being good with python, just barely enough to get by. I fell back to using PHP on the cli. Yes, it's weird, it takes a little setup, but it works.
This script will scrape your hot spot, format the information into arrays (much nicer to work with this way), attempt to decide if a new message for you has arrived, and forward that message to pushover. (Other services could be added as needed.)
A simple web page was found when looking at the html source for the dashboard.
/mmdvmhost/pages.php It's not perfect, but should be better then having the hotspot render the full dashboard everytime you make a request.
It is recommended to only update about every 60 seconds or so, but can be changed as needed.
This script goes into a loop, a & on the command line should let it run in the background. Error message reporting is turned off, if you are having issues you can turn it back on, and see if you can figure out what is going on. 


I have PHP 7.4 installed, php74-cli, php7.4-command, php7.4-curl, php7.4-json on my test machine. (I beleive these are all required for this too work, thou I installed most of them a while ago)
simple_html_dom is required http://simplehtmldom.sourceforge.net/ for more information.

Posiable limitations - assume that you don't get many pages, so the 1st key should always be the newest on the dashboard. At this point if you get more then 1 page in the same time period, pages could be lost.

This software is provided free of charge, and as-is where is. It may or may not work for you. 
*/

require('simple_html_dom.php');

// Turn off all error reporting 
error_reporting(0);

$mmdvm_ip = 'http://192.168.1.231/mmdvmhost/pages.php'; //change to your IP address of your hotspot or use http://pistar.local/mmdvmhost/pages.php replace pistar with the name of your hotspot (may not work as well...mine was slow using the FQDN

$wait_time = 60; //Your delay in seconds between message checks, let's not hammer the crap out of the MMDVM hum?

$pager_id = '#######'; //your pager cap code/RIC or the code you want to subscribe to, this script only supports one for now (maybe one day more - someone help me with that)
//$pager_id = '0000216'; //time update RIC USED to test the script should be commented out and the above is what you should use

// Pushover configuration
$pushover_token = ""; # Your Pushover API token
$pushover_user = ""; # Your Pushover user key

//Found at https://stackoverflow.com/questions/8287152/parse-html-table-using-file-get-contents-to-php-array

$PagerMSG = array();
$oldMSG = array();
$oldMSG[1] = "";

echo "Starting...\n";

while(1) {
echo "Checking for new messages.. \n";
$html = file_get_html($mmdvm_ip);
$table = array();
$count = -1;
if (!empty($html)) {
foreach($html->find('tr') as $row) {
    $time = $row->find('td',0)->plaintext;
    $timeslot = $row->find('td',1)->plaintext;
    $target = $row->find('td',2)->plaintext;
    $message = $row->find('td',3)->plaintext;
    $count++;
    $table[$count][$target] = $message . " -" . $time;
 }
}

//print_r($table); //troubleshooting

//Find the Pager ID we want

$count = 0;
for ($row = 1; $row < 21; $row++) {
  if (array_key_exists($pager_id, $table[$row])) {
        $count++;
        $PagerMSG[$count] = $table[$row][$pager_id];
 }
}

//print_r($PagerMSG); //toubleshooting

if (array_key_exists("1", $PagerMSG)) {
  if ($oldMSG[1] != $PagerMSG[1]) {
     //assume that you don't get many pages, so the 1st key should always be the newest on the dashboard. At this point if you get more then 1 page in the same time period, pages could be lost.
     echo "Sending to pushover.....";
     echo "\n\r" . $PagerMSG[1] . "\n\r";
     $oldMSG[1] = $PagerMSG[1];
     curl_setopt_array($ch = curl_init(), array(
     CURLOPT_URL => "https://api.pushover.net/1/messages.json",
     CURLOPT_POSTFIELDS => array(
     "token" => $pushover_token,
     "user" => $pushover_user,
     "message" => $PagerMSG[1],
   ),
    CURLOPT_SAFE_UPLOAD => true,
    CURLOPT_RETURNTRANSFER => true,
   ));
   curl_exec($ch);
   curl_close($ch);
     }
         //Other services can be added here (Maybe I should have used a function for pushover)
    else {
      echo "\nNo new messages.\n";
    } 
  } else { echo "\nNo messages.\n"; }

 sleep($wait_time); //delay before next read of site
 echo "\nLooping.... ";
} //end while loop needed

die;

/* 

 NOTES:
  My PHP are a little rusty, I used the following websites to help me remember what
  I was doing.
  https://www.w3schools.com/php/php_arrays_multidimensional.asp
  https://www.geeksforgeeks.org/c-program-cyclically-rotate-array-one/
  https://code.tutsplus.com/tutorials/working-with-php-arrays-in-the-right-way--cms-28606
  https://stackoverflow.com/questions/10261925/best-way-to-clear-a-php-arrays-values
  https://www.php.net/manual/en/function.error-reporting.php

*/

?>
