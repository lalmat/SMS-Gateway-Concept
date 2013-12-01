<?php
// Clearly need some static methods here.

class sms {

  public $id;
  public $email;
  public $destinataire;
  public $message;

  public function __construct($email=null, $tel=null, $msg=null, $id=null) {
    $this->email        = $email;
    $this->destinataire = isset($tel) ? substr($tel,-9) : null;
    $this->message      = isset($msg) ? escapeshellarg($msg) : null;
    $this->id           = $id;
  }

  public function queue() {
    include_once("config.app.php");
    try {

      $db = new PDO($cfg['DB_DSN'], $cfg['DB_USR'], $cfg['DB_PWD']);

      $sql = "INSERT INTO sms (sms_email, sms_dest, sms_msg, sms_datetime, sms_state)
              VALUES (:email, :dest, :msg, now(), 0);";

      $stm = $db->prepare($sql);

      $values[':email'] = $this->email;
      $values[':dest']  = $this->destinataire;
      $values[':msg']   = $this->message;

      $rs = ($stm->execute($values) !== FALSE);

      unset($stm);
      unset($db);
    }
    catch(Exception $e) {
      echo $e->getMessage();
    }
    return $rs;
  }

  public static function fetch() {
    $sms = null;
    include_once("config.app.php");
    try {

      $db = new PDO($cfg['DB_DSN'], $cfg['DB_USR'], $cfg['DB_PWD']);

      $sql = "SELECT sms_id, sms_email, sms_dest, sms_msg
              FROM sms
              WHERE sms_state=0
              ORDER BY sms_id
              LIMIT 0,1";

      $s = $db->query($sql);

      $rAry = $s->fetch(PDO::FETCH_ASSOC);
      unset($s);
      unset($db);

      $sms = new self($rAry['sms_email'], $rAry['sms_dest'], $rAry['sms_msg'], $rAry['sms_id']);
    }
    catch(Exception $e) {
      echo $e->getMessage();
    }
    return $sms;
  }

  public static function ack($id) {
    $rs = false;
    include_once("config.app.php");
    try {

      $db = new PDO($cfg['DB_DSN'], $cfg['DB_USR'], $cfg['DB_PWD']);
      $stm = $db->prepare("UPDATE sms SET sms_state=1, sms_datetime=NOW() WHERE sms_id=:id");
      $rs = $stm->execute(array(":id"=>$id));
      unset($db);
    }
    catch(Exception $e) {
      echo $e->getMessage();
    }
    return ($rs !== FALSE) ;
  }

}