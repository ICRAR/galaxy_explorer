<?php

class Email {
	
	private $email_app_id;
	private $email_address;
	private $email_subject;
	private $email_cc;
	private $email_bcc;
	private $email_body;
	
	function Email($app_id = null){
		
		
		//assign app id
		$this->email_app_id = $app_id;
				
	}
	
	
	/** 
	* set_address()
	*
	* this function will set email recipient
	* 
	*/
	function set_address($address){
	
		$this->email_address = $address;
	}
	
	function set_subject($subject){
	
		$this->email_subject = $subject;
	}
	
	function set_cc($cc){
	
		$this->email_cc = $cc;
	}
	
	function set_bcc($bcc){
	
		$this->email_bcc = $bcc;
	}
	
	function set_body($body){
	
		$this->email_body = $body;
	}
	
	
	/**
	 * create_sharing_link()
	 *
	 * this function will output a sharer link
	 * 
	 * Use classes for styling
	 */
	function create_sharing_link($link_id, $link_text, $classes = array()){
		
		
		$params = '';
		
		$classes_string = implode(' ', $classes);
		
		if($classes_string){
			
			$classes_string = ' class="'.$classes_string.'"';
		}
		
		$link = "mailto:";
		
		if($this->email_address){
			
			$link .= urlencode($this->email_address);
		}
		
		$link .= "?";
		
		if($this->email_subject){
				
			$params .= '&subject='.urlencode($this->email_subject);
		}
		
		if($this->email_cc){
		
			$params .= '&cc='.urlencode($this->email_cc);
		}
		
		if($this->email_bcc){
		
			$params .= '&bcc='.urlencode($this->email_bcc);
		}
		
		if($this->email_body){
		
			$params .= '&body='.urlencode($this->email_body);
		}
		
		$params = str_replace('+', '%20', $params);
		
		//trim first &
		$params = trim($params, '&');
		
		$code = '<a href="'.$link.$params.'" id="'.$link_id.'"'.$classes_string.'>'.$link_text.'</a>';
		
		
		return $code;
		
	}	
}