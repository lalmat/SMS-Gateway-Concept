<?php

class dbSMS {

  public $id;
  public $destinataire;
  private $message;

  private $cfg_API;
  private $cfg_GammuCfg;

  // Loading configuration values
  public function __construct() {
    include_once("config.agent.php");
    $this->cfg_API      = $config['API_URL'];
    $this->cfg_GammuCfg = $config['GAMMU_CFG'];
  }

  // Checking if new SMS is in queue to be sent
  public function fetch() {
    $data = json_decode(file_get_contents($this->cfg_API."?mode=fetch"));
    if (is_object($data) && $data->id != null) {
      $this->id           = $data->id;
      $this->destinataire = $data->destinataire;
      $this->message      = $data->message;
      return true;
    }
    return false;
  }

  // Sending SMS
  public function emmit() {
    $cmd = "echo \"".$this->message."\" | gammu -c ".$this->cfg_GammuCfg." --sendsms TEXT 0".$this->destinataire;
    if (@exec($cmd)) {
      fclose(fopen($this->cfg_API."?mode=ack&id=".$this->id, "r"));
      return true;
    }
    return false;
  }
}