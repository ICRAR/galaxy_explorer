//default input fileds labels
var defaultLabels = {
		//input name value
		'login_email' 				: 'Email',
		'login_password'			: 'Password',
		'forgot_email' 				: 'Email',
		'firstname'					: 'First Name',
		'lastname'					: 'Last Name',
		'schoolGroupName'			: 'School group name',
		'schoolGroupNumber'			: 'Number in school group',
		'ademail'					: 'Email',
		'email'						: 'Email address',
		'pass'						: 'Password',
		'repassword'				: 'Re-enter your password',
		'country'					: 'Country',
		'state'						: 'State',
		'state_text'				: 'State/Region',
		'schoolType'				: 'School group level',
		'gender'					: 'Gender',
		'age'						: 'Age',
		'education'					: 'Education level',
		'suburb'					: 'Suburb',
		'phone'						: 'Contact phone number'
};

var totalBoxes = '';
var boxes = new Array();


$( window ).load(function() {
	
	
	
	if($('#galaxy-tool').length > 0){
		
		if($('html.canvas').length > 0){
		
			//handle form submission
			initializeDrawingTool();
		}
		
		
	}
	
	
});


$(document).ready(function() {
	
	//identify browser and platform
	handleDifferentPlatforms();
	
	//check same height elements
	initSameHeight();
	
	handleMenuRegister();
	
	//handle forms
	if($('form').length > 0){
		
		//handle blur and focus for labels on forms
		handleBlurFocus();
		
		//handle custom checkboxes and radios
		handleCustomInputs();
	}
	
	//handle registration form scripts
	if($('#form-registration').length > 0){
		
		handleTriggerLogin();
		
		//first time
		handleRegistrationFields();
		
		//on change
		handleRegistrationFieldsOnChange();
		
		//handle countries/states first time
		handleCountries();
		
		//handle countries/states on change
		handleCountriesOnChange();
		
		//handle parental check on change
		handleParentalOnAgeChange();
		
		//handle form submission
		handleRegistrationSubmission();
	}
	
	//handle login
	if($('#form-login').length > 0){
		
		//handle show hide login panel
		handleLoginPanel();
		
		//handle login/forgot password panels
		handleLoginForgot();
		
		//login
		handleLoginProcess();
		
		//forgot password
		handleForgotProcess();
	}
	
	//handle reset password form
	if($('#form-reset-password').length > 0){
		
		//handle form submission
		handleResetProcess();
	}

	//handle tool functions
	if($('#galaxy-tool').length > 0){
		
		
		//handle form submission
		//initializeDrawingTool();
		
		//handle form submission
		handleFavourite();
		
		//handle tool steps
		handleToolSteps();
		
		//handle competition popup bindings
		handleCompetitionPopup();
		
		//handle the help menu
		handleHelpMenu();
		
		//handle the tutorial
		handleTutorial();
	}
	
	//handle get-started
	if($('#get-started').length > 0){
		
		
		//handle form submission
		//initializeDrawingTool();
		
		//handle form submission
		handleFavourite();
		
		//handle tool steps
		handleGetStarted();
		
		//add support browser message
		if($('html.no-canvas').length > 0){
			
			$("#get-started-register").before('<p>You\'re currently using IE8 and some parts of the galaxy classification tool won\'t work in this browser. You can continue with IE8 but we recommend changing to another browser or upgrading to IE9+.</p>');
			
		}
		
	}
	
	//handle almost-ready
	if($('#almost-ready').length > 0){
		
		
		//handle form submission
		//initializeDrawingTool();
		
		//handle form submission
		handleFavourite();
		
		//handle tool steps
		handleAlmostReady();
		
		//handle the tutorial
		handleTutorial();
		
		
		
	}
	
	//handle favorite page
	if($('#favourite-page').length > 0){
		
		handleTriggerLogin();
		
		//handle form submission
		handleFavouriteGallery();
	}
	
	
	//handle faq page toggle
	if($('#faq-section').length > 0){
		
		//handle faq
		handleFaq();
		
		
	}
	
	
});


function handleFaq(){
	
	//bind click to element
	$('body').on('click', '.faq-question', function() {
		
		var faq_item = $(this).parent();
		
		faq_item.children(".faq-answer").stop(true,true).slideToggle();
		
	});

}



function handleTutorial(){
	
	$('body').on("click", "#trigger-tutorial", function(e) {
		
		if($(this).hasClass('ajax-loading')){
			return false;
		}
		//add specific classes depending on pthe page we are
		if($('#galaxy-tool').length > 0 ){
			
		}
		
		if($('#almost-ready').length > 0 ){
			
		}
		
		$(this).addClass('ajax-loading');
		
		$('#tool-container').prepend('<div id="tutorial-loading-mask"><div id="tutorial-loader">Loading tutorial...</div></div>');
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: {
				action		: "handle_tutorial" }
			
		})
		.done(function(response){ 
			
			$('#tutorial-loading-mask').fadeOut(function(){$(this).remove()});
			$('#tool-container').prepend(response.content);
			
			$('#help-menu-link').removeClass('open');
			$('#help-menu').hide();
			
			if($('#almost-ready.first-time').length > 0){
				
				$('#tutorial-finish').hide();
			}
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){
			
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #001');
			}
		})
		.always(function(){ 
			$('#trigger-tutorial').removeClass('ajax-loading');
		});
	});
	
	
	$('body').on("click", "#tutorial-next-step", function(e) {
		
		if($(this).hasClass('disabled')){
			return;
		}
		
		//get active item
		$active_step = $('#tutorial-steps-list .active');
		$next_step = $active_step.next();
		
		$active_step.removeClass('active').hide();
		$next_step.addClass('active').show();
		
		$('#tutorial-prev-step').removeClass('disabled');
		
		if($next_step.hasClass('last')){
			
			$('#tutorial-next-step').addClass('disabled');
			
			$('#tutorial-finish').show();
		}
		
		//tempShowStep();
			
		
	});
	
	$('body').on("click", "#tutorial-prev-step", function(e) {
		
		if($(this).hasClass('disabled')){
			return;
		}
		
		//get active item
		$active_step = $('#tutorial-steps-list .active');
		$prev_step = $active_step.prev();
		
		$active_step.removeClass('active').hide();
		$prev_step.addClass('active').show();
		
		$('#tutorial-next-step').removeClass('disabled');
		
		if($prev_step.hasClass('first')){
			
			$('#tutorial-prev-step').addClass('disabled');
			
		}
		
		//tempShowStep();
		
	
	});
	
	
	$('body').on("click", "#tutorial-finish", function(e) {
		
		$('#trigger-tutorial').removeClass('ajax-loading');
		
		if($('#galaxy-tool').length > 0){
			
			$('#tutorial-steps-container').fadeOut(function(){$(this).remove()});
			
			return;
		}
		
		if($('#almost-ready').length > 0){
			
			if($('#almost-ready').hasClass('first-time')){
				
				if($(this).hasClass('ajax-running')){
					
					return false;
				}
				
				$(this).addClass('ajax-running');
				
				//check if first time, in that case update tutorial status
				
				$.ajax({
					type: "post",
					url: MyAjax.ajaxurl,
					dataType : "json",	
					data: { action: 	"update_tutorial_status" }
					
				})
				.done(function(response){ 
					
					window.location = "/classify/";
				 })
				.fail(function(jqXHR, textStatus, errorThrown){ 

					if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
			              return;  
					}else{
						alert('Sorry an error occured - Please try again later #002');
					}
				})
				
			}else{
				
				window.location = "/classify/";
				
			}
		}
	});
}

function tempShowStep(){
	
	var active_image = $('#tutorial-steps-list li.active img').attr('src');
	var active_image_array = active_image.split("/");
	var active_image_name = active_image_array.pop();
	
	active_image_name = active_image_name.replace(".jpg", "");
	active_image_name = active_image_name.replace("tutorial_step_", "");
	
	$('#temp-counter').text(active_image_name);
	
}



function handleMenuRegister(){
	
	
	
	$('body').on("click", "#menu-main-menu .register-disabled", function(e) {
		
		e.preventDefault();
		return false;
	});
}




function handleHelpMenu(){
	
	
	
	
	$('body').on("click", "#help-menu-link", function(e) {
		
		if ($(this).hasClass('open')){
			
			$(this).removeClass('open');
			$('#help-menu').hide();
			
		}else{
			$(this).addClass('open');
			$('#help-menu').show();
		}
	});
	
	$('body').on("click", "#trigger-faq,#trigger-guide,#trigger-guide2", function(e) {
		
		if ($(this).hasClass('ajax-running')){
			
			return false;
		}
		
		var element = $(this);
		var element_id = element.attr('id');
		
		
		element.addClass('ajax-running');
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: {
				element_id	: element_id,
				action		: "handle_help_lightbox" }
			
		})
		.done(function(response){ 
			
			if(response.error){
				
				alert(response.message);
			}else{

				if(response.content != ''){
					
					$.colorbox({
						html			: response.content,
						closeButton		: true,
						overlayClose	: true,
						className		: element_id + '-lightbox',
						escKey			: true,
						onComplete: function(){
							
							
							$('#help-menu-link').removeClass('open');
							$('#help-menu').hide();
							
							$( ".article-content img" ).load(function() {
								$('.article-content').sameHeight({
									elements: 'figure',
									flexible: true,
									multiLine: true
								});
								
								$.colorbox.resize();
							});
							
							
						}
					});
					
					
				}
			}
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){
			
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #003');
			}
		})
		.always(function(){ 
			element.removeClass('ajax-running');
		});
		
		
		
		
	});	
	
	
	
}


function handleTriggerLogin(){
	
	$('body').on("click", "#trigger-login-panel", function(e) {
		
		$('.menu-login').trigger('click');
	});
}


function startTutorial(){
	
	//set tutorial boxes properties
	boxes = [];
	
	boxes['step1'] = {	'top'		: 100,
						'left'		: -155,
						'width'		: 390,
						'height' 	: 170,
						'image' 	: ''
	};
	
	boxes['step2'] = {	'top'		: 200,
						'left'		: 15,
						'width'		: 205,
						'height' 	: 65,
						'image' 	: ''
	};
	
	boxes['step3'] = {	'top'		: 230,
						'left'		: 25,
						'width'		: 310,
						'height' 	: 42,
						'image' 	: ''
	};
	
	totalBoxes = 3;
	
	$('#tutorial-popup').removeClass().addClass('step1');
	$('#tutorial-popup .step-content').removeAttr('style');
	$('#cta-tut-prev').removeClass().addClass('disabled');
	$('#cta-tut-next').removeClass().addClass('step2');
	
	//set first box
	$('#tutorial-popup .step-content').html($('#storyboard-step1').html());
	
	
	$('#tutorial-popup').fadeIn();
	
	handleTutorialSteps();
}

function handleTutorialSteps(){
	
	$('body').on("click", "#cta-tut-next", function(e) {
		
		if($(this).hasClass('disabled')){
			return;
		}
		
		resetTutorial();
		
		var prevStep = $('#tutorial-popup').attr('class');
		var thisStep = $(this).attr('class');
		var thisStepNum = parseInt(thisStep.replace('step',''));
		
		if(thisStepNum + 1 <= totalBoxes){
			var nextStep = "step" + (thisStepNum + 1);
		}else{
			var nextStep = "disabled";
		}
		
		
		var newContent = $('#storyboard-' + thisStep).html();
		$('#tutorial-popup .step-content').html(newContent);
			
		$("#tutorial-popup .step-content").stop().animate({
				width: 		boxes[thisStep].width,
				height:		boxes[thisStep].height
				
			},450
		
		);
		
		$("#tutorial-popup").stop().animate({
				top: 	boxes[thisStep].top,
				left: 	boxes[thisStep].left
				
			},450
		
		);
		
		
		$('#tutorial-popup').removeClass().addClass(thisStep);
		$('#cta-tut-prev').removeClass().addClass(prevStep);
		$('#cta-tut-next').removeClass().addClass(nextStep);
		
		//also add specifics elements or animations
		tutorialSpecialCases(thisStep,'prev');
		
	});
	
	$('body').on("click", "#cta-tut-prev", function(e) {
		
		if($(this).hasClass('disabled')){
			return;
		}
		
		
		resetTutorial();
		
		var nextStep = $('#tutorial-popup').attr('class');
		var thisStep = $(this).attr('class');
		var thisStepNum = parseInt(thisStep.replace('step',''));
		
		if(thisStepNum - 1 > 0){
			var prevStep = "step" + (thisStepNum - 1);
		}else{
			var prevStep = "disabled";
		}
		
		var newContent = $('#storyboard-' + thisStep).html();
		$('#tutorial-popup .step-content').html(newContent);
			
		$("#tutorial-popup .step-content").stop().animate({
				width: 		boxes[thisStep].width,
				height:		boxes[thisStep].height
				
			},450
		
		);
		
		$("#tutorial-popup").stop().animate({
				top: 	boxes[thisStep].top,
				left: 	boxes[thisStep].left
				
			},450
		
		);
			
		$('#tutorial-popup').removeClass().addClass(thisStep);
		$('#cta-tut-prev').removeClass().addClass(prevStep);
		$('#cta-tut-next').removeClass().addClass(nextStep);
		
		//also add specifics elements or animations
		tutorialSpecialCases(thisStep,'prev');
		
	});
	
	$('body').on("click", "#finish-tutorial", function(e) {
		
		
		if($('#almost-ready').hasClass('first-time')){
			
			if($(this).hasClass('ajax-running')){
				
				return false;
				
			}
			
			$(this).addClass('ajax-running');
			
			//check if first time, in that case update tutorial status
			
			$.ajax({
				type: "post",
				url: MyAjax.ajaxurl,
				dataType : "json",	
				data: { action: 	"update_tutorial_status" }
				
			})
			.done(function(response){ 
				
				window.location = "/classify/";
			 })
			.fail(function(jqXHR, textStatus, errorThrown){ 

				if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
		              return;  
				}else{
					alert('Sorry an error occured - Please try again later #004');
				}
			})
			
		}else{
			
			window.location = "/classify/";
			
		}
	});
}

function resetTutorial(){
	
	$(".highlightbox").remove();
	
}

function pulsateItem(selector){
	
	for(i=0;i<3;i++) {
		$(selector).fadeTo('fast', 0.1).fadeTo('fast', 1.0);
	}
}

function tutorialSpecialCases(step, direction){
	
	switch (step){
		
		case 'step2':

			//alert("here");
			$('.tool-image').append('<div class="highlightbox">&nbsp;</div>');

			$(".highlightbox").css("position", "absolute");
			$(".highlightbox").css("z-index", "1505");
			$(".highlightbox").css("left", "-413px");
			$(".highlightbox").css("top", "20px");
			$(".highlightbox").css("width", "403px");
			$(".highlightbox").css("height", "572px");
			$(".highlightbox").css("border", "3px solid #009cff");

			window.setTimeout(function() { pulsateItem(".highlightbox"); }, 450);

			break;
		
	}
}

function handleGetStarted(){
	
	$('body').on("click", "#get-started-login a", function(e) {
		
		$('.menu-login').trigger('click');
	});
	
	
}

function handleAlmostReady(){
	
	$('body').on("click", "#start-tutorial", function(e) {
		
		//start tutorial
		startTutorial();
	});
	
}





function initSameHeight(){
	
	$('#subpages-list').sameHeight({
		elements: '.subpage-tile p',
		flexible: true,
		multiLine: true
	});
	
	$('.article-content').sameHeight({
		elements: 'figure',
		flexible: true,
		multiLine: true
	});
	
	
}


function handleCustomInputs(){
	
	$('input:radio').screwDefaultButtons({ 
		image: "url(/wp-content/themes/galaxy-explorer/images/icn_radio.png)",
		width:	 17,
		height:	 17
	});
	
	$('input:checkbox').screwDefaultButtons({ 
		image: "url(/wp-content/themes/galaxy-explorer/images/icn_checkbox.png)",
		width:	 20,
		height:	 18
	});
	
	$('.customSelect').customSelect();
	
}



function handleToolSteps(){
	
	$('body').on("click", "#step-broken-image", function(e) {
		
		var confirmation = confirm("Are you sure this galaxy can\'t be classified?\n\nChoose this option if the image is weird or distorted\nor you\'re unable to make out a galaxy");
		
		if (confirmation == true) {
			
			$('#step-1-result').val(9);
			element = $(this);
			element.addClass('ajax-loading');
			element.parent().append('<div id="tool-image-saving">Saving your data...</div>');
			element.hide();
			
			$('#step-finish').trigger('click');
			
		} else {
		    return false;
		}
		
	});
	
	$('body').on("click", "#tool-restart", function(e) {
		
		$('.steps-container').addClass('restarting');
		
		$('#image-canvas').addClass('disabled');
		//reset values
		$('.steps-container .user-input-data').val('');
		$("#step-text-result").text('');
		
		//reset to first step
		/*$('.steps-container').fadeOut(400, function(){
			
			//clear any drawing from the canvas
			clearGalaxyToolDrawing();
			
			//remove ticks and selected class
			$('.step-wrapper li a').removeClass('ajax-running selected');
			$('.step-wrapper li p span.tick-selected').remove();
			
			$('.steps-container').css('left', 0);
			$('.step-wrapper').hide();
			$('#tool-first-step').show();
			
			$('.steps-container').fadeIn();
			
			$('.steps-container').removeClass('restarting');
			
		});*/
		
		//clear any drawing from the canvas
		clearGalaxyToolDrawing();
		
		//remove ticks and selected class
		$('.step-wrapper li a').removeClass('ajax-running selected');
		$('.step-wrapper li p span.tick-selected').remove();
		
		$('.step-wrapper').hide();
		$('#tool-first-step').show();
		$('#crosshair-right').show();
        $('#crosshair-bottom').show();
		
		$('.steps-container').removeClass('restarting');
		
	});
	
	
	$('body').on("click", "#tool-first-step li a", function(e) {
		
		if($('.steps-container').hasClass('restarting')){
			return;
		}
		
		//just add the ajax-runnig class to avoid double clicks (not running any ajax)
		if($('#tool-first-step a.ajax-running').length > 0){
			return;
		}
		
		$(this).addClass('ajax-running selected');
		
		$(this).find('p').append('<span class="tick-selected"></span>');
	
		//get the step value in the hidden field
		var value = $(this).find('.step1-value').val();
		
		$("#step-text-result").append('<li>- ' +$(this).find('p').text() + '</li>');
		
		//save this value in the global image data section
		$('#step-1-result').val(value);
		
		//now based on the value, call next step
		switch(value){
			case '1':
				$('#tool-second-step h2').text('Does the galaxy have any features?');
				toolNextStep('tool-second-step');
				break;
			case '2':
				$('#tool-second-step h2').text('Does the galaxy in the centre have any features?');
				toolNextStep('tool-second-step');
				break;
			case '3':
				toolNextStep('tool-fifth-step');
				break;
		}
		
	});
	
	$('body').on("click", "#tool-second-step li a", function(e) {
		
		if($('.steps-container').hasClass('restarting')){
			return;
		}
		
		//just add the ajax-runnig class to avoid double clicks (not running any ajax)
		if($('#tool-second-step a.ajax-running').length > 0){
			return;
		}
		
		$(this).addClass('ajax-running selected');
		
		$(this).find('p').append('<span class="tick-selected"></span>');
		
		//get the step value in the hidden field
		var value = $(this).find('.step2-value').val();
		
		$("#step-text-result").append('<li>- ' +$(this).find('p').text() + '</li>');
		
		//save this value in the global image data section
		$('#step-2-result').val(value);
		
		//now based on the value, call next step
		switch(value){
			case '1':
				toolNextStep('tool-third-step');
				break;
			case '2':
				toolNextStep('tool-fourth-step');
				break;
		}
		
	});
	
	$('body').on("click", "#tool-third-step li a", function(e) {
		
		if($('.steps-container').hasClass('restarting')){
			return;
		}
		
		//multiselect here
		if($(this).hasClass('selected')){
			
			$(this).removeClass('selected');
			$(this).find('.tick-selected').remove();
		}else{
			
			$(this).addClass('selected');
			$(this).find('p').append('<span class="tick-selected"></span>');
		}
		
		//this is a multiple select, so i need to handle the value when user click next
		
		
	});
	
	$('body').on("click", "#step-3-continue", function(e) {
		
		if($('.steps-container').hasClass('restarting')){
			return;
		}
		
		
		//need to get all the selected values, so i'm looping
		var values = new Array();
		var values_string = new Array();
		
		$( "#tool-third-step a.selected" ).each(function(index) {
			values.push($(this).find('.step3-value').val());
			
			values_string.push($(this).find('p').text());
			
		});
		
		var result = values.join();
		var result_string = values_string.join(', ');
		
		$('#step-3-result').val(result);
		$("#step-text-result").append('<li>- ' +result_string + '</li>');
		
		//if any selected, go to ellipse tool, otherwise go to step 4
		if(result){
			
			toolNextStep('tool-fifth-step');
		}else{
			toolNextStep('tool-fourth-step');
		}
		
		
		
	});
	
	
	$('body').on("click", "#tool-fourth-step li a", function(e) {
		
		if($('.steps-container').hasClass('restarting')){
			return;
		}
		
		//just add the ajax-runnig class to avoid double clicks (not running any ajax)
		if($('#tool-fourth-step a.ajax-running').length > 0){
			return;
		}
		
		$(this).addClass('ajax-running selected');
		
		$(this).find('p').append('<span class="tick-selected"></span>');
		
		//get the step value in the hidden field
		var value = $(this).find('.step4-value').val();
		
		$("#step-text-result").append('<li>- ' +$(this).find('p').text() + '</li>');
		
		//save this value in the global image data section
		$('#step-4-result').val(value);
		
		//get to fith step
		toolNextStep('tool-fifth-step');
		
	});
	
	$('body').on("click", "#step-finish, #step-finish-no-canvas, #step-finish-no-classification", function(e) {
		
		//ajax to add to favourite, no remove
		if ($(this).hasClass('ajax-loading')){
			
			return false;
		}
		
		var element = $(this);
		
		element.addClass('ajax-loading');
		element.parent().append('<div id="tool-image-saving">Saving your data...</div>');
		element.hide();
		
		$('#tool-restart').remove();
		
		
		var image_data = getItems();
		
		
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: {
				image_id	: $('#processing-image-id').val(),
				image_name	: $('#processing-image-name').val(),
				image_data	: image_data, 
				step_1		: $('#step-1-result').val(),
				step_2		: $('#step-2-result').val(),
				step_3		: $('#step-3-result').val(),
				step_4		: $('#step-4-result').val(),
				action		: "handle_page_finished" }
			
		})
		.done(function(response){ 
			
			if(response.error){
				
				alert(response.message);
			}else{
				
				if(response.popup != ''){
					
					var closeButton = false;
					var overlayClose = false;
					var escKey = false;
					
					//set colorbox properties based on id
					switch(response.popup_id){
						case 'guest-registration-reminder-lightbox':
							closeButton = false;
							overlayClose = false;
							escKey = false;
							break;
						case 'competition-lightbox':
							closeButton = false;
							overlayClose = false;
							escKey = false;
							break;
					}
					
					$.colorbox({
						html			: response.popup,
						closeButton		: closeButton,
						overlayClose	: overlayClose,
						className		: 'popup-style',
						escKey			: escKey,
						onComplete: function(){
							
							$('#terms').screwDefaultButtons({ 
								image: "url(/wp-content/themes/galaxy-explorer/images/icn_checkbox.png)",
								width:	 20,
								height:	 18
							});
							
							$.colorbox.resize();
							
							if(response.popup_id == 'competition-lightbox'){
								
								/*grecaptcha.render('captcha-container', {
							        'sitekey' : '6LfAgwgTAAAAANv3G-NiXo7ct9_owxpeqxKwkK8h'
							    });*/
								
								
								handleCustomInputs();
								
							}
							
						}
					});
					
					
					
				}else{
					
					$('#tool-image-saving').text('Saved!');
					window.location = "/classify/";
				}
				
			}
			
						
			
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #005');
			}
		})
		.always(function(){ 
			element.removeClass('ajax-loading');
		});
	});
}

function showCompetitionPopupOnStart(){
	
	$.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		dataType : "json",	
		data: {
			action	: "handle_show_competition_popup_on_start" }
		
	})
	.done(function(response){ 
		
		if(response.error){
			
			alert(response.message);
		}else{
			
			if(response.popup != ''){
				
				$.colorbox({
					html			: response.popup,
					closeButton		: false,
					overlayClose	: false,
					escKey 			: false,
					className		: 'popup-style',
					onComplete: function(){
						
						if(response.type == 'individual'){
						
							/*$('#terms').screwDefaultButtons({ 
								image: "url(/wp-content/themes/galaxy-explorer/images/icn_checkbox.png)",
								width:	 20,
								height:	 18
							});*/
							
							handleCustomInputs();
							
							/*grecaptcha.render('captcha-container', {
						        'sitekey' : '6LfAgwgTAAAAANv3G-NiXo7ct9_owxpeqxKwkK8h'
						    });*/
						}
						
						$.colorbox.resize();
					}
				});
			}
		}
		
		
		
		
	 })
	.fail(function(jqXHR, textStatus, errorThrown){ 
		if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
            return;  
		}else{
			alert('Sorry an error occured - Please try again later #006');
		}
	})
	.always(function(){ 
		
	});
	
	
}

function handleCompetitionPopup(){
	
	
	/*$('body').on("click", "#captcha-reload", function(e) {
		
		
		grecaptcha.reset();
		
	});*/
	
	
	$('body').on("click", "#competition-enter-continue", function(e) {
		
		if($('#competition-lightbox').hasClass('on-start')){
			
			
			$.colorbox.close();
			$("html,body").animate({scrollTop: 0}, 0);
			
			var counter = parseInt($('#competition-counter').text());
			counter = counter + 1;
			$('#competition-counter').text(counter);
			
		}else{
			
			window.location = "/classify/";
		}
		
	});
	
	$('body').on("click", "#competition-school-continue", function(e) {
		
		if($('#competition-school-lightbox').hasClass('on-start')){
			
			
			$.colorbox.close();
			
			$("html,body").animate({scrollTop: 0}, 0);
			
			
			var counter = parseInt($('#competition-counter').text());
			counter = counter + 1;
			$('#competition-counter').text(counter);
			
			
			
		}else{
			
			window.location = "/classify/";
		}
		
	});
	
	
	
	$('body').on("click", "#competition-enter-submit", function(e) {
		
		if($(this).hasClass('ajax-running')){
			
			return;
			
		}
		
		$('#competition-lightbox .form-error').remove();
		
		$.colorbox.resize();
		
		$(this).addClass('ajax-running');
		
		
		if ($('#captcha-checkbox').is(':checked')) {
			var captcha_checkbox = 1;
		} else {
			var captcha_checkbox = 0;
		} 
		
		//all good, ajax call
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: {
				action				: "handle_submit_competition_entry",
				/*captcha			: grecaptcha.getResponse(),*/
				message				: $.trim($('#competition-feedback').val()),
				captcha_checkbox	: captcha_checkbox,
				email				: $('#email').val(),
			}
			
		})
		.done(function(response){ 
			
			
			if(response.error){
				
				
				switch(response.field){
						
					case 'captcha-reload':
						$('#competition-lightbox .row.captcha').prepend('<div class="form-error">' + response.message + '</div>');
						//grecaptcha.reset();
						break;
					case 'captcha':
						$('#competition-lightbox .row.captcha').prepend('<div class="form-error">' + response.message + '</div>');
						break;
					case 'captcha_checkbox':
						$('#competition-lightbox .row-checkbox').append('<div class="form-error">' + response.message + '</div>');
						break;
				
					default:
						alert(response.message);
						//grecaptcha.reset();
				}
				
				

			}else{
				
				
				//$('#captcha-container').appendTo("#galaxy-tool").hide();
				
				$('#competition-lightbox form').remove();
				$('#competition-lightbox p').remove();
				$( "#competition-lightbox h2" ).after( "<p>Your competition entry has been accepted.</p><p>You now have a total of " + response.total_entries + " entries. Good luck!</p>" );
				$( "#competition-lightbox .buttons-section" ).html( '<a href="javascript:void(0)" class="cta-tut-next" id="competition-enter-continue"><span>Continue</span></a>' );
				
			}
			$.colorbox.resize();
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #007');
				//grecaptcha.reset();
			}
			
		})
		.always(function(){ 
			$('#competition-enter-submit').removeClass('ajax-running');
		});
		
	});
	
	
}



function toolNextStep(step_id){
	
	//special case for no canvas support
	if(step_id == 'tool-fifth-step'  && $('html.canvas').length == 0){
		
		
		step_id = 'tool-no-canvas-step';
		$('#classification-summary').html('<ul>'+$("#step-text-result").html()+'</ul>');
	}
	
	$('.step-wrapper').hide();
	$('#' + step_id).show();
	
	/*$('.steps-container').delay(500).animate({"left": '-=400'}, 400, function() {
	
		// Animation complete, start galaxy drawing
		if(step_id == 'tool-fifth-step'){
			
			$('#image-canvas').removeClass('disabled');
			startGalaxyToolDrawing();
		}
	
	});*/
	
	// Animation complete, start galaxy drawing
	if(step_id == 'tool-fifth-step'){
		
		$('#image-canvas').removeClass('disabled');
		startGalaxyToolDrawing();
		$('#crosshair-right').hide();
        $('#crosshair-bottom').hide();
	}
	
}

function startGalaxyToolDrawing(){
	
	
	//defailt values ??
	/*
	 *  x		: 400, 
		y		: 400, 
		radA	: 275, 
		radB	: 159, 
		rot		: -0.65, 
    		
	 */
	
	var x = parseFloat($('#ellipse_data_x').val());
	var y = parseFloat($('#ellipse_data_y').val());
	var radA = parseFloat($('#ellipse_data_radA').val());
	var radB = parseFloat($('#ellipse_data_radB').val());
	var rot = parseFloat($('#ellipse_data_rot').val());
	
	//x = 400;
	//y= 400;
	//radA= 275; 
	//radB= 159;
	//rot	= -0.65;
	
	//console.log(x);
	//console.log(y);
	//console.log(radA);
	//console.log(radB);
	//console.log(rot);
	
	
	// input ellipses/points
    inputItems({
    	ellipses:[{
    		x		: x, 
    		y		: y, 
    		radA	: radA, 
    		radB	: radB, 
    		rot		: rot, 
    		isMain	: true}
    	],
    	points:[]
    })
    
}

function clearGalaxyToolDrawing(){
	
	clearAllDrawing();
}


function handleFavouriteGallery(){
	
	$('body').on("mouseenter", ".favourite-tile", function(e) {
		
		$(this).find('.favourite-tile-delete').show();
		
	});
	
	$('body').on("mouseleave", ".favourite-tile", function(e) {
		
		$(this).find('.favourite-tile-delete').hide();
		
	});
	
	$('body').on("mouseenter", ".lightbox-image", function(e) {
		
		$(this).find('#favourite-lightbox-delete').show();
		
	});
	
	$('body').on("mouseleave", ".lightbox-image", function(e) {
		
		$(this).find('#favourite-lightbox-delete').hide();
		
	});
	
	$('body').on("click", "#share-facebook", function(e) {
		
		
		
		e.preventDefault();
		
		var picture = $('#fav-data-image').attr('src');
		var caption = 'Image ' + $('#fav-data-image-name').text() + ', ' + $('#fav-data-distance').text();
		
		
		FB.ui({
			method : "feed",
			name : "ABC Galaxy Explorer",
			link : $('#fav-data-baseurl').text(),
			picture : picture,
			caption : caption,
			description : "Be a citizen scientist with the ABC Galaxy Explorer. Classify galaxy images and help Australian researchers understand how galaxies evolve."
		});
		
	});
	
	$('body').on("click", "#share-twitter", function(e) {
		
		
		var url  = $('#fav-data-baseurl').text();
		var text = "Be a citizen scientist with the ABC Galaxy Explorer. Classify galaxy images and help Australian researchers understand how galaxies evolve.";
		var link = "https://twitter.com/share?url=" + encodeURIComponent(url) + "&text=" + encodeURIComponent(text);
		
		window.open(link, "twitter_popup", "width=626,height=436");
		
	});
	
	
	$('body').on("click", ".favourite-tile-delete", function(e) {
		
		var confirmation = confirm("Are you sure message?");
		
		if (confirmation == true) {
			
			var element = $(this);

			element.addClass('ajax-running');
			
			//all good, ajax call
			$.ajax({
				type: "post",
				url: MyAjax.ajaxurl,
				dataType : "json",	
				data: {
					favourite_id	: element.parent().find('.source-favourite-id').text(),
					action			: "handle_remove_favourite"
				}
				
			})
			.done(function(response){ 
			
				if(response.error){
					
					alert(response.message);
				}else{
					
					element.parent().fadeOut("slow",function(){
						$(this).remove()
					});
				}
				
			 })
			.fail(function(jqXHR, textStatus, errorThrown){ 
				if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
		              return;  
				}else{
					alert('Sorry an error occured - Please try again later #008');
				}
			})
			.always(function(){ 
				element.removeClass('ajax-running');
			});
			
		} else {
		    return false;
		}
		
	});
	
	$('body').on("click", "#favourite-lightbox-delete", function(e) {
		
		var confirmation = confirm("Are you sure?");
		
		if (confirmation == true) {
			
			
			var element = $(this);
			var tile = $('#' + $('#fav-data-tile-id').text());
			
			element.addClass('ajax-running');
			
			//all good, ajax call
			$.ajax({
				type: "post",
				url: MyAjax.ajaxurl,
				dataType : "json",	
				data: {
					favourite_id	: element.parents('#favourite-lightbox').find('#fav-data-favourite-id').text(),
					action			: "handle_remove_favourite"
				}
				
			})
			.done(function(response){ 
			
				if(response.error){
					
					alert(response.message);
				}else{
					
					$.colorbox.close();
					
					//also remove the tile in the grid
					tile.remove();
					
				}
				
			 })
			.fail(function(jqXHR, textStatus, errorThrown){ 
				if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
		              return;  
				}else{
					alert('Sorry an error occured - Please try again later #009');
				}
			})
			.always(function(){ 
				element.removeClass('ajax-running');
			});
			
		} else {
		    return false;
		}
		
	});
	
	$('body').on("click", ".favourite-tile a.image-wrapper", function(e) {
		
		//get the lightbox template
		var template = $('#favourite-lightbox-wrapper').html();
		//delete the template from the page to avoid IDs double ups
		$('#favourite-lightbox-wrapper').html('');
		
		var tile = $(this).parent();

		$.colorbox({
			html			: template,
			closeButton		: true,
			onComplete: function(){
				
				$('#fav-data-tile-id').text(tile.attr('id'));
				$('#fav-data-favourite-id').text(tile.find('.source-favourite-id').text());
				$('#fav-data-image').attr('src', tile.find('.source-data-image').attr('src') );
				$('#fav-data-date-analized').text(tile.find('.source-data-date-analized').text());
				
				if(tile.find('.source-data-classification').length > 0){
				
					$('.lightbox-classification').show();
					$('#fav-data-classification').text(tile.find('.source-data-classification').text());
					
				}else{
					$('.lightbox-classification').hide();
				}
				
				$('#fav-data-image-name').text(tile.find('.source-data-image-name').text());
				$('#fav-data-distance').text(tile.find('.source-data-data-distance').text());
				
				
				$.colorbox.resize();
			},
			onCleanup: function(){
				//reappend the template where it belongs
				
				$('#fav-data-tile-id').text('');
				$('#fav-data-favourite-id').text('');
				$('#fav-data-image').attr('src', '');
				$('#fav-data-date-analized').text('');
				$('#fav-data-classification').text('').show();
				$('#fav-data-image-name').text('');
				$('#fav-data-distance').text('');
				
				var initial_template = $('#favourite-lightbox').parent().html();
				$('#favourite-lightbox').remove();
				
				$('#favourite-lightbox-wrapper').html(initial_template);
				
			}
		});
	});
	
	
	//handle prev button
	$('body').on("click", ".favourite-gallery-next", function(e) {
		
		//.fadeOut();
		
		$('#favourite-lightbox').fadeOut( 400, function() {
			
			//get the current tile id displayed
			var tile_id = $('#fav-data-tile-id').text();
			
			//get the tile element
			var current_tile = $('#' + tile_id);
			
			//get next tile element
			var new_tile = current_tile.next();
			
			//if not found(last element) , get the first
			if(!new_tile.length){
				new_tile = $('.favourite-tile').eq(0);
			}
			
			//replace values
			$('#fav-data-tile-id').text(new_tile.attr('id'));
			$('#fav-data-favourite-id').text(new_tile.find('.source-favourite-id').text());
			$('#fav-data-image').attr('src', new_tile.find('.source-data-image').attr('src') );
			$('#fav-data-date-analized').text(new_tile.find('.source-data-date-analized').text());
			
			if(new_tile.find('.source-data-classification').length > 0){
				
				$('.lightbox-classification').show();
				$('#fav-data-classification').text(new_tile.find('.source-data-classification').text());
				
			}else{
				$('.lightbox-classification').hide();
			}


			$('#fav-data-image-name').text(new_tile.find('.source-data-image-name').text());
			$('#fav-data-date-taken').text(new_tile.find('.source-data-date-taken').text());
			$('#fav-data-distance').text(new_tile.find('.source-data-data-distance').text());
			$('#fav-data-age').text(new_tile.find('.source-data-data-age').text());
			
			$('#favourite-lightbox').fadeIn();
			
			$('#favourite-lightbox').fadeIn(400, function() {$.colorbox.resize();});
			
		});
	});
	
	//handle prev button
	$('body').on("click", ".favourite-gallery-prev", function(e) {
		
		//.fadeOut();
		
		$('#favourite-lightbox').fadeOut( 400, function() {
			
			//get the current tile id displayed
			var tile_id = $('#fav-data-tile-id').text();
			
			//get the tile element
			var current_tile = $('#' + tile_id);
			
			//get next tile element
			var new_tile = current_tile.prev();
			
			//if not found(last element) , get the first
			if(!new_tile.length){
				new_tile = $('.favourite-tile').eq($('.favourite-tile').length -1);
			}
			
			//replace values
			$('#fav-data-tile-id').text(new_tile.attr('id'));
			$('#fav-data-favourite-id').text(new_tile.find('.source-favourite-id').text());
			$('#fav-data-image').attr('src', new_tile.find('.source-data-image').attr('src') );
			$('#fav-data-date-analized').text(new_tile.find('.source-data-date-analized').text());
			
			if(new_tile.find('.source-data-classification').length > 0){
				
				$('.lightbox-classification').show();
				$('#fav-data-classification').text(new_tile.find('.source-data-classification').text());
				
			}else{
				$('.lightbox-classification').hide();
			}
			
			$('#fav-data-image-name').text(new_tile.find('.source-data-image-name').text());
			$('#fav-data-date-taken').text(new_tile.find('.source-data-date-taken').text());
			$('#fav-data-distance').text(new_tile.find('.source-data-data-distance').text());
			$('#fav-data-age').text(new_tile.find('.source-data-data-age').text());
			
			$('#favourite-lightbox').fadeIn(400, function() {$.colorbox.resize();});
			
			
		});
	});
	
}

function handleFavourite(){
	
	$('body').on("click", "#add-favourites", function(e) {
		
		//ajax to add to favourite, no remove
		if ($(this).hasClass('ajax-loading')){
			
			return false;
			
		}
		
		var element = $(this);
		
		element.addClass('ajax-loading').hide();
		element.parent().append('<div class="loading-small"></div>');
		
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: { image_id: $('#processing-image-id').val(), 
					action: "handle_add_favourite" }
			
		})
		.done(function(response){ 
			
			element.addClass("favourite-added");
			
			//update frontend counter
			var total = $('#total-favourites').text();
			
			$('#total-favourites').text(parseInt(total) + 1);
				
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 
			
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #010');
			}
		})
		.always(function(){ 
			element.parent().find('.loading-small').remove();
			element.show();
		});
		
		
		
		
	});
	
}

function handleDifferentPlatforms(){
	if (navigator.platform.indexOf("Mac") != -1 && navigator.userAgent.indexOf(") Gecko") != -1) {
	    
		//you can add specific classes for Mac firefox
		$('a.cta-sidebar, a.cta-carousel, a.cta-primary').addClass('mac-firefox');
	}
}


function resetLabels(){
	
	$( ".addLabel" ).each(function() {
		
		var element = $(this);
		var elementName = element.attr('name');
		
		if(!elementName){
			
			element = element.parent().find('select');
			elementName = element.parent().find('select').attr('name');
		}
		
		//console.log(elementName);
		
		if($.trim(element.val()) == '' || $.trim(element.val().toLowerCase()) ==  defaultLabels[elementName].toLowerCase()) {
	        
			element.addClass('isLabel');
			
			if(!element.hasClass('customSelect')){
				
				element.val(defaultLabels[elementName]);
			}
			
			//also, if it is a password field, change it to text
			if(element.hasClass('passwordReplace')){
				
				element.attr('type','text');
			}
			
        }else{
        	element.removeClass('isLabel');
        	
        	
        	if(element.hasClass('customSelect') ){
    			
        		element.parent().find('.customSelect').removeClass('isLabel');
    			
    		}
        	
        	
        	
        }
		
	
	});
}


function handleBlurFocus(){
	
	//check if text in input is a label or or user entry
	resetLabels();
	
	
	
	//focus
	$('body').on("focus", ".addLabel", function(e) {
		
		var elementName = $(this).attr('name');

		$(this).addClass('current-focus');
		
		if($.trim($(this).val().toLowerCase()) == defaultLabels[elementName].toLowerCase()) {
        
			this.value = '';
        }
		
		if($(this).hasClass('passwordReplace') ){
			
			$(this).attr('type','password');
			
		}
		
		if($(this).hasClass('customSelect') ){
			
			$(this).parent().find('.customSelect').removeClass('isLabel');
			
		}
		
		$(this).removeClass('isLabel');
		
		
	});
	
	
	//blur
	$('body').on("blur", ".addLabel", function(e) {
		
		var elementName = $(this).attr('name');
		
		$(this).removeClass('current-focus');
		
		if($.trim($(this).val()) == '' || $.trim($(this).val().toLowerCase()) ==  defaultLabels[elementName].toLowerCase()) {
        
			this.value = defaultLabels[elementName];
			
			$(this).addClass('isLabel');
			
			
			if($(this).hasClass('customSelect') ){
				
				$(this).parent().find('.customSelect').addClass('isLabel');
				
				this.value = '';
				
			}
			
			
        }
		
		/*if($(this).hasClass('customSelect') && $.trim($(this).val())== '' ){
			
			$(this).parent().find('.customSelect').addClass('isLabel');
			
			
		}*/
		
		
		//handle password fields
		if($(this).hasClass('passwordReplace') && ($.trim($(this).val().toLowerCase()) ==  defaultLabels[elementName].toLowerCase())){
			
			$(this).attr('type','text');
			
		}
		
		
	});
}

function sanitizeFormInputs(form){
	
	
	$(form).find('input[type="text"],input[type="password"],input[type="email"],input[type="number"]').each(function( index ) {
		
		name = $( this ).attr('name');
		value = $( this ).val();
		
		if(typeof defaultLabels[name] != 'undefined' && $.trim($(this).val().toLowerCase()) == defaultLabels[name].toLowerCase()) {
	        
			this.value = '';
        }
	});
	
}

function handleResetProcess(){
	
	
	$('body').on('click', "#reset_password_submit", function() {
		
		if($(this).hasClass('ajax-running')){
			return false;
		}
		
		$('#error-message').html('');
		$('.general-form .row').removeClass('row-error');
		$('.general-form .form-error').remove();
		$('#form-reset-password .loading').show();
		$("#reset_password_submit").addClass('ajax-running');
		
		sanitizeFormInputs($('#form-reset-password'));
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: $("#form-reset-password").serialize()
			
		})
		.done(function(response){ 
			
			
			if(response.error){
				if(response.message){
					
					if(response.field){
						
						$('#' + response.field).parents('.row').addClass('row-error').append('<span class="form-error">' + response.message + '</span>');
					}else{
						$('#error-message').append('<span class="important">' + response.message + '</span>');
						
					}
					
				}else{
					
					$('#error-message').append('<span class="important">Sorry an error occured - Please try again later #011</span>');
				
				}
				
				
			}else{
				
				//all good, redirect to start page
				window.location = "/classify/";
			}
				
				
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #012');
			}
		})
		.always(function(){ 
			$('#form-reset-password .loading').hide();
			$("#reset_password_submit").removeClass('ajax-running');
			
			resetLabels();
			
		});
		
		return false;
		
	});
	
	
}




function handleLoginPanel(){
	
	$('body').on('click', ".menu-login", function() {
		
		$('.menu-bar .dropdown').show();
		
		if($(this).hasClass('open')){
			
			$('.menu-bar .dropdown').hide();
			
			$(this).removeClass('open');
		}else{
		
			$('.menu-bar .dropdown').show();
			
			$(this).addClass('open');
		}
		
	});
	
	
}



function handleForgotProcess(){
	
	$('body').on('click', "#forgot_submit", function() {
		
		if($(this).hasClass('ajax-running')){
			return false;
		}
		
		$('#form-forgot .form-error').html('');
		$("#forgot_submit").addClass('ajax-running');
		$("#forgot_submit").parent().append('<div class="loading-login"></div>');
		
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: $("#form-forgot").serialize()
			
		})
		.done(function(response){ 
			
			if(response.error){
				if(response.message){
					
					$('#form-forgot .form-error').append('<p>' + response.message + '</p>');
				}else{
					
					$('#form-forgot .form-error').append('<p>Sorry an error occured - Please try again later #013</p>');
				
				}
			}else{
				$('#form-forgot .form-error').append('<p>Please check your email for further instructions. Thanks.</p>');
				$('#forgot_email').val('');
				
				$('#forgot_submit').hide();
				$('#form-forgot .row').hide();
			}
				
				
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #014');
			} 
		})
		.always(function(){ 
			$('#form-forgot .loading-login').remove();
			$("#forgot_submit").removeClass('ajax-running');
		});
		
		return false;
		
	});
	
}



function handleLoginProcess(){
	
	$('body').on('click', "#login_submit", function() {
		
		if($(this).hasClass('ajax-running')){
			return false;
		}
		
		$('#form-login .form-error').html('');
		$("#login_submit").addClass('ajax-running');
		$("#login_submit").parent().append('<div class="loading-login"></div>');
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: $("#form-login").serialize()
			
		})
		.done(function(response){ 
			
			if(response.error){
				if(response.message){
					
					$('#form-login .form-error').append('<p>' + response.message + '</p>');
				}else{
					
					$('#form-login .form-error').append('<p>Sorry an error occured - Please try again later #015</p>');
				
				}
			}else{
				$('#login_email').val('');
				$('#login_password').val('');
				
				if(response.user == 'admin'){
					
					window.location = "/wp-admin/";
				}else{
					window.location = "/classify/";
				}
			}
				
				
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 
			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #016');
			} 
		})
		.always(function(){ 
			$('#form-login .loading-login').remove();
			$("#login_submit").removeClass('ajax-running');
		});
		
		return false;
		
	});
	
}



function handleLoginForgot(){
	
	$('body').on('click', "#form-login a.switch-action", function() {
		
		$('#form-login').hide();
		$('#form-forgot').show();
		
		$('#form-login .form-error').html('');
		$('#form-login .loading').hide();
		
		$('#form-forgot .form-error').html('');
		$('#form-forgot .loading').hide();
		
		$('#login_email').val('Email');
		$('#login_password').val('Password');
		
		$('#forgot_submit').show();
		$('#form-forgot .row').show();
				
	});
	
	$('body').on('click', "#form-forgot a.switch-action", function() {
		
		$('#form-login').show();
		$('#form-forgot').hide();
		
		$('#form-login .form-error').html('');
		$('#form-login .loading').hide();
		
		$('#form-forgot .form-error').html('');
		$('#form-forgot .loading').hide();
		
		$('#forgot_email').val('Email');
				
	});
	
}




function validateRegistrationForm(){
	
	
	sanitizeFormInputs($('#form-registration'));
	
	//save fields;
	var registrationType = $('#form-registration :radio[name="option"]').filter(':checked').val();
	var firstname = $.trim($('#firstname').val());
	var lastname = $.trim($('#lastname').val());
	var country = $('#country').val();
	var country_states = $('#country_states').val();
	var state = $('#state').val();
	//var state_text = $.trim($('#state_text').val());
	var suburb = $.trim($('#suburb').val());
	var schoolGroupName = $.trim($('#schoolGroupName').val());
	var schoolType = $('#schoolType').val();
	var schoolGroupNumber = $('#schoolGroupNumber').val();
	var gender = $.trim($('#gender').val());
	var age = $('#age').val();
	var education = $('#education').val();
	var ademail = $.trim($('#ademail').val());
	var phone = $.trim($('#phone').val());
	var pass = $.trim($('#pass').val());
	var repassword = $.trim($('#repassword').val());
	
	if ($('#parental').is(':checked')) {
		var parental = 1;
	} else {
		var parental = 0;
	} 
	
	if ($('#research').is(':checked')) {
		var research = 1;
	} else {
		var research = 0;
	} 
	
	if ($('#terms').is(':checked')) {
		var terms = 1;
	} else {
		var terms = 0;
	} 
	
	
	$('.general-form .row').removeClass('row-error');
	$('.general-form .form-error').remove();
	
	//firstname
	if(!firstname){
		
		$('#firstname').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your first name</span>');
	}
	
	//lastname
	if(!lastname){
		
		$('#lastname').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your last name</span>');
		
	}
	
	//country
	if(country == '' || country == 'divider' ){
		
		$('#country').parents('.row').addClass('row-error').append('<span class="form-error">Please select your country</span>');
		
	}
	
	//check the state(based on country_states: if 0, is a text, if 1, is from the dropdown )
	if(country_states == '1' && country == 'Australia'){
		
		if(state == ''){
			
			$('#state').parents('.row').addClass('row-error').append('<span class="form-error">Please select your state</span>');
			
		}
		
		
	}else{
		
		/*if(!state_text){
					
			$('#state_text').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your state</span>');
		}*/
	}
	
	//suburb
	if(country == 'Australia' && !suburb){
		
		$('#suburb').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your suburb</span>');
		
	}
	
	//check schoolGroupName (only for group registration) 
	if(registrationType == 'group'){
		
		if(!schoolGroupName){
			$('#schoolGroupName').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your school group name</span>');
		}
		
	}
	
	//check schoolType (only for group registration) 
	/*if(registrationType == 'group'){
		
		if(schoolType == ''){
			$('#schoolType').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your school type</span>');
		}
		
	}*/
	
	//check schoolType (only for group registration) 
	if(registrationType == 'group'){
		
		if(!schoolGroupNumber){
			$('#schoolGroupNumber').parents('.row').addClass('row-error').append('<span class="form-error">Please enter a number for the school group</span>');
		}else if(!isPositiveInteger(schoolGroupNumber)){
			$('#schoolGroupNumber').parents('.row').addClass('row-error').append('<span class="form-error">Please enter a valid number for the school group</span>');
		}
		
	}
	
	//check gender (only for individual registration) 
	/*if(registrationType == 'individual'){
		
		if(gender != 'Male' && gender != 'Female'){
			$('#gender').parents('.row').addClass('row-error').append('<span class="form-error">Please select your gender</span>');
		}
		
	}*/
	
	//check age (only for individual registration) 
	/*if(registrationType == 'individual'){
		
		if(age == ''){
			$('#age').parents('.row').addClass('row-error').append('<span class="form-error">Please select your age</span>');
		}
		
	}*/
	
	//check education level (only for individual registration) 
	/*if(registrationType == 'individual'){
		
		if(education == ''){
			$('#education').parents('.row').addClass('row-error').append('<span class="form-error">Please select your education level</span>');
		}
		
	}*/
	
	//email
	if(!ademail){
		
		$('#ademail').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your email</span>');
		
	}else{
		
		if(!validEmail(ademail)){
			$('#ademail').parents('.row').addClass('row-error').append('<span class="form-error">Please enter a valid email</span>');
			
		}
		
	}
	
	//phone
	if(country == 'Australia' && !phone){
		
		$('#phone').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your contact phone number</span>');
		
	}
	
	//password
	if(!pass){
		
		$('#pass').parents('.row').addClass('row-error').append('<span class="form-error">Please enter your password</span>');
	}else{
		
		var passwordCheckResponse = checkPassword(pass,repassword);
		
		if(passwordCheckResponse != '')
		
			$('#pass').parents('.row').addClass('row-error').append('<span class="form-error">' + passwordCheckResponse + '</span>');
		
	}
	
	//parental check
	
	if((registrationType == 'individual' && age == "Under 18") ){
		if(parental == '0'){
			
			$('#parental').parents('.row').addClass('row-error').append('<span class="form-error">Please check that you have parental permission</span>');
		}
	}
	
	//research check
	if(research == '0'){
		
		$('#research').parents('.row').addClass('row-error').append('<span class="form-error">Please check that you agree to the \'Galaxy Explorer\' scientific contribution</span>');
	}
	
	//terms check
	if(terms == '0'){
		
		$('#terms').parents('.row').addClass('row-error').append('<span class="form-error">Please check that you agree to the \'Galaxy Explorer\' competition terms and conditions</span>');
	}
	
	//display error message if valorized
	if($('.general-form .form-error'). length > 0){
		
		$("html,body").animate({scrollTop: $('.general-form .row-error').eq(0).offset().top - 10}, 300);
		
		resetLabels();
		
		return false;
	}
	
	
	return true;


}



function handleRegistrationSubmission(){
	
	
	$('body').on('click', "#registration_submit", function() {
		
		if($(this).hasClass('ajax-running')){
			return false;
		}
		
		
		$("#registration_submit").addClass('ajax-running');
		
		if(validateRegistrationForm()){
			$("#registration_submit").removeClass('ajax-running');
			
			$('#form-registration').submit();
			
			return true;
		}
		
		$("#registration_submit").removeClass('ajax-running');
		return false;
		
	});
	
	
}



function handleParentalOnAgeChange(){
	
	
	$('body').on('change', "#form-registration #age", function() {
		
		handleParental();
		
	});
}




function handleCountriesOnChange(){
	
	
	$('body').on('change', "#form-registration #country", function() {
	
		//remove states list
		$("#state option.state").remove();
		
		handleCountries();
		
	});
}


function handleCountries(){
	
	//get the country id
	var countryId = $('#country :selected').attr('id');
	
	$('#state').val('').trigger('change');
	$('#state').parent().find('.customSelect').addClass('isLabel');
	
	//if the country has a states list, get them through AJAX call - changed to aus only
	if($('#country :selected').val() == 'Australia'){
		
		$.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			dataType : "json",	
			data: { countryId: countryId, action: "handle_states" }
			
		})
		.done(function(response){ 
			
			if(response.error){
				
				//something went wrong, show text field
				$('#state').parents('.row').hide();
				$('#suburb').parents('.row').hide();
				$('#phone').parents('.row').hide();
				$('#country').parents('.row').find('.side-notes').show();
				//$('#state_text').parents('.row').show();
				$('#country_states').val('0');
				//$('#state_text').val('State/Region').addClass('isLabel');
				
				
			}else{
				
				var preSelected;
				$.each(response.message,function(index, value){ 
				
				
					if($.trim($('#country_states_post').val()) == $.trim(value.state_name)){
						preSelected = ' selected="selected"';
					}else{
						preSelected = '';
					}
						
				
					
					$("#state").append('<option value="' + value.state_name + '" class="state"' + preSelected + '>' + $.trim(value.state_name) + '</option>');
					
					$('#state').parents('.row').show();
					//$('#state_text').parents('.row').hide();
					$('#country_states').val('1');
					//$('#state_text').val('State/Region').addClass('isLabel');
					$('#suburb').parents('.row').show();
					$('#phone').parents('.row').show();
					$('#country').parents('.row').find('.side-notes').hide();
					
				});
				
				
				//check if there is a preselected value, in case remove label class
				if($("#state").val() != ''){
				
					$("#state").trigger('change');
					$("#state").parent().find('.customSelect').removeClass('isLabel');
					
				}
				
			}
			
		 })
		.fail(function(jqXHR, textStatus, errorThrown){ 

			if(jqXHR.readyState == 0 || jqXHR.status == 0){ 
	              return;  
			}else{
				alert('Sorry an error occured - Please try again later #017');
			}
			
			$('#state').parents('.row').hide();
			//$('#state_text').parents('.row').show();
			$('#suburb').parents('.row').hide();
			$('#phone').parents('.row').hide();
			$('#country_states').val('0');
			$('#country').parents('.row').find('.side-notes').show();
			//$('#state_text').val('State').addClass('isLabel');
			
		})
		.always(function(){ 
			
		});
		
		
		
	}else{
		
		$('#state').parents('.row').hide();
		//$('#state_text').parents('.row').show();
		$('#country_states').val('0');
		$('#suburb').parents('.row').hide();
		$('#phone').parents('.row').hide();
		$('#country').parents('.row').find('.side-notes').show();
		//$('#state_text').val('');
		
	}
	
	return;
	
	
		
}



function handleRegistrationFieldsOnChange(){
	
	
	$('body').on('change', "#form-registration :radio[name='option']", function() {
		
		handleRegistrationFields();
		
	});
}


function handleRegistrationFields(){
	
	//get selected value
	var registrationType = $('#form-registration :radio[name="option"]').filter(':checked').val();
	
	if(registrationType == 'individual'){
		
		$('label[for="firstname"]').html('First name');
		$('label[for="lastname"]').html('Last name');
		$('label[for="ademail"]').html('Email');
		$('#ademail').parents('.row').find("span.change").html('You');
		$('#schoolGroupName').parents('.row').hide();
		$('#schoolType').parents('.row').hide();
		$('#schoolGroupNumber').parents('.row').hide();

		$('.teacherNote').hide();
		$('#terms_individual').show();
		$('#terms_teacher').hide();

		$('#gender').parents('.row').show();
		$('#age').parents('.row').show();
		$('#education').parents('.row').show();
		$('#parental').parents('.row').find('.label_individual').show();
		$('#research').parents('.row').find('.label_individual').show();
		$('#parental').parents('.row').find('.label_group').hide();
		$('#research').parents('.row').find('.label_group').hide();
		
		$('#terms-link').attr('href', '/terms-and-conditions/#section-individual');
		

		handleParental();
		
		
	}else if(registrationType == 'group'){
		
		$('label[for="firstname"]').html('Teacher\'s first name');
		$('label[for="lastname"]').html('Teacher\'s last name');
		$('label[for="ademail"]').html('Contact email address');
		$('#ademail').parents('.row').find("span.change").html('You and your students');
		$('#schoolGroupName').parents('.row').show();
		$('#schoolType').parents('.row').show();
		$('#schoolGroupNumber').parents('.row').show();

		$('.teacherNote').show();
		$('#terms_individual').hide();
		$('#terms_teacher').show();

		$('#gender').parents('.row').hide();
		$('#age').parents('.row').hide();
		$('#education').parents('.row').hide();
		$('#parental').parents('.row').show();
		
		$('#parental').parents('.row').find('.label_individual').hide();
		$('#research').parents('.row').find('.label_individual').hide();
		$('#parental').parents('.row').find('.label_group').hide();
		$('#parental').parents('.row').hide();
		$('#research').parents('.row').find('.label_group').show();
		
		$('#terms-link').attr('href', '/terms-and-conditions/#section-group');
		
		
	}
}


function handleParental(){
	
	if($('#age').val() == 'Under 18' ){
		$('#parental').parents('.row').show();
	}else{
		$('#parental').parents('.row').hide();
	}
}

function todo(){
	
	alert("Functionality not yet implemented.");
	
}



function validEmail(email){
	
	var filter  = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
	if (filter.test(email)){
		return true;
	}else{
		return false;
	}
}


function validPhone(phone){
	
	//accepted chars
	phone= phone.replace(/ /g, "");
	phone= phone.replace(/\-/g, "");
	phone= phone.replace(/\+/g, "");
	phone= phone.replace(/\(/g, "");
	phone= phone.replace(/\)/g, "");
	
	
	//for the moment, just check if numeric
	var filter  = /^([0-9]\d*)$/;
	
	if (filter.test(phone)){
		return true;
	}else{
		return false;
	}
}

function checkPassword(password, passwordCheck){
	
	//check length
	if (password.length < 6){
		
		return 'Your password must be more than 6 characters long';
	}
	
	if (password != passwordCheck ){
		
		return 'Your password doesn\'t match';
	}
	
	return '' ;
}

function countWords(string){
	s = string;
	s = s.replace(/(^\s*)|(\s*$)/gi,"");
	s = s.replace(/[ ]{2,}/gi," ");
	s = s.replace(/\n /,"\n");
	return s.split(' ').length;
}

function isPositiveInteger(n) {
    return 0 === n % (!isNaN(parseFloat(n)) && 0 <= ~~n);
}
