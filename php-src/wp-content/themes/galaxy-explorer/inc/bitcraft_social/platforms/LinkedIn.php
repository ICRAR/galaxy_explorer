<?php

class LinkedIn {
	
	private $linkedIn_app_id;
	private $li_url;
	private $li_title;
	private $li_source;
	private $li_description;
	
	function LinkedIn($app_id = null){
		
		//assign app id
		$this->linkedIn_app_id = $app_id;
				
	}
	
	
	/** 
	* set_url()
	*/
	function set_url($url){
	
		$this->li_url = $url;
	}
	
	
	/**
	* set_title()
	*
	*/
	function set_title($title){
	
		$this->li_title = $title;
	}
	
	/**
	 * set_source()
	 */
	function set_source($source){
	
		$this->li_source = $source;
	}
	
	/**
	 * set_description()
	 */
	function set_description($description){
	
		$this->li_description = $description;
	}
	
	
	/**
	 * create_sharing_link()
	 *
	 * this function will output a sharer link
	 * Use classes for styling
	 */
	function create_sharing_link($link_id, $link_text, $classes = array()){
		
		
		$params = '';
		
		$classes_string = implode(' ', $classes);
		
		if($classes_string){
			
			$classes_string = ' class="'.$classes_string.'"';
		}
		
		
		$code = '<a href="javascript:void(0)" id="'.$link_id.'"'.$classes_string.'>'.$link_text.'</a>';
		
		$link = "http://www.linkedin.com/shareArticle?mini=true";
		
		if($this->li_url){
			
			$link .= "&url=" .urlencode($this->li_url);
		}
		
		if($this->li_title){
				
			$link .= "&title=" .urlencode($this->li_title);
		}
		
		if($this->li_source){
		
			$link .= "&source=" .urlencode($this->li_source);
		}
		
		if($this->li_description){
				
			$link .= "&summary=" .urlencode($this->li_description);
		}
		
		
		$code .= '<script type="text/javascript">
					$(document).ready(function() {
		
						$( "body" ).on( "click", "#'.$link_id.'", function(e) {'
                        . '                     
							window.open("'.$link.'", "linkedin_popup", "width=750,height=436");
		
				
						});
					});
				</script>';
		
		return $code;
		
	}	
}