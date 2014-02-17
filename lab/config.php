<?php
BaseExploit::$url="http://localhost/wp38/";
BaseExploit::$path="/Users/abiusx/www/wp38/";
BaseExploit::$logdir="/tmp/logs"; //this folder should contain log files, it will pack them after each test into a folder
BaseExploit::$evasionFunction=function($length) {
	if ($length<4) return "";
	return "/*".str_repeat("'ZZZ",($length-4)/4)."*/";
};
// BaseExploit::$verbose=true;