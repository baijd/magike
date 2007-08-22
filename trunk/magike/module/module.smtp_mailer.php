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
	}
	
	public function runModule($args)
	{
		$require = array('waitting_for' => NULL);
		$getArgs = $this->initArgs($args,$require);
		if($getArgs['waitting_for'] && isset($this->stack[$getArgs['waitting_for']]['mailer']))
		{
				$phpmailer = new Phpmailer();
				$userModel = $this->loadModel('users');
				$webMaster =  $userModel->fetchOneByKey(1);
				$phpmailer->isSMTP();
				$phpmailer->SMTPDebug = __DEBUG__;
				$phpmailer->CharSet = $this->stack['static_var']['charset'];
				$phpmailer->Encoding = 'base64';
				$phpmailer->From = $webMaster['user_mail'];
				$phpmailer->FromName = $webMaster['user_name'];
				
				$phpmailer->Host = $this->stack['static_var']['smtp_host'];
				$phpmailer->Port = $this->stack['static_var']['smtp_port'];
				$phpmailer->SMTPAuth = $this->stack['static_var']['smtp_auth'];
				$phpmailer->IsHTML(false);
				$phpmailer->IsSSL($this->stack['static_var']['smtp_ssl']);
				$phpmailer->Username = $this->stack['static_var']['smtp_user'];
				$phpmailer->Password = $this->stack['static_var']['smtp_pass'];
				
				$phpmailer->AddAddress($this->stack[$getArgs['waitting_for']]['mailer']['send_to'],
				$this->stack[$getArgs['waitting_for']]['mailer']['send_to_user']);
				
				if(isset($this->stack[$getArgs['waitting_for']]['mailer']['reply']) && $this->stack[$getArgs['waitting_for']]['mailer']['reply'])
				{
					$phpmailer->AddReplyTo($this->stack[$getArgs['waitting_for']]['mailer']['reply'][1],
					$this->stack[$getArgs['waitting_for']]['mailer']['reply'][0]);
				}
				
				$phpmailer->WordWrap = 50;
				$phpmailer->Subject = $this->stack[$getArgs['waitting_for']]['mailer']['subject'];
				$phpmailer->Body = $this->stack[$getArgs['waitting_for']]['mailer']['body'];
				$phpmailer->Send();
		}
	}
}
?>
