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
	$username_options_array[$row['part']][$row[[$row['option_id']] = $row[$language_request];
	endwhile;

$json_result = [ "items" => [] ];

$used_array = [];

$count_temp = 0;
while ($count_temp < 10):
	$adjective_one_temp = $adjective_two_temp = $noun_temp = null;
	while (empty($adjective_one_temp)):
																						 

	

  $count_temp++; endwhile;



echo json_encode($json_result);

exit; ?>
