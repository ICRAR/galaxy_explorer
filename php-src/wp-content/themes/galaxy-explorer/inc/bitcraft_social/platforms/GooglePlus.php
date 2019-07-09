<?php

class GooglePlus {
	
	private $googlePlus_app_id;
	private $gp_url;
	private $gp_description;
	
	function GooglePlus($app_id = null){
		
		
		//assign app id
		$this->googlePlus_app_id = $app_id;
				
	}
	
	
	/** 
	* set_url()
	*
	* this function will set the shared link
	* IMPORTANT: this need to be set
	*/
	function set_url($url){
	
		$this->gp_url = $url;
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
		
		$link = "https://plus.google.com/share?url=" .urlencode($this->gp_url);
		
		
		$code .= '<script type="text/javascript">
					$(document).ready(function() {
		
                                            $( "body" ).on( "click", "#'.$link_id.'", function(e) {
                                            
							window.open("'.$link.'", "googlePlus_popup", "width=626,height=436");
		
				
						});
					});
				</script>';
		
		return $code;
		
	}	
}