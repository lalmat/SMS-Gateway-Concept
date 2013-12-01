<?php

class dbSMS {

  public $id;
  public $destinataire;
  private $message;

  private $cfg_API;
  private $cfg_GammuCfg;

  // Loading configuration values
  public function __construct() {
    include("config.agent.php");
    $this->cfg_API = $config['API'];
    $this->cfg_GammuCfg = $config['API'];
  }

  // Checking if new SMS is in queue to be sent
  public function fetch() {
    $data = json_decode(file_get_contents($this->cfg_API."?mode=fetch"));
    if (is_object($data)) {
      $this->id           = $data->id;
      $this->destinataire = $data->destinataire;
      $this->message      = $data->message;
      return true;
    }
    return false;
  }

  // Sending SMS
  public function emmit() {
    $cmd = "echo \"".$this->message."\" | gammu -c ".$this->cfg_GammuCfg." --sendsms TEXT ".$this->destinataire;
    if (@exec($cmd)) {
      fclose(fopen($this->cfg_API."?mode=ack&id=".$this->id, "r"));
      return true;
    }
    return false;
  }
}