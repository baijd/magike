<?php
/**********************************
 * Created on: 2007-8-20
 * File Name : module.tags_search.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class TagsSearch extends MagikeModule
{
	private $result;

	public function getTags()
	{
		$tagModel = $this->loadModel("tags");
		$this->result = $tagModel->getTagsByKeywords($_GET["keywords"]);
	}

	public function runModule()
	{
		$this->result = array();
		$this->onGet("keywords","getTags");
		return $this->result;
	}
}
?>
