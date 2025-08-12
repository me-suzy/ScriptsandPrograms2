<?PHP
// Generates a random number.  Now only returns 0.
$ad_type = rand(0,0);

// Defines the add location on the page.  As of now only top is allowed.
$ad_location = "top";

// If the random number is 0 then the ad is a text ad.
if($ad_type==0){
	$ad_type = "text";
}

// This part has not yet been completly implemented.  Soon to come...
//if($ad_type==1){
//	$ad_type = "banner";
//}
//if($ad_type==2){
//	$ad_type = "script";
//}
//////////


$final_html = NULL; 

// If the ad is a text ad, get the information needed and write it to the template page.
if($ad_type=="text"){

	// Defines the loops.
	$textad_str = $template->get_loop ("textad");
	$ad_str = $template->get_loop ("ad"); 

	// Randomly return 4 text ads to display.
	$ad_result = mysql_query("SELECT * FROM ads WHERE ad_location='". $ad_location ."' AND ad_type='text' ORDER BY RAND() LIMIT 4");

	// Loop through, and display them.
	while($ad_row = mysql_fetch_array($ad_result)){

		// Gets and sets the information from the database.
		$ad_text = $ad_row['ad_text_name'];
		$ad_text_href = $ad_row['ad_text_href'];
		$ad_text_description = $ad_row['ad_text_description'];

		// Creates a new mini-template.
		$mini_template = new MiniTemplate ();

		// Defines which loop to place the information into.
		$mini_template->template_html = $ad_str;

		// Then plugs the information into the loop.
		$mini_template->set_template ("ad_text", $ad_text);
		$mini_template->set_template ("ad_text_href", $ad_text_href);
		$mini_template->set_template ("ad_text_description", $ad_text_description);

		// Lastly, returns the whole loop as a string.
		$final_html .= $mini_template->return_html ();
	}

// After all the loops have been set, display them.
$template->end_loop ("textad", $template->end_loop ("ad", $final_html, $textad_str));

// Since this is a text ad we want to get rid of the banner ad loop.
$template->end_loop ("bannerad", "");

// If the ad is a banner ad, get this information and write it to the template page.
} else if($ad_type=="banner"){

	// Defines the loop.
	$bannerad_str = $template->get_loop ("bannerad"); 

	// Gets and sets the information from the database.
	$ad_result = mysql_query("SELECT * FROM ads WHERE ad_location='". $ad_location ."' AND ad_type='banner' ORDER BY RAND()");
	$ad_row = mysql_fetch_array($ad_result);

	// Gets and sets the information from the database.
	$ad_banner_img = $ad_row['ad_banner_img'];
	$ad_banner_href = $ad_row['ad_banner_href'];

	// Then plugs the information into the loop.
	$template->set_template ("ad_banner_img", $ad_banner_img);
	$template->set_template ("ad_banner_href", $ad_banner_href);

	// Display the information.
	$template->end_loop ("bannerad", $bannerad_str);

	// Since this is a banner ad we want to get rid of the text ad loop.
	$template->end_loop ("textad", "");
}
?>