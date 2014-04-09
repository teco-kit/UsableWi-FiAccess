<?php
require_once("auth.inc");
require_once("functions.inc");
require_once("captiveportal.inc");

$count = $_POST['pscount'];
if($count < 10)
{
	if($count == 0)
	{
		// logging
		require_once("captiveportal-logging.php");
		$logstring = "powersheet {$_POST['logindex']} - login attempt";
		logging($logstring);
	}
	
	$count += 1;
	include("captiveportal-powersheet_login.html");
	echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>document.authform.pscount.value = {$count};</script>");
	echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['psurl']}';</script>");
	echo("<script type='text/javascript'>window.onload=PageLoad;</script>");
	return;
}

$now = time();

exec("python pscopy.py 01 > /dev/null");
$powersheet_datei = fopen("pstemp.csv", "r");


$powersheet_line = fgetcsv($powersheet_datei);
while($powersheet_line[0] != "Station MAC")
{
	$powersheet_line = fgetcsv($powersheet_datei);
}

$goodmac = FALSE;
$clientip = $_SERVER['REMOTE_ADDR'];
$clientmac = arp_get_mac_by_ip($clientip);

$powersheet_line = fgetcsv($powersheet_datei);
while($powersheet_line !== FALSE)
{
	if(strcasecmp($powersheet_line[0], $clientmac) == 0)
	{
		$goodmac = TRUE;
		break;
	}
	
	$powersheet_line = fgetcsv($powersheet_datei);
}
fclose($powersheet_datei);

if($goodmac == FALSE)
{
	// logging
	require_once("captiveportal-logging.php");
	$logstring = "powersheet {$_POST['logindex']} - not in range";
	logging($logstring);
	
	include("captiveportal-powersheet_login.html");
	echo("<center>No login station in range, please try again.</center>");
	echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['psurl']}';</script>");
	return;
}

$clienttime = strtotime($powersheet_line[2]);
$difftime = abs($now - $clienttime);
if($difftime > 10)
{
	// logging
	require_once("captiveportal-logging.php");
	$logstring = "powersheet {$_POST['logindex']} - time difference";
	logging($logstring);
	
	include("captiveportal-powersheet_login.html");
	echo("<center>Your device was last seen over 10 seconds ago, please try again.</center>");
	echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['psurl']}';</script>");
	return;
}

// logging
require_once("captiveportal-logging.php");
$logstring = "powersheet {$_POST['logindex']} - access granted";
logging($logstring);

global $redirurl;
if($_POST['psurl'] != "" && $_POST['psurl'] != "/index.php")
{
	$redirurl = $_POST['psurl'];
}
else
{
	$redirurl = "http://www.kit.edu";
}
portal_allow($clientip, $clientmac,"powersheet");
?>
