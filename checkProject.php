<?php

function checkDir($path)
{
	$resource = opendir($path);
	$rc = copy('index.html', $path.'/index.html');

	while($dir = readdir($resource))
	{
		if($dir === '.' || $dir === '..') continue;

		$currentpath = $path.'/'.$dir;
		if(is_dir($currentpath))
		{
			//$rc = copy('index.html', $currentpath.'/index.html');
			checkDir($currentpath);
		}
		elseif(is_file($currentpath))
		{
			$info = pathinfo($currentpath);
			if($info['extension'] != 'php') continue;

			$content = file_get_contents($currentpath);

			if(strpos($content, '_JEXEC') !== false) continue;

			$content = "<?php \n defined('_JEXEC') or die(); \n".substr($content, 6);
			$rc = file_put_contents($currentpath, $content);
		}
	}
}

checkDir(dirname(__FILE__).'/tcpdf');