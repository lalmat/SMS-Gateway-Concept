<?php
require("dbSMS.class.php");

$sms = new dbSMS();
echo "Agent Started.".PHP_EOL;

while ($sms->fetch()) {
	echo "> Sending SMS #".$sms->id." to ".$sms->destinataire.PHP_EOL;
	if ($sms->emmit()) echo "> SMS Sent !".PHP_EOL;
  else echo "> Error sending SMS.";
}

echo "Agent stopped.".PHP_EOL;