<?php

class Twitter {
	
	private $twitter_app_id;
	private $tw_url;
	private $tw_description;
	
	function Twitter($app_id = null){
		
		
		//assign app id
		$this->twitter_app_id = $app_id;
				
	}
	
	
	/** 
	* set_url()
	*
	* this function will set the shared link
	* IMPORTANT: this need to be set
	*/
	function set_url($url){
	
		$this->tw_url = $url;
	}
	
	
	/**
	 * set_description()
	 *
	 * this function will override the og:description meta
	 */
	function set_description($description){
	
		$this->tw_description = $description;
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
		
		
		$code = '<a href="javascript:void(0)" id="'.$link_id.'"'.$classes_string.'>'.$link_text.'</a>';
		
		$link = "https://twitter.com/share?url=" .urlencode($this->tw_url)."&text=" .urlencode($this->tw_description);
		
		
		$code .= '<script type="text/javascript">
					$(document).ready(function() {
		
						$( "body" ).on( "click", "#'.$link_id.'", function(e) {
				
							window.open("'.$link.'", "twitter_popup", "width=626,height=436");
		
				
						});
					});
				</script>';
		
		return $code;
		
	}
}