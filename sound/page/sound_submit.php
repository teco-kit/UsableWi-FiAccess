<?php
$index = $_POST['index_data'];


if($_POST['submittype'] == "submit")
{
	// clientdaten schreiben
	$data_datei = fopen("{$index}_clientdata.txt", "w");
	fwrite($data_datei, $_POST['sound_data']);
	fclose($data_datei);
}


$result_data = file("{$index}_result.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
switch($result_data[0])
{
	case "import error":
		echo("<center>import error</center>");
		break;

	case "sound error":
		echo("<center>error during record: server sound device busy</center>");
		break;

	case "testaccess error":
		echo("<center>error during analysis: testaccess error</center>");
		break;

	case "file error":
		echo("<center>error during analysis: file error</center>");
		break;

	case "time error":
		echo("<center>recording was disturbed. please try again</center>");
		break;

	case "fingerprint error":
		echo("<center>error during analysis: fingerprint error</center>");
		break;

	case "recording":
		include("captiveportal-sound_login.html");
		echo("<center>recording ... please wait</center>");
		echo("<script type='text/javascript'>document.soundform1.index_data.value = {$index};</script>");
		echo("<script type='text/javascript'>document.soundform1.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform2.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform1.submiturl.value = '{$_POST['submiturl']}';</script>");
		echo("<script type='text/javascript'>window.onload=PageWait;</script>");
		return;
		break;

	case "record done":
		// result auf checking setzen
		$result_datei = fopen("{$index}_result.txt", "w");
		fwrite($result_datei, "checking");
		fclose($result_datei);

		// python-fp-checkausfuehren
		exec("python main.py check {$index} > /dev/null &");

		// logging
		require_once("captiveportal-logging.php");
		$logstring = "sound {$_POST['logindex']} - record done - index: {$index}";
		logging($logstring);

		include("captiveportal-sound_login.html");
		echo("<center>record done ... please wait</center>");
		echo("<script type='text/javascript'>document.soundform1.index_data.value = {$index};</script>");
		echo("<script type='text/javascript'>document.soundform1.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform2.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform1.submiturl.value = '{$_POST['submiturl']}';</script>");
		echo("<script type='text/javascript'>window.onload=PageWait;</script>");
		return;
		break;

	case "checking":
		include("captiveportal-sound_login.html");
		echo("<center>sound is being processed ... this could take up to several seconds, please wait</center>");
		echo("<script type='text/javascript'>document.soundform1.index_data.value = {$index};</script>");
		echo("<script type='text/javascript'>document.soundform1.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform2.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform1.submiturl.value = '{$_POST['submiturl']}';</script>");
		echo("<script type='text/javascript'>window.onload=PageWait;</script>");
		return;
		break;

	case "access granted":
		// logging
		require_once("captiveportal-logging.php");
		$logstring = "sound {$_POST['logindex']} - access granted - index: {$index}";
		logging($logstring);

		// verwendete dateien bereinigen:
		$temp_datei = fopen("{$index}_result.txt", "w");
		fclose($temp_datei);
		$temp_datei = fopen("{$index}_serverdata.txt", "w");
		fclose($temp_datei);
		$temp_datei = fopen("{$index}_servertime.txt", "w");
		fclose($temp_datei);
		$temp_datei = fopen("{$index}_clientdata.txt", "w");
		fclose($temp_datei);

		// gibt den session-index wieder frei:
		$index_data = file("session_index.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$index_datei = fopen("session_index.txt", "w");
		foreach($index_data as $string)
		{
			if($index != $string)
				fwrite($index_datei, $string."\n");
		}
		fclose($index_datei);
		
		// portal login
		require_once("auth.inc");
		require_once("functions.inc");
		require_once("captiveportal.inc");
		$clientip = $_SERVER['REMOTE_ADDR'];
		$clientmac = arp_get_mac_by_ip($clientip);
		global $redirurl;
		if($_POST['submiturl'] != "" && $_POST['submiturl'] != "/index.php")
		{
			$redirurl = $_POST['submiturl'];
		}
		else
		{
			$redirurl = "http://www.kit.edu";
		}
		portal_allow($clientip, $clientmac,"sound");
		break;

	case "access denied":
		echo("<center>access denied</center>");
		
		// logging
		require_once("captiveportal-logging.php");
		$logstring = "sound {$_POST['logindex']} - access denied - index: {$index}";
		logging($logstring);

		// verwendete dateien bereinigen:
		$temp_datei = fopen("{$index}_result.txt", "w");
		fclose($temp_datei);
		$temp_datei = fopen("{$index}_serverdata.txt", "w");
		fclose($temp_datei);
		$temp_datei = fopen("{$index}_servertime.txt", "w");
		fclose($temp_datei);
		$temp_datei = fopen("{$index}_clientdata.txt", "w");
		fclose($temp_datei);

		// gibt den session-index wieder frei:
		$index_data = file("session_index.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$index_datei = fopen("session_index.txt", "w");
		foreach($index_data as $string)
		{
			if($index != $string)
				fwrite($index_datei, $string."\n");
		}
		fclose($index_datei);
		break;

	default:
		include("captiveportal-sound_login.html");
		echo("<center>unknown error: </center>");
		echo("<center>".$result_data[0]."</center>");
		echo("<script type='text/javascript'>document.soundform1.index_data.value = {$index};</script>");
		echo("<script type='text/javascript'>document.soundform1.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform2.logindex.value = {$_POST['logindex']};</script>");
		echo("<script type='text/javascript'>document.soundform1.submiturl.value = '{$_POST['submiturl']}';</script>");
		echo("<script type='text/javascript'>window.onload=PageWait;</script>");
		return;
		break;
}
?>
