<?php

$logtsp = time();
$logzeit = date("d.m.Y - H:i:s", $logtsp);
$logindex = rand(0, 65535);
$logdatei = fopen("log.txt", "a");
$logstring = $logzeit . ": kinect {$logindex} - captive portal\n";
fwrite($logdatei, $logstring);
fclose($logdatei);

include("captiveportal-kinect_login.html");
echo("<script type='text/javascript'>document.authform.logindex.value = {$logindex};</script>");

?>
