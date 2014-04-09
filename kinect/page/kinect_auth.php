<?php

$index_data = file("session_index.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$index = $_POST['index'];
if($index_data[0] != "")
{
	if($index_data[0] == $index)
	{
		$kinect_data = file("kinect.log", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		while(end($kinect_data) == "waiting for server response")
		{
			usleep(1000);
			$kinect_data = file("kinect.log", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}
		
		// free index
		$kinect_datei = fopen("kinect.log", "a");
		fwrite($kinect_datei, "session end\n");
		fclose($kinect_datei);
		fclose(fopen("session_index.txt", "w"));
		
		if(end($kinect_data) == "access granted")
		{
			// logging
			require_once("captiveportal-logging.php");
			$logstring = "kinect {$_POST['logindex']} - access granted";
			logging($logstring);

			// portal allow
			require_once("auth.inc");
			require_once("functions.inc");
			require_once("captiveportal.inc");
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
			portal_allow($clientip, $clientmac,"kinect");
		}
		else
		{
			// logging
			require_once("captiveportal-logging.php");
			$logstring = "kinect {$_POST['logindex']} - access denied";
			logging($logstring);

			// access denied
			include("captiveportal-kinect_login.html");
			echo("<center>access denied</center>");
			echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
			echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['authurl']}';</script>");
		}
	}
	else
	{
		// logging
		require_once("captiveportal-logging.php");
		$logstring = "kinect {$_POST['logindex']} - user wait";
		logging($logstring);

		// user has to wait
		include("captiveportal-kinect_login.html");
		echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['authurl']}';</script>");
		echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>window.onload=PageLoadWait;</script>");
	}
}
else
{
	$index = rand(0, 65535);
	$index_datei = fopen("session_index.txt", "w");
	fwrite($index_datei, "{$index}");
	fclose($index_datei);
	

	// logging
	require_once("captiveportal-logging.php");
	$logstring = "kinect {$_POST['logindex']} - login attempt";
	logging($logstring);

	// start login attempt
	include("captiveportal-kinect_login.html");
	echo("<center>Please raise your hand</center>");
	echo("<script type='text/javascript'>document.loginform.redirurl.value = '{$_POST['authurl']}';</script>");
	echo("<script type='text/javascript'>document.authform.index.value = {$index};</script>");
	echo("<script type='text/javascript'>document.authform.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>window.onload=PageLoadAuth;</script>");
	
	exec("python kinect.py > /dev/null &");
}
?>
