<?php
class sms {

	public $id;
	public $email;
	public $destinataire;
  public $message;

  private $db_dsn;
  private $db_user;
  private $db_pass;

	public function __construct($email, $tel, $msg) {

		include("config.app.php");
		$this->db_dns  = $cfg['DB_DSN'];
		$this->db_user = $cfg['DB_USR'];
		$this->db_pass = $cfg['DB_PWD'];

		$this->email        = $email;
		$this->destinataire = substr($tel,-9);
		$this->message      = escapeshellarg($msg);
	}

	public function queue() {
		$db = new PDO($this->db_dsn, $this->db_user, $this->db_pass);

		$sql = "INSERT INTO sms (sms_email, sms_dest, sms_msg, sms_datetime, sms_state)
				    VALUES (:email, :dest, :msg, now(), 0);";

		$stm = $db->prepare($sql);

		$values[':email'] = $this->email;
		$values[':dest']  = $this->destinataire;
		$values[':msg']   = $this->message;

		$rs = ($stm->execute($values) !== FALSE);

		unset($stm);
		unset($db);

		return $rs;
	}

	public function fetch() {
		$db = new PDO($this->db_dsn, $this->db_user, $this->db_pass);
		$sql = "SELECT sms_id, sms_email, sms_dest, sms_msg
				    FROM sms
				    WHERE sms_state=0
				    ORDER BY sms_id
				    LIMIT 0,1";

		$s = $db->query($sql);

		$rAry = $s->fetch(PDO::FETCH_ASSOC);
		unset($s);
		unset($db);

		if (is_array($rAry)) {
			$this->id           = $rAry['sms_id'];
			$this->email        = $rAry['sms_email'];
			$this->destinataire = $rAry['sms_dest'];
			$this->message      = $rAry['sms_msg'];
		  return true;
		} else {
			return false;
		}
	}

	public function ack($id) {
		$db = new PDO($this->db_dsn, $this->db_user, $this->db_pass);
		$stm = $db->prepare("UPDATE sms SET sms_state=1, sms_datetime=NOW() WHERE sms_id=:id");
		$rs = $stm->execute(array(":id"=>$id));
		unset($db);
		return ($rs !== FALSE) ;
  }

}