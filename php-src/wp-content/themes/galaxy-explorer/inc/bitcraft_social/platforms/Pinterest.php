<?php

class Pinterest {
	
	private $pinterest_app_id;
	private $pt_url;
	private $pt_image;
	private $pt_description;
	
	function Pinterest($app_id = null){
		
		//assign app id
		$this->pinterest_app_id = $app_id;
				
	}
	
	
	/** 
	* set_url()
	*
	* this function will set the shared link
	* IMPORTANT: this need to be set
	*/
	function set_url($url){
	
		$this->pt_url = $url;
	}
	
	
	/**
	* set_image()
	*
	*/
	function set_image($image){
	
		$this->pt_image = $image;
	}
	
	/**
	 * set_description()
	 *
	 * this function will override the og:description meta
	 */
	function set_description($description){
	
		$this->pt_description = $description;
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
		
		$link = "http://pinterest.com/pin/create/button/?url=" .urlencode($this->pt_url);
		
		if($this->pt_image){
			
			$link .= "&media=" .urlencode($this->pt_image);
		}
		
		if($this->pt_description){
				
			$link .= "&description=" .urlencode($this->pt_description);
		}
		
		
		$code .= '<script type="text/javascript">
					$(document).ready(function() {
		
						$( "body" ).on( "click", "#'.$link_id.'", function(e) {
				
							window.open("'.$link.'", "pinterest_popup", "width=750,height=436");
		
				
						});
					});
				</script>';
		
		return $code;
		
	}
}