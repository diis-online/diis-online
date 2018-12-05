<? if (empty($script_code)): exit; endif;

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

// Match must be greater than this percent to work
$percent_cutoff = 75;

$_POST['name'] = "riotous red horizon";
$_POST['passcode'] = "555555";

// Check signin name
$_POST['name'] = trim($_POST['name']) ?? null;
if (empty($_POST['name'])): json_output("failure", "Name was empty."); endif;
if (strlen($_POST['name']) > 50): json_output("failure", "Name too long."); endif;
if (strlen($_POST['name']) < 9): json_output("failure", "Name too short."); endif;

// Check signin passcode
$_POST['passcode'] = trim($_POST['passcode']) ?? null;
if (empty($_POST['passcode'])): json_output("failure", "Passcode was empty."); endif;
if (!(ctype_digit($_POST['passcode']))): json_output("failure", "Passcode was not numeric."); endif;
if (strlen($_POST['passcode']) < 6): json_output("failure", "Passcode was too short."); endif;
if (strlen($_POST['passcode']) > 6): json_output("failure", "Passcode was too long."); endif;

$name_array_temp = explode(" ", $_POST['name']);
$name_array = [];

// Remove whitespace
foreach ($name_array_temp as $key_temp => $name_temp):
	if (ctype_space($name_temp)): continue; endif;
	if (empty($name_temp)): continue; endif;
	if (strlen($name_temp) < 2): continue; endif;
	if (in_array($name_temp, ["and", "w", "u"])): continue; endif;
	$name_array[] = str_replace([".", ","], null, $name_temp);
 	endforeach;
if (count($name_array) < 3): json_output("failure", "Name too brief."); endif;
if (count($name_array) > 3): json_output("failure", "Name too wordy."); endif;

// Identify the name...
// 1) Find all closest matching words in each language, 2) Match it to the grammar, 3) Check for matched-ness
$possible_languages_array = ["ar_fem", "ar_mas", "en", "ku", "tr"];
$words_array = [
	"noun" => [],
	"adjective quality" => [],
	"adjective color" => [],
	];
foreach ($possible_languages_array as $lang_temp):
	foreach ($words_array as $key_temp => $array_temp):
		$words_array[$key_temp][$lang_temp] = [];
		endforeach;
	endforeach;
$options_temp = [];
$statement_temp = "SELECT * FROM username_options";
$result = pg_query($database_connection, $statement_temp);
while ($row = pg_fetch_assoc($result)):

	foreach ($possible_languages_array as $lang_temp):

		if (empty($row[$lang_temp])): continue; endif;

		$options_temp[$row[$lang_temp]] = $row['option_id'];

		$places_temp = [];

		// If it's a noun, the place is important...
		if ($row['part'] == "noun"):
			if (in_array($lang_temp, ["ar_fem", "ar_mas"])): $places_temp = ["0"];
			elseif ($lang_temp == "en"): $places_temp = ["2"];
			elseif ($lang_temp == "ku"): $places_temp = ["0"];
			elseif ($lang_temp == "tr"): $places_temp = ["2"]; endif;

		// If it's an adjective, the order does not matter...
		elseif (in_array($row['part'], ["adjective quality", "adjective color"])):
			if (in_array($lang_temp, ["ar_fem", "ar_mas"])): $places_temp = ["1", "2"];
			elseif ($lang_temp == "en"): $places_temp = ["0", "1"];
			elseif ($lang_temp == "ku"): $places_temp = ["1", "2"];
			elseif ($lang_temp == "tr"): $places_temp = ["0", "1"]; endif;
			
		// If the part of speech is not recognized...
		else: continue; endif;
			
		foreach ($places_temp as $place_temp):

			// Add the و if it is the second adjective and Arabic :-)
			if (($place_temp == 2) && in_array($row['part'], ["adjective quality", "adjective color"]) && in_array($lang_temp, ["ar_fem", "ar_mas"])):
				$row[$lang_temp] = "و".$row[$lang_temp];
				endif;

			$similarity_temp = similar_text($name_array[$place_temp], $row[$lang_temp], $percent_temp);
			$words_array[$row['part']][$lang_temp][process_percent($percent_temp)."_".random_number(10)] = $row[$lang_temp];
			endforeach;
		
		endforeach;

	endwhile;

foreach ($words_array as $part_temp => $array_temp):
	foreach ($possible_langauges_array as $lang_temp):
		krsort($words_array[$part_temp][$lang_temp]);
		endforeach;
	endforeach;

$possible_names = [];

foreach ($possible_languages_array as $lang_temp):

	// We generate four possible usernames: 
	// 1 and 2) adjective_quality adjective_quality noun (backwards and forwards).
	// 3 and 4) adjective_quality adjective_color noun (backwards and forwards).
	// The order is agnositc, so 'big red car' and 'red big car' are both accepted.

	$noun_final = $adjective_quality_final = $adjective_wildcard_final = 0;

	// First generate the noun...
	$noun_temp = array_values(array_slice($words_array['noun'][$lang_temp],0,1));
	$noun_final = $noun_temp[0];

	// Second, generate the adjective_quality...
	$adjective_quality_temp = array_values(array_slice($words_array['adjective quality'][$lang_temp],0,1));
	$adjective_quality_final = $adjective_quality_temp[0];

	// Next, generate a wildcard adjective that is a 'quality'...
	$count_temp = 1;
	$adjective_wildcard_temp = array_values(array_slice($words_array['adjective quality'][$lang_temp],$count_temp,1));
	while ( ($adjective_quality_temp == $adjective_wildcard_temp) && ($count_temp < 100) ):
		$count_temp++;
		$adjective_wildcard_temp = array_values(array_slice($words_array['adjective quality'][$lang_temp],$count_temp,1));
		endwhile;
	$adjective_wildcard_final[] = $adjective_wildcard_temp[0];

	// Then, generate a wildcard adjective that is a 'color'...
	$adjective_wildcard_temp = array_values(array_slice($words_array['adjective color'][$lang_temp],0,1));
	$adjective_wildcard_final[] = $adjective_wildcard_temp[0];

	// Finally, loop through them all...
	$name_combined_array = [
		[ $adjective_quality_final,	$adjective_wildcard_final[0],	$noun_final ],
		[ $adjective_wildcard_final[0],	$adjective_quality_final,	$noun_final ],
		[ $adjective_quality_final,	$adjective_wildcard_final[1],	$noun_final ],
		[ $adjective_wildcard_final[1],	$adjective_quality_final,	$noun_final ],
		];

	foreach ($name_combined_array as $name_temp):
		$combined_temp = username_combine($name_temp[0], $name_temp[1], $name_temp[2], $lang_temp);
		$similarity_temp = similar_text($_POST['name'], $combined_temp, $percent_temp);
		if ($percent_temp < $percent_cutoff): continue; endif;
		$possible_names[process_percent($percent_temp)."_".random_number(10)] = [
			"adjective_quality" => $options_temp[$name_temp[0]],
			"adjective_wildcard" => $options_temp[$name_temp[1]],
			"noun" => $options_temp[$name_temp[2]],
			"combined" => $combined_temp,
			];
		if ($percent_temp == 100): break 2; endif;
		endforeach;

	endforeach;

if (empty($possible_names)):
	json_output("failure", "No matches");
	endif;

krsort($possible_names);
$name_result = array_slice($possible_names, 0, 1);
$name_result = array_values($name_result);
$name_result = $name_result[0];

print_r($possible_names);

if ($percent_temp == 100):

	// We have an exact match, so from there check if username exists, order of adjectives does not matter

	// This is the order it has to be in...
	$values_temp = [];
	$values_temp = [
		"name_one" => $name_result['adjective_quality'],
		"name_two" => $name_result['adjective_wildcard'],
		];
	asort($values_temp);
	$values_temp = array_values($values_temp);
	$values_temp[] = $name_result['noun'];

	// 

	// If no match, then output passcode failure

	// Check if passcode matches
	// If no, then output passcode failure

	// If signin passed, then update the session database and make cookies and give a positive response and redirect...

	endif;

// Give out the first one as a recommendation
json_output("failure", "Close: ".$name_result['combined']);

function process_percent($percent) {
	if (empty($percent) || ($percent < 1)): return "000.000"; endif;
	$percent_array = explode(".", $percent);
	$percent_array[0] = $percent_array[0] ?? 0;
	$percent_array[1] = substr($percent_array[1], 0, 6) ?? 0;
	while (strlen($percent_array[0]) < 3): $percent_array[0] = "0".$percent_array[0]; endwhile;
	while (strlen($percent_array[1]) < 6): $percent_array[1] = $percent_array[1]."0"; endwhile;
	return implode(".", [$percent_array[0], $percent_array[1]]); } ?>
