<?php
/**********************************
 * Created on: 2006-12-5
 * File Name : build.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Build extends MagikeObject
{
	private $templateFile;
	private $template;
	private $includeFile;
	private $module;

	function __construct($templateFile,$template)
	{
		parent::__construct(array('public' => array('stack')));

		$this->template = $template;
		$this->templateFile = str_replace('.tpl','',$templateFile);
		$this->includeFile = array();
		$this->module = array();

		$html = $this->link($this->templateFile);
		$html = $this->filterVarSyntax($html);
		$html = $this->filterIfSyntax($html);
		$html = $this->filterModuleSyntax($html);
		$html = $this->filterLoopSyntax($html);
		$html = $this->filterWhileSyntax($html);
		$this->makeIncludeFile();
		$this->makeConfigFile();

		file_put_contents(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'.php',$html);
	}

	private function link($inputTpl)
	{
		$templateFile = __TEMPLATE__.'/'.$this->template.'/'.$inputTpl.'.tpl';
		if(!file_exists($templateFile))
		{
			$this->throwException(E_FILENOTEXISTS,$templateFile);
		}
		if(array_key_exists($templateFile,$this->includeFile))
		{
			return	NULL;
		}

		$this->includeFile[$templateFile] = filemtime($templateFile);
    	$fileArray = @file($templateFile);
        foreach($fileArray as $key=>$val)
        {
            if(preg_match_all("/\[include:\s*(.+?)\]/is",$val,$out))
            {
                $fileArray[$key] = $this->_link($out[1][0]);
            }
        }

        return implode("", $fileArray);
	}

	private function makeConfigFile()
	{
		MagikeAPI::exportArrayToFile(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'.cnf.php',$this->includeFile,'files');
	}

	private function praseVar($input)
	{
		$input = preg_replace("/\\$([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)/is","\$this->data['\\1']['\\2']['\\3']",$input);
		$input = preg_replace("/\\$([_0-9a-zA-Z-]+)\[\\$([_0-9a-zA-Z-]+)\]\.([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)/is","\$this->data['\\1'][\$\\2]['\\3']['\\4']",$input);
		$input = preg_replace("/\\$([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)/is","\$this->data['\\1']['\\2']",$input);
		$input = preg_replace("/\\$([_0-9a-zA-Z-]+)\[\\$([_0-9a-zA-Z-]+)\]\.([_0-9a-zA-Z-]+)/is","\$this->data['\\1'][\$\\2]['\\3']",$input);
		$input = preg_replace("/\\$([^this\-][_0-9a-zA-Z-]+)/is","\$this->data['\\1']",$input);
		$input = preg_replace("/\\$([^this\-][_0-9a-zA-Z-]+)\[\\$([_0-9a-zA-Z-]+)\]/is","\$this->data['\\1'][\$\\2]",$input);
		return $input;
	}

	private function filterVarSyntax($input)
	{
		$input = preg_replace_callback("/\{([_0-9a-zA-Z-\.\[\]\\$]+)\}/is",array($this,'filterVarSyntaxCallback'),$input);
		return $input;
	}

	private function filterVarSyntaxCallback($matches)
	{
		$matches[0] = substr($matches[0],1,-1);
		$matches[0] = "<?php echo ".$matches[0]."; ?>";
		return $this->praseVar($matches[0]);
	}

	private function filterIfSyntax($input)
	{
		$input = preg_replace_callback("/\[if \s*(.+?)\]/is",array($this,'filterIfSyntaxCallback'),$input);
		$input = str_replace("[/if]","<?php } ?>",$input);
		$input = str_replace("[else]","<?php else{ ?>",$input);
		$input = str_replace("[/else]","<?php } ?>",$input);
		return $input;
	}

	private function filterIfSyntaxCallback($matches)
	{
		$matches[1] = $this->praseVar($matches[1]);
		return "<?php if($matches[1]){ ?>";
	}

	private function filterModuleSyntax($input)
	{
		$input = preg_replace_callback("/\[module:([_0-9a-zA-Z-]+)\]/is",array($this,'filterModuleSyntaxCallback'),$input);
		return $input;
	}

	private function filterModuleSyntaxCallback($matches)
	{
		$this->module[] = $matches[1];
		return NULL;
	}

	private function filterLoopSyntax($input)
	{
		$input = preg_replace_callback("/\[loop@\(([_0-9a-zA-Z-\.\[\]\\$]+),([_0-9a-zA-Z-\.\[\]\\$]+),([_0-9a-zA-Z-\.\[\]\\$]+)\)\]/is",
										array($this,'filterLoopSyntaxCallback'),$input);
		$input = str_replace("[/loop]","<?php } ?>",$input);
		return $input;
	}

	private function filterLoopSyntaxCallback($matches)
	{
		$matches[0] = "<?php for(".$this->praseVar($matches[3])." = "
					  .$this->praseVar($matches[1]).";"
					  .$this->praseVar($matches[3])." < "
					  .$this->praseVar($matches[2]).";"
					  .$this->praseVar($matches[3])."++) { ?>";
		return $matches[0];
	}

	private function filterWhileSyntax($input)
	{
		$input = preg_replace_callback("/\[while@\(([_0-9a-zA-Z-\.\[\]\\$]+),([_0-9a-zA-Z-\.\[\]\\$]+)\)\]/is",
										array($this,'filterWhileSyntaxCallback'),$input);
		$input = str_replace("[/while]","<?php } ?>",$input);
		return $input;
	}

	private function filterWhileSyntaxCallback($matches)
	{
		$matches[0] = "<?php for(".$this->praseVar($matches[2])." = 0;"
					  .$this->praseVar($matches[2])." < "
					  ."count(".$this->praseVar($matches[1]).");"
					  .$this->praseVar($matches[2])."++) { ?>";
		return $matches[0];
	}

	private function makeIncludeFile()
	{
		$include = array();
		$str = "";
		foreach($this->module as $module)
		{
			$include[] = MagikeAPI::fileToModule($module);
			if(isset($this->stack->data['module'][$module]) && file_exists($this->stack->data['module'][$module]))
			{
				$str .= php_strip_whitespace($this->stack->data['module'][$module]);
			}
		}

		$str = "<?php\n\$module=".var_export($include,true)."; ?>".$str;
		file_put_contents(__COMPILE__.'/'.$this->templateFile.'@'.$this->template.'.inc.php',$str);
	}
}
?>
