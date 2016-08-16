<?php

$last= ($_POST['last']=='y'?true:false);
	$all= intval($_POST['all']);
	$offset= intval($_POST['offset']);
	$chunk= $_POST['chunk'];
	$length= strlen($chunk);
	if($offset===0 && strpos($chunk,',') !== false) $chunk= substr($chunk,strpos($chunk,',')+1);
	$fo= fopen(MODX_BASE_PATH.'box/_tmp', 'a');
	flock($fo, LOCK_EX);
	if($offset===0) ftruncate($fo, 0);
	fwrite($fo, $chunk);
	
	sleep(2);
	
	tolog('offset: '.$offset);
	tolog('length: '.$length);
	tolog('all: '.$all);
	tolog('last: '.($last?'y':'n'));
	
	if( ! $last) exit();
	
	fclose($fo);
	
	//----------------------------------------------
	
	$fo= fopen(MODX_BASE_PATH.'box/_tmp', 'r');
		if($fo)
		{
			tolog('import:: 01');

			$fo2= fopen(MODX_BASE_PATH.$file_path, 'w');
			if($fo2)
			{
				tolog('import:: 02');

				while( ! feof($fo))
				{
					$tmp= fread($fo, 1024*1024*8);
					$tmp= base64_decode($tmp);
					fwrite($fo2, $tmp);
				}

				tolog('import:: 03');
				fclose($fo2);
			}
			tolog('import:: 04');
			fclose($fo);
	
