<?php 



/*******************
 * Modify the User Search in Admin to include first, last names.
 * Add sorting by name if search string starts with 'byname:'.
*/
add_action('pre_user_query','custom_pre_user_query');

function custom_pre_user_query($user_search) {
   global $wpdb;
   
   
   $user = wp_get_current_user();
   
   if( ! current_user_can( 'edit_user' ) ) return $user_search;
   
   
   // print_r($user_search);
   $vars = $user_search->query_vars;
   if (!is_null($vars['search'])) {
      /* For some reason, the search term is enclosed in asterisks.
         Remove them */
      $search = preg_replace('/^\*/','',$vars['search']);
      $search = preg_replace('/\*$/','',$search);
      $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON " .
         "{$wpdb->users}.ID=m1.user_id AND (m1.meta_key='first_name')";
      $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m2 ON " .
         "{$wpdb->users}.ID=m2.user_id AND (m2.meta_key='last_name')";
 
      // IF the search var starts with byname:, sort by name.
      if (preg_match('/^byname:/',$search)) {
         $search = preg_replace('/^byname:/','',$search);
         $user_search->query_orderby = ' ORDER BY UPPER(m2.meta_value), UPPER(m1.meta_value) ';
         $user_search->query_vars['search'] = $search;
         $user_search->query_where = str_replace('byname:','',$user_search->query_where);
      }
      $names_where = $wpdb->prepare("m1.meta_value LIKE '%s' OR m2.meta_value LIKE '%s'",
         "%{$search}%","%$search%");
      $user_search->query_where = str_replace('WHERE 1=1 AND (',
         "WHERE 1=1 AND ({$names_where} OR ",$user_search->query_where);
   }
   
   //print_r('<br />SEARCH OBJECT: ');print_r($user_search);
   //print_r('<br />SEARCH TERM: ');print_r($search);
   //print_r('<br />QUERY_FROM: ');print_r($user_search->query_from);
   //print_r('<br />NAMES_WHERE: ');print_r($names_where);
   //print_r('<br />QUERY_WHERE: ');print_r($user_search->query_where);
}

/*----------------------------------------------------------------------------------------------
 //admin stats
 ------------------------------------------------------------------------------------------------*/
add_action( 'admin_menu', 'register_stats_page' );

function register_stats_page(){
	add_menu_page( 'custom menu title', 'Statistics', 'manage_options', 'custompage', 'dashboard_admin_site_statistics', '', 120 );
}

/*----------------------------------------------------------------------------------------------
 add shortcode
 ------------------------------------------------------------------------------------------------*/
add_shortcode( 'compeligible', 'compeligible_func' );
function compeligible_func( $atts ) {

	if(COMPETITION_ACTIVE){

		if (user_can_enter_competition()){
			return "<p><strong>Congratulations! <br />You are now eligible to enter the competition for a chance to win a tablet device. <a href='/competition'>Enter Now.</a></strong></p>";
		}

	}

	return "";
}


/*----------------------------------------------------------------------------------------------
 //trick for Contact Form 7 plugin. add class to form
 ------------------------------------------------------------------------------------------------*/
add_filter( 'wpcf7_form_class_attr', 'wpcf7_form_custom_class' );

function wpcf7_form_custom_class( $class ) {
	$class .= ' general-form';
	return $class;
}




/*----------------------------------------------------------------------------------------------
 handy function for formatted print for debugging
 ------------------------------------------------------------------------------------------------*/
if (!function_exists( 'preprint' )){

	function preprint($data){

		echo "<pre style='background_color:#FFFFFF;color:#000000'>";
		print_r($data);
		echo "</pre>";
	}
}


/*----------------------------------------------------------------------------------------------
 Clean input data
 ------------------------------------------------------------------------------------------------*/


function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }

function cleanInput_array($data)
{

	
	$exclusions = array("pass", "repassword");
	foreach($data AS $key=>$value)
	{
		if (in_array($key, $exclusions))
			continue;

		$data[$key] = ( cleanInput($value) );
	}

	return $data;

}


/*----------------------------------------------------------------------------------------------
 Create a random string given the length
 ------------------------------------------------------------------------------------------------*/
function getRandomString($length){

	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$size = strlen( $chars );
	$string = '';

	for( $i = 0; $i <= $length - 1 ; $i++ ) {
		$string .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $string;
}


/*----------------------------------------------------------------------------------------------
 generate a valid random username that doesn't already exist
 ------------------------------------------------------------------------------------------------*/
function generateUsername($length = 20, $prefix = ''){

	$validUsername = false;

	//try max 10 times, should be enough
	for ($x=0; $x < 10 && !$validUsername; $x++) {

		$username = getRandomString($length);

		$username = $prefix . $username;

		//check if exist
		if ( !username_exists( $username ) ){

			$validUsername = true;
		}
	}


	if ($validUsername){

		return $username;
	}else{

		return false;
	}
}


/*----------------------------------------------------------------------------------------------
 generate a fake email for temp users
 ------------------------------------------------------------------------------------------------*/
function generateFakeEmail($length = 20, $prefix = ''){

	$validEmail = false;

	//try max 10 times, should be enough
	for ($x=0; $x < 10 && !$validEmail; $x++) {

		$fakeEmail = getRandomString($length);

		$fakeEmail = $prefix . $fakeEmail.'@fake.com';

		//check if exist
		if ( !email_exists( $fakeEmail ) ){

			$validEmail = true;
		}
	}


	if ($validEmail){

		return $fakeEmail;
	}else{

		return false;
	}
}



/*----------------------------------------------------------------------------------------------
 check email field valid
 ------------------------------------------------------------------------------------------------*/
function validEmail($email){

	$isValid = true;

	$atIndex = strrpos($email, "@");

	if (is_bool($atIndex) && !$atIndex){

		$isValid = false;

	}else{

		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);

		if ($localLen < 1 || $localLen > 64){
				
			// local part length exceeded
			$isValid = false;

		}else if ($domainLen < 1 || $domainLen > 255){

			// domain part length exceeded
			$isValid = false;

		}else if ($local[0] == '.' || $local[$localLen-1] == '.'){

			// local part starts or ends with '.'
			$isValid = false;

		}else if (preg_match('/\\.\\./', $local)){

			// local part has two consecutive dots
			$isValid = false;

		}else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){

			// character not valid in domain part
			$isValid = false;

		}else if (preg_match('/\\.\\./', $domain)){

			// domain part has two consecutive dots
			$isValid = false;

		}else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))){

			// character not valid in local part unless
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))){

				$isValid = false;

			}

		}

		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){

			// domain not found in DNS
			$isValid = false;

		}
	}

	return $isValid;
}



/*----------------------------------------------------------------------------------------------
 remove wordpress backend login for subscriber users
 ------------------------------------------------------------------------------------------------*/
add_action('admin_init', 'no_login_dashboard');

function no_login_dashboard() {

	//redirect subscriber users to homepage
	if(defined('DOING_AJAX') && DOING_AJAX){
		//allow
	}elseif (!current_user_can('manage_options') ) {
		wp_redirect(home_url());
	}
}



/*----------------------------------------------------------------------------------------------
 handle login redirections
 ------------------------------------------------------------------------------------------------*/
//add_filter( 'login_url', 'default_logon_url' );
function default_logon_url() {

	return esc_url( home_url( '/' ) );
}

/*----------------------------------------------------------------------------------------------
 handle logout redirections
 ------------------------------------------------------------------------------------------------*/
add_filter( 'logout_url', 'default_logout_url' );
function default_logout_url($default){

	if(is_admin()){

		return $default. '&amp;redirect_to=' . urlencode( get_bloginfo('url') );

	}else{

		return $default;

	}
}



/*----------------------------------------------------------------------------------------------
 get images base path
 ------------------------------------------------------------------------------------------------*/
function get_image_path(){

	//in case we want to move images
	return get_template_directory_uri().'/images';

}




/*----------------------------------------------------------------------------------------------
 get tool image directory base path
 ------------------------------------------------------------------------------------------------*/
function get_tool_image_path(){

	return 'https://s3-us-west-2.amazonaws.com/galaxyexplorer-images/ABCimages';

}




/*----------------------------------------------------------------------------------------------
 send emails setting html type
 ------------------------------------------------------------------------------------------------*/
function send_email($to, $subject, $message, $headers = '', $attachments = array()){


	add_filter( 'wp_mail_content_type', 'set_html_content_type' );

	if(!wp_mail($to, $subject, $message, $headers = '', $attachments = array())){

		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		return false;
	};

	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	return true;

}

function set_html_content_type(){
	return 'text/html';
}

add_filter( 'wp_mail_from_name', 'custom_wp_mail_from_name' );
function custom_wp_mail_from_name( $original_email_from ) {
	return EMAILS_FROM_NAME;
}

add_filter( 'wp_mail_from', 'custom_wp_mail_from' );
function custom_wp_mail_from( $original_email_address ) {
	return EMAILS_FROM;
}



/*----------------------------------------------------------------------------------------------
 //generate social icon list html
 ------------------------------------------------------------------------------------------------*/
function get_social_icons_list(){

	//TOD - maybe need to fix links
	echo '<ul class="social-networks">';
	echo '<li><a href="http://www.facebook.com/ABCScience" target="_blank">facebook</a></li>';
	echo '<li><a class="twitter" href="https://twitter.com/ABCscience" target="_blank">twitter</a></li>';
	echo '<li><a class="email" href="http://www.abc.net.au/science/contact/lists/updates/" target="_blank">email</a></li>';
	echo '</ul>';
}

/*----------------------------------------------------------------------------------------------
 //generate social sharing icons for tool
 ------------------------------------------------------------------------------------------------*/
function get_share_icons_tool($share_data, $share_working = true, $favourite = true){
	
	if($share_working){
		
		include_once  get_stylesheet_directory().'/inc/bitcraft_social/BitcraftSocial.php';
		
		$sharing = new BitcraftSocial(false);
		
		//Facebook
		$facebook = $sharing->facebook_init('xxxxxx');
		
		//Facebook - set parameters(these will override the og tags)
		$facebook->set_title('ABC Galaxy Explorer');
		$facebook->set_url(esc_url( home_url( '/' ) ));
		$facebook->set_image($share_data['image_path']);
		$facebook->set_caption('Image '.$share_data['image_name'] . ', '. $share_data['image_distance']);
		$facebook->set_description('Be a citizen scientist with the ABC Galaxy Explorer. Classify galaxy images and help Australian researchers understand how galaxies evolve.');
	
		$facebook_link = $facebook->create_sharing_link('share-facebook', 'Share on Facebook', array('social'));

		//Twitter
		$twitter = $sharing->twitter_init();
		
		//Twitter - set parameters
		$twitter->set_url(esc_url( home_url( '/' ) ));
		$twitter->set_description('Be a citizen scientist with the ABC Galaxy Explorer. Classify galaxy images and help us understand how galaxies evolve');
		
		$twitter_link = $twitter->create_sharing_link('share-twitter', 'Share on Twitter', array('social'));
	}else{
		$facebook_link = '<a class="social" href="javascript:void(0)" id="share-facebook">Share on Facebook</a>';
		$twitter_link = '<a class="social" href="javascript:void(0)" id="share-twitter">Share on Twitter</a>';
	}
	
	$html = '';
	$html .= '<ul id="social-share-icons" class="clear">';
	
	
	if($favourite){
		$html .= '<li><a href="javascript:void(0)" id="add-favourites">Add favourites</a></li>';
	}
	$html .= '<li>'.$facebook_link.'</li>';
	$html .= '<li>'.$twitter_link.'</li>';
	//$html .= '<li><a class="social" href="'.todo().'" id="share-instagram">Share on Instagram</a></li>';
	
	$html .= '</ul>';

	return $html;
}


/*----------------------------------------------------------------------------------------------
 //generate social sharing icons for article page
 ------------------------------------------------------------------------------------------------*/
function get_article_sharing_icons($identifier, $image_url,$title){
	
	include_once  get_stylesheet_directory().'/inc/bitcraft_social/BitcraftSocial.php';
	
	$sharing = new BitcraftSocial(false);
	
	//Facebook
	$facebook = $sharing->facebook_init('xxxxxx');
	
	//Facebook - set parameters(these will override the og tags)
	$facebook->set_title('ABC Galaxy Explorer');
	//$facebook->set_url(esc_url( home_url( '/' ) ));
	$facebook->set_image($image_url);
	$facebook->set_caption($title);
	$facebook->set_description('Be a citizen scientist with the ABC Galaxy Explorer. Classify galaxy images and help Australian researchers understand how galaxies evolve.');
	
	$facebook_link = $facebook->create_sharing_link('share-facebook-'.$identifier, 'Share on Facebook', array('social', 'facebook'));
	
	//Twitter
	$twitter = $sharing->twitter_init();
	
	//Twitter - set parameters
	//$twitter->set_url(esc_url( home_url( '/' ) ));
	$twitter->set_description('Be a citizen scientist with the ABC Galaxy Explorer. Classify galaxy images and help us understand how galaxies evolve');
	
	$twitter_link = $twitter->create_sharing_link('share-twitter-'.$identifier, 'Share on Twitter', array('social', 'twitter'));
	
	$html = '';
	$html .= '<ul id="social-share-icons" class="clear">';
	
	
	$html .= '<li>'.$facebook_link.'</li>';
	$html .= '<li>'.$twitter_link.'</li>';
	//$html .= '<li><a class="social" href="'.todo().'" id="share-instagram">Share on Instagram</a></li>';
	
	$html .= '</ul>';
	
	return $html;
	
}


/*----------------------------------------------------------------------------------------------
 //generate html for image zoom controls
 ------------------------------------------------------------------------------------------------*/
function get_zoom_controls(){
	
	$html = '';
	
	$html .= '<div class="tool-header-image-controllers">';
	$html .= '<ul class="clear">';
	$html .= '<li><a href="javascript:void(0)" id="tool-zoom-out">Zoom Out</a></li>';
	$html .= '<li><a href="javascript:void(0)" id="tool-zoom-in">Zoom In</a></li>';
	$html .= '<li><a href="javascript:void(0)" id="tool-zoom-reset">Reset</a></li>';
	$html .= '</ul>';
	$html .= '</div>';
	
	return $html;
}


/*----------------------------------------------------------------------------------------------
 //create session user
 ------------------------------------------------------------------------------------------------*/
function get_user_id_from_session(){

	if(!isset($_SESSION['user_id'])){

		$username = generateUsername();
		$password = getRandomString(20);
		$ademail = generateFakeEmail(10, 'temp_');


		//all good at this point, insert new user in db
		$newUserArgs = array(
				'user_login'	=> $username,
				'user_pass'		=> $password,
				'user_email'	=> $ademail,
				'role'			=> 'session_guest'
		);

		$user_id = wp_insert_user($newUserArgs);

		if ( is_wp_error($user_id) ){

			return false;
		}

		//user created, update with additional details provided
		update_user_meta( $user_id, 'show_admin_bar_front', 'false');
		update_user_meta( $user_id, 'show_tutorial', 'true');
		update_user_meta( $user_id, 'user_type', 'individual');

		$_SESSION['user_id'] = $user_id;
	}

	return $_SESSION['user_id'];

}

/*----------------------------------------------------------------------------------------------
 //custom function to get the user id depending if is logged in or stored in session
 ------------------------------------------------------------------------------------------------*/
function get_current_custom_user_id(){

	if (is_user_logged_in()) {

		$user_id = get_current_user_id();
	}else{

		$user_id = get_user_id_from_session();
	}

	return $user_id;

}


/*----------------------------------------------------------------------------------------------
 //if there is an user in session, merge it wit the real logged in user
 ------------------------------------------------------------------------------------------------*/
function merge_session_user(){
	
	if(is_user_logged_in() && isset($_SESSION['user_id']) && $_SESSION['user_id'] !=0){

		global $wpdb;
		
		
		//update favourite table
		$wpdb->query("UPDATE wp_favourite
						SET user_id = ".get_current_user_id()."
					  WHERE user_id = ".$_SESSION['user_id']
		);
		
		//if temp user completed the tutorial, mark as completed for the user as well
		$tutorial = get_user_meta($_SESSION['user_id'], 'show_tutorial', true);
		
		if($tutorial == 'false'){
			update_user_meta(get_current_user_id(), 'show_tutorial', 'false');
		}
		
		
		
		
		//only for individual user
		if(is_user_individual()){
			//if total images marked by guest user + reminder of the actual user is >= limit, mark for competition popup
			if(get_user_total_images($_SESSION['user_id']) + (get_user_total_images(get_current_user_id()) % COMPETITION_IMAGES_SINGLE ) >= COMPETITION_IMAGES_SINGLE ){
				
				update_user_meta(get_current_user_id(), 'popup-competition', 'true');
				
				
			}
		}else{
			//if total images marked by guest user + reminder of the actual user is >= limit, mark for competition popup
			if(get_user_total_images($_SESSION['user_id']) + (get_user_total_images(get_current_user_id()) % COMPETITION_IMAGES_GROUP ) >= COMPETITION_IMAGES_GROUP ){
				
				//don't show competition, just enter competition
				update_user_meta(get_current_user_id(), 'popup-competition', 'true');
				
				
				
				
			}
		}
		
		
		//update results table
		$wpdb->query("UPDATE wp_result
						SET user_id = ".get_current_user_id()."
					  WHERE user_id = ".$_SESSION['user_id']
		);
				
		//delete session user
		wp_delete_user( $_SESSION['user_id'], get_current_user_id());
		
	}
	
	
	unset($_SESSION['user_id']);
	
}




/*----------------------------------------------------------------------------------------------
 //handle the registration process
 ------------------------------------------------------------------------------------------------*/
function handleRegistration($data){

	$response = array('error' => '','message' => '');

	//server side validation
	if(	!isset($data['option']) 			||
			!isset($data['firstname']) 			||
			!isset($data['lastname']) 			||
			!isset($data['country']) 			||
			!isset($data['state']) 				||
			//!isset($data['state_text']) 		||
			!isset($data['schoolGroupName'])	||
			!isset($data['schoolType']) 		||
			!isset($data['schoolGroupNumber']) 	||
			!isset($data['age']) 				||
			!isset($data['education']) 			||
			!isset($data['ademail']) 			||
			!isset($data['pass']) 				||
			!isset($data['repassword'])){

		$response['error'] = true;
		$response['message'] = 'something went wrong';

		return $response;

	}

	// sanitisa

	$data = cleanInput_array($data);

	//save friendly named variables
	$option = $data['option'];
	$firstname = $data['firstname'];
	$lastname = $data['lastname'];
	$country = $data['country'];
	$country_states = $data['country_states'];
	$state = $data['state'];
	//$state_text = $data['state_text'];
	$suburb = $data['suburb'];
	$schoolGroupName = $data['schoolGroupName'];
	$schoolType = $data['schoolType'];
	$schoolGroupNumber = $data['schoolGroupNumber'];

	$age = $data['age'];
	$education = $data['education'];
	$ademail = $data['ademail'];
	$phone = $data['phone'];
	$pass = $data['pass'];
	$repassword = $data['repassword'];

	if($option == 'individual'){
		$gender = $data['gender'];
	}else{
		$gender = '';
	}



	//CHECK IMPORTANTANT FIELDS(email and password) - Server side validation

	//CHECK EMAIL
	//check valorized
	if($ademail == ''){

		$response['error'] = true;
		$response['message'] = 'email is not valorized';

		return $response;
	}

	//check valid email
	if(!validEmail($ademail)){
			
		$response['error'] = true;
		$response['message'] = 'email is not valid';
			
		return $response;
			
	}

	//CHECK PASSWORD
	//check valorized
	if($pass == ''){

		$response['error'] = true;
		$response['message'] = 'password is not valorized';

		return $response;
	}

	//check length
	if(strlen($pass) < 6){

		$response['error'] = true;
		$response['message'] = 'password is too short';

		return $response;

	}

	//check password match
	if($pass != $repassword){

		$response['error'] = true;
		$response['message'] = 'password doesn\'t match';

		return $response;

	}

	//all good, check if user existing in session, otherwise create new
	if(!isset($_SESSION['user_id'])){


		//create random username
		$username = generateUsername();

		if(!$username){

			$response['error'] = true;
			$response['message'] = 'couldn\'t create a valid username, try again';

			return $response;
		}


		//all good at this point, insert new user in db
		$newUserArgs = array(
				'user_login'	=> $username,
				'user_pass'		=> $pass,
				'user_email'	=> $ademail,
				'role'			=> 'subscriber'
		);

		$user_id = wp_insert_user($newUserArgs);

		if ( is_wp_error($user_id) ){

			$response['error'] = true;
			$response['message'] = $user_id->get_error_message();

			return $response;
		}

		update_user_meta( $user_id, 'show_admin_bar_front', 'false');


		update_user_meta( $user_id, 'show_tutorial', 'true');
		

	}else{

		$user_id = $_SESSION['user_id'];

		//need to get user username
		$user_info = get_user_by( 'id', $user_id );
		$username = $user_info->user_login;

		//update user info
		//all good at this point, insert new user in db
		$newUserArgs = array(
				'ID'			=> $user_id,
				'user_pass'		=> $pass,
				'user_email'	=> $ademail,
				'role'			=> 'subscriber'
		);


		$user_id = wp_update_user($newUserArgs);

		if ( is_wp_error($user_id) ){

			$response['error'] = true;
			$response['message'] = $user_id->get_error_message();

			return $response;
		}

		//unset the session user_id. We are loggin the user in, so we don't need this anymore
		unset($_SESSION['user_id']);


	}


	//user created, update with additional details provided
	update_user_meta( $user_id, 'first_name', $firstname);
	update_user_meta( $user_id, 'last_name', $lastname);
	update_user_meta( $user_id, 'country', $country);

	if($country_states == 1){
		update_user_meta( $user_id, 'state', $state);
	}else{
		//update_user_meta( $user_id, 'state', $state_text);
	}
	
	update_user_meta( $user_id, 'suburb', $suburb);
	update_user_meta( $user_id, 'phone', $phone);

	if($option == 'group'){
		update_user_meta( $user_id, 'user_type', 'group');
		update_user_meta( $user_id, 'schoolGroupName', $schoolGroupName);
		update_user_meta( $user_id, 'schoolType', $schoolType);
		update_user_meta( $user_id, 'schoolGroupNumber', $schoolGroupNumber);
		update_user_meta( $user_id, 'email_competition', 'false');
	}else{
		update_user_meta( $user_id, 'user_type', 'individual');
		update_user_meta( $user_id, 'gender', $gender);
		update_user_meta( $user_id, 'age', $age);
		update_user_meta( $user_id, 'education', $education);
	}

	//sign in the user
	$userCredentials = array(
			'user_login'	=> $username,
			'user_password'	=> $pass,
			'remember' 		=> false
	);


	$user = wp_signon( $userCredentials, false );

	if ( is_wp_error($user) ){
			
		$response['error'] = true;
		$response['message'] = $user->get_error_message();

		return $response;
		
	}

		
	//set current user
	wp_set_current_user($user->ID);
		
	//if it is a school group, send email to teacher
	if(is_user_school_group()){
		
		//update_user_meta(get_current_user_id(), 'popup-competition', 'false');
		
		$to = $ademail;
		$subject = 'Galaxy Explorer Registration';
		
		$message = 
			'Thanks for signing up your school group - '.$schoolGroupName.' - to participate in Galaxy Explorer!<br /><br />
			Your group\'s login details are:<br />
			Email: '.$ademail.'<br />
			Password: '.$pass.'<br /><br />
			You just need to pass on the email address and password to the group members so they can login and start classifying galaxies.<br /><br />
			The first time each group member logs in, please ask them to complete the tutorial so that they can learn how to classify the galaxies. They can find tutorial in the Help section of the classifying section<br />
			<a href="'.esc_url( home_url( '/' ) ).'classify/">'.esc_url( home_url( '/' ) ).'classify/</a><br /><br />
			Good luck and have fun exploring!<br /><br />
			The Galaxy Explorer Team<br />
			ABC Science';
		
		send_email($to, $subject, $message);
		
		
	}	

	//ALL GOOD RETURN POSITIVE FEEDBACK
	$response['error'] = false;

	return $response;

}


/*----------------------------------------------------------------------------------------------
 //switch if user is an individual
 ------------------------------------------------------------------------------------------------*/
function is_user_individual(){

	if(get_user_meta(get_current_custom_user_id(), 'user_type', true) == 'individual'){

		return true;
	}

	return false;

}



/*----------------------------------------------------------------------------------------------
 //switch if user is a school group
 ------------------------------------------------------------------------------------------------*/
function is_user_school_group(){

	if(get_user_meta(get_current_custom_user_id(), 'user_type', true) == 'group'){

		return true;

	}

	return false;

}




/*----------------------------------------------------------------------------------------------
 //get the button to enter competion or any alternative content based on user status
 ------------------------------------------------------------------------------------------------*/
function get_competition_element(){

	$html = '';
	
	if(COMPETITION_ACTIVE){
	
		$competition_entries = get_user_total_competition_entries();
		
		$html = '<p>Competition: <span id="competition-counter">'.$competition_entries.'</span> '.(($competition_entries == 1)? 'entry': 'entries' ).'</p>' ;
	}else{
		$html = '<p>Competition closed</p>' ;
		
	}
	

	return $html;

}


/*----------------------------------------------------------------------------------------------
 ///get the user favourite list
 ------------------------------------------------------------------------------------------------*/
function get_favourite_list(){
	
	global $wpdb;
	
	$favourite = $wpdb->get_results("SELECT wp_favourite.id as favourite_id,
											wp_favourite.user_id as user_id,
											wp_favourite.date_added as date_added,
											wp_favourite.image_id as image_id,
											wp_image.image_name as image_name,
											wp_image.dist_ml_yr as dist_ml_yr,
											wp_result.id as result_id,
											wp_step1.step_description as step1,
											wp_step2.step_description as step2,
											wp_step3.step_description as step3,
											wp_step4.step_description as step4
									   FROM wp_favourite

								  LEFT JOIN wp_image 
											ON wp_image.id = wp_favourite.image_id
			
								  LEFT JOIN wp_result
											ON wp_result.image_id = wp_favourite.image_id
											AND wp_result.user_id = wp_favourite.user_id
			
								  LEFT JOIN wp_image_steps wp_step1
											ON wp_step1.step_value_code = wp_result.step_1
											AND wp_step1.step_name = 'step1'
			
								  LEFT JOIN wp_image_steps wp_step2
											ON wp_step2.step_value_code = wp_result.step_2
											AND wp_step2.step_name = 'step2'
						
								  LEFT JOIN wp_image_steps wp_step3
											ON wp_step3.step_value_code = wp_result.step_3
											AND wp_step3.step_name = 'step3'
						
								  LEFT JOIN wp_image_steps wp_step4
											ON wp_step4.step_value_code = wp_result.step_4
											AND wp_step4.step_name = 'step4'
			
									  WHERE wp_favourite.user_id = ".get_current_custom_user_id()."
								   ORDER BY date_added DESC");
	
	return $favourite;
	
}


/*----------------------------------------------------------------------------------------------
 ///get number of photo to go before entering competition
 ------------------------------------------------------------------------------------------------*/
function get_user_photo_to_go(){

	$totalentries = get_user_total_images();

	if(is_user_school_group()){
		$tot = COMPETITION_IMAGES_GROUP - ($totalentries % COMPETITION_IMAGES_GROUP);
	}else{
		
		$tot = COMPETITION_IMAGES_SINGLE - ($totalentries % COMPETITION_IMAGES_SINGLE);
	}

	return $tot;


}



/*----------------------------------------------------------------------------------------------
 //check if user has enter competition
 ------------------------------------------------------------------------------------------------*/
function user_has_entered_competition(){

	$competitionEntries = get_user_total_competition_entries();

	if($competitionEntries > 0 ){
		
		return true;
	}

	return false;

}

/*----------------------------------------------------------------------------------------------
 //check if user can enter competition
 ------------------------------------------------------------------------------------------------*/
function user_can_enter_competition(){

	$total = get_user_total_images();

	if(is_user_school_group()){

		if($total >= COMPETITION_IMAGES_GROUP){
			return true;
		}

	}else{

		if($total >= COMPETITION_IMAGES_SINGLE){
			return true;
		}
	}

	return false;

}

/*----------------------------------------------------------------------------------------------
 //get the user total images entries
 ------------------------------------------------------------------------------------------------*/
function get_user_total_images($user_id = ''){

	if(!$user_id){
		
		$user_id = get_current_custom_user_id();
	}

	global $wpdb;

	//better get the total from the result table
	$total = $wpdb->get_var( "SELECT count(id)
								FROM wp_result
							   WHERE user_id = ".$user_id);

	return $total;
}


/*----------------------------------------------------------------------------------------------
//get the user total competition entries
------------------------------------------------------------------------------------------------*/
function get_user_total_competition_entries($user_id = ''){
	
	if(!$user_id){
	
		$user_id = get_current_custom_user_id();
	}
	
	global $wpdb;
	
	//better get the total from the result table
	$total = $wpdb->get_var( "SELECT count(id)
								FROM wp_competition
							   WHERE user_id = ".$user_id);
	
	return $total;
}


/*----------------------------------------------------------------------------------------------
 //increment the processed counter for a specific image
 ------------------------------------------------------------------------------------------------*/
function update_image_total_processed($image_id){

	global $wpdb;

	$wpdb->query(
				$wpdb->prepare(
						"UPDATE wp_image
							SET times_processed = times_processed + 1
						  WHERE id = %d
								",
						$image_id
				)
		);

}



/*----------------------------------------------------------------------------------------------
 //get the link to the term and conditions page, based on user type
 ------------------------------------------------------------------------------------------------*/
function get_terms_conditions_link(){

	if(get_user_meta(get_current_user_id(), 'user_type', true) == 'group'){

		echo esc_url(home_url('/')).'terms-and-conditions/#section-group';
	}else{
		echo esc_url(home_url('/')).'terms-and-conditions/#section-individual';
	}

}

/*----------------------------------------------------------------------------------------------
 //check if user need to complete the tutorial
 ------------------------------------------------------------------------------------------------*/
function show_tutorial(){

	$showTutorial = false;

	$showTutorial = get_user_meta(get_current_custom_user_id(), 'show_tutorial', true);


	if($showTutorial == 'true'){
		return true;
	}else{
		return false;
	}
}



/*----------------------------------------------------------------------------------------------
 //update the current batch number
 ------------------------------------------------------------------------------------------------*/
function update_current_batch_number(){
	
	global $wpdb;
	
	//get the new batch
	$current = $wpdb->get_var("
			SELECT MIN(batch_number) 
			  FROM wp_image
			 WHERE batch_number > 0
			   AND times_processed < 10
			");
	
	if(!$current || $current == 0){
		//this will trigger error later
		return false;
	}
	
	
	//increment counter
	update_option( 'current_batch_number', $current );
	
	$update_time = current_time('d/m/Y \a\t\ g:i:s A');
	
	update_option( 'current_batch_started', $update_time );
	
	//also notify admin of new batch
	$to = ERROR_NOTIFICATION_EMAIL;
	$subject = NOTIFICATION_SUBJECT;
	
	$message = "
			Hi Admin,<br />
			A new batch of images started on GALAXY EXPLORER on ".$update_time."<br />
			The current new batch number is: ".$current;
	
	send_email($to, $subject, $message);
	
	return $current;
	
}

/*----------------------------------------------------------------------------------------------
 //Get the current batch number for the images
 ------------------------------------------------------------------------------------------------*/
function get_current_batch_number(){


	$batch_number = get_option('current_batch_number');

	//handle first time
	if(!$batch_number){

		$batch_number = update_option( 'current_batch_number', 1 );
		update_option( 'current_batch_started', current_time('d/m/Y \a\t\ g:i:s A') );

		return 1;
	}

	return intval($batch_number);

}


/*----------------------------------------------------------------------------------------------
 //This is the main function that select the image to identify
 ------------------------------------------------------------------------------------------------*/

function get_image_to_identify(){

	global $wpdb;
	
	$batch_number = get_current_batch_number();
	
	/* 19/08/2015 - changes how image is selected.
	 * 
	 * Instead of grabbing the image from the current batch_number, we create an array with the top resolution images (1 only) and then N times
	 * the current batch (to increase the odds of picking the current batch). Then we select randomly between them 
	 * 
	 */
	//3/4 odds to get current batch
	$choices_array = array(
			1,
			$batch_number,
			$batch_number,
			$batch_number,
	);
	
	$rand_key = array_rand($choices_array);
	
	$selected_batch = $choices_array[$rand_key];
	
	//NOTE - uncomment this if you want to include batch 1 (best images) 
	//if($selected_batch == $batch_number){
	if($batch_number){
		
		
		//select a random image with the lowest time_processed value, randomly
		$current_image = $wpdb->get_row('SELECT *
									   FROM wp_image
									  WHERE batch_number = '.$batch_number.'
										AND times_processed < 10
								   ORDER BY RAND()
									  LIMIT 1' );
		
	}else{
		
		//select a random image with the lowest time_processed value, randomly
		$current_image = $wpdb->get_row('SELECT *
									   FROM wp_image
									  WHERE batch_number = '.$selected_batch.'
								   ORDER BY RAND()
									  LIMIT 1' );
		
	}
	
	

	
		
	//if i don't find any images, it means I finished the current batch, so I need to update it
	if(!$current_image){
		
		$batch_number = update_current_batch_number();
		
		//do query again
		$current_image = $wpdb->get_row('SELECT *
										   FROM wp_image
										  WHERE batch_number = '.$batch_number.'
											AND times_processed < 10
									   ORDER BY RAND()
										  LIMIT 1' );
		
	}
	
	
	//if still I haven't found any image, just serve a random one and notify of the error
	if(!$current_image){
	
		//do query again
		$current_image = $wpdb->get_row('SELECT *
										   FROM wp_image
									   ORDER BY RAND()
										  LIMIT 1' );
		
		if(SEND_ERROR_NOTIFICATION){
		
			$to = ERROR_NOTIFICATION_EMAIL;
			$subject = ERROR_NOTIFICATION_SUBJECT;
		
			$message = "--- FUNCTION ---";
			$message .= "<br />get_image_to_identify()";
		
			$message .= "<br /><br />--- ERROR ---";
			$message .= "<br />Image batches are finished.";
			$message .= "<br />for user: ".get_current_custom_user_id();
		
			send_email($to, $subject, $message);
		
			return false;
		}
	}


	if($current_image){

		//update image_served counter
		$wpdb->update(
				'wp_image',
				array(	'times_served' => $current_image->times_served + 1),
				array(	'id' => $current_image->id ));


	}else{

		if(SEND_ERROR_NOTIFICATION){

			$to = ERROR_NOTIFICATION_EMAIL;
			$subject = ERROR_NOTIFICATION_SUBJECT;

			$message = "--- FUNCTION ---";
			$message .= "<br />get_image_to_identify()";

			$message .= "<br /><br />--- ERROR ---";
			$message .= "<br />Couldn't get a random image record from database";
			$message .= "<br />for user: ".get_current_custom_user_id();

			send_email($to, $subject, $message);
				
			return false;
		}
	}

	return $current_image;

}


/*----------------------------------------------------------------------------------------------
 ///get the tool and favorite page header bar
 ------------------------------------------------------------------------------------------------*/
function get_tool_header_bar(){
	
	$html =  '<div class="container clear">
				<h3>'.get_user_intro_text().'</h3>
				<table id="tool-header-table">
					<tr>
						<td><p>Galaxies: '.get_user_total_images().'</p></td>
						<td class="middle">'.get_competition_element().'</td>
						<td class="last"><a href="/favourite/" target="_blank" id="link-favourite">Favourites: <span id="total-favourites">'.get_total_favourites().'</span></a></td>
					</tr>
				</table>
			</div>';
	
	return $html;
}

/*----------------------------------------------------------------------------------------------
 ///get the user intro text based on the user type
 ------------------------------------------------------------------------------------------------*/
function get_user_intro_text(){

	$html = '';

	if (is_user_logged_in()) {

		if(is_user_school_group()){
				
			$html = 'Welcome '.get_current_group_name();
				
		}else{

			$html = 'Welcome '.get_current_user_name();
		}
	}else{

		$html = 'Welcome Galactic Explorer';
	}

	return $html;

}


/*----------------------------------------------------------------------------------------------
 //get the current group name
 ------------------------------------------------------------------------------------------------*/
function get_current_group_name(){

	return get_user_meta(get_current_user_id(), 'schoolGroupName', true);

}


/*----------------------------------------------------------------------------------------------
 //get the current user first name
 ------------------------------------------------------------------------------------------------*/
function get_current_user_name(){
	
	$current_user = wp_get_current_user();

	return $current_user->user_firstname;

}

/*----------------------------------------------------------------------------------------------
 ///get the total number of favourites entries
 ------------------------------------------------------------------------------------------------*/
function get_total_favourites(){

	global $wpdb;

	$total = $wpdb->get_var('SELECT count(*)
							   FROM wp_favourite
							  WHERE user_id = '.get_current_custom_user_id());

	if ($total > 99){
		
		$total = '99+';
	}
	
	return $total;

}

/*----------------------------------------------------------------------------------------------
 ///get the user level based on images completed
 ------------------------------------------------------------------------------------------------*/
function get_user_level(){

	if(!is_user_logged_in()){
		
		$level = 'Trainee';
	}else{
	
		global $wpdb;
	
		$images_completed = get_user_total_images();
	
		$level = $wpdb->get_var('SELECT level
								   FROM wp_levels
								  WHERE images_from <= '.$images_completed.'
									AND images_to >= '.$images_completed);
	
	}
		return $level;
}

/*----------------------------------------------------------------------------------------------
 ///get the total number of images in DB
 ------------------------------------------------------------------------------------------------*/
function get_total_galaxies(){

	global $wpdb;

	$total = $wpdb->get_var('SELECT count(*)
							   FROM wp_image');

	return $total;
}

/*----------------------------------------------------------------------------------------------
 ///get the number of galaxies under analysis, so with time_processed between 1 and 4
 ------------------------------------------------------------------------------------------------*/
function get_total_galaxies_under_analysis(){

	global $wpdb;

	$total = $wpdb->get_var('SELECT count(*)
							   FROM wp_image
							  WHERE times_processed <= 9');

	return $total;
}



/*----------------------------------------------------------------------------------------------
 ///get the number of galaxies completed, so with time_processed between more than 5
 ------------------------------------------------------------------------------------------------*/
function get_total_galaxies_completed(){

	global $wpdb;

	$total = $wpdb->get_var('SELECT count(*)
							   FROM wp_image
							  WHERE times_processed >= 1');

	return $total;
}

/*----------------------------------------------------------------------------------------------
 //send password reset email to user
 ------------------------------------------------------------------------------------------------*/
function sendForgotPasswordEmail($userId, $activationCode) {

	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$activationCodeLink = md5($activationCode);

	$user_info = get_userdata($userId);

	$messageTo = stripslashes($user_info->user_email);

	$userFullName = $user_info->user_firstname . ' ' . $user_info->user_lastname ;

	$messageSubject = 'Reset password instructions';

	$messageCopy  = 'Dear '.$userFullName.',<br /><br />';
	$messageCopy .= 'We\'ve received a request to reset your password for Galaxy Explorer.<br /><br />';
	$messageCopy .= 'If you would like to set a new password please click this link:<br/>';
	$messageCopy .= '<a href="'.get_option('siteurl') . '/change-password?action=reset&login='.$userId.'&key='.$activationCodeLink.'" target="_blank">'.get_option('siteurl') . '/change-password?action=reset&login='.$userId.'&key='.$activationCodeLink.'</a><br /><br />';
	$messageCopy .= 'Please note that this link is valid for 1 hour only.<br /><br />';
	$messageCopy .= 'If you don\'t want to reset your password please ignore this email.<br /><br />';
	$messageCopy .= 'Thanks for taking part in Galaxy Explorer.<br />';
	$messageCopy .= 'Galaxy Explorer -  <a href="'.esc_url( home_url( '/' ) ).'">'.esc_url( home_url( '/' ) ).'</a>';



	if(!send_email( $messageTo, $messageSubject, $messageCopy )){
		return false;
	};

	return true;


}


/*----------------------------------------------------------------------------------------------
 //Get the total number of registered users ("individual" or "group" o "all" )
 ------------------------------------------------------------------------------------------------*/
function get_registered_subscribers($type = 'all'){

	$counter = 0;
	
	global $wpdb;

	if($type == "all"){
			
		$counter_individual = $wpdb->get_var( "SELECT count(*) 
												 FROM wp_usermeta 
												 JOIN wp_usermeta as capabilities 
													  ON capabilities.user_id = wp_usermeta.user_id
													  AND capabilities.meta_key = 'wp_capabilities'
													  AND capabilities.meta_value not like  '%session_guest%'
												WHERE wp_usermeta.meta_key = 'user_type' 
												  AND wp_usermeta.meta_value = 'individual'" );
			
		$counter_group = $wpdb->get_var( "SELECT SUM(CASE 
														WHEN meta_value > 100 
															THEN 100 
															ELSE meta_value 
													  END)
											FROM wp_usermeta 
										   WHERE meta_key = 'schoolGroupNumber'") ;
			
		$counter = $counter_individual + $counter_group;
			
	}elseif($type == "individual"){
			
		$counter = $wpdb->get_var("SELECT count(*) 
									 FROM wp_usermeta 
									 JOIN wp_usermeta as capabilities 
										  ON capabilities.user_id = wp_usermeta.user_id
										  AND capabilities.meta_key = 'wp_capabilities'
										  AND capabilities.meta_value not like  '%session_guest%'
									WHERE wp_usermeta.meta_key = 'user_type' 
									  AND wp_usermeta.meta_value = 'individual'");

	}elseif($type == "group"){

		$counter = $wpdb->get_var("SELECT SUM(CASE 
												WHEN meta_value > 100 
													THEN 100 
													ELSE meta_value 
											  END)
									FROM wp_usermeta 
								   WHERE meta_key = 'schoolGroupNumber'");
	}


	return $counter;

}



/*----------------------------------------------------------------------------------------------
 // Add string for feature image recommended size
 ------------------------------------------------------------------------------------------------*/
function custom_admin_post_thumbnail_html( $content, $post_id ) {
	
	$parent_id = wp_get_post_parent_id( $post_id );
	
	if($parent_id == PAGE_ID_SCIENCE){
		
		$content = str_replace( 'Set featured image', 'Set featured image<br />Required size: 830 x 490 pixels', $content );
	}
	
	return $content;
}

add_filter( 'admin_post_thumbnail_html', 'custom_admin_post_thumbnail_html', 10, 2 );


/*----------------------------------------------------------------------------------------------
 // calculate values in pixels to draw the starting ellipse
 ------------------------------------------------------------------------------------------------*/
function convertImageDataToPixels($image_data){
	
	//cast values (i have string in DB)
	$ra_right = floatval($image_data->ra_right);
	$ra_left = floatval($image_data->ra_left);
	$dec_top = floatval($image_data->dec_top);
	$dec_bottom = floatval($image_data->dec_bottom);
	$pixels = (int)($image_data->pixels);
	
	$ra_deg = floatval($image_data->ra_deg);
	$dec_deg = floatval($image_data->dec_deg);
	$rot_w2n = floatval($image_data->rot_w2n);
	$radminasec = floatval($image_data->radminasec);
	$radmajasec = floatval($image_data->radmajasec);
	
	$RAscale  = (($pixels / 800) * ($ra_right - $ra_left)) / ($pixels - 1);
	$Decscale = (($pixels / 800) * ($dec_bottom - $dec_top)) / ($pixels - 1);
	
	$RA0 = $ra_left - ($RAscale / 2);
	$Dec0 = $dec_top - ($Decscale / 2);
	
	//(RAdeg-RA0)/RAscale
	$x = ($ra_deg - $RA0) / $RAscale;
	$y = ($dec_deg - $Dec0) / $Decscale;
	
	$rot = $rot_w2n * pi() / 180;
	#We have to apply a rotation shift to use Joe�s code
	$rot = pi() - $rot;
	//Converts degrees -> pixels. 
	//It also corrects for some minor projection distortion for calculating the semi-major/minor ellipse parameters
	
	$radApx = sqrt(
					pow(($radmajasec / 3600 / $RAscale  * cos($rot)), 2) + 
					pow(($radmajasec / 3600 / $Decscale * sin($rot)), 2)
			);
	
	$radBpx = sqrt(
					pow(($radminasec / 3600 / $RAscale  * cos($rot + pi() / 2)), 2) + 
					pow(($radminasec / 3600 / $Decscale * sin($rot + pi() / 2)), 2)
			);
	
	
	$result = array(
			'x' 		=> $x,
			'y' 		=> $y,
			'rot'		=> $rot,
			'radApx'	=> $radApx,
			'radBpx'	=> $radBpx
	);
	
	return $result;
	
}


/*----------------------------------------------------------------------------------------------
 // calculate values in degrees to save result
 ------------------------------------------------------------------------------------------------*/
function convertImageDataToCoordinate($image_id, $ellipse_data){
	
	global $wpdb;
	
	
	//get the image
	$image_data = $wpdb->get_row("
			SELECT * 
			  FROM wp_image
			 WHERE id = ".$image_id);
	

	//cast values (i have strings in DB)
	$ra_right = floatval($image_data->ra_right);
	$ra_left = floatval($image_data->ra_left);
	$dec_top = floatval($image_data->dec_top);
	$dec_bottom = floatval($image_data->dec_bottom);
	$pixels = (int)($image_data->pixels);

	$RAscale  = (($pixels / 800) * ($ra_right - $ra_left)) / ($pixels - 1);
	$Decscale = (($pixels / 800) * ($dec_bottom - $dec_top)) / ($pixels - 1);
	
	$RA0 = $ra_left - ($RAscale / 2);
	$Dec0 = $dec_top - ($Decscale / 2);

	
	//convertToCoord
	$ra_deg = $RA0 + $ellipse_data['x'] * $RAscale;
	$dec_deg = $Dec0 + $ellipse_data['y'] * $Decscale;
	$rot_w2n = $ellipse_data['rot'] * 180 / pi();
	$rot_w2n = modulo((0 - $rot_w2n), 180);
	
	
	#It also corrects for some minor projection distortion for calculating the semi-major/minor ellipse parameters
	$radAdeg = sqrt(
					pow($ellipse_data['radA'] * $RAscale  * cos($rot_w2n * pi() / 180), 2) +
					pow($ellipse_data['radA'] * $Decscale * sin($rot_w2n * pi() / 180), 2)
					
	);
	
	$radBdeg = sqrt(
			pow($ellipse_data['radB'] * $RAscale  * cos(($rot_w2n + 90) * pi() / 180), 2) +
			pow($ellipse_data['radB'] * $Decscale * sin(($rot_w2n + 90) * pi() / 180), 2)
				
	);
	
	//The purpose of the code below is to ensure than radmin is always the smaller of radAdeg/radBdeg and radmaj is the large
	if($radAdeg > $radBdeg){
		$radmaj = $radAdeg;
		$radmin = $radBdeg;
	}else{
		$radmin = $radAdeg;
		$radmaj = $radBdeg;
		$rot_w2n  = $rot_w2n + 90;
		
	}
	
	//We have to apply a rotation shift from Joe�s code
	$rot_w2n  = modulo($rot_w2n , 180);
	
	$result = array(
			'ra_deg' 		=> $ra_deg,
			'dec_deg' 		=> $dec_deg,
			'rot_w2n'		=> $rot_w2n,
			'radminasec'	=> $radmin * 3600,
			'radmajasec'	=> $radmaj * 3600
	);
	
	
	return $result;

}

function modulo($a, $b) {
	return $a - $b * floor($a / $b);
}

/*----------------------------------------------------------------------------------------------
 // return string of the galaxy distance value
 ------------------------------------------------------------------------------------------------*/
function convert_galaxy_distance($distance){
	
	$distance = floatval($distance);
	
	if($distance == 0){
		
		return 'Distance from earth not available.';
		
	}elseif($distance < 100){
		
		return round($distance, 1).' million light years from Earth.';
		
	}else{
		return round(($distance / 1000), 1).' billion light years from Earth.';
	}
	
	
}


/*----------------------------------------------------------------------------------------------
 // return html for the registration reminder after 10 images as guest
 ------------------------------------------------------------------------------------------------*/
function get_popup_guest_user_registration_reminder(){

	if(COMPETITION_ACTIVE){
		$text = "If you register now you'll need to complete 5 more galaxies and you can enter the competition to win a telescope.";
	}else{
		$text = "COMP CLOSED";
	}
	
	$html = '
	<div id="guest-registration-reminder-lightbox-wrapper">

		<div id="guest-registration-reminder-lightbox" class="general-lightbox">

			<h2>Want to register?</h2>
		
			<p>You\'ve analysed 5 galaxies now, thanks for your contribution.</p>
			
			<p>'.$text.'</p>
			<p>By registering you can also save your favourite galaxy images to look at another time.</p>
		
			<div class="buttons-section clear">
				<a href="/classify/" class="cta-tut-next"><span>Continue</span></a>
				<div class="already-registered">
					<p>Already registered?</p>
					<a class="cta-tut" href="/classify?login=true"><span>Login now</span></a>
				</div>
				<a href="/register" class="cta-tut"><span>Register now</span></a>

			</div>
		
		
		</div>
	</div>';

	return $html;


}



/*----------------------------------------------------------------------------------------------
 // return html for the competition popup (INDIVIDUAL)
 ------------------------------------------------------------------------------------------------*/
function get_popup_competition_individual($class = null){
	
	$competition_entries = get_user_total_competition_entries();
	
	$html = '
	<div id="competition-lightbox-wrapper">
				
		<div id="competition-lightbox" class="general-lightbox '.$class.'">
		
			<h2>Congratulations</h2>
			';
	
	if($competition_entries > 0){
		
		$html .= '<p>You\'re now eligible for another entry into the competition. You have '.$competition_entries.' '.(($competition_entries == 1)? 'entry':'entries' ).' so far.</p>';
	}else{
		$html .= '<p>You\'re now eligible to enter the competition.</p>';
	}
	
	$html .= '<p>To confirm your entry, check the box below to tell us you\'re human!</p>			
			
			<form class="general-form">';
	
	/*$html .='	<div class="row captcha">
					<div class="g-recaptcha" id="captcha-container" data-size="normal" data-sitekey="'.GE_CAPTCHA_PUBLIC_KEY.'"></div>
							
							
					<a href="javascript:void(0)" id="captcha-reload">Reload Captcha</a>
				</div>';*/
	
	$html .='	<div class="row row-checkbox">
					<input id="email" name="email" type="text" value="" />
			
					<input id="captcha-checkbox" name="captcha-checkbox" type="checkbox" />
					<label for="captcha-checkbox"><span>I am a human, not a robot.</span></label>
				</div>
				
				<p>Would you like to give us some feedback on your experience with Galaxy Explorer so far?</p>
				
				<div class="row">
					<textarea maxlength="500" name="competition-feedback" id="competition-feedback"></textarea>
				</div>
				
				
			</form>
			<div class="buttons-section clear">
				<a id="competition-enter-submit" href="javascript:void(0)" class="cta-tut"><span>Enter</span></a>
				
			</div>
			
			
		</div>
	</div>
	';
	
	return $html;
	
}


/*----------------------------------------------------------------------------------------------
 // return html for the competition popup (SCHOOL)
 ------------------------------------------------------------------------------------------------*/
function get_popup_competition_school($class = null){

	$competition_entries = get_user_total_competition_entries();

	$html = '
	<div id="competition-school-lightbox-wrapper">

		<div id="competition-school-lightbox" class="general-lightbox '.$class.'">

			<h2>Congratulations</h2>
			
			<p>Your school group has analysed '.(($competition_entries > 1)? 'another ': '' ).COMPETITION_IMAGES_GROUP .' galaxies and has '.$competition_entries.' '.(($competition_entries > 1)? 'entries': 'entry' ).' into the competition.</p>
			
			<p>Thanks for all your work!</p>
					
			<div class="buttons-section clear">
				<a id="competition-school-continue" href="javascript:void(0)" class="cta-tut"><span>Continue</span></a>
			</div>
		
		</div>
	</div>
	';

	return $html;

}


/*----------------------------------------------------------------------------------------------
 // Add the entry to the competition table
 ------------------------------------------------------------------------------------------------*/
function add_competition_entry($message = ''){
	
	//should never arrive here if comp is not active, but just in case
	if (!COMPETITION_ACTIVE){
		return true;
	}
	
	$results = get_user_total_images(get_current_user_id());
	$competition_entries = get_user_total_competition_entries(get_current_user_id());
	$supposed_entries = floor($results / 10 );
	
	//check if for some reon user has already enough comps entries, in case, silent die
	if($supposed_entries <= $competition_entries){
			
		return true;
	}
	
	global $wpdb;
	//get states list
	$insert_record = $wpdb->insert('wp_competition',
			array(
					'user_id' 	=> get_current_user_id(),
					'message' 	=> $message
			),
			array(
					'%d',
					'%s'
			));
	
	if(!$insert_record){
	
		return false;
	}
	
	return true;
	
}




/*----------------------------------------------------------------------------------------------
 // Send notification to teacher about the new competition entry
 ------------------------------------------------------------------------------------------------*/
function send_competition_email_to_teacher(){
	
	$current_user = wp_get_current_user();
	$school_group = get_user_meta(get_current_user_id(), 'schoolGroupName', true );
	$competition_entries = get_user_total_competition_entries();
	
	$to = $current_user->user_email;
	
	$subject = 'School Group Competition email (Subject to change)';
	
	$message = 'Congratulations!<br />
				Your school group '.$school_group.' has analysed '.(($competition_entries > 1)? 'another ': '' ).COMPETITION_IMAGES_GROUP.' galaxies in the 
				ABC Galaxy Explorer and has '.(($competition_entries > 1)? 'another': 'an' ).' entry into the competition. 
				'.$school_group.' now have '.$competition_entries.' competition '.(($competition_entries > 1)? 'entries': 'entry' ).' in total.';
	
	send_email($to, $subject, $message);
	 
}




/*----------------------------------------------------------------------------------------------
 //trick for Contact Form 7 plugin. We set for default that 'additional mail'
 //in the plugin setting send a copy to the user as well. Than here we add
 //an action that if the checkbox 'send me a copy' is unchecked, it reset
 //the addition mail field
 ------------------------------------------------------------------------------------------------*/
add_filter( 'wpcf7_additional_mail', 'my_wpcf7_use_mail_2_or_not', 10, 2 );

function my_wpcf7_use_mail_2_or_not( $additional_mail, $cf ) {
	
	
	
	$submission = WPCF7_Submission::get_instance();
	
	if($submission){
		
		$data = $submission->get_posted_data();
		
		if (isset($data['contact-sendCopy'][0]) && $data['contact-sendCopy'][0] == '' ){
			$additional_mail = array();
		}
	}
	
	
	return $additional_mail;
}




/*----------------------------------------------------------------------------------------------
 //display no canvas support notification, only once per session
 ------------------------------------------------------------------------------------------------*/
function get_old_browser_notification(){
	
	if(!isset($_SESSION['no-canvas-support-message'])){
		
		$_SESSION['no-canvas-support-message'] = 1; 
		
		return '<p class="browser-notification">Update your browser message </p>';
		
	}
	
}


function todo(){
	return "javascript:alert('TODO');";
}


/*----------------------------------------------------------------------------------------------
 // Create the function to output the contents of our Dashboard Widget
 ------------------------------------------------------------------------------------------------*/
function dashboard_admin_site_statistics() {

	//retrieve the cached file


	$file = 'dashboard.txt';

	$upload_dir = wp_upload_dir();

	if (file_exists($upload_dir['basedir'].'/'.$file)) {
		$text = file_get_contents($upload_dir['basedir'].'/'.$file);
	} else {
		$text = '<p>There are not statistics available at the moment, try again in one hour.</p>';
	}
	
	echo $text;
	
	return;

	
}


/*----------------------------------------------------------------------------------------------
 // get the total fake user who did at least 1 classification
 ------------------------------------------------------------------------------------------------*/
 
function get_total_fake_users(){
	
	global $wpdb;
	
	//get states list
	$fake_users = $wpdb->query(
			'SELECT DISTINCT (wp_result.user_id)
			   FROM wp_result
		  LEFT JOIN wp_users 
					ON wp_users.id = wp_result.user_id
			  WHERE (	wp_users.ID is null 
					 OR wp_users.user_email like "%@fake.com%"
					)'
	);
	
	return $fake_users;
	
}



/*----------------------------------------------------------------------------------------------
 // Create the function to output the contents of our Dashboard Widget
 ------------------------------------------------------------------------------------------------*/
function get_dashboard_data() {


	
	//heavy queries, launch once only
	$current_batch = get_option('current_batch_number');
	$current_batch_date = get_option('current_batch_started');
	$total_individual_citizens = get_registered_subscribers('individual');
	$total_groups = get_total_user_groups();
	$total_fake_users = get_total_fake_users();
	
	$total_group_citizens = get_registered_subscribers_group_total();
	
	
	
	$total_images_average_individual = get_total_images_by_user_type('individual');
	$total_images_average_group = get_total_images_by_user_type('group');
	$total_images_results = get_total_results();
	$total_images_by_agegroup = get_total_images_by_agegroup();
	$total_images_by_school_type = get_total_images_by_school_type();
	$user_individual_top_contribution = get_user_highest_images('individual');
	$user_individual_top_contribution_data = get_userdata( $user_individual_top_contribution->user_id );
	$user_group_top_contribution = get_user_highest_images('group');
	$user_group_top_contribution_data = get_userdata( $user_group_top_contribution->user_id );
	
	$total_competition = get_tot_competition_entries();
	$total_competition_group = get_tot_competition_entries('group');
	$total_competition_individual = get_tot_competition_entries('individual');
	
	$user_individual_top_competition = get_user_highest_competition('individual');
	$user_individual_top_competition_data = get_userdata( $user_individual_top_competition->user_id );
	$user_group_top_competition = get_user_highest_competition('group');
	$user_group_top_competition_data = get_userdata( $user_group_top_competition->user_id );
	
	

	
	$html = '
<div id="galaxy_explorer_stats">
	<h2>Last update: '.current_time("d/m/Y g:i:s a").'</h2>
	
	<div class="table-row">
		<div class="table table_users">
			<p class="sub">Users</p>
			<table>
				<tbody>
					<tr class="first">
						<td class="first b">'.($total_individual_citizens + $total_group_citizens).'</td>
						<td class="t">Total citizen scientists (inc. school groups)</td>
					</tr>
					<tr>
						<td class="first b">'.$total_individual_citizens.'</td>
						<td class="t">Individual Registered users</td>
					</tr>
					<tr>
						<td class="first b">'.$total_fake_users.'</td>
						<td class="t">Guest Users</td>
					</tr>
					<tr>
						<td class="first b">'.$total_groups.'</td>
						<td class="t">School groups users (tot. '.$total_group_citizens.')</td>
					</tr>
								
				</tbody>
			</table>
		</div>
								
								
		<div class="table table_geographics">
			<p class="sub">Users geographic distribution</p>
			<table>
				<tbody>
					<tr class="first">
						<td class="first b">'.get_registered_subscribers_by_state('Australian Capital Territory').'</td>
						<td class="t">Australian Capital Territory</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('New South Wales').'</td>
						<td class="t">New South Wales</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('Northern Territory').'</td>
						<td class="t">Northern Territory</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('Queensland').'</td>
						<td class="t">Queensland</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('South Australia').'</td>
						<td class="t">South Australia</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('Tasmania').'</td>
						<td class="t">Tasmania</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('Victoria').'</td>
						<td class="t">Victoria</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('Western Australia').'</td>
						<td class="t">Western Australia</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('Other').'</td>
						<td class="t">Australia (other)</td>
					</tr>
					<tr>
						<td class="first b">'.get_registered_subscribers_by_state('').'</td>
						<td class="t">Rest of the world</td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>
								
								
	<div class="table-row">
		<div class="table table_users">
			<p class="sub">Competition</p>
			<table>
				<tbody>
					<tr class="first">
						<td class="first b">'.$total_competition.'</td>
						<td class="t">Total competition entries</td>
					</tr>
					<tr>
						<td class="first b">'.$total_competition_individual.'</td>
						<td class="t">Individual competition entries</td>
					</tr>
					<tr>
						<td class="first b">'.$total_competition_group.'</td>
						<td class="t">Group competition entries</td>
					</tr>
				</tbody>
			</table>
		</div>
								
								
		<div class="table table_geographics">
			<p class="sub">Competition top entries</p>
			<table>
				<tbody>
					<tr class="first">
						<td class="first b">'.$user_individual_top_competition->total.'</td>
						<td class="t">for individual - User '.$user_individual_top_competition_data->first_name.' '.$user_individual_top_competition_data->last_name.'</td>
					</tr>
					<tr>
						<td class="first b">'.$user_group_top_competition->total.'</td>
						<td class="t">for school groups - User '.$user_group_top_competition_data->first_name.' '.$user_group_top_competition_data->last_name.' from "'.get_user_meta($user_group_top_competition->user_id, 'schoolGroupName', true).'"</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
								
								
	<div class="table-row">
		<div class="all_left">
			<div class="table table_kelp">
				<p class="sub">Images Overall</p>
				<table>
					<tbody>
						<tr class="first">
							<td class="first b">'.get_total_galaxies().'</td>
							<td class="t">Total images</td>
						</tr>
						<tr class="first">
							<td class="first b">'.get_total_images_processed('all').'</td>
							<td class="t">Total images processed</td>
						</tr>
						<tr class="first">
							<td class="first b">'.get_total_images_processed('0').'</td>
							<td class="t">Total images not processed</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('1').'</td>
							<td class="t">Images processed 1 time</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('2').'</td>
							<td class="t">Images processed 2 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('3').'</td>
							<td class="t">Images processed 3 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('4').'</td>
							<td class="t">Images processed 4 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('5').'</td>
							<td class="t">Images processed 5 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('more').'</td>
							<td class="t">Images processed more than 5 times</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="all_right">
			<div class="table table_average_images">
				<p class="sub">Current Batch: '.$current_batch.'</p>
				<p class="sub">Stated on: '.$current_batch_date.'</p>
				<table>
					<tbody>
						<tr class="first">
							<td class="first b">'.get_total_images_processed('0',$current_batch).'</td>
							<td class="t">Images processed 0 time</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('1',$current_batch).'</td>
							<td class="t">Images processed 1 time</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('2',$current_batch).'</td>
							<td class="t">Images processed 2 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('3',$current_batch).'</td>
							<td class="t">Images processed 3 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('4'.$current_batch).'</td>
							<td class="t">Images processed 4 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('5',$current_batch).'</td>
							<td class="t">Images processed 5 times</td>
						</tr>
						<tr>
							<td class="first b">'.get_total_images_processed('more',$current_batch).'</td>
							<td class="t">Images processed more than 5 times</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
									
									
									
	<div class="table-row">
		<div class="all_left">
			<div class="table table_average_images">
				<p class="sub">Observations</p>
				<table>
					<tbody>
						<tr class="first">
							<td class="first b">'.$total_images_results .'</td>
							<td class="t">total observations made</td>
						</tr>
						<tr class="first">
							<td class="first b">'.$total_images_average_individual .'</td>
							<td class="t">total observations for individual ('.round(($total_images_average_individual / $total_individual_citizens )).' average)</td>
						</tr>
						<tr>
							<td class="first b">'.$total_images_average_group.'</td>
							<td class="t">total observations for school groups ('.  ( $total_groups > 0?round(($total_images_average_group / $total_groups )):"n/a" )    .' average)</td>
						</tr>
						<tr>
							<td class="first b">'.($total_images_results - ($total_images_average_individual + $total_images_average_group)).'</td>
							<td class="t">total observations for guests users</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="all_right">
			<div class="table table_highest_images">
				<p class="sub">Highest number of observations</p>
				<table>
					<tbody>
						<tr class="first">
							<td class="first b">'.$user_individual_top_contribution->total.'</td>
							<td class="t">for individual - User '.$user_individual_top_contribution_data->first_name.' '.$user_individual_top_contribution_data->last_name.'</td>
						</tr>
						<tr>
							<td class="first b">'.$user_group_top_contribution->total.'</td>
							<td class="t">for school groups - User '.$user_group_top_contribution_data->first_name.' '.$user_group_top_contribution_data->last_name.' from "'.get_user_meta($user_group_top_contribution->user_id, 'schoolGroupName', true).'"</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>';

	
	/*
	$html .= '<div class="table-row">
		<div class="table table_age_range">
			<p class="sub">Age range of participants</p>
			<table>
				<tbody>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('Under 18') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">Under 18</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('18 - 29') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">18 - 29</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('30 - 39') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">30 - 39</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('40 - 49') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">40 - 49</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('50 - 59') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">50 - 59</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('60 - 69') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">60 - 69</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('70 - 79') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">70 - 79</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_agegroup('Over 79') * 100 / $total_individual_citizens),2).'%</td>
						<td class="t">Over 79</td>
					</tr>
					<tr class="table-divider">
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_school_group('Primary') * 100 / $total_groups),2).'%</td>
						<td class="t">Primary School Group</td>
					</tr>
					<tr class="first">
						<td class="first b">'.number_format((get_total_users_by_school_group('Secondary') * 100 / $total_groups),2).'%</td>
						<td class="t">Secondary School Group</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="table table_age_range_images">
			<p class="sub">Number of observations per age range</p>
			<table>
				<tbody>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['Under 18']) ? $total_images_by_agegroup['Under 18'] : '0') .'</td>
						<td class="t">Under 18</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['18 - 29']) ? $total_images_by_agegroup['18 - 29'] : '0') .'</td>
						<td class="t">18 - 29</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['30 - 39']) ? $total_images_by_agegroup['30 - 39'] : '0') .'</td>
						<td class="t">30 - 39</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['40 - 49']) ? $total_images_by_agegroup['40 - 49'] : '0') .'</td>
						<td class="t">40 - 49</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['50 - 59']) ? $total_images_by_agegroup['50 - 59'] : '0') .'</td>
						<td class="t">50 - 59</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['60 - 69']) ? $total_images_by_agegroup['60 - 69'] : '0') .'</td>
						<td class="t">60 - 69</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['70 - 79']) ? $total_images_by_agegroup['70 - 79'] : '0') .'</td>
						<td class="t">70 - 79</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_agegroup['Over 79']) ? $total_images_by_agegroup['Over 79'] : '0') .'</td>
						<td class="t">Over 79</td>
					</tr>
					<tr class="table-divider">
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_school_type['Primary']) ? $total_images_by_school_type['Primary'] : '0') .'</td>
						<td class="t">Primary School Group</td>
					</tr>
					<tr class="first">
						<td class="first b">'.(isset($total_images_by_school_type['Secondary']) ? $total_images_by_school_type['Secondary'] : '0') .'</td>
						<td class="t">Secondary School Group</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";*/
	
	$html .= '</div>';


	return $html;
}


/*----------------------------------------------------------------------------------------------
 //Get the total number of competition entries
 ------------------------------------------------------------------------------------------------*/
function get_tot_competition_entries($user_type = null){
	
	global $wpdb;
	
	if ($user_type){
		
		$total = $wpdb->get_var(
				"SELECT count(*)
				   FROM wp_competition a,
						wp_usermeta c
				  WHERE a.user_id = c.user_id
					AND c.meta_key = 'user_type'
					AND c.meta_value = '".$user_type."'"
				
		);
		
	}else{
		
		$total = $wpdb->get_var(
				"SELECT count(*)
				   FROM wp_competition
		");
		
	}
	
	return $total;
	
}

/*----------------------------------------------------------------------------------------------
 //Get the total number of groups
 ------------------------------------------------------------------------------------------------*/
function get_total_user_groups(){

	global $wpdb;
	$query = $wpdb->prepare("SELECT count(*) FROM wp_usermeta WHERE meta_key = %s AND meta_value = %s" , 'user_type', 'group');

	$counter = $wpdb->get_var( $query );

	return $counter;
}


/*----------------------------------------------------------------------------------------------
 //Get the overall number of people in groups
 ------------------------------------------------------------------------------------------------*/
function get_registered_subscribers_group_total(){

	$counter = 0;

	global $wpdb;

	$query = $wpdb->prepare("SELECT sum(meta_value) FROM wp_usermeta WHERE meta_key = %s" , 'schoolGroupNumber'	);

	$counter = $wpdb->get_var( $query );

	return $counter;

}

/*----------------------------------------------------------------------------------------------
 // Get the total images processed by user type
 ------------------------------------------------------------------------------------------------*/
function get_total_images_by_user_type($userType){

	global $wpdb;

	$query = $wpdb->prepare("SELECT count(*)
							   FROM wp_result a,
									wp_usermeta c
							  WHERE a.user_id = c.user_id
								AND c.meta_key = 'user_type'
								AND c.meta_value = %s", $userType);


	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;

}


/*----------------------------------------------------------------------------------------------
 // Get the total images processed
 ------------------------------------------------------------------------------------------------*/
function get_total_results(){

	global $wpdb;



	$counter = $wpdb->get_var( "SELECT count(*)
							   FROM wp_result" );

	if(!$counter){
		$counter = 0;
	}

	return $counter;

}

/*----------------------------------------------------------------------------------------------
 // Get the total images processed by age group
 ------------------------------------------------------------------------------------------------*/
function get_total_images_by_agegroup(){


	global $wpdb;

	$query = $wpdb->prepare("SELECT c.meta_value as age ,count(b.id)  as total
							   FROM wp_result b,
									wp_usermeta c
							  WHERE b.user_id = c.user_id
								AND c.meta_key = %s
								GROUP BY c.meta_value", 'age');





	$result = $wpdb->get_results( $query );

	if(!$result){
		$ageGroupArray = array();
	}else{
		foreach($result as $agegroup){

			$ageGroupArray[$agegroup->age] = $agegroup->total;

		}
	}

	return $ageGroupArray;

}


/*----------------------------------------------------------------------------------------------
 // Get the total images processed by school type
 ------------------------------------------------------------------------------------------------*/
function get_total_images_by_school_type(){


	global $wpdb;

	$query = $wpdb->prepare("SELECT c.meta_value as schoolType ,count(b.id)  as total
							   FROM wp_result b,
									wp_usermeta c
							  WHERE b.user_id = c.user_id
								AND c.meta_key = %s
								GROUP BY c.meta_value", 'schoolType');





	$result = $wpdb->get_results( $query );

	if(!$result){
		$schoolArray = array();
	}else{
		foreach($result as $schoolType){

			$schoolArray[$schoolType->schoolType] = $schoolType->total;

		}
	}

	return $schoolArray;


}


/*----------------------------------------------------------------------------------------------
 // Get the more active user
 ------------------------------------------------------------------------------------------------*/
function get_user_highest_images($user_type){

	global $wpdb;
	
	$query = $wpdb->prepare("
			SELECT wp_result.user_id, 
				   count(*) as total 
			  FROM wp_result
			  JOIN wp_usermeta 
					ON wp_usermeta.user_id = wp_result.user_id
				   AND meta_key = %s 
				   AND meta_value = %s 
		  GROUP BY wp_result.user_id 
		  ORDER BY total DESC
			 LIMIT 1", 
			'user_type',$user_type);
	
	$data = $wpdb->get_row( $query );
	
	return $data;
}


/*----------------------------------------------------------------------------------------------
 // Get the users with more competition entries
 ------------------------------------------------------------------------------------------------*/
function get_user_highest_competition($user_type){

	global $wpdb;

	$query = $wpdb->prepare("
			SELECT wp_competition.user_id,
				   count(*) as total
			  FROM wp_competition
			  JOIN wp_usermeta
					ON wp_usermeta.user_id = wp_competition.user_id
				   AND meta_key = %s
				   AND meta_value = %s
		  GROUP BY wp_competition.user_id
		  ORDER BY total DESC
			 LIMIT 1",
			'user_type',$user_type);

	$data = $wpdb->get_row( $query );

	return $data;
}


/*----------------------------------------------------------------------------------------------
 //Get the overall numbers of people by state
 ------------------------------------------------------------------------------------------------*/
function get_registered_subscribers_by_state($state){


	$counter = 0;
	//also get total number of students
	global $wpdb;


	if($state != ''){

		$query = $wpdb->prepare("SELECT * FROM wp_usermeta WHERE meta_key = %s AND meta_value = %s" , 'state', $state	);
			
		$result = $wpdb->get_results( $query );
		
			
		foreach($result as $user){

			if(get_user_meta($user->user_id, 'country', true) == 'Australia'){

				if(get_user_meta($user->user_id, 'user_type', true) == 'individual'){

					$counter++;

				}else{

					$counter = $counter + get_user_meta($user->user_id, 'schoolGroupNumber', true) ;
				}
			}

		}

	}else{

		$query = $wpdb->prepare("SELECT * FROM wp_usermeta WHERE meta_key = %s AND meta_value <> %s" , 'country', 'Australia'	);
			
		$result = $wpdb->get_results( $query );
			
		foreach($result as $user){

			if(get_user_meta($user->user_id, 'country', true) != 'Australia'){
					
				if(get_user_meta($user->user_id, 'user_type', true) == 'individual'){

					$counter++;

				}else{

					$counter = $counter + get_user_meta($user->user_id, 'schoolGroupNumber', true) ;
				}
			}
		}
	}

	return $counter;
}



/*----------------------------------------------------------------------------------------------
 // Get the total number of total images by processed times
 ------------------------------------------------------------------------------------------------*/
function get_total_images_processed($number, $batch = null){


	//if number is 'all', get all images processed(image_processed <> 0)
	//if number is 1/2/3, get images processed X times(image_processed =  x)
	//if number is 'more, get images processed more than 5 times(image_processed > 5)

	global $wpdb;

	if($number == 'all'){
		
			
		$query = "SELECT count(id) 
					FROM wp_image 
				   WHERE times_processed <> 0";

	}elseif($number == 'more'){
			
		$query = "SELECT count(id) 
					FROM wp_image 
				   WHERE times_processed > 5";

	}else{
			
		$query = "SELECT count(id) 
					FROM wp_image 
				   WHERE times_processed = ". $number;
	}
	
	if($batch){
		
		$query .= " AND batch_number = ".$batch;
	}


	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;

}



/*----------------------------------------------------------------------------------------------
 // Get the total number of user by age group
 ------------------------------------------------------------------------------------------------*/
function get_total_users_by_agegroup($agegroup){

	global $wpdb;

	$query = $wpdb->prepare("SELECT count(umeta_id)
							   FROM wp_usermeta
							  WHERE meta_key = 'age'
								AND meta_value = %s", $agegroup);


	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;


}



/*----------------------------------------------------------------------------------------------
 // Get the total number of user by school group
 ------------------------------------------------------------------------------------------------*/
function get_total_users_by_school_group($schoolGroup){

	global $wpdb;

	$query = $wpdb->prepare("SELECT count(umeta_id)
							   FROM wp_usermeta
							  WHERE meta_key = 'schoolType'
								AND meta_value = %s", $schoolGroup);


	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;


}

/*

add_filter( 'wp_default_scripts', 'removeJqueryMigrate' );
function removeJqueryMigrate(&$scripts){
 if(!is_admin()){
 // $scripts->remove('jquery');
//  $scripts->add('jquery', false, array('jquery-core'), '1.10.2');
 }
}

*/






