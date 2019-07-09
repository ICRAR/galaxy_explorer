

	</div><!-- #main -->

	<div id="footer">
		<ul class="clients">
			<li>
				<a href="http://www.abc.net.au/science/" target="_blank"><img src="<?php echo get_image_path();?>/footer_logo_1.png" width="65" height="90" alt="abc Science" /></a>
			</li>
			<li>
				<a href="http://www.scienceweek.net.au" target="_blank"><img src="<?php echo get_image_path();?>/footer_logo_2.png" width="115" height="90" alt="national science week" /></a>
			</li>
			<li>
				<a href="http://australia.gov.au" target="_blank"><img src="<?php echo get_image_path();?>/footer_logo_3.png" width="155" height="90" alt="An Australian Government Initiative" /></a>
			</li>
			<li>
				<a href="http://www.innovation.gov.au/science/inspiringaustralia/Pages/default.aspx" target="_blank"><img src="<?php echo get_image_path();?>/footer_logo_4.png" width="95" height="90" alt="inspiring" /></a>
			</li>
			<li>
				<a href="http://www.icrar.org/" target="_blank"><img src="<?php echo get_image_path();?>/footer_logo_5.1.png" width="197" height="90" alt="The University of Western Australia" /></a>
			</li>
			<li>
				<a href="http://www.gama-survey.org/" target="_blank"><img src="<?php echo get_image_path();?>/footer_logo_6.png" width="71" height="90" alt="The University of Western Australia" /></a>
			</li>
			
		</ul>
		<ul class="footer-nav">
			<li><a href="<?php echo esc_url( home_url( '/' ) ).'about/';?>" class="school-group">About</a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ).'terms-and-conditions/';?>" class="school-group">Terms &amp; Conditions</a></li>
			<li><a href="http://about.abc.net.au/abc-privacy-policy/" target="_blank">Privacy Policy</a></li>
			<li><a href="<?php echo esc_url( home_url( '/' ) ).'contact-us/';?>" class="school-group">Contact Us</a></li>
		</ul>
		<?php get_social_icons_list();?>
	</div>
		
</div><!-- #page -->

<?php wp_footer(); ?>

<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-30762234-5', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>