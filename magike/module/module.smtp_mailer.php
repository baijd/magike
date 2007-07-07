<?php
/**********************************
 * Created on: 2007-7-2
 * File Name : module.smtp_mailer.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class SmtpMailer extends MagikeModule
{
	function __construct()
	{
		parent::__construct();
		$this->initPrivateObject(array('phpmailer'));
	}
	
	public function runModule($args)
	{
		$require = array('waitting_for' => NULL);
		$getArgs = $this->initArgs($args,$require);
		if($getArgs['waitting_for'] && isset($this->stack[$getArgs['waitting_for']]['mailer']))
		{
				$userModel = $this->loadModel('users');
				$webMaster =  $userModel->fetchOneByKey(1);
				$this->phpmailer->isSMTP();
				$this->phpmailer->SMTPDebug = __DEBUG__;
				$this->phpmailer->CharSet = $this->stack['static_var']['charset'];
				$this->phpmailer->Encoding = 'base64';
				$this->phpmailer->From = $webMaster['user_mail'];
				$this->phpmailer->FromName = $webMaster['user_name'];
				
				$this->phpmailer->Host = $this->stack['static_var']['smtp_host'];
				$this->phpmailer->Port = $this->stack['static_var']['smtp_port'];
				$this->phpmailer->SMTPAuth = $this->stack['static_var']['smtp_auth'];
				$this->phpmailer->IsHTML(false);
				$this->phpmailer->IsSSL($this->stack['static_var']['smtp_ssl']);
				$this->phpmailer->Username = $this->stack['static_var']['smtp_user'];
				$this->phpmailer->Password = $this->stack['static_var']['smtp_pass'];
				
				$this->phpmailer->AddAddress($this->stack[$getArgs['waitting_for']]['mailer']['send_to'],
				$this->stack[$getArgs['waitting_for']]['mailer']['send_to_user']);
				
				if(isset($this->stack[$getArgs['waitting_for']]['mailer']['reply']) && $this->stack[$getArgs['waitting_for']]['mailer']['reply'])
				{
					$this->phpmailer->AddReplyTo($this->stack[$getArgs['waitting_for']]['mailer']['reply'][1],
					$this->stack[$getArgs['waitting_for']]['mailer']['reply'][0]);
				}
				
				$this->phpmailer->WordWrap = 50;
				$this->phpmailer->Subject = $this->stack[$getArgs['waitting_for']]['mailer']['subject'];
				$this->phpmailer->Body = $this->stack[$getArgs['waitting_for']]['mailer']['body'];
				$this->phpmailer->Send();
		}
	}
}
?>
