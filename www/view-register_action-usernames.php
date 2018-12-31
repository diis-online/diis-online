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
$username_options_array = $words_array = [];
$database_query = "SELECT * FROM username_options";
$result = pg_query($database_connection, $database_query);
while ($row = pg_fetch_assoc($result)):
	if (empty($row[$language_request])): continue; endif;
	if (empty($username_options_array[$row['part']])): $username_options_array[$row['part']] = []; endif;
	$username_options_array[$row['part']][$row['option_id']] = $row[$language_request];
	endwhile;

// This is to find matches
// If we have 'big red rock' it will catch 'big red rock' or 'red big rock'
$array_temp = [
	"(name_one=$1 OR name_one=$2)",
	"(name_two=$1 OR name_two=$2)",
	"(name_three=$3)",
	];
$statement_temp = "SELECT * FROM users WHERE ".implode(" AND ", $array_temp);
$result_temp = pg_prepare($database_connection, "check_users_statement", $statement_temp);
if (database_result($result_temp) !== "success"): json_output("failure", "Database #176."); endif;

$json_result = [ "items" => [] ];

$used_array = [];

$count_temp = 0;
while ($count_temp < 30):

	$count_temp++;

	$combined_temp = $adjective_quality_temp = $adjective_wildcard_temp = $noun_temp = null;

	// One adjective has to be a quality...
	$adjective_quality_temp = array_rand($username_options_array['adjective quality']);

	// Another wildcard adjective can be either a quality or a color...
	$options_temp = ["adjective quality", "adjective color"];
	$option_temp = $options_temp[array_rand($options_temp)];
	$adjective_wildcard_temp = array_rand($username_options_array[$option_temp]);

	// And there must be a noun
	$noun_temp = array_rand($username_options_array['noun']);

	// Must be three words...
	if (empty($adjective_quality_temp) || empty($adjective_wildcard_temp) || empty($noun_temp)): continue; endif;

	// Get the words themselves, not just IDs...
	$adjective_quality_word_temp = $username_options_array['adjective quality'][$adjective_quality_temp] ?? null;
	$adjective_wildcard_word_temp = $username_options_array['adjective quality'][$adjective_wildcard_temp] ?? $username_options_array['adjective color'][$adjective_wildcard_temp] ?? null;
	$noun_word_temp = $username_options_array['noun'][$noun_temp] ?? null;

	// Double check the words exist...
	if (empty($adjective_quality_word_temp) || empty($adjective_wildcard_word_temp) || empty($noun_word_temp)): continue; endif;

	// And the two adjectives cannot be the same...
	if ($adjective_quality_temp == $adjective_wildcard_temp): continue; endif;

	// And cannot exist already for another user...
	$values_temp = [$adjective_quality_temp, $adjective_wildcard_temp, $noun_temp];
	$result_temp = pg_execute($database_connection, "check_share_id_statement", $values_temp);
	if (database_result($result_temp) !== "success"): json_output("failure", "Database #177."); endif;
	while ($row_temp = pg_fetch_assoc($result_temp)):
		continue 2;
		endwhile;

	// Alternatively, we can SELECT all users and do array_intersect to see if
	// they have three (or even just two) words in common

	// But if it passes all this then it can be used...
	$json_result['items'][] = [
		"combined" => username_combine($adjective_quality_word_temp, $adjective_wildcard_word_temp, $noun_word_temp, $language_request),
		"name-one" => $adjective_quality_temp, 
		"name-two" => $adjective_wildcard_temp, 
		"name-three" => $noun_temp,
		];

	endwhile;

echo json_encode($json_result);

exit; ?>
