<?php
require("dbSMS.class.php");
include("config.agent.php");

$sms = new dbSMS();
echo "Agent Started.".PHP_EOL;

while ($sms->fetch()) {
	echo "> Sending SMS #".$sms->id.PHP_EOL;
	if ($sms->emmit()) echo "> SMS Sent !".PHP_EOL;
  else echo "> Error sending SMS.";
}

echo "Agent stopped.".PHP_EOL;