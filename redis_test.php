<?php
	#包含redis自动加载文件
	require 'predis/autoload.php';
	
	#创建redis对象实例
	$redis = new Predis\Client();
	
	$redis -> lpush('numtotal','1','2','3');
	echo $redis -> get('foo');  

	
?>
