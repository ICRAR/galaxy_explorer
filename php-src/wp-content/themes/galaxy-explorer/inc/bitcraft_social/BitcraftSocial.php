<?php

require_once 'platforms/Facebook.php';
require_once 'platforms/Twitter.php';
require_once 'platforms/GooglePlus.php';
require_once 'platforms/Pinterest.php';
require_once 'platforms/LinkedIn.php';
require_once 'platforms/Email.php';

class BitcraftSocial {
	
	public $debug;
	private $facebook;
	private $twitter;
	private $googlePlus;
	private $pinterest;
	private $linkedIn;
	private $email;
	
	
	function BitcraftSocial($debug = false){
		
		$this->debug = $debug;
		
	}
	
	function facebook_init($app_id = null){
		
		//check it is a valid app id - 
		/*$object = json_decode(@file_get_contents('https://graph.facebook.com/'.$app_id));
		
		if(!$object ){
				
			if($this->debug){
				echo ('BitcraftSocial ERROR - FACEBOOK - This is not a valid facebook App Id');
			}
			
			return false;
		}*/
		
		
		$this->facebook = new Facebook($app_id);
		
		return $this->facebook;
		
	}
	
	function twitter_init($app_id = null){
	
		$this->twitter = new Twitter($app_id);
	
		return $this->twitter;
	
	}
	
	function googlePlus_init($app_id = null){
	
		$this->googlePlus = new GooGlePlus($app_id);
	
		return $this->googlePlus;
	
	}
	
	function pinterest_init($app_id = null){
	
		$this->pinterest = new Pinterest($app_id);
	
		return $this->pinterest;
	
	}
	
	function linkedIn_init($app_id = null){
	
		$this->linkedIn = new LinkedIn($app_id);
	
		return $this->linkedIn;
	
	}
	
	function email_init($app_id = null){
	
		$this->email = new Email($app_id);
	
		return $this->email;
	
	}
	
	
	
}