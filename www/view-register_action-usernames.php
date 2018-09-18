<? if (empty($script_code)): exit; endif;

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");
	// if failure
	// header("HTTP/1.0 412 Precondition Failed", true, 412);
	// and end headers here
	// if no redirect
	// header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	// and end headers here
header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");

// Get a list of all username options currently in the database...
$username_options_array = [];
$database_query = "SELECT * FROM username_options";
$result = pg_query($database_connection, $database_query);
while ($row = pg_fetch_assoc($result)):
	if (empty($username_options_array[$row['part']])): $username_options_array[$row['part']] = []; endif;
	$username_options_array[$row['part']][$row['option_id']] = $row[$language_request];
	endwhile;

$json_result = [ "items" => [] ];

$used_array = [];

$count_temp = 0;
while ($count_temp < 5):
	$adjective_quality_temp = $adjective_color_temp = $noun_temp = null;

	$cycle_temp = 0;
	while (empty($adjective_one_temp)):
		$cycle_temp++;
		$rand_temp = array_rand($username_options_array['adjective quality']);
		$adjective_one_temp = $username_options_array['adjective quality'][$rand_temp];
		if (in_array($rand_temp, $used_array) && ($cycle_temp < 50)): $adjective_one_temp = null;
		else: $used_array[] = $rand_temp; endif;
		endwhile;

	$cycle_temp = 0;						
	while (empty($adjective_two_temp)):
		$cycle_temp++;
		$options_temp = ["adjective quality", "adjective color"];
		$option_temp = $options_temp[array_rand($options_temp)];
		$rand_temp = array_rand($username_options_array[$option_temp]);
		$adjective_two_temp = $username_options_array[$option_temp][$rand_temp];
		if (in_array($rand_temp, $used_array) && ($cycle_temp < 50)): $adjective_two_temp = null;
		else: $used_array[] = $rand_temp; endif;
		endwhile;

	$cycle_temp = 0;						
	while (empty($noun_temp)):
		$cycle_temp++;
		$rand_temp = array_rand($username_options_array['noun']);
		$noun_temp = $username_options_array['noun'][$rand_temp];
		if (in_array($rand_temp, $used_array) && ($cycle_temp < 50)): $noun_temp = null;
		else: $used_array[] = $rand_temp; endif;
		endwhile;
						   
	$json_result['items'][] = [
		"username-one"=>$adjective_one_temp, 
		"username-two"=>$adjective_two_temp, 
		"username-three"=>$noun_temp,
		];

	$count_temp++; endwhile;

echo json_encode($json_result);

exit; ?>
