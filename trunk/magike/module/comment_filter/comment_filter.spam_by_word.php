<?php
/**********************************
 * Created on: 2007-3-3
 * File Name : comment_filter.spam_by_word.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class SpamByWord
{
	private $settingWord;
	
	function __construct($val)
	{
		$this->settingWord = explode(",",$val);
	}
	
	private function hasWord($items,$str)
	{
		foreach($items as $val)
		{
			if(false !== strpos($str,trim($val)))
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function runFilter()
	{
		$content = isset($_POST['comment_text']) ? $_POST['comment_text'] : NULL;
		$content = isset($_POST['excerpt']) ? $_POST['excerpt'] : $content;

		if($this->hasWord($this->settingWord,$content))
		{
			return array('publish' => 'spam','word' => '您的言论由于含有敏感词汇,将在管理员审核通过后展现');
		}
		else
		{
			return array();
		}
	}
}
?>
