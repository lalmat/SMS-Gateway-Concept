<?php
require("sms.class.php");
include("config.app.php");

$rs = "SMS Gateway API";

switch($_GET['mode']) {

	case "queue":
		$sms = new sms($_GET['email'], $_GET['tel'], $_GET['msg']);
		$rs = $sms->queue();
		break;

	case "fetch":
		$rs = $sms->fetch();
		break;

	case "ack":
		$rs = $sms->ack($_GET['id']);
		break;
}

echo json_encode($rs);