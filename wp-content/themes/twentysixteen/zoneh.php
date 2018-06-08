<html>
<head>
<title>Zone-H Mass Notify - T1KUS90T</title>
<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700' rel='stylesheet' type='text/css'>
</head>
<style type="text/css">
select{width:400px; height:28px;}
input{width:400px;}
textarea {width:400px;height:195px;}
.tombol {background:#b70505;color:white;border: 1px solid #000; padding:6px 6px 6px 6px; width:80px;}
.masuk {color:black;border: 1px solid #000; padding:6px 6px 6px 6px;}
.tombol:hover {background:#c0bfbf;color:#000000;}
</style>
<body bgcolor="black">
<?php	
	if($_POST['submit']) {
		$domain = explode("\r\n", $_POST['url']);
		$nick =  $_POST['nick'];
		echo "<font color='white'>Defacer Onhold:</font> <a href='http://www.zone-h.org/archive/notifier=$nick/published=0' target='_blank'>http://www.zone-h.org/archive/notifier=$nick/published=0</a><br>";
		echo "<font color='white'>Defacer Archive:</font> <a href='http://www.zone-h.org/archive/notifier=$nick' target='_blank'>http://www.zone-h.org/archive/notifier=$nick</a><br><br>";
		function zoneh($url,$nick) {
			$ch = curl_init("http://www.zone-h.com/notify/single");
				  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				  curl_setopt($ch, CURLOPT_POST, true);
				  curl_setopt($ch, CURLOPT_POSTFIELDS, "defacer=$nick&domain1=$url&hackmode=1&reason=1&submit=Send");
			return curl_exec($ch);
				  curl_close($ch);
		}
		foreach($domain as $url) {
			$zoneh = zoneh($url,$nick);
			if(preg_match("/color=\"red\">OK<\/font><\/li>/i", $zoneh)) {
				echo "<font color='white'>$url -> </font><font color=green>OK</font><br>";
			} else {
				echo "<font color='white'>$url -> </font><font color=red>ERROR</font><br>";
			}
		}
	} else {
		echo "<center>
<font size='4' color='white' face='Ubuntu Mono'>Zone-H Mass Notify</font><br>
<font color='white' face='Ubuntu Mono'><p>Fake defacements or Created domains not accepted<br>You must submit real deface not fake defacements</p></font></center>";
		echo "<center>
		<form method='post'>
		<input class='masuk' type='text' name='nick' placeholder='Attacker'><br><br>
		<select>
    	<option value='Not Available' selected>Not Available</option>
    	<option value='SQL Injection'>SQL Injection</option>
    	<option value='URL Poisoning'>URL Poisoning</option>
    	<option value='File Inclusion'>File Inclusion</option>
    	<option value='DNS Hijacking'>DNS Hijacking</option>
    	<option value='DNS Poisoning'>DNS Poisoning</option>
    	<option value='Admin poor Password'>Admin poor Password</option>
		</select><br><br>
		<textarea placeholder='http://site.com/' name='url'></textarea><br><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class='tombol' type='submit' name='submit' value='Submit'>
		</form>";
	}
	echo "</center>";
	?>
</body>
</html>