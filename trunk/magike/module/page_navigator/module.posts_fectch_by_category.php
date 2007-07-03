<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_fectch_by_category.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsFectchByCategory extends PageNavigator
{
	private $getArgs;
	private $result;
	
	public function outputPageNav()
	{
		$postModel = $this->loadModel('posts');
		$categoryModel = $this->loadModel('categories');
		$total = $postModel->countPostsByCategory($_GET['category_postname']);
		$category = $categoryModel->fectchOneByFieldEqual('category_postname',$_GET['category_postname']);
		$this->result = $this->makeClassicNavigator($this->getArgs['limit'],$total,'category/'.$_GET['category_postname']);
		$this->stack['static_var']['blog_title'] = $category['category_name'].' &raquo; 分类 &raquo; '.$this->stack['static_var']['blog_name'];
	}
	
	public function runModule($args)
	{
		$this->result = array();
		$require = array('limit'  => $this->stack['static_var']['post_page_num']);
		$this->getArgs = $this->initArgs($args,$require);
		$this->onGet('category_postname','outputPageNav');
		return $this->result;
	}
}
?>
