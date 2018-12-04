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

// For all gendered languages...
if (in_array($language_request, ["ar"])):
	$append_temp = ["_fem", "_mas"];
	$rand_temp = array_rand($append_temp);
	$language_request = $append_temp[$rand_temp];
	endif;

// Get a list of all username options currently in the database...
$username_options_array = [];
$database_query = "SELECT * FROM username_options";
$result = pg_query($database_connection, $database_query);
while ($row = pg_fetch_assoc($result)):
	if (empty($row[$language_request])): continue; endif;
	if (empty($username_options_array[$row['part']])): $username_options_array[$row['part']] = []; endif;
	$username_options_array[$row['part']][$row[$language_request]] = $row['option_id'];
	endwhile;

$json_result = [ "items" => [] ];

$used_array = [];

$count_temp = 0;
while ($count_temp < 30):

	$count_temp++;

	$combined_temp = $adjective_quality_temp = $adjective_wildcard_temp = $noun_temp = null;

	// Has to be a quality...
	$cycle_temp = 0;
	while (empty($adjective_quality_temp)):
		$cycle_temp++;
		$rand_temp = array_rand($username_options_array['adjective quality']);
		$adjective_quality_temp = $username_options_array['adjective quality'][$rand_temp];
		if (in_array($rand_temp, $used_array) && ($cycle_temp < 30)): $adjective_quality_temp = null;
		else: $used_array[] = $combined_temp[] = $rand_temp; endif;
		endwhile;

	// Can be either a quality or a color...
	$cycle_temp = 0;						
	while (empty($adjective_wildcard_temp)):
		$cycle_temp++;
		$options_temp = ["adjective quality", "adjective color"];
		$option_temp = $options_temp[array_rand($options_temp)];
		$rand_temp = array_rand($username_options_array[$option_temp]);
		$adjective_wildcard_temp = $username_options_array[$option_temp][$rand_temp];
		if (in_array($rand_temp, $used_array) && ($cycle_temp < 30)): $adjective_wildcard_temp = null;
		else: $used_array[] = $combined_temp[] = $rand_temp; endif;
		endwhile;

	// Has to be a noun...
	$cycle_temp = 0;						
	while (empty($noun_temp)):
		$cycle_temp++;
		$rand_temp = array_rand($username_options_array['noun']);
		$noun_temp = $username_options_array['noun'][$rand_temp];
		if (in_array($rand_temp, $used_array) && ($cycle_temp < 30)): $noun_temp = null;
		else: $used_array[] = $combined_temp[] = $rand_temp; endif;
		endwhile;

	// Must be three words...
	if (empty($adjective_quality_temp) || empty($adjective_wildcard_temp) || empty($noun_temp)): continue; endif;

	// Names may not contain two nor three of the same words as any other name.		   
	$json_result['items'][] = [
		"combined" => username_combine($combined_temp[0], $combined_temp[1], $combined_temp[2], $language_request),
		"name-one" => $adjective_quality_temp, 
		"name-two" => $adjective_wildcard_temp, 
		"name-three" => $noun_temp,
		];

	endwhile;

echo json_encode($json_result);

exit; ?>
