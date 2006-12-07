<?php
/**********************************
 * Created on: 2006-12-5
 * File Name : build.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Build extends MagikeObject
{
	private $templateFile,$template;
	private $includeFile;
	private $data;
	private $istack;
	private $mod;
	private $block;
	private $publicLang,$publicReplaceLang;

	function __construct($templateFile,$template)
	{
		parent::__construct(array('public' => array('stack')));

		$this->template = $template;
		$this->templateFile = str_replace('.tpl','',$templateFile);
		$this->istack = NULL;
		$this->includeFile = array();
		$this->data["block"] = array();
		$this->data["static"] = array();
		$this->block = array();
		$this->tag = array();
		$this->mod = array();
		$this->publicLang = array();

		//载入语言文件
		if(file_exists(__LANGUAGE__."/".$this->stack->data['static']['language']."/public.php"))
		{
			$lang = array();
			require(__LANGUAGE__."/".$this->stack->data['static']['language']."/public.php");
			$this->publicLang = $lang;
		}

		$html = $this->link($this->templateFile);
		$this->tag($html);
		$this->makeCache();
		$this->makeConf();
	}

	//连接函数
	private function link($inputTpl)
	{
		$templateFile = __TEMPLATE__.'/'.$this->template.'/'.$inputTpl.'.tpl';
		//检测文件是否存在
		if(!file_exists($templateFile))
		{
			$this->throwException(E_FILENOTEXISTS,$templateFile);
		}

		//检测是否存在死循环
		if(isset($this->includeFile[$templateFile]))	return	NULL;

		$this->includeFile[$templateFile] = filemtime($templateFile);

    	$fileArray = @file($templateFile);        //打开脚本
        foreach($fileArray as $key=>$val)
        {
            //搜索是否存在[include],若存在则递归
            if(preg_match_all("/\[include:\s*(.+?)\]/is",$val,$out))
            {
                $fileArray[$key] = $this->_link($out[1][0]);
            }
        }

        return implode("", $fileArray);
	}

	//生成缓存
	private function makeCache()
	{
		$str  = "<?php\n";
		$str .= '$module = '.var_export($this->mod,true).';';
		$str .= '$data = '.var_export($this->data,true).';';
		$str .= '$block = '.var_export($this->block,true).';';
		$str .= "function load_template_".basename($this->templateFile,".tpl")."(\$blockName,\$block,\$data)\n";
		$str .= "{\n";
		$str .= "switch (\$blockName) {";
		$str .= $this->istack;
		$str .= "}\n";
		$str .= "return \$block[\$blockName];";
		$str .= "}\n";
		$str .= "?>";

		file_put_contents(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'@'.$this->stack->data['static']['language'].'.php',$str);
		$str  = php_strip_whitespace(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'@'.$this->stack->data['static']['language'].'.php');
		file_put_contents(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'@'.$this->stack->data['static']['language'].'.php',$str);
	}

	//生成配置信息
	private function makeConf()
	{
		MagikeAPI::exportArrayToFile(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'.cnf.php',$this->includeFile,'files');
	}

	//载入语言
	private function loadLang($blockArray)
	{
		if(is_array($blockArray))
		{
		foreach($blockArray as $val)
		{
			if(file_exists(__LANGUAGE__."/".$this->stack->data['static']['language']."/".$val.".php"))
			{
				$lang = array();
				require(__LANGUAGE__."/".$this->stack->data['static']['language']."/".$val.".php");
				$this->publicLang = array_merge($this->publicLang,$lang);
			}
		}
		}
		$this->publicReplaceLang = MagikeAPI::replaceArray(array_keys($this->publicLang),'/{','}/is');
	}

	//替换语言
	private function lang($inputHtml)
	{
		$inputHtml = preg_replace($this->publicReplaceLang,$this->publicLang,$inputHtml);
		return $inputHtml;
	}

	//输出所有tag,并且组织tag
	private function tag($inputHtml)
	{
		global $system;

		preg_match_all("/\[\/([_0-9a-zA-Z-]+)\]/i",$inputHtml,$out);
		if(!isset($out[1])) $out[1] = array();

		$this->loadLang($out[1]);
		array_push($out[1],"@");
		foreach($out[1] as $val)
		{
					if($val != "@") preg_match("/\[{$val}\]\s*(.+?)\[\/{$val}\]/is",$inputHtml,$mystack);
					else $mystack[1] = $inputHtml;
					if(isset($mystack[1]))
					{
						$this->block[$val] = NULL;
						$mystack[1] = $this->lang($mystack[1]);
						$mystack[1] = $this->stack($mystack[1],$val);
						$mystack[1] = $this->getIf($mystack[1],$val);
						$mystack[1] = $this->callback($mystack[1],$val);
						if($val != "@")
						{
							if(isset($this->stack->data['module'][$val]))	$this->mod[$val] = $this->stack->data['module'][$val];
							$mystack[1] = $this->filter($mystack[1],$val);
							$this->istack .= "case \"{$val}\":
							\$block['{$val}'] = \"".$mystack[1]."\";
							break;
							";
							$inputHtml = preg_replace("/\[{$val}\]\s*(.+?)\[\/{$val}\]/is","{\$block['{$val}']}",$inputHtml);
						}
						else
						{
							$this->istack .= "case \"@\":
							\$block['@'] = \"".$mystack[1]."\";
							break;
							default:
							break;
							";
						}
					}
		}

		return;
	}

	//输出段
	private function stack($inputHtml,$blockName)
	{
		//为段数据初始化做准备
		$inputHtml = stripslashes($inputHtml);
        $inputHtml = addslashes($inputHtml);
        $inputHtml = str_replace("\'","'",$inputHtml);

		if(preg_match_all("/\{\\$([_0-9a-zA-Z-]+)\}/is",$inputHtml,$out))
		{
			if(isset($out[1]))
			{
				foreach($out[1] as $val)
				{
					$this->data["block"][$blockName][$val] = NULL;
					$inputHtml = str_replace("{\$".$val."}","{\$data['block']['{$blockName}']['{$val}']}",$inputHtml);
				}
			}
		}

		//为全局数据初始化做准备
		if(preg_match_all("/\{\\$([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)\}/is",$inputHtml,$out))
		{
			if(isset($out[2]))
			{
				foreach($out[2] as $key => $val)
				{
					$this->data[$out[1][$key]][$val] = NULL;
					$inputHtml = str_replace("{\$".$out[1][$key].".".$val."}","{\$data['".$out[1][$key]."']['{$val}']}",$inputHtml);
				}
			}
		}

		return $inputHtml;
	}

	//解析filter语法
	private function filter($inputHtml,$blockName)
	{
		global $system;

		if(preg_match_all("/\[filter:([_0-9a-zA-Z-]+)\]/is",$inputHtml,$out))
    	{
    			if(isset($out[1]))
    			{
    				foreach($out[1] as $key => $val)
    				{
    				$inputHtml = str_replace($out[0][$key],"",$inputHtml);
    				if(isset($this->stack->data['module'][$out[1][$key]]) && isset($this->mod[$blockName]))
    					{
    						if(!isset($this->mod[$blockName]["filter"]))	$this->mod[$blockName]["filter"] = array();
    						$this->mod[$blockName]["filter"][$out[1][$key]] = $this->stack->data['module'][$out[1][$key]];
    					}
    				}
    			}
    	}

    	return $inputHtml;
	}

	//解析if语法
    private function getIf($inputHtml,$blockName)
    {
    	$ifTag = array();	//保存if标签的数?
    	$finishTag = array();	//处理嵌套后的数组

    	//处理嵌套数据
    	if(preg_match_all("/\[\s*(.+?)\]/is",$inputHtml,$out))
 		{
        	if(isset($out[1]))		//如果找到符合的块
        	{
        	foreach($out[1] as $key => $val)
            {
            	if(preg_match("/if \s*(.+?)/is",$val))
            	{
					$tmp = stripslashes($val);
            		$inputHtml = str_replace($val,$tmp,$inputHtml);
            		$val = $tmp;
            		$val = preg_quote($val);
            		array_push($ifTag,$val);
            		continue;
            	}
            	else if($val == "/if")
            	{
            		array_push($finishTag,array_pop($ifTag));
            	}
            }
            }
        }

        //开始解析条件语句
        if($finishTag)
        {
        foreach($finishTag as $val)
        	{
        		$inputHtml = preg_replace("/\[{$val}\]\s*(.+?)\[\/if\]/is","\".(("
        		.$this->praseIf($val,$blockName).") ? \"\\1\" : NULL).\"",$inputHtml);
        	}
        }

        return $inputHtml;
    }

    private function praseIf($str,$blockName)
    {
    		if(preg_match_all("/\\$([_0-9a-zA-Z\.]+)/is",$str,$out))
    		{
    			foreach($out[1] as $val)
    			{
    				$str = str_replace("$".$val,$this->praseIfValue($val,$blockName),$str);
    			}
    		}
    		$str = str_replace("if ","",$str);
    		$str = str_replace("\\","",$str);
    		return $str;
    }

    private function praseIfValue($str,$blockName)
    {
    	if(ereg("([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)",$str,$out))
    	{
    		$this->data[$out[1]][$out[2]] = NULL;
    		return "\$data['{$out[1]}']['{$out[2]}']";
    	}
    	else if(ereg("([_0-9a-zA-Z]+)",$str,$out) && $blockName != "@")
    	{
    		$this->data['block'][$blockName][$out[1]] = NULL;
    		return "\$data['block']['{$blockName}']['{$out[1]}']";
    	}
    	else return "NULL";
    }

    //处理callback语法
    private function callback($inputHtml,$blockName)
    {
    	global $system;
    	if(preg_match_all("/\[callback:([_0-9a-zA-Z-]+)\]/is",$inputHtml,$out))
    	{
    		if(isset($out[1]))
    		{
    			foreach($out[1] as $key => $val)
    			{
    			$inputHtml = str_replace($out[0][$key],"",$inputHtml);
    			if(isset($this->stack->data['module'][$out[1][$key]]))
    			{
    				if($blockName != "@")	$this->mod += array($val => $this->stack->data['module'][$out[1][$key]]);
    				else	$this->mod = array_merge(array($val => $this->stack->data['module'][$out[1][$key]]) , $this->mod);
    			}
    			}
    		}
    	}

    	return $inputHtml;
    }
}
?>
