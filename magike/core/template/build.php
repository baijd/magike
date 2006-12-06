<?php
/**********************************
 * Created on: 2006-12-5
 * File Name : build.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Build extends MagikeObject
{
	private $tpl,$tpl_name,$cache,$lang;
	private $include_file;
	private $data;
	private $istack;
	private $mod;
	private $block;
	private $public_lang,$public_rlang;

	function __construct($templateFile,$template)
	{
		parent::__construct(array('public' => array('stack')));

		$this->tpl_name = $template;
		$this->tpl = str_replace('.tpl','',$templateFile);
		$this->lang = $this->stack->data['static']['language'];
		$this->istack = NULL;
		$this->include_file = array();
		$this->data["block"] = array();
		$this->data["static"] = array();
		$this->block = array();
		$this->tag = array();
		$this->mod = array();
		$this->public_lang = array();
		//载入语言文件
		if(file_exists(__LANGUAGE__."/".$this->lang."/public.php"))
		{

			$lang = array();
			require(__LANGUAGE__."/".$this->lang."/public.php");
			$this->public_lang = $lang;
		}

		$html = $this->_link($this->tpl);
		$this->_tag($html);
		$this->_make_cache();
		$this->_make_conf();
	}

	//连接函数
	private function _link($input_tpl)
	{
		$templateFile = __TEMPLATE__.'/'.$this->tpl_name.'/'.$input_tpl.'.tpl';
		//检测文件是否存在
		if(!file_exists($templateFile))
		{
			$this->throwException(E_FILENOTEXISTS,$templateFile);
		}

		//检测是否存在死循环
		if(isset($this->include_file[$templateFile]))	return	NULL;

		$this->include_file[$templateFile] = filemtime($templateFile);

    	$f_array = @file($templateFile);        //打开脚本
        foreach($f_array as $key=>$val)
        {
            //搜索是否存在[include],若存在则递归
            if(preg_match_all("/\[include:\s*(.+?)\]/is",$val,$out))
            {
                $f_array[$key] = $this->_link($out[1][0]);
            }
        }

        return implode("", $f_array);
	}

	//生成缓存
	private function _make_cache()
	{
		$str  = "<?php\n";
		$str .= '$module = '.var_export($this->mod,true).';';
		$str .= '$data = '.var_export($this->data,true).';';
		$str .= '$block = '.var_export($this->block,true).';';
		$str .= "function load_template_".basename($this->tpl,".tpl")."(\$block_name,\$block,\$data)\n";
		$str .= "{\n";
		$str .= "switch (\$block_name) {";
		$str .= $this->istack;
		$str .= "}\n";
		$str .= "return \$block[\$block_name];";
		$str .= "}\n";
		$str .= "?>";

		file_put_contents(__COMPILE__.'/'.$this->tpl.'@'.$this->tpl_name.'@'.$this->lang.'.php',$str);
	}

	//生成配置信息
	private function _make_conf()
	{
		MagikeAPI::exportArrayToFile(__COMPILE__.'/'.$this->tpl.'@'.$this->tpl_name.'.cnf.php',$this->include_file,'files');
	}

	//载入语言
	private function _load_lang($block_array)
	{
		if(is_array($block_array))
		{
		foreach($block_array as $val)
		{
			if(file_exists(__LANGUAGE__."/".$this->lang."/".$val.".php"))
			{
				$lang = array();
				require(__LANGUAGE__."/".$this->lang."/".$val.".php");
				$this->public_lang = array_merge($this->public_lang,$lang);
			}
		}
		}
		$this->public_rlang = MagikeAPI::replaceArray(array_keys($this->public_lang),'/{','}/is');
	}

	//替换语言
	private function _lang($input_html)
	{
		$input_html = preg_replace($this->public_rlang,$this->public_lang,$input_html);
		return $input_html;
	}

	//输出所有tag,并且组织tag
	private function _tag($input_html)
	{
		global $system;

		preg_match_all("/\[\/([_0-9a-zA-Z-]+)\]/i",$input_html,$out);
		if(!isset($out[1])) $out[1] = array();

		$this->_load_lang($out[1]);
		array_push($out[1],"@");
		foreach($out[1] as $val)
		{
					if($val != "@") preg_match("/\[{$val}\]\s*(.+?)\[\/{$val}\]/is",$input_html,$mystack);
					else $mystack[1] = $input_html;
					if(isset($mystack[1]))
					{
						$this->block[$val] = NULL;
						$mystack[1] = $this->_lang($mystack[1]);
						$mystack[1] = $this->_stack($mystack[1],$val);
						$mystack[1] = $this->_if($mystack[1],$val);
						$mystack[1] = $this->_callback($mystack[1],$val);
						if($val != "@")
						{
							if(isset($this->stack->data['module'][$val]))	$this->mod[$val] = $this->stack->data['module'][$val];
							$mystack[1] = $this->_filter($mystack[1],$val);
							$this->istack .= "case \"{$val}\":
							\$block['{$val}'] = \"".$mystack[1]."\";
							break;
							";
							$input_html = preg_replace("/\[{$val}\]\s*(.+?)\[\/{$val}\]/is","{\$block['{$val}']}",$input_html);
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
	private function _stack($input_html,$block_name)
	{
		//为段数据初始化做准备
		$input_html = stripslashes($input_html);
        $input_html = addslashes($input_html);
        $input_html = str_replace("\'","'",$input_html);

		if(preg_match_all("/\{\\$([_0-9a-zA-Z-]+)\}/is",$input_html,$out))
		{
			if(isset($out[1]))
			{
				foreach($out[1] as $val)
				{
					$this->data["block"][$block_name][$val] = NULL;
					$input_html = str_replace("{\$".$val."}","{\$data['block']['{$block_name}']['{$val}']}",$input_html);
				}
			}
		}

		//为全局数据初始化做准备
		if(preg_match_all("/\{\\$([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)\}/is",$input_html,$out))
		{
			if(isset($out[2]))
			{
				foreach($out[2] as $key => $val)
				{
					$this->data[$out[1][$key]][$val] = NULL;
					$input_html = str_replace("{\$".$out[1][$key].".".$val."}","{\$data['".$out[1][$key]."']['{$val}']}",$input_html);
				}
			}
		}

		return $input_html;
	}

	//解析filter语法
	private function _filter($input_html,$block_name)
	{
		global $system;

		if(preg_match_all("/\[filter:([_0-9a-zA-Z-]+)\]/is",$input_html,$out))
    	{
    			if(isset($out[1]))
    			{
    				foreach($out[1] as $key => $val)
    				{
    				$input_html = str_replace($out[0][$key],"",$input_html);
    				if(isset($this->stack->data['module'][$out[1][$key]]) && isset($this->mod[$block_name]))
    					{
    						if(!isset($this->mod[$block_name]["filter"]))	$this->mod[$block_name]["filter"] = array();
    						$this->mod[$block_name]["filter"][$out[1][$key]] = $this->stack->data['module'][$out[1][$key]];
    					}
    				}
    			}
    	}

    	return $input_html;
	}

	//解析if语法
    private function _if($input_html,$block_name)
    {
    	$if_tag = array();	//保存if标签的数?
    	$finish_tag = array();	//处理嵌套后的数组

    	//处理嵌套数据
    	if(preg_match_all("/\[\s*(.+?)\]/is",$input_html,$out))
 		{
        	if(isset($out[1]))		//如果找到符合的块
        	{
        	foreach($out[1] as $key => $val)
            {
            	if(preg_match("/if \s*(.+?)/is",$val))
            	{
					$tmp = stripslashes($val);
            		$input_html = str_replace($val,$tmp,$input_html);
            		$val = $tmp;
            		$val = preg_quote($val);
            		array_push($if_tag,$val);
            		continue;
            	}
            	else if($val == "/if")
            	{
            		array_push($finish_tag,array_pop($if_tag));
            	}
            }
            }
        }

        //开始解析条件语句
        if($finish_tag)
        {
        foreach($finish_tag as $val)
        	{
        		$input_html = preg_replace("/\[{$val}\]\s*(.+?)\[\/if\]/is","\".(("
        		.$this->_prase_if($val,$block_name).") ? \"\\1\" : NULL).\"",$input_html);
        	}
        }

        return $input_html;
    }

    private function _prase_if($str,$block_name)
    {
    		if(preg_match_all("/\\$([_0-9a-zA-Z\.]+)/is",$str,$out))
    		{
    			foreach($out[1] as $val)
    			{
    				$str = str_replace("$".$val,$this->_prase_ifvalue($val,$block_name),$str);
    			}
    		}
    		$str = str_replace("if ","",$str);
    		$str = str_replace("\\","",$str);
    		return $str;
    }

    private function _prase_ifvalue($str,$block_name)
    {
    	if(ereg("([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)",$str,$out))
    	{
    		$this->data[$out[1]][$out[2]] = NULL;
    		return "\$data['{$out[1]}']['{$out[2]}']";
    	}
    	else if(ereg("([_0-9a-zA-Z]+)",$str,$out) && $block_name != "@")
    	{
    		$this->data['block'][$block_name][$out[1]] = NULL;
    		return "\$data['block']['{$block_name}']['{$out[1]}']";
    	}
    	else return "NULL";
    }

    //处理callback语法
    private function _callback($input_html,$block_name)
    {
    	global $system;
    	if(preg_match_all("/\[callback:([_0-9a-zA-Z-]+)\]/is",$input_html,$out))
    	{
    		if(isset($out[1]))
    		{
    			foreach($out[1] as $key => $val)
    			{
    			$input_html = str_replace($out[0][$key],"",$input_html);
    			if(isset($this->stack->data['module'][$out[1][$key]]))
    			{
    				if($block_name != "@")	$this->mod += array($val => $this->stack->data['module'][$out[1][$key]]);
    				else	$this->mod = array_merge(array($val => $this->stack->data['module'][$out[1][$key]]) , $this->mod);
    			}
    			}
    		}
    	}

    	return $input_html;
    }
}
?>
