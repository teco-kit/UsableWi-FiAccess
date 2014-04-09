<?php

require_once("auth.inc");
require_once("functions.inc");
require_once("captiveportal.inc");

if(local_backed($_POST['usr'], $_POST['pwd']))		
{
	// logging
	require_once("captiveportal-logging.php");
	$logstring = "normal {$_POST['logindex']} - access granted";
	logging($logstring);
	
	// portal login
	$clientip = $_SERVER['REMOTE_ADDR'];
	$clientmac = arp_get_mac_by_ip($clientip);
	global $redirurl;
	if($_POST['authurl'] != "" && $_POST['authurl'] != "/index.php")
	{
		$redirurl = $_POST['authurl'];
	}
	else
	{
		$redirurl = "http://www.kit.edu";
	}

	portal_allow($clientip, $clientmac,"normal");
}
else
{
	// logging
	require_once("captiveportal-logging.php");
	$logstring = "normal {$_POST['logindex']} - access denied";
	logging($logstring);

	// access denied
	include("captiveportal-normal_login.html");
	echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['authurl']}';</script>");
	echo("<center>Wrong username or password! Please try again.</center>");
}
?>
