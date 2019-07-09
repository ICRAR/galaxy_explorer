<?php
/*
Template Name: Register Template
*/



	//check submission
	if(isset($_POST['action'])){
		if($_POST['action'] == 'register'){
		
			$response = handleRegistration($_POST);
			
			if ($response['error']){
				//handle error
				$errorSwitch = $response['error'];
				$errorMessage = $response['message'];
				$justJoined = false;
				
			}else{
				//Registration succesful, set to display thank you page
				$errorSwitch = '';
				$errorMessage = '';
				$justJoined = true;
				
				
				
			}
		}
	}
	
	
	if ( is_user_logged_in()){
	
		wp_redirect( '/classify/' ); exit;
		
	}


	
	//set radio individual/group
	$individualSelected = true;
	$groupSelected = false;
	
	if(isset($_POST['option']) && $_POST['option'] == 'group'){
	
		$individualSelected = false;
		$groupSelected = true;
	}
	
	
	//countries
	$countries_options_html = '';
	$selectedCountryId = false;
	$typePrev = '1';
	$swDivider = false;
	
	$countries = $wpdb->get_results( "SELECT *
										FROM wp_country 
									ORDER BY country_priority DESC, 
											 country_name" );
	
	foreach ($countries as $country){
		
		$selected = '';
		$class = '';
			
		//check if the priority changed, and in case put a divider
		if($typePrev !=  $country->country_priority && !$swDivider) {
			
			$countries_options_html .= '<option value="divider">-----------------------</option>';
			$swDivider = true;
		}
			
		//assign class if country has states list
		if($country->country_stateList == '1'){

			$class = ' class="countryList"';
		}
			
		//check if coming from post
		if(isset($_POST['country']) && $_POST['country'] == $country->country_name){
				
			$selected = ' selected="selected"';
			$selectedCountryId = $country->id;
		}
			
		$countries_options_html .= '<option value="'.$country->country_name.'"'.$class.' id="'.$country->id.'"'.$selected.'>'.$country->country_name.'</option>';
			
	}
	
		
	
get_header(); ?>

<div class="content-page-wrapper">
	<div class="container">
	
	<?php while ( have_posts() ) : the_post(); ?>

		<div id="register" class="content-page">
		
			<h1><?php echo get_the_title();?></h1>
			
			<div class="intro">
				<?php if(isset($_GET['message']) && $_GET['message'] == 'guest'):?>
				
					<p style="text-decoration: underline;">You have completed 10 images, you now need to register before continuing</p>
				
				<?php endif;?>
				<?php the_content(); ?>
				
				
			</div>
			
			<form class="general-form" method="post" action="" id="form-registration">
				<fieldset>
					
					<input name="action" type="hidden" value="register"/>
					
					<div class="choose-one clear">
						
						<span class="choose">Would you like to register as:</span>
						
						<div class="radio-list">
						    <div>
			                    <input id="individual"<?php echo ($individualSelected ? ' checked="checked"' : '' ); ?> type="radio" name="option" value="individual" />
								<label for="individual">An Individual</label>
							</div>
							<div>
								<input id="group"<?php echo ($groupSelected ? 'checked="checked"' : ''  ) ?> type="radio" name="option" value="group" />
								<label for="group">A teacher on behalf of a school group</label>
							</div>
						</div>
					</div>
					
					<div class="quick-login">
						<span class="login-link-span">Already registered? </span>
						<a id="trigger-login-panel"  href="javascript:void(0)">Login now</a>
					</div>
					
					
					<div id="error-message">
						<?php if (isset($errorSwitch) && ($errorSwitch)):?>
						
							<span class="important">* <?php echo $errorMessage;?></span>
						
							<script type="text/javascript">
								$(document).ready(function() {
									$("html,body").animate({scrollTop: $("#error-message").offset().top - 10}, 300);
								});
							</script>
							
						<?php endif;?>	
					</div>
					
					<div class="row">
						<label for="firstname" class="text_label">First Name</label>
						<input id="firstname" name="firstname" class="addLabel" type="text" value="<?php echo (isset($_POST['firstname']) ? $_POST['firstname'] : 'First name'); ?>" maxlength="150" /><span class="required">*</span>
						<span class="side-notes teacherNote">First name of teacher. </span>

					</div>
					
					<div class="row">
						<label for="lastname" class="text_label">Last Name</label>
						<input id="lastname" name="lastname" class="addLabel" type="text" value="<?php echo (isset($_POST['lastname']) ? $_POST['lastname'] : 'Last name'); ?>" maxlength="150" /><span class="required">*</span>
						<span class="side-notes teacherNote">Last name of teacher. </span>
					</div>
					
					
					<div class="row">
						<label for="country" class="text_label">Country</label>
						<select id="country" name="country" class="addLabel customSelect">
							<option value=""<?php echo (!isset($_POST['country']) ? ' selected="selected"' : '') ?>>Country</option>
							<?php echo $countries_options_html?>
						</select>
						<input id="country_states" name="country_states" type="hidden" value="<?php echo ($selectedCountryId ? 1 : 0); ?>" />
		                <input id="country_states_post" name="country_states_post" type="hidden" value="<?php echo (isset($_POST['state']) ? $_POST['state'] : '' ); ?>" />
		                <span class="required">*</span>
		                <span class="side-notes">Entry to the competition is open to Australian residents only.</span>
					</div>
					
					
		            
		            <div class="row">
						<label for="state" class="text_label">State</label>
						<select id="state" name="state" class="addLabel customSelect">
							<option value=""<?php echo (!isset($_POST['state']) ? ' selected="selected"' : '') ?>>State</option>
						</select>
						<span class="required">*</span>
					</div>
					
					<?php /*
					<div class="row">
						<label for="state_text" class="text_label">State (Other)</label>
						<input id="state_text" class="addLabel" name="state_text" type="text" value="<?php echo (isset($_POST['state_text']) ? $_POST['state_text'] : 'State/Region'); ?>" maxlength="150" />
						<span class="required">*</span>
					</div>
					*/?>
					
					<div class="row">
						<input id="suburb" class="addLabel" name="suburb" type="text" value="<?php echo (isset($_POST['suburb']) ? $_POST['suburb'] : 'Suburb'); ?>" maxlength="150" />
						<span class="required">*</span>
					</div>
					
					
					<div class="row">
						<label for="schoolGroupName" class="text_label">School Group Name</label>
						<input id="schoolGroupName" class="addLabel" name="schoolGroupName" type="text" value="<?php echo (isset($_POST['schoolGroupName']) ? $_POST['schoolGroupName'] : 'School group name'); ?>" maxlength="150" />
						<span class="required">*</span>
					</div>
					
					
					<div class="row">
						<label for="schoolType" class="text_label">School Type</label>
						<select id="schoolType" name="schoolType" class="addLabel customSelect">
							<option value=""<?php echo (!isset($_POST['schoolType']) ? ' selected="selected"' : '') ?>>School group level</option>
							<option value="Primary"<?php echo (isset($_POST['schoolType']) && $_POST['schoolType'] == 'Primary'  ? ' selected="selected"' : '') ?>>Primary</option>
							<option value="Secondary"<?php echo (isset($_POST['schoolType']) && $_POST['schoolType'] == 'Secondary'  ? ' selected="selected"' : '') ?>>Secondary</option>
						</select>
					</div>
					
					
					<div class="row">
						<label for="schoolGroupNumber" class="text_label">Number in School Group</label>
						<input id="schoolGroupNumber" class="addLabel" name="schoolGroupNumber" type="text" value="<?php echo (isset($_POST['schoolGroupNumber']) ? $_POST['schoolGroupNumber'] : 'Number in school group'); ?>" maxlength="150" />
						<span class="required">*</span>
					</div>
					
					
					<div class="row">
						<label for="gender" class="text_label">Gender</label>
						<select id="gender" name="gender" class="addLabel customSelect">
							<option value=""<?php echo (!isset($_POST['gender']) ? ' selected="selected"' : '') ?>>Gender</option>
							<option value="Male"<?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male'  ? ' selected="selected"' : '') ?>>Male</option>
							<option value="Female"<?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female'  ? ' selected="selected"' : '') ?>>Female</option>
						</select>
					</div>
					
		            
		            <div class="row">
						<label for="age" class="text_label">Age Range</label>
						<select id="age" name="age" class="addLabel customSelect">
							<option value=""<?php echo (!isset($_POST['age']) ? ' selected="selected"' : '') ?>>Age</option>
							<option value="Under 18"<?php echo (isset($_POST['age']) && $_POST['age'] == 'Under 18'  ? ' selected="selected"' : '') ?>>Under 18</option>
							<option value="18 - 29"<?php echo (isset($_POST['age']) && $_POST['age'] == '18 - 29'  ? ' selected="selected"' : '') ?>>18 - 29</option>
							<option value="30 - 39"<?php echo (isset($_POST['age']) && $_POST['age'] == '30 - 39'  ? ' selected="selected"' : '') ?>>30 - 39</option>
							<option value="40 - 49"<?php echo (isset($_POST['age']) && $_POST['age'] == '40 - 49'  ? ' selected="selected"' : '') ?>>40 - 49</option>
							<option value="50 - 59"<?php echo (isset($_POST['age']) && $_POST['age'] == '50 - 59'  ? ' selected="selected"' : '') ?>>50 - 59</option>
							<option value="60 - 69"<?php echo (isset($_POST['age']) && $_POST['age'] == '60 - 69'  ? ' selected="selected"' : '') ?>>60 - 69</option>
							<option value="70 - 79"<?php echo (isset($_POST['age']) && $_POST['age'] == '70 - 79'  ? ' selected="selected"' : '') ?>>70 - 79</option>
							<option value="Over 79"<?php echo (isset($_POST['age']) && $_POST['age'] == 'Over 79'  ? ' selected="selected"' : '') ?>>Over 79</option>
						</select>
					</div>
					
					
					<div class="row">
						<label for="education" class="text_label">Education Level</label>
						<select id="education" name="education" class="addLabel customSelect">
							<option value=""<?php echo (!isset($_POST['education']) ? ' selected="selected"' : '') ?>>Education level</option>
							<option value="Primary"<?php echo (isset($_POST['education']) && $_POST['education'] == 'Primary'  ? ' selected="selected"' : '') ?>>Primary</option>
							<option value="Secondary"<?php echo (isset($_POST['education']) && $_POST['education'] == 'Secondary'  ? ' selected="selected"' : '') ?>>Secondary</option>
							<option value="University / College"<?php echo (isset($_POST['education']) && $_POST['education'] == 'University / College'  ? ' selected="selected"' : '') ?>>University / College</option>
						</select>
						<span class="side-notes">What is your highest level of education? If you're currently at school or studying, select the level you're at now.</span>
					</div>
					
					
					<div class="row">
						<label for="ademail" class="text_label">Email Address</label>
						<input id="ademail" class="addLabel" name="ademail" type="text" value="<?php echo (isset($_POST['ademail']) ? $_POST['ademail'] : 'Email'); ?>" maxlength="150" />
						<span class="required">*</span>
						<span class="side-notes"><span class="change">You</span> will use this to log in. We will only contact you in relation to 'Galaxy Explorer'. Read our <a href="http://about.abc.net.au/abc-privacy-policy/" target="_blank">Privacy Policy</a>. </span>
					</div>
					
					<div class="row">
						<label for="phone" class="text_label">Phone</label>
						<input id="phone" class="addLabel" name="phone" type="text" value="<?php echo (isset($_POST['phone']) ? $_POST['phone'] : 'Contact phone number'); ?>" maxlength="150" />
						<span class="required">*</span>
						<span class="side-notes">We will only contact you in relation to the prize</span>
					</div>
					
					
					<div class="row">
						<label for="pass" class="text_label">Password</label>
						<input id="pass" class="addLabel passwordReplace" name="pass" type="password" value="Password" maxlength="150" />
						<span class="required">*</span>
						<span class="side-notes">Minimum 6 characters</span>

						<span class="side-notes teacherNote">This password will be given to your students to log in. Please don’t use a personal password. </span>
					</div>
					
					
					<div class="row">
						<label for="repassword" class="text_label">Confirm Password</label>
						<input id="repassword" class="addLabel passwordReplace" name="repassword" type="password" value="Re-enter your password" maxlength="150" />
						<span class="required">*</span>
					</div>
					
					<div class="row row-checkbox">
						<input id="parental" name="parental" type="checkbox"/>
						<label for="parental">
							<span class="label_individual">I have parental permission to take part in 'Galaxy Explorer'.</span>
							<span class="label_group">I have permission from parents / guardians for students to take part in 'Galaxy Explorer'.</span>
						</label>
					</div>
					
					<div class="row row-checkbox">
						<input id="research" name="research" type="checkbox"  />
						<label for="research"><span class="label_individual">I understand that by taking part in 'Galaxy Explorer' I am contributing to a scientific research project and intend to participate to the best of my abilities.</span><span class="label_group">I understand that by taking part in 'Galaxy Explorer' my school group is contributing to a scientific research project and the group members intend to participate to the best of their abilities.</span></label>
					</div>
					
					<?php if (COMPETITION_ACTIVE):?>
					<div class="row row-checkbox">
						<input id="terms" name="terms" type="checkbox"  />
						<label for="terms" id="terms_individual">I accept the competition <a id="terms-link" href="terms-and-conditions">Terms and Conditions</a></label>
						<label for="terms" id="terms_teacher">
							I have the authority to, and do, accept the competition <a id="terms-link" href="terms-and-conditions">Terms and Conditions</a>
							 on behalf of myself and on behalf of every member of my student group.
							</label>
					</div>
					<?php endif;?>
					
					
					<a href="javascript:void(0);" class="cta-30-white" id="registration_submit"><span>Join now</span></a>
					
				</fieldset>
			</form>
		</div>

	<?php endwhile; // end of the loop. ?>
	
	</div>
</div>

<?php get_footer(); ?>
