<?php

class Facebook {
	
	private $facebook_app_id;
	private $fb_title;
	private $fb_url;
	private $fb_image;
	private $fb_caption;
	private $fb_description;
	
	function Facebook($app_id = null){
		
		
		//assign app id
		$this->facebook_app_id = $app_id;
		
		$code = '
				<div id="fb-root"></div>
				<script type="text/javascript">
					window.fbAsyncInit = function() {
						FB.init({
							appId : "'.$this->facebook_app_id.'",
							status : true,
							cookie : true,
							xfbml : true
						});
					};
					(function() {
						var e = document.createElement("script");
						e.async = true;
						e.src = document.location.protocol
						+ "//connect.facebook.net/en_US/all.js";
		
					document.getElementById("fb-root").appendChild(e);
					}()
		
					);
				</script>';
		
		echo $code;
				
	}
	
	
	/**
	* set_name()
	*
	* this function will override the og:title meta
	*
	*/
	function set_title($title){
		
		$this->fb_title = $title;
	}
	
	
	/** 
	* set_url()
	*
	* this function will set the shared link
	* IMPORTANT: this need to be set
	*/
	function set_url($url){
	
		$this->fb_url = $url;
	}
	
	
	/**
	* set_image()
	*
	* this function will override the og:image
	* IMPORTANT: Use images that are at least 1200 x 630 pixels for 
	* 			 the best display on high resolution devices. At the minimum, 
	* 			 you should use images that are 600 x 315 pixels to display link 
	* 			 page posts with larger images.
	* 			 If your image is smaller than 600 x 315 px, 
	* 			 it will still display in the link page post, but the size will be much smaller.
	*/
	function set_image($image){
	
		$this->fb_image = $image;
	}
	
	/**
	 * set_caption()
	 *
	 * this function will override the link qappearing under the title
	 * IMPORTANT: There is not a specific og tag for this
	 */
	function set_caption($caption){
	
		$this->fb_caption = $caption;
	}
	
	
	/**
	 * set_description()
	 *
	 * this function will override the og:description meta
	 */
	function set_description($description){
	
		$this->fb_description = $description;
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
		
		//set parameters
		if($this->fb_title){
			
			$params .= 'name : "'.addslashes($this->fb_title).'",';
		}
		
		if($this->fb_url){
				
			$params .= 'link : "'.addslashes($this->fb_url).'",';
		}
		
		if($this->fb_image){
		
			$params .= 'picture : "'.addslashes($this->fb_image).'",';
		}
		
		if($this->fb_caption){
		
			$params .= 'caption : "'.addslashes($this->fb_caption).'",';
		}
		
		if($this->fb_description){
		
			$params .= 'description : "'.addslashes($this->fb_description).'",';
		}
		
		//trim last comma
		$params = trim($params, ',');
		
		//add initial comma if field valid
		if($params){
			
			$params = ','.$params;
		}
		
		$code .= '<script type="text/javascript">
					$(document).ready(function() {
						
						$("#'.$link_id.'").click(function(e){
							
							e.preventDefault();
							
							FB.ui({
								method : "feed"
								'.$params.'
							});
				
							
						});
					});
				</script>';
		
		return $code;
		
	}
	
	
	
}