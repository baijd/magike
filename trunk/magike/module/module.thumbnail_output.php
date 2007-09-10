<?php
/**********************************
 * Created on: 2006-8-18
 * File Name : module.thumbnail_output.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class ThumbnailOutput extends MagikeModule
{
	private function getResampledImg($img,$size,$msize,$type = 0)
	{
		list($w,$h) = $size;
		list($mw,$mh) = $msize;
		
		if($type)
		{
			if($w/$h > $mw/$mh)
			{
				$rw = ($mw*$h)/$mh;
				$simg = imagecreatetruecolor($rw,$h);
				imagecopy($simg,$img,0,0,0,0,$rw,$h);
				$pimg = imagecreatetruecolor($mw,$mh);
				imagecopyresampled($pimg,$simg,0,0,0,0,$mw,$mh,$rw,$h);
				return $pimg;
			}
			else
			{
				$mw = ($w*$mh)/$h;
				$pimg = imagecreatetruecolor($mw,$mh);
				imagecopyresampled($pimg,$img,0,0,0,0,$mw,$mh,$w,$h);
				return $pimg;
			}
		}
		else
		{
			if($h/$w > $mh/$mw)
			{
				$rh = ($mh*$w)/$mw;
				$simg = imagecreatetruecolor($w,$rh);
				imagecopy($simg,$img,0,0,0,0,$w,$rh);
				$pimg = imagecreatetruecolor($mw,$mh);
				imagecopyresampled($pimg,$simg,0,0,0,0,$mw,$mh,$w,$rh);
				return $pimg;
			}
			else
			{
				$mh = ($h*$mw)/$w;
				$pimg = imagecreatetruecolor($mw,$mh);
				imagecopyresampled($pimg,$img,0,0,0,0,$mw,$mh,$w,$h);
				return $pimg;
			}
		}
	}
	
	public function runModule($args)
	{
		$require = array('width' => 80,
					'height' => 80);
		$getArgs = $this->initArgs($args,$require);
		

		
		$fileModel = $this->loadModel('files');
		$file = $fileModel->fetchOneByKey($_GET['file_id']);
		if($file && $file['file_name'] == $_GET['file_name'])
		{
			$path = __UPLOAD__.mgGetGuidPath($file['file_guid']).'/'.$file['file_guid'];
			if(file_exists($path) && function_exists("gd_info"))
			{
				$this->stack['static_var']['content_type'] = ($file['file_type'] == 'image/pjpeg' ? 'image/jpeg' : $file['file_type']);
				$fileType = array();
				
				$supportItem = gd_info();
				if($supportItem["GIF Read Support"] && $supportItem["GIF Create Support"])
				{
					$fileType[] = 'image/gif';
				}
				if($supportItem["JPG Support"])
				{
					$fileType[] = 'image/jpeg';
					$fileType[] = 'image/pjpeg';
				}
				if($supportItem["PNG Support"])
				{
					$fileType[] = 'image/png';
				}
				
				if(in_array($file['file_type'],$fileType))
				{
					$size = getimagesize($path);
					$image = imagecreatefromstring(file_get_contents($path));
					$dst = $this->getResampledImg($image,$size,array($getArgs['width'],$getArgs['height']),0);
					imagejpeg($dst, null, 100);
					return;
				}
				else
				{
					return file_get_contents($path);
				}
			}
			else
			{
				return '对不起,您要访问的资源不存在';
			}
		}
		else
		{
			return '对不起,您要访问的资源不存在';
		}
	}
}
?>
