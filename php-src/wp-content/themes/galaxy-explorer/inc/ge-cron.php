<?php 



add_action( 'init', 'process_cronjobs' );

function process_cronjobs() {

     if( isset( $_GET['create_statistics_file'] ) ) {
          // hourly
	     	create_statistics_file();
	     	die;
     }

     if( isset( $_GET['cron_check_images_batch'] ) ) {
          // hourly
	     	cron_check_images_batch();
	     	die;
     }

     if( isset( $_GET['cleanup_users'] ) ) {
          // daily
	     	cleanup_users();
	     	die;
     }
     
     
     if( isset( $_GET['export_results'] ) ) {
     	// on request
     	export_results();
     	die;
     }


}


/*----------------------------------------------------------------------------------------------
 //Make sure we are using the correct batch and send email wit stats (hourly)
 ------------------------------------------------------------------------------------------------*/
//add_action ( 'cron_check_images_batch_hook', 'cron_check_images_batch' );

function cron_check_images_batch(){
	
	global $wpdb;
	
	$current_used_batch = get_option('current_batch_number');
	$current_used_batch_date = get_option('current_batch_started');
	
	$supposed_batch = $wpdb->get_var("
			SELECT MIN(batch_number) 
			  FROM wp_image
			 WHERE batch_number > 0
			   AND times_processed < 10
	");
	
	if($current_used_batch != $supposed_batch){
		
		update_option( 'current_batch_number', $supposed_batch );
		$current_used_batch_date = current_time('d/m/Y \a\t\ g:i:s A');
		
		update_option( 'current_batch_started', $current_used_batch_date );
		
		$to = ERROR_NOTIFICATION_EMAIL;
		$subject = NOTIFICATION_SUBJECT;
		
		$message = "
			Hi Admin,<br />
			A new batch of images started on GALAXY EXPLORER on ".$current_used_batch_date."<br />
			The current new batch number is: ".$supposed_batch;
		
		send_email($to, $subject, $message);
		
	}
	
	
	//get totals for current batch
	$batch_totals = $wpdb->get_results("
			SELECT (CASE 
						WHEN times_processed > 5 
							THEN '5+' 
						ELSE times_processed 
					END ) as processed, 
					count(*) as total
			  FROM wp_image 
			 WHERE batch_number = ".$supposed_batch." 
		  GROUP BY (CASE 
						WHEN times_processed > 5 
							THEN '5+' 
						ELSE times_processed 
					END )
		ORDER by times_processed desc
			");
	
	$message = '';
	
	foreach ($batch_totals as $record){
		
		$message .= "Images processed ".$record->processed." times: ".$record->total."<br />";
		
	}
	
	
	//also send email with some stats about the current batch
	$to = ERROR_NOTIFICATION_EMAIL;
	$subject = UPDATE_SUBJECT;
	
	$message = "
			Hi Admin,<br />
			Here some statistics about the current images batch:<br /><br />
			Batch Number: ".$supposed_batch."<br />
			Batch started: ".$current_used_batch_date."<br />".$message;
	
	send_email($to, $subject, $message);
	
}


/*----------------------------------------------------------------------------------------------
 //create the statistics file (hourly)
 ------------------------------------------------------------------------------------------------*/
//add_action ( 'statistics_hook', 'create_statistics_file' );

function create_statistics_file() {

	$file = 'dashboard.txt';

	$upload_dir = wp_upload_dir();

	$text = get_dashboard_data();

	file_put_contents($upload_dir['basedir'].'/'.$file,$text);
	
	//also update/save the total number of user to use on the frontend
	
	$total_galaxy_subcribers = get_registered_subscribers();
	
	update_option( 'total_galaxy_subcribers', $total_galaxy_subcribers );
	

}


/*----------------------------------------------------------------------------------------------
 //delete session users (daily)
 ------------------------------------------------------------------------------------------------*/
//add_action ( 'cleanup_users_hook', 'cleanup_users' );

function cleanup_users() {
	
	global $wpdb;
	
	$last_week_time = time() - (60*60*24*3);
	
	$last_week = date( 'Y-m-d', $last_week_time );
	
	
	$users = $wpdb->get_results(
				'SELECT *
			   		FROM wp_users
		  	WHERE wp_users.user_email like "%@fake.com%"
			AND user_registered <= "'.$last_week.'"'
	);
	
	/*if(count($users) == 0){
		
		//also send email with some stats about the current batch
		$to = 'developer@example.com';
		$subject = 'CRON FINITO';
		
		$message = "
			Hi Admin,<br />
			stop the cron";
		
		send_email($to, $subject, $message);
		
		die;
		
	}*/
	
	
	
	
	/*$args = array(
			'role' => 'session_guest',
			'date_query'    => array(
					array(
						'before'     => $last_week,
						'inclusive' => true,
					),
			'number' => 10
			));
	
	
	$wp_user_query = new WP_User_Query( $args);
	
	// Get the results
	$users = $wp_user_query->get_results();*/
	
	//preprint($users);die;
	
	foreach ($users as $user){
		
		//delete favourite
		$favorite_delete = $wpdb->delete(
				'wp_favourite',
				array( 	'user_id' 	=> $user->ID
				),
				array( '%d' )
		);
		
		//delete the user
		require_once(ABSPATH.'wp-admin/includes/user.php' );
		wp_delete_user( $user->ID );
		
		
		
	}
	
	//echo '<br>Users deleted: '.count($users);
	

}

/*----------------------------------------------------------------------------------------------
 //export batch results (on demand)
 ------------------------------------------------------------------------------------------------*/
//add_action ( 'export_results_hook', 'export_results' );

function export_results() {

	global $wpdb;
	
	/*//SET THE BATCH NUMBER
	$batch_number = 1;*/
	$export_batch = get_option('current_batch_export');
	
	
	//get the max of the batches
	$max_batch = $wpdb->get_var("SELECT MAX(batch_number) FROM wp_image");
	
	
	//check if I've all the batched (batches are fixed, so )
	if ($export_batch > $max_batch ){
		
		$to = 'developer@example.com';
		$subject = 'CRON JOB - Result export';
		
		$message = "
			Hi Admin,<br />
			The export has done all the batches.<br /><br />
			Please stop the job or reset \"current_batch_export\" in wp_options table back to 1 ";
		
		send_email($to, $subject, $message);
		die;
		
	}
	
	
	//grab the batch number from the options, set it to one if you want to restart.
	//it will write incrementally on the csv files, marking results as they get downloaded, so it won't get downloaded twice
	
	//set to true to create file
	$write_csv = true;
	
	
	
	//get steps description
	$step_description = $wpdb->get_results("
			SELECT *
			  FROM wp_image_steps");
	
	$steps_array = array();
	foreach ($step_description as $step){
		
		$steps_array[$step->step_name][$step->step_value_code] = $step->step_description;
		
		
	}
	
	$steps_array['step1'][9] = 'Can\'t classify';
	
	//preprint($steps_array);
	$results = $wpdb->get_results("
			SELECT wp_result.* 			   
			  FROM wp_result
			  JOIN wp_image 
				ON wp_image.id = wp_result.image_id
				   AND wp_image.batch_number = ".$export_batch."
			 WHERE wp_result.result_downloaded = 0
		  ORDER BY wp_result.image_id limit 5000");
	
	
	//preprint($results);
	
	
	if(!$results){
		
		
		$to = 'developer@example.com';
		$subject = 'CRON JOB - Export Progress';
		
		$message = "
			Hi Admin,<br />
			The export has finished with batch: ".$export_batch.".<br /><br />
			Starting new batch";
		
		send_email($to, $subject, $message);
		
		
		$export_batch++;
		update_option( 'current_batch_export', $export_batch );
		
		
		
		return;
		
	}
	
	
	$upload_dir = wp_upload_dir();
	$basedir = $upload_dir['basedir'].'/results/';
	
	$filename = 'batch_'.$export_batch.'.csv';
	
	$write_header = true;
	if (file_exists($basedir.$filename)) {
		$write_header = false;
	}
	
	
	if($write_csv){
		$fp = fopen($basedir.$filename, 'a');
	}
	
	//write header
	if($write_header){
		$csvArray = array(
				'image name',
				'step 1',
				'step 2',
				'step 3',
				'step 4',
				'ra_deg',
				'dec_deg',
				'rot_w2n',
				'radminasec',
				'radmajasec',
				'points'
				
		);
		
		if($write_csv){
			fputcsv($fp, $csvArray);
		}
	}
	
	foreach ($results as $data){
		
		//preprint($data);
		
		$step_1 = '';
		$step_2 = '';
		$step_3 = '';
		$step_4 = '';
		
		if(isset($steps_array['step1'][$data->step_1])){
			
			$step_1 = $steps_array['step1'][$data->step_1];
			
		}
		
		if(isset($steps_array['step2'][$data->step_2])){
			
			$step_2 = $steps_array['step2'][$data->step_2];
			
			$step_2 = trim($step_2, ' -');
			
		}
		
		if(isset($steps_array['step3'][$data->step_3])){
				
			$step_3 = $steps_array['step3'][$data->step_3];
				
		}
		
		if(isset($steps_array['step4'][$data->step_4])){
			
			$step_4 = $steps_array['step4'][$data->step_4];
			
		}
		
		$points_array = unserialize($data->points_reference);
		
		$point_array_convert = array();
		
		if($points_array){
			foreach ($points_array as $counter => $point){
				
				$point_array_convert[] = 'Point '.($counter + 1).': '. round($point['x'], 0). ' x ' .round($point['y'], 0);
				
			}
		}
		
		$point_array_string = implode(', ', $point_array_convert);
		
		
		$csvArray = array(
				$data->image_name,
				$step_1,
				$step_2,
				$step_3,
				$step_4,
				$data->ra_deg,
				$data->dec_deg,
				$data->rot_w2n,
				$data->radminasec,
				$data->radmajasec,
				$point_array_string
		
		);
		
		
		//preprint($csvArray);
		
		
		if($write_csv){
			fputcsv($fp, $csvArray);
		}
		
		
		//update result table
		$wpdb->query("UPDATE wp_result
						SET result_downloaded = 1
					  WHERE id = ".$data->id
		);
		
		
		
	}
	
	if($write_csv){
		fclose($fp);
	}
	
}

/*----------------------------------------------------------------------------------------------
 //fix competition entries
 ------------------------------------------------------------------------------------------------*/
add_action ( 'fix_competition_entries_hook', 'fix_competition_entries' );

function fix_competition_entries() {

	global $wpdb;
	
	//get last id from options
	$last_id = get_option('fix_competition_entries_last_id');
	//$last_id = 4449;
	
	//echo "Starting from : ".$last_id;
	//echo "<br>";
	
	$users = $wpdb->get_results("
			SELECT * 
			  FROM wp_users
			  JOIN wp_usermeta as type 
				   ON type.user_id = wp_users.ID
				  AND type.meta_key = 'user_type'
				  AND type.meta_value = 'individual'
			  JOIN wp_usermeta as role   
				   ON role.user_id = wp_users.ID
				  AND role.meta_key = 'wp_capabilities'
				  AND role.meta_value like '%subscriber%'
			 WHERE wp_users.ID > ".$last_id."
		  ORDER BY wp_users.ID
			 LIMIT 100;
			");
	
	
	if(!$users){
	
		$to = 'developer@example.com';
		$subject = 'CRON JOB - fix_competition_entries';
	
		$message = "
			Hi Admin,<br />
			The fix_competition_entries is finished.<br /><br />
			Please stop the CRON job";
	
		send_email($to, $subject, $message);
		
		die;	
	}
	
	$update_latest_id = $last_id;
	
	foreach ($users as $user){
		
		$results = get_user_total_images($user->ID);
		$competition_entries = get_user_total_competition_entries($user->ID);
		$supposed_entries = floor($results / 10 );
		
		if($supposed_entries != $competition_entries) {
			//echo "USER: ".$user->ID;
			//echo "<br>";
			//echo "tot images: ".$results;
			//echo "<br>";
			//echo "entries: ".$competition_entries;
			//echo "<br>";
			//echo "should be : ".$supposed_entries;
			//echo "<br>";
			

			
			if($supposed_entries > $competition_entries){
				
				
				$difference = $supposed_entries - $competition_entries;
				
				for($x=0; $x<$difference; $x++){
					
					$insert_record = $wpdb->insert('wp_competition',
							array(
									'user_id' 	=> $user->ID,
									'message' 	=> 'Automated entry'
							),
							array(
									'%d',
									'%s'
							));
					
					//echo "--- adding comp for user : ".$user->ID;
					//echo "<br>";
					
					
						
				}
				
				
				
			}
			
			if($supposed_entries < $competition_entries){
				
				//echo "something wrong";
				//echo "<br>";
				
				//send email
				$to = 'developer@example.com';
				$subject = 'ERROR - fix_competition_entries ';
				
				$message = "
					Hi Admin,<br />
					The User: ".$user->ID."<br /><br />
					Has more competition entries than expected<br><br>
					Total existing entries: ".$competition_entries."<br>
					Supposed entries : ".$supposed_entries;
				
				send_email($to, $subject, $message);
			}
		}
		
		$update_latest_id = $user->ID;
		
		//echo "<br>";
	}
	
	
	
	//update global option for next round
	update_option( 'fix_competition_entries_last_id', $update_latest_id );

}


/*----------------------------------------------------------------------------------------------
 //test cron singularity
 ------------------------------------------------------------------------------------------------*/
add_action ( 'test_cron_singularity_hook', 'test_cron_singularity' );


function test_cron_singularity() {

	$function_name = 'test_cron_singularity';
	
	//check if cron is running already
	$job_status = get_option($function_name);
	
	//if not found, it means it is the first time running, create it
	if(!$job_status){
		
		update_option( $function_name, '' );
		$job_status = '';
		
	}
	
	//exit if already runnning
	if ($job_status != ''){
		
		echo 'job running';
		
		//send email
		$to = 'developer@example.com';
		$subject = 'CRON JOB DUPLICATE for site '.home_url();
		
		$message = "
					Hi Admin,<br /><br />
					The CRON job : ".$function_name."<br />
					for the website: ".home_url()."<br>
					is locked. Please check it.";
		
		send_email($to, $subject, $message);
		
		die;
		
		
	} 
	
	//lock the CRON job
	update_option( $function_name, 'locked' );
	
	
	
	//TODO - function here
	
	
	
	
	//at the end, release the job
	update_option( $function_name, '' );
	


}


