<?php




























/*----------------------------------------------------------------------------------------------
 //add a hourly interval
------------------------------------------------------------------------------------------------*/
add_action ( 'statistics_hook1', 'create_statistics_file1' );
add_action ( 'process_results_hook', 'process_results' );
add_action ( 'sanitize_empty_observations_hook', 'sanitize_empty_observations' );



function create_statistics_file1() {

	$file = 'dashboard.txt';

	$upload_dir = wp_upload_dir();

	$text = get_dashboard_data();

	file_put_contents($upload_dir['basedir'].'/'.$file,$text);

}




















/*----------------------------------------------------------------------------------------------
 // Get the total number of people who can enter the competition, by user type
------------------------------------------------------------------------------------------------*/
function get_competition_total_can_enter($userType){


	global $wpdb;

	if($userType == 'individual'){
		

		$minimunEntries = 10;

	}else{

		$minimunEntries = 50;

	}

	//TODO - not using tool_total_entries anymore, get total in result table
	$query = $wpdb->prepare("SELECT count(a.user_id)
							   FROM wp_usermeta a,
							   		wp_usermeta b
							  WHERE a.user_id = b.user_id
							    AND a.meta_key = 'user_type'
								AND a.meta_value = %s
								AND b.meta_key = 'tool_total_entries'
								AND b.meta_value >= %d", $userType,$minimunEntries);
	
	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;

}



/*----------------------------------------------------------------------------------------------
 // Get the total number of people who has entered the competition, by user type
------------------------------------------------------------------------------------------------*/
function get_competition_has_entered($userType){

	global $wpdb;

	$query = $wpdb->prepare("SELECT count(a.user_id)
							   FROM wp_usermeta a,
							   		wp_usermeta b
							  WHERE a.user_id = b.user_id
							    AND a.meta_key = 'user_type'
								AND a.meta_value = %s
								AND b.meta_key = 'competitition_entered'
								AND b.meta_value = 'true'", $userType);

	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;

}




/*----------------------------------------------------------------------------------------------
 // Get the total number of people who has not entered the competition, by user type
------------------------------------------------------------------------------------------------*/
function get_competition_has_not_entered($userType){

	global $wpdb;

	if($userType == 'individual'){

		$minimunEntries = 10;

	}else{

		$minimunEntries = 50;

	}
	//TODO - not using tool_total_entries anymore, get total in result table
	$query = $wpdb->prepare("SELECT count(a.user_id)
							   FROM wp_usermeta a,
							   		wp_usermeta b,
									wp_usermeta d
							  WHERE a.user_id = b.user_id
							    AND a.user_id = d.user_id
								AND a.meta_key = 'user_type'
								AND a.meta_value = %s
								AND b.meta_key = 'tool_total_entries'
								AND b.meta_value >= %d
								AND d.meta_key = 'competitition_entered'
								AND d.meta_value = 'false'", $userType,$minimunEntries);

	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;
}


/*----------------------------------------------------------------------------------------------
 // Get the total number of total images
------------------------------------------------------------------------------------------------*/
function get_total_images(){

	global $wpdb;

	$query = $wpdb->prepare("SELECT count(id) FROM wp_image WHERE 1 = %d", 1	);

	$counter = $wpdb->get_var( $query );

	if(!$counter){
		$counter = 0;
	}

	return $counter;

}









/*----------------------------------------------------------------------------------------------
 // Get the total number of individual users
------------------------------------------------------------------------------------------------*/
function get_registered_subscribers_individual(){


	$counter = 0;
	//also get total number of students
	global $wpdb;

	$query = $wpdb->prepare("SELECT count(*) FROM wp_usermeta WHERE meta_key = %s AND meta_value = %s" , 'user_type', 'individual'	);

	$counter = $wpdb->get_var( $query );

	return $counter;

}


/*----------------------------------------------------------------------------------------------
 // Get the total number of group users
------------------------------------------------------------------------------------------------*/
function get_registered_subscribers_group(){


	$counter = 0;
	//also get total number of students
	global $wpdb;

	$query = $wpdb->prepare("SELECT count(*) FROM wp_usermeta WHERE meta_key = %s AND meta_value = %s" , 'user_type', 'group'	);

	$counter = $wpdb->get_var( $query );

	return $counter;

}




/*----------------------------------------------------------------------------------------------
 // Get the more active individual user
------------------------------------------------------------------------------------------------*/
function get_total_images_highest_individual(){

	global $wpdb;

	$query = $wpdb->prepare("SELECT c.user_id, d.user_email, e.meta_value as first_name, f.meta_value as last_name, count(*) as total
							   FROM wp_observation a, 
									wp_page b, 
									wp_usermeta c, 
									wp_users d, 
									wp_usermeta e, 
									wp_usermeta f
							  WHERE a.page_id = b.id
								AND b.user_id = c.user_id
								AND b.user_id = e.user_id
								AND b.user_id = f.user_id
								AND b.user_id = d.ID
								AND c.meta_key = 'user_type'
								AND c.meta_value = %s
								AND e.meta_key = 'first_name'
								AND f.meta_key = 'last_name'
						   GROUP BY b.user_id
						   ORDER BY total DESC
							  LIMIT 1", 'individual');

	$data = $wpdb->get_row( $query );

	return $data;


}


/*----------------------------------------------------------------------------------------------
 // Get the more active group user
------------------------------------------------------------------------------------------------*/
function get_total_images_highest_group(){

	global $wpdb;

	$query = $wpdb->prepare("SELECT c.user_id, d.user_email, e.meta_value as first_name, f.meta_value as last_name, g.meta_value as school_name, count(*) as total
							   FROM wp_observation a,
									wp_page b,
									wp_usermeta c,
									wp_users d,
									wp_usermeta e,
									wp_usermeta f,
									wp_usermeta g
							  WHERE a.page_id = b.id
								AND b.user_id = c.user_id
								AND b.user_id = e.user_id
								AND b.user_id = f.user_id
								AND b.user_id = g.user_id
								AND b.user_id = d.ID
								AND c.meta_key = 'user_type'
								AND c.meta_value = %s
								AND e.meta_key = 'first_name'
								AND f.meta_key = 'last_name'
								AND g.meta_key = 'schoolGroupName'
						   GROUP BY b.user_id
						   ORDER BY total DESC
							  LIMIT 1", 'group');

	$data = $wpdb->get_row( $query );

	return $data;


}








