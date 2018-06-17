<?php

#	r_reporting(1);
	$target = '/opt/www/laiyoga'; // 生产环境web目录
	$token = 'P9d4nsHXmPpofjz2tUFTKfIRXhtmZEn0CPV2VoVy';
#	$json = json_decode(file_get_contents('php://input'), true);
#	if (empty($json['token']) || $json['token'] !== $token) {
#	    exit('error request');
#	}
	$cmds = array(
		'cd /opt/www/laiyoga/',
		'git pull'
	);
	foreach ($cmds as $cmd) {
	     var_dump(shell_exec($cmd));
	}
	#$cmd = "cd $target &&sudo -Hu nginx git pull";
	#echo $cmd;
	#var_dump(shell_exec($cmd));