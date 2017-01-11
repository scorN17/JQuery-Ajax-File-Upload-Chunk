<?php
	if($action=='fileChunkUpload')
	{
		$fs= intval($_GET['fs']);
		$ii= intval($_GET['ii']);
		$cc= intval($_GET['cc']);
		$kk= intval($_GET['kk']);
		$chunk= $_POST['chunkblob'];
		if(strpos($chunk,',') !== false) $chunk= substr($chunk,strpos($chunk,',')+1);
		$chunk= base64_decode($chunk);
		
		// usleep($kk*300000); //0.3 секунды
		// usleep($kk*10000); //0.01 секунды
		
		$tmpfolder= '/assets/tmp/orders/'.$code.'/';
		if( ! file_exists($nc_core->DOCUMENT_ROOT.$tmpfolder)) mkdir($nc_core->DOCUMENT_ROOT.$tmpfolder, 0777, true);
		
		$fn= trim(urldecode($_GET['fn']));
		$fn= generAlias($fn);
		$fn= $fs.'_'.$fn;
		
		clearstatcache();
		
		$fo= fopen($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn.'_'.$kk, 'w');
		fwrite($fo, $chunk);
		fclose($fo);
		
		$chunkssizesum= 0;
		for($ww=0; $ww<$cc; $ww++)
		{
			$chunkssizesum += filesize($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn.'_'.$ww);
		}
		
		if($chunkssizesum<$fs)
		{
			print $chunkssizesum;
		}else{
			print 'lastchunk';
			
			$fo= fopen($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn, 'w');
			if($fo)
			{
				flock($fo, LOCK_EX);
				for($ww=0; $ww<$cc; $ww++)
				{
					$fo2= fopen($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn.'_'.$ww, 'r');
					$contents= fread($fo2, filesize($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn.'_'.$ww));
					fwrite($fo, $contents);
					fclose($fo2);
					unlink($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn.'_'.$ww);
				}
				fclose($fo);
			}
			
			if(filesize($nc_core->DOCUMENT_ROOT.$tmpfolder.$fn)==$fs)
			{
			}
		}
	}
