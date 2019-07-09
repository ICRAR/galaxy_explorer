<?php

/*----------------------------------------------------------------------------------------------
 //handle the tutorial content lightboxes
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_tutorial', 'handleTutorial' );
add_action( 'wp_ajax_handle_tutorial', 'handleTutorial' );

function handleTutorial() {
	
	
	$response = array('error' => true,  'message' => '');

	$content = '<div id="tutorial-steps-container">';
	$content .= '<ul id="tutorial-steps-list">';
	$content .= '<li class="first active"><img src="'.get_image_path().'/tutorial/tutorial_step_01.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_02.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_03.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_04.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_05.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_06.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_07.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_08.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_09.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_10.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_11.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_12.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_13.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_14.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_15.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_16.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_17.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_18.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_19.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_20.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_21.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_22.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_23.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_24.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_25.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_26.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_27.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_28.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_29.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_30.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_31.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_32.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_33.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_34.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_35.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_36.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_37.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_38.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_39.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_40.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_41.jpg" /></li>';
	$content .= '<li><img src="'.get_image_path().'/tutorial/tutorial_step_42.jpg" /></li>';
	$content .= '<li class="last"><img src="'.get_image_path().'/tutorial/tutorial_step_43.jpg" /></li>';
	$content .= '</ul>';
	$content .= '<div id="tutorial-steps-controllers">';
	$content .= '<a id="tutorial-finish" class="cta-tut" href="javascript:void(0)"><span>Close</span></a>';
	$content .= '<a id="tutorial-next-step" class="cta-tut-next" href="javascript:void(0)"><span>Next</span></a>';
	$content .= '<a id="tutorial-prev-step" class="disabled cta-tut-prev" href="javascript:void(0)"><span>Prev</span></a>';
	//$content .= '<span id="temp-counter">01</span>';
	
	$content .= '</div>';
	$content .= '</div>';
	
	$response['error'] = false;
	$response['content'] = $content;
	
	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}


/*----------------------------------------------------------------------------------------------
 //handle the help menu lightboxes
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_help_lightbox', 'handleHelpLightbox' );
add_action( 'wp_ajax_handle_help_lightbox', 'handleHelpLightbox' );

function handleHelpLightbox() {

	$response = array('error' => true,  'message' => '');

	//server side validation
	if(	!isset($_POST['element_id'])){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$element_id = trim($_POST['element_id']);

	$content = '';

	if($element_id == 'trigger-faq'){

		$content .= '<div id="help-faq-lightbox-wrapper">';
		$content .= '<div id="help-faq-lightbox" class="general-lightbox">';

		$content .= '<h1>'.get_the_title(FAQ_PAGE_ID).'</h1>';
		$content .= '<div id="faq-section-lightbox">';

		$rows_sections = get_field('acf_faq_section', FAQ_PAGE_ID);
		
		if($rows_sections){
				
			foreach($rows_sections as $row_section){

				if(isset($row_section['acf_faq_section_show_in_tool'][0]) && $row_section['acf_faq_section_show_in_tool'][0] == 'yes'){

					$content .= '<h2>'.$row_section['acf_faq_section_name'].'</h2>';
						
					$rows_faqs = $row_section['acf_faq_row'];
						
					if($rows_faqs){

						foreach($rows_faqs as $row){
								
							$content .= '<div class="faq-item">';
							$content .= '<h3>'.$row['acf_faq_row_question'].'</h3>';
							$content .=	'<div class="faq-answer">';
							$content .= $row['acf_faq_row_answer'];
							$content .= '</div>';
							$content .= '</div>';
								
						}
					}
				}
			}
		}
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';



	}else if($element_id == 'trigger-guide' || $element_id == 'trigger-guide2'){

		$content .= '<div id="help-guide-lightbox-wrapper">';
		$content .= '<div id="help-guide-lightbox" class="general-lightbox content-page">';
		$content .= '<h1>'. get_the_title(GUIDE_PAGE_ID).'</h1>';
		$content .= '<div class="content-intro-blue">';
		$content .= get_field('acf_blue_intro', GUIDE_PAGE_ID);
		$content .= '</div>';
		
		$content .= '<div class="article-content">';
		$post_content = get_post_field('post_content', GUIDE_PAGE_ID);
		
		$content .= apply_filters('the_content', $post_content);
		
		$content .= '</div>';

		
		
		$content .= '</div>';
		$content .= '</div>';

	}else{

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}

	$response['error'] = false;
	$response['content'] = $content;

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}


/*----------------------------------------------------------------------------------------------
 //handle the add to favourite function
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_remove_favourite', 'handleRemoveFavourite' );
add_action( 'wp_ajax_handle_remove_favourite', 'handleRemoveFavourite' );

function handleRemoveFavourite() {

	$response = array('error' => true,  'message' => '');

	//server side validation
	if(	!isset($_POST['favourite_id'])){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$favourite_id = trim($_POST['favourite_id']);
	$user_id = get_current_custom_user_id();

	global $wpdb;

	//try to delete record
	$favorite_delete = $wpdb->delete(
			'wp_favourite',
			array( 	'id' 		=> $favourite_id,
					'user_id' 	=> $user_id
			),
			array( '%d','%d' )
	);



	if($favorite_delete != 1){

		$response['error'] = true;
		$response['message'] = 'Cheating?';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}



	$response['error'] = false;
	$response['message'] = '';

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}



/*----------------------------------------------------------------------------------------------
 //handle the add to favourite function
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_add_favourite', 'handleAddFavourite' );
add_action( 'wp_ajax_handle_add_favourite', 'handleAddFavourite' );

function handleAddFavourite() {

	$response = array('error' => true,  'message' => '');

	//server side validation
	if(	!isset($_POST['image_id'])){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$image_id = trim($_POST['image_id']);
	$user_id = get_current_custom_user_id();

	global $wpdb;

	//check if exists already
	$favorite_record = $wpdb->get_var("SELECT id
										 FROM wp_favourite
										WHERE image_id = ".$image_id."
										  AND user_id = ".$user_id);

	if(!$favorite_record){

		//get states list
		$insert_record = $wpdb->insert('wp_favourite',
				array(
						'user_id' 	=> $user_id,
						'image_id' 	=> $image_id
				),
				array(
						'%d',
						'%d'
				));

		if(!$insert_record){

			$response['error'] = true;
			$response['message'] = 'Can\'t insert';
				
			header( "Content-Type: application/json" );
			echo json_encode( $response );
			exit;
		}

	}



	$response['error'] = false;
	$response['message'] = '';

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}

/*----------------------------------------------------------------------------------------------
 //handle the state list refresh - AJAX
------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_states', 'handleStatesAction' );
add_action( 'wp_ajax_handle_states', 'handleStatesAction' );

function handleStatesAction() {

	$response = array('error' => true,  'message' => '');


	//server side validation
	if(	!isset($_POST['countryId'])){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$countryId = trim($_POST['countryId']);

	global $wpdb;
	//get states list
	$query = $wpdb->prepare("SELECT * FROM wp_state WHERE state_country_id= %d  ORDER BY state_name", $countryId );

	$states = $wpdb->get_results( $query );

	$response['error'] = false;
	$response['message'] = $states;

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}




/*----------------------------------------------------------------------------------------------
 //handle the forgot password process - AJAX - check
------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_forgot', 'handleForgotAction' );
add_action( 'wp_ajax_handle_forgot', 'handleForgotAction' );

function handleForgotAction() {

	
	$response = array('error' => true,  'message' => '');


	//server side validation
	if(	!isset($_POST['forgot_email'])){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$email = trim($_POST['forgot_email']);

	//Validation

	//EMAIL is valorized
	if(!$email){
		$response['error'] = true;
		$response['message'] = 'Please enter your email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//EMAIL is valid
	if(!validEmail($email)){
		$response['error'] = true;
		$response['message'] = 'Please enter a valid email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//all good, check if existing user
	$user = get_user_by( 'email', $email );

	if(!$user){

		$response['error'] = true;
		$response['message'] = 'We can\'t match your email in our records, please check and try again or <a href="'. esc_url( home_url( '/' ) ).'contact-us/">contact us</a> for help.';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}


	//create activation code for reset and save on table
	$activationCode = wp_generate_password(20);
	
	$activationCode = str_replace("%", "", $activationCode);

	global $wpdb;
	//$wpdb->query("INSERT INTO wp_reset_request (reset_userId,reset_token,reset_time, reset_status) VALUES(".$user->ID.",'".$activationCode."','".date("Y-m-d H:i:s")."', 1);");


	$wpdb->insert(
			'wp_reset_request',
			array(
					'reset_userId' 	=> $user->ID,
					'reset_token' 	=> $activationCode,
					'reset_time' 	=> date("Y-m-d H:i:s"),
					'reset_status' 	=> 1
			)
	);

	$lastInserted = $wpdb->insert_id;

	//check if record has been saved
	if(!$lastInserted){

		$response['error'] = true;
		$response['message'] = 'Sorry, something went wrong - Please try again later';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}

	//deactive other active requests
	$query = $wpdb->prepare("UPDATE wp_reset_request SET reset_status = 0 WHERE reset_userId = %d and id != %d;", $user->ID,$lastInserted );

	$wpdb->query($query);

	//send email
	if(!sendForgotPasswordEmail($user->ID, $activationCode)){

		$response['error'] = true;
		$response['message'] = 'Sorry, we couldn\'t send the email. Please try again or <a href="'.esc_url( home_url( '/' ) ).'contact-us/">contact us</a> for help.';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}


	//All good, email sent
	$response['error'] = false;
	$response['message'] = '';

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;
}




/*----------------------------------------------------------------------------------------------
 //handle the login process - AJAX - check TODO add guest user data to real user
------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_login', 'handleLoginAction' );
add_action( 'wp_ajax_handle_login', 'handleLoginAction' );

function handleLoginAction() {

	$response = array('error' => true,  'message' => '','user' => '');


	//server side validation
	if(	!isset($_POST['login_email']) 	||
	!isset($_POST['login_password']) ){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$email = trim($_POST['login_email']);
	$password = trim($_POST['login_password']);

	//Validation

	//EMAIL is valorized
	if(!$email){
		$response['error'] = true;
		$response['message'] = 'Please enter your email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//EMAIL is valid
	if(!validEmail($email)){
		$response['error'] = true;
		$response['message'] = 'Please enter a valid email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//CHECK PASSWORD
	//check valorized
	if($password == ''){

		$response['error'] = true;
		$response['message'] = 'Please enter your password';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//check length
	if(strlen($password) < 6){

		$response['error'] = true;
		$response['message'] = 'Please enter a valid password';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}

	//all good, get user by email
	$user = get_user_by( 'email', $email );

	if(!$user){

		$response['error'] = true;
		$response['message'] = 'Sorry, that email address cannot be found';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}


	//sign in the user
	$userCredentials = array(
			'user_login'	=> $user->user_login,
			'user_password'	=> $password,
			'remember' 		=> false
	);

	$userSigned = wp_signon( $userCredentials, false );


	if ( is_wp_error($userSigned) ){

		//handle default error with custom error - create a case for each know error code, oterwise generic error

		//incorrect password
		if($userSigned->get_error_code() == 'incorrect_password'){

			$response['error'] = true;
			$response['message'] = 'Sorry, incorrect password';

			header( "Content-Type: application/json" );
			echo json_encode( $response );
			exit;

		}


		//generic errror message
		$response['error'] = true;
		$response['message'] = 'Sorry, login details are incorrect';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}
	
	//set current user
	wp_set_current_user($userSigned->ID);

	//check if I need to merge the session user with the logged in one
	merge_session_user();

	//All good, user signed in
	$response['error'] = false;
	$response['message'] = '';

	//set user type for redirect
	if(current_user_can('manage_options')){
		$response['user'] = 'admin';
	}



	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;
}



/*----------------------------------------------------------------------------------------------
 //handle the reset password process - AJAX - check
------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_reset', 'handleResetAction' );
add_action( 'wp_ajax_handle_reset', 'handleResetAction' );

function handleResetAction() {

	$response = array('error' => true,  'message' => '');


	//server side validation
	if(	!isset($_POST['email']) 		||
	!isset($_POST['pass']) 			||
	!isset($_POST['repassword'])	||
	!isset($_POST['get_key']) 		||
	!isset($_POST['get_login'])){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//save friendly named variables
	$email = trim($_POST['email']);
	$password = trim($_POST['pass']);
	$repassword = trim($_POST['repassword']);
	$userId = trim($_POST['get_login']);
	$key = trim($_POST['get_key']);

	//Validation

	//EMAIL is valorized
	if(!$email){
		$response['error'] = true;
		$response['message'] = 'Please enter your email';
		$response['field'] = 'email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//EMAIL is valid
	if(!validEmail($email)){
		$response['error'] = true;
		$response['message'] = 'Please enter a valid email';
		$response['field'] = 'email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//CHECK PASSWORD
	//check valorized
	if($password == ''){

		$response['error'] = true;
		$response['message'] = 'Please enter your password';
		$response['field'] = 'pass';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//check length
	if(strlen($password) < 6){

		$response['error'] = true;
		$response['message'] = 'Please enter a valid password';
		$response['field'] = 'pass';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}

	//check password match
	if($password != $repassword){

		$response['error'] = true;
		$response['message'] = 'Your password doesn\'t match';
		$response['field'] = 'pass';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}

	//all good, get user by login name

	$user = get_user_by( 'email', $email );

	if(!$user){
		$response['error'] = true;
		$response['message'] = 'We can\'t match your email address, please check and try again.';
		$response['field'] = 'email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}

	//also check that user ID match with paramenters
	if($user->ID != $userId){
		$response['error'] = true;
		$response['message'] = 'We can\'t match your email address, please check and try again.';
		$response['field'] = 'email';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

	}


	//get password request history (status 0)
	global $wpdb;


	$query = $wpdb->prepare("SELECT * FROM wp_reset_request WHERE reset_userId = %d AND reset_status = 0 ORDER BY reset_time DESC;", $user->ID);
	$passwordResetHistory = $wpdb->get_results( $query );

	//check if an old link was clicked
	if($passwordResetHistory){
		foreach ($passwordResetHistory as $passwordResetItem){

			if(md5($passwordResetItem->reset_token) == $key ){

				$response['error'] = true;
				$response['message'] = 'You have clicked a link in an old email. You have since requested another password reset and a new email has been sent to you at <strong>'.$email.'</strong> or you already changed your password. Please click the link in the new email to reset your password or send a new request.';
				

				header( "Content-Type: application/json" );
				echo json_encode( $response );
				exit;

				return $response;
			}

		}
	}

	//get active password request (status 1)
	$query = $wpdb->prepare("SELECT * FROM wp_reset_request WHERE reset_userId = %d AND reset_status = 1;",$user->ID);
	$validPasswordReset = $wpdb->get_row( $query );

	if(!$validPasswordReset){
		$response['error'] = true;
		$response['message'] = 'We couldn\'t find any active password reset request.';
		

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//calculate time to check if link has expired (1 hour)
	$actualTime = date("Y-m-d H:i:s");

	$expiringDate = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($actualTime)));


	if($expiringDate > $validPasswordReset->reset_time){

		$response['error'] = true;
		$response['message'] = 'The link you clicked has expired as it is more than one hour old. Please use the forgotten password link to request another reset link to be emailed to you.';
		

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;


	}

	//compare Link and db activation key
	if(md5($validPasswordReset->reset_token) != $key){

		$response['error'] = true;
		$response['message'] = 'Sorry, your data doesn\'t match with the database.';
		

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;

		return $response;
	}


	//All good, change password and log in the user
	wp_set_password( $password, $user->ID );

	//sign in the user
	$userCredentials = array(
			'user_login'	=> $user->user_login,
			'user_password'	=> $password,
			'remember' 		=> false
	);

	$userSigned = wp_signon( $userCredentials, false );

	if ( is_wp_error($userSigned) ){
			
		$response['error'] = true;
		$response['message'] = 'Sorry, an error occured.Try again later.';
		

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}
	
	
	//reset status password change request
	//$wpdb->query("UPDATE wp_reset_request SET reset_status = 0 WHERE id = ".$validPasswordReset->id.";");
	
	$wpdb->update(
			'wp_reset_request',
			array(
					'reset_status' => 0
			),
			array( 	'id' => $validPasswordReset->id )
	);
	
	//set current user
	wp_set_current_user($userSigned->ID);
	
	//check if I need to merge the session user with the logged in one
	merge_session_user();

	//All good, user signed in
	$response['error'] = false;
	$response['message'] = '';

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}


/*----------------------------------------------------------------------------------------------
 //handle the page finished process
------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_page_finished', 'handlePageFinished' );
add_action( 'wp_ajax_handle_page_finished', 'handlePageFinished' );

function handlePageFinished() {

	
	$response = array('error' => true,  'message' => '', 'popup' => '');

	global $wpdb;
	
	//server side validation
	if(	!isset($_POST['image_id'])		||
		!isset($_POST['image_name'])	||
		!isset($_POST['step_1'])		||
		!isset($_POST['step_2'])		||
		!isset($_POST['step_3'])		||
		!isset($_POST['step_4'])		||
		!isset($_POST['image_data'])	){

		$response['error'] = true;
		$response['message'] = 'Something went wrong';

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	//handy variables
	$image_data = $_POST['image_data'];
	$image_id = $_POST['image_id'];
	$image_name = $_POST['image_name'];
	$step_1 = $_POST['step_1'];
	$step_2 = $_POST['step_2'];
	$step_3 = $_POST['step_3'];
	$step_4 = $_POST['step_4'];
	
	
	if($image_data == 0){
		
		//save result
		$wpdb->query(
			'INSERT 
			   INTO wp_result
				  ( user_id,
					image_id,
					image_name,
					step_1,
					step_2,
					step_3,
					step_4,
					ra_deg,
					dec_deg,
					rot_w2n,
					radminasec,
					radmajasec,
					points_reference,
					ellipse_reference)
			VALUES( '.get_current_custom_user_id().',
					'.$image_id.',
					'.$image_name.',
					"'.$step_1.'",
					"'.$step_2.'",
					"'.$step_3.'",
					"'.$step_4.'",
					NULL,
					NULL,
					NULL,
					NULL,
					NULL,
					NULL,
					NULL);'
		);
		
		//mark image as complete only if 3rd option selected in first step or image skipped
		if ($step_1 == '3' ||
			$step_1 == '9'){
			update_image_total_processed($image_id);
		}
		
	}else{
	
	
		//we should always have one and only ellipse
		if(!isset($image_data['ellipses'][0])){
			
			//do not save the result, something went wrong
			$response['error'] = true;
			$response['message'] = 'Something went wrong';
			
			header( "Content-Type: application/json" );
			echo json_encode( $response );
			exit;
		}
		
		$ellipse_data = $image_data['ellipses'][0];
		
		//regarding the point, is not necessary to have them
		$points_data = '';
		
		if(isset($image_data['points'])){
		
			$points_data = $image_data['points'];
		}
		
		//increment image processed
		update_image_total_processed($image_id);
		
		$ellipse_data_degree = convertImageDataToCoordinate($image_id, $ellipse_data);
		
		$wpdb->insert(
				'wp_result',
				array(
						'user_id' 			=> get_current_custom_user_id(),
						'image_id'	 		=> $image_id,
						'image_name'	 	=> $image_name,
						'step_1'	 		=> $step_1,
						'step_2'	 		=> $step_2,
						'step_3'	 		=> $step_3,
						'step_4'	 		=> $step_4,
						'ra_deg'	 		=> $ellipse_data_degree['ra_deg'],
						'dec_deg'	 		=> $ellipse_data_degree['dec_deg'],
						'rot_w2n'	 		=> $ellipse_data_degree['rot_w2n'],
						'radminasec' 		=> $ellipse_data_degree['radminasec'],
						'radmajasec'	 	=> $ellipse_data_degree['radmajasec'],
						'points_reference'	=> serialize($points_data),
						'ellipse_reference'	=> serialize($ellipse_data)
				)
		);
	}
	
	
	
	$images_completed = get_user_total_images();
	
	$popup = '';
	$popup_id = '';
	
	//handle special cases for guest user
	if(!is_user_logged_in()){
		
		if($images_completed >= COMPETITION_IMAGES_SINGLE){
		
			//if user is a guest user, and he just complete the 10th image,
			//add a flag, so when user register (and is an individual) i'll show the competition popup
			update_user_meta(get_current_custom_user_id(), 'popup-competition', 'true');
		}
		
		if($images_completed == 5){
		
			//if user is a guest user, and he just complete the 10th image,
			//show popup registration reminder
			$popup = get_popup_guest_user_registration_reminder();
			$popup_id = 'guest-registration-reminder-lightbox';
		}
		
	}else{
		
		if(COMPETITION_ACTIVE){
		
			//handle registered user
			if(is_user_individual()){
				
				//individual user
				if($images_completed % COMPETITION_IMAGES_SINGLE == 0){
					$popup = get_popup_competition_individual();
					$popup_id = 'competition-lightbox';
					
				}
				
				
			}else{
				
				//school user
				
				if($images_completed % COMPETITION_IMAGES_GROUP == 0){
					
					//add competition record for school
					add_competition_entry();
					
					//send_competition_email_to_teacher();
					
					$popup = get_popup_competition_school();
					$popup_id = 'competition-school-lightbox';
				
				}
				
				
			}
		}
	}
	
	//All good
	$response['error'] = false;
	$response['message'] = '';
	$response['popup'] = $popup;
	$response['popup_id'] = $popup_id;

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}




/*----------------------------------------------------------------------------------------------
 ///update the tutorial status to completed -AJAX
------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_update_tutorial_status', 'updateTutorialStatus' );
add_action( 'wp_ajax_update_tutorial_status', 'updateTutorialStatus' );

function updateTutorialStatus(){


	update_user_meta( get_current_custom_user_id(), 'show_tutorial', 'false');
	
}


/*----------------------------------------------------------------------------------------------
 ///show the competition popup on starting the tool
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_show_competition_popup_on_start', 'handleShowCompetitionPopupOnStart' );
add_action( 'wp_ajax_handle_show_competition_popup_on_start', 'handleShowCompetitionPopupOnStart' );

function handleShowCompetitionPopupOnStart(){

	$response = array('error' => true,  'message' => '', 'popup' => '');
	
	//server side check
	if(is_user_logged_in() && get_user_meta(get_current_user_id(),'popup-competition', true ) == 'true' && COMPETITION_ACTIVE){
		
		if(is_user_individual()){
		
			$response['popup'] = get_popup_competition_individual('on-start');
			$response['type'] = 'individual';
			$response['popup_id'] = 'competition-lightbox';
		}else{
			
			//add competition record for school
			add_competition_entry();
			
			//send_competition_email_to_teacher();
			
			update_user_meta(get_current_user_id(), 'popup-competition', 'false');
			
			$response['popup'] = get_popup_competition_school('on-start');
			$response['type'] = 'group';
			$response['popup_id'] = 'competition-school-lightbox';
			
		}
		
	}
	
	//All good
	$response['error'] = false;
	$response['message'] = '';
	
	
	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;

}

/*----------------------------------------------------------------------------------------------
 ///handle the competition submission for individual users
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_submit_competition_entry', 'handleSubmitCompetitionEntry' );
add_action( 'wp_ajax_handle_submit_competition_entry', 'handleSubmitCompetitionEntry' );

function handleSubmitCompetitionEntry(){

	$response = array('error' => true,  'message' => '');
	
	//server side validation
	if(	//!isset($_POST['captcha'])	||
		!isset($_POST['message']) ||
		!isset($_POST['captcha_checkbox']) ||
		!isset($_POST['email'])){
	
		$response['error'] = true;
		$response['message'] = 'Something went wrong';
	
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}
	
	
	//handy variables
	//$captcha = $_POST['captcha'];
	$message = trim(substr ( $_POST['message'] , 0, 500 ));
	$captcha_checkbox = $_POST['captcha_checkbox'];
	
	//this is a honeypot
	$email_honeypot = $_POST['email'];
	
	/*if(!$captcha){
		
		$response['error'] = true;
		$response['field'] = 'captcha';
		$response['message'] = 'Please verify that you\'re a real user.';
		
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
		
	}*/
	
	
	//if honeypot is filled, just show ok message
	if($email_honeypot != ''){
	
		$response['error'] = false;
		$response['message'] = '';
		
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	
	}
	
	
	if($captcha_checkbox != '1'){
	
		$response['error'] = true;
		$response['field'] = 'captcha_checkbox';
		$response['message'] = 'Please tick to verify you\'re a real person.';
	
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	
	}
	
	
	/*require_once get_template_directory() . '/inc/ReCaptcha/autoload.php';
	
	$recaptcha = new \ReCaptcha\ReCaptcha(GE_CAPTCHA_PRIVATE_KEY);

	$resp = $recaptcha->verify($captcha, $_SERVER['REMOTE_ADDR']);
	
	if (!$resp->isSuccess()){
		
		$response['error'] = true;
		$response['field'] = 'captcha-reload';
		$response['message'] = 'Something went wrong. Please try again.';
		
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}*/
	
	
	//make sure user is logged in and is a individual, not a school
	if(!is_user_logged_in() || !is_user_individual()){
		
		$response['error'] = true;
		$response['message'] = 'Something went wrong. Please try again.';
		
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}
	
	
	//enter competition
	if(!add_competition_entry($message)){
		
		$response['error'] = true;
		$response['message'] = 'Something went wrong. Please try again.';
		
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
		
	}else{
		$response['total_entries'] = get_user_total_competition_entries();
	}
	
	//alway clean the switch for the competition popup on start tool
	update_user_meta(get_current_user_id(), 'popup-competition', 'false');
	
	
	$response['error'] = false;
	$response['message'] = '';
	
	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;
	

}


/*----------------------------------------------------------------------------------------------
 ///handle the competition skip
 ------------------------------------------------------------------------------------------------*/
add_action( 'wp_ajax_nopriv_handle_skip_competition_entry', 'handleSkipCompetitionEntry' );
add_action( 'wp_ajax_handle_skip_competition_entry', 'handleSkipCompetitionEntry' );

function handleSkipCompetitionEntry(){

	update_user_meta(get_current_user_id(), 'popup-competition', 'false');


	$response['error'] = false;
	$response['message'] = '';

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;


}


