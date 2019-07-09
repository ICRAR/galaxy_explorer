<?php 

add_action ( 'import_csv_file_image_data_hook', 'import_csv_file_image_data' );
function import_csv_file_image_data(){

	echo "are you sure!!!!"; die;

	//read images listing from csv, get extra data from amaozon server file and inser in db
	$upload_dir = wp_upload_dir();
	$csv_count = 1;
	$email_count = 1;

	echo date('Y-m-s H:i:s');

	global $wpdb;

	$handle = fopen($upload_dir['basedir']."/tool/images_data.csv", "r");

	if ($handle !== FALSE) {

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				
			$image_name		= $data[0];
			$ra_deg			= $data[1];
			$dec_deg 		= $data[2];
			$rot_w2n 		= $data[3];
			$radminasec		= $data[4];
			$radmajasec		= $data[5];
			$use			= $data[6];
			$z_cmb			= $data[7];
			$logmstar		= $data[8];
			$dist_ml_yr		= $data[9];
				
				
			$wpdb->query('
						INSERT
						  INTO wp_image
							 ( image_name,
							   ra_deg,
							   dec_deg,
							   rot_w2n,
							   radminasec,
							   radmajasec,
							   image_use,
							   z_cmb,
							   logmstar,
							   dist_ml_yr)
						VALUES
							 ( '.$image_name.',
							   "'.$ra_deg.'",
							   "'.$dec_deg.'",
							   "'.$rot_w2n.'",
							   "'.$radminasec.'",
							   "'.$radmajasec.'",
							   "'.$use.'",
							   "'.$z_cmb.'",
							   "'.$logmstar.'",
							   "'.$dist_ml_yr.'")');
				
			$csv_count++;
				
			/*if($csv_count >= 100){
				break;
				}*/
				
		}

		fclose($handle);

		echo date('Y-m-s H:i:s');


	}

}


function import_extra_image_data(){

	echo "are you sure!!!!"; die;
	
	//read extra images data from file and update records
	$upload_dir = wp_upload_dir();
	$csv_count = 1;
	$email_count = 1;

	echo date('Y-m-s H:i:s');
	echo "<br />";

	global $wpdb;

	$handle = fopen($upload_dir['basedir']."/tool/images_additional.csv", "r");

	if ($handle !== FALSE) {

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				
			$image_name		= $data[0];
			$ra_left		= $data[1];
			$dec_bottom		= $data[2];
			$ra_right 		= $data[3];
			$dec_top		= $data[4];
			$pixels			= $data[5];
				
				
			$wpdb->query('
						UPDATE wp_image
						   SET ra_left = "'.$ra_left.'",
							   ra_right = "'.$ra_right.'",
							   dec_top = "'.$dec_top.'",
							   dec_bottom = "'.$dec_bottom.'",
							   pixels = "'.$pixels.'"
						 WHERE image_name = '.$image_name);
				
			$csv_count++;
			
			/*if($csv_count >= 1000){
				break;
			}*/
				
		}

		fclose($handle);
	}
	
	echo $csv_count++;
	echo "<br />";
	
	echo date('Y-m-s H:i:s');
	echo "<br />";

}



function create_images_batches_old(){
	
	//echo "OLD SYSTEM< NOT USED ANYMORE!!";die;
	
	echo "are you sure!!!!"; die;
	
	//we virtually divided the images in good and bad, based on the "pixels" field
	//if pixel >= 166 =====> GOOD (~ 65000 images - 30% )
	//if pixel < 166 =====> BAD (~ 150000 images - 70% )
	// we create batches of 1000 images, with 500 good and 500 bad, until we run out of good ones
	//this means the greater packages will have only bad images
	$pixels_limit = 190;
	

	//read extra images data from file and update records
	echo 'START SCRIPT: '.date('Y-m-s H:i:s');
	echo "<br /><br />";

	global $wpdb;
	
	//get last batch
	$batch_number = $wpdb->get_var("
			SELECT MAX(batch_number) 
			  FROM wp_image");
	
	
	//create ten batches at the time
	for ($x = 0; $x <= 14 ; $x++){
		
		$batch_number++;
		
		
		//update 500 good images
		$good_images = $wpdb->query('UPDATE wp_image
									SET batch_number = '.$batch_number.'
								  WHERE batch_number = 0
									AND cast(pixels as decimal) >= "'.$pixels_limit.'"
							   ORDER BY cast(pixels as decimal) DESC
								  LIMIT 500');
		
		
		//update 500 good images
		$bad_images = $wpdb->query('UPDATE wp_image
									SET batch_number = '.$batch_number.'
								  WHERE batch_number = 0
									AND cast(pixels as decimal) < "'.$pixels_limit.'"
									AND pixels <> "0"
							   ORDER BY cast(pixels as decimal) DESC
								  LIMIT '.(1000 - $good_images));
		
		echo '<br />----------------------------';
		echo '<br />ROUND			: '.$batch_number;
		echo '<br />Good Images	: '.$good_images;
		echo '<br />Bad Images	: '.$bad_images;
		
		
	}
	
	
	
	
	//get (1000 - good images) bad images
		

	echo '<br /><br /><br />END SCRIPT:'.date('Y-m-s H:i:s');
	echo "<br />";

}


function create_images_batches(){

	//echo "are you sure!!!!"; die;

	//we create the batches ordering images based on quality:
	// batch 1: best images
	//batch [last] : rubbish images
	
	echo 'START SCRIPT: '.date('Y-m-s H:i:s');
	echo "<br /><br />";

	global $wpdb;

	//get last batch
	$batch_number = $wpdb->get_var("
			SELECT MAX(batch_number)
			  FROM wp_image");


	//create 15 batches at the time
	for ($x = 0; $x <= 15 ; $x++){

		$batch_number++;


		//update 500 good images
		$good_images = $wpdb->query('UPDATE wp_image
									SET batch_number = '.$batch_number.'
								  WHERE batch_number = 0
									AND cast(pixels as decimal) <> "0"
							   ORDER BY cast(pixels as decimal) DESC, 
										cast(dist_ml_yr as decimal) desc
								  LIMIT 1000');


		echo '<br />----------------------------';
		echo '<br />ROUND			: '.$batch_number;
		echo '<br />Good Images	: '.$good_images;
		

	}




	//get (1000 - good images) bad images


	echo '<br /><br /><br />END SCRIPT:'.date('Y-m-s H:i:s');
	echo "<br />";

}