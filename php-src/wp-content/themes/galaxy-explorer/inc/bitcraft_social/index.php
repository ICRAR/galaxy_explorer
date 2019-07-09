<html>
<head>

<meta property="og:type"   content="website" /> 
<meta property="og:title" content="Workday Sets Price Range for I.P.O." />
<meta property="og:site_name" content="My Favorite News"/>
<meta property="og:url" content="http://fbtest2.bitcraft-staging.com/" />
<meta property="og:image" content="http://fbtest2.bitcraft-staging.com/lakepic.jpg"/>
<meta property="og:description" content="Workday, a provider of cloud-based
applications for human resources, said on Monday that it would seek to price
its initial public offering at $21 to $24 a share.  At the midpoint of that
range, the offering would value the company at $3.6 billion. Like many other
technology start-ups, Workday, founded in 2005, will have a dual-class share
structure, with each Class B share having 10 votes. Its co-chief executives,
David Duffield, the founder of PeopleSoft, and Aneel Bhusri, who was chief
strategist at PeopleSoft, will have 67 percent of the voting rights after
the I.P.O., according to the prospectus." />

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"
	type="text/javascript"></script>



</head>

<body>
	<h1>HELLO TESTING</h1>
	
	<?php 
		include_once 'BitcraftSocial.php';	
		
		//example: get current url without parameters
		
		$url_base = 'http://' . $_SERVER['HTTP_HOST'];
		$url_page = explode("?", $_SERVER['REQUEST_URI']);
		
		$current_url = $url_base .$url_page[0];
		$image = 'http://www.airnorth.com.au/sites/default/files/Car%20hire%20-%20Budget%20Hyundai%20i30%20-%20LR.jpg';
		$description = 'This is the content of the description field, below the caption.';
		
		
		
		//initialize Sharing
		$sharing = new BitcraftSocial(true);
		
		
		
		
		
		//Facebook
		/*
		 * NOTES: for properly sharing on Facebook, you need to create a n API key
		 * Log in to facebook developer (https://developers.facebook.com/) as developer@example.com
		 * Select "My Apps" 
		 * --> "Add a new App"
		 * --> "Website" 
		 * --> type a name for your app (this will appear in the facebook popup, so choose wisely)
		 * --> Create New Facebook App ID
		 * From the popup, specify if is a test app(usually no), and select the category (Business, Education...)
		 * At this point, don't worry about the JS there, just note the App ID, and click "skip Quick setup"
		 * in "Settings" --> "Basic", put developer@example.com in "Contact Email" (you need this to enable the app) --> Save
		 * in "Status % Review" --> "Do you want to make this app and all its live features available to the general public?" switch to yes
		 * ALL DONE!
		 * 
		 */
		$facebook = $sharing->facebook_init('xxxxxx');
		
		//Facebook - set paramenters(these will override the og tags)
		$facebook->set_title('Title here');
		$facebook->set_url($current_url);
		$facebook->set_image($image);
		$facebook->set_caption('This is the content of the caption field.');
		$facebook->set_description($description);
		
		//Facebook - get the link (add class array for styling)
		echo "<br />";
		echo $facebook->create_sharing_link('facebook_share_link', 'Share on Facebook', array('manuel', 'massella'));
		
		
		
		
		
		//Twitter
		$twitter = $sharing->twitter_init();
		
		//Twitter - set parameters
		$twitter->set_url($current_url);
		$twitter->set_description('Twitter description');
		
		//Twitter - get the link (add class array for styling)
		echo "<br />";
		echo $twitter->create_sharing_link('twitter_share_link', 'Share on Twitter');
		
		
		
		
		
		
		//Google Plus
		$googlePlus = $sharing->googlePlus_init();
		
		//Google Plus - set paramenters
		$googlePlus->set_url($current_url);
		
		//Google Plus - get the link (add class array for styling)
		echo "<br />";
		echo $googlePlus->create_sharing_link('googleplus_share_link', 'Share on GoglePlus');
		
		
		
		
		
		//Pinterest - link and image required
		$pinterest = $sharing->pinterest_init();
		
		//Pinterest - set paramenters
		$pinterest->set_url($current_url);
		$pinterest->set_image($image);
		$pinterest->set_description($description);
		
		//Pinterest - get the link (add class array for styling)
		echo "<br />";
		echo $pinterest->create_sharing_link('pinterest_share_link', 'Share on Pinterest');
		
		
		
		
		
		
		//LinkedIn - it uses og tag, can be overriden - no control over image, it uses the og tags
		//link is required
		$linkedIn = $sharing->linkedIn_init();
		
		//LinkedIn - set paramenters
		$linkedIn->set_url($current_url);
		//$linkedIn->set_title("A custom Title");
		//$linkedIn->set_source('BITCRAFT SOURCE');
		$linkedIn->set_description($description);
		
		//LinkedIn - get the link (add class array for styling)
		echo "<br />";
		echo $linkedIn->create_sharing_link('linkedin_share_link', 'Share on LinkedIn');
		
		
		
		//Email
		$email = $sharing->email_init();
		
		//LinkedIn - set paramenters
		$email->set_address('info@example.com');
		$email->set_cc('info@example.com');
		$email->set_bcc('info@example.com');
		$email->set_subject('Info Request');
		
		$email->set_body('Great site. I would love some more info.');
		
		//LinkedIn - get the link (add class array for styling)
		echo "<br />";
		echo $email->create_sharing_link('email_share_link', 'Share on Email');
		
		
	?>
	
	
</body>
</html>


















