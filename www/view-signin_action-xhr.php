<? if (empty($script_code)): exit; endif;

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

$_POST['name'] = "riotous sinking chamber";

$_POST['name'] = trim($_POST['name']) ?? null;
$_POST['passcode'] = trim($_POST['passcode']) ?? null;
if (empty($_POST['name'])): json_output("failure", "Name was empty."); endif;
if (strlen($_POST['name']) > 40): json_output("failure", "Name too long."); endif;
if (strlen($_POST['name']) < 9): json_output("failure", "Name too short."); endif;
if (empty($_POST['passcode'])): json_output("failure", "Passcode was empty."); endif;

$name_array_temp = explode(" ", $_POST['name']);
$name_array = [];

// Remove whitespace
foreach ($name_array_temp as $key_temp => $name_temp):
	if (ctype_space($name_temp)): continue; endif;
	if (empty($name_temp)): continue; endif;
	if (strlen($name_temp) < 2): continue; endif;
	if ($name_temp == "and"): continue; endif;
	$name_array[] = str_replace([".", ","], null, $name_temp);
 	endforeach;
if (count($name_array) < 3): json_output("failure", "Name too short."); endif;
if (count($name_array) > 3): json_output("failure", "Name too long."); endif;

// Identify the name...
// 1) Find all closest matching words in each language, 2) Match it to the grammar, 3) Check for matched-ness
$possible_languages_array = ["ar_fem", "ar_mas", "en", "ku", "tr"];
foreach ($possible_languages_array as $lang_temp):
	$noun_array[$lang_temp] = $adjective_quality_array[$lang_temp] = $adjective_color_array[$lang_temp] = [];
	endforeach;
$options_temp = [];
$statement_temp = "SELECT * FROM username_options";
$result = pg_query($database_connection, $statement_temp);
while ($row = pg_fetch_assoc($result)):
	if ($row['part'] == "noun"):
		if (!(empty($row['ar_fem']))):
			$similarity_temp = similar_text($name_array[0], $row['ar_fem'], $percent_temp);
			$noun_array['ar_fem'][$percent_temp."_".random_number(10)] = $row['ar_fem'];
			$options_temp[$row['ar_fem']] = $row['option_id']; endif;
		if (!(empty($row['ar_mas']))):
			$similarity_temp = similar_text($name_array[0], $row['ar_mas'], $percent_temp); endif;
			$noun_array['ar_mas'][$percent_temp."_".random_number(10)] = $row['ar_mas'];
			$options_temp[$row['ar_mas']] = $row['option_id']; endif;
		if (!(empty($row['en']))):
			$similarity_temp = similar_text($name_array[2], $row['en'], $percent_temp);
			$noun_array['en'][$percent_temp."_".random_number(10)] = $row['en'];
			$options_temp[$row['en']] = $row['option_id']; endif;
		if (!(empty($row['ku']))):
			$similarity_temp = similar_text($name_array[0], $row['ku']."y", $percent_temp);
			$noun_array['ku'][$percent_temp."_".random_number(10)] = $row['ku'];
			$options_temp[$row['ku']] = $row['option_id']; endif;
		if (!(empty($row['tr']))):
			$similarity_temp = similar_text($name_array[2], $row['tr'], $percent_temp);
			$noun_array['tr'][$percent_temp."_".random_number(10)] = $row['tr'];
			$options_temp[$row['tr']] = $row['option_id']; endif;
	elseif ($row['part'] == "adjective_quality"):
		if (!(empty($row['ar_fem']))):
			$similarity_temp = similar_text($name_array[1], $row['ar_fem'], $percent_temp);
			$adjective_quality_array['ar_fem'][$percent_temp."_".random_number(10)] = $row['ar_fem'];
			$similarity_temp = similar_text($name_array[2], $row['ar_fem'], $percent_temp);
			$adjective_quality_array['ar_fem'][$percent_temp."_".random_number(10)] = $row['ar_fem'];
			$options_temp[$row['ar_fem']] = $row['option_id']; endif;
		if (!(empty($row['ar_mas']))):
			$similarity_temp = similar_text($name_array[1], $row['ar_mas'], $percent_temp); endif;
			$adjective_quality_array['ar_mas'][$percent_temp."_".random_number(10)] = $row['ar_mas'];
			$similarity_temp = similar_text($name_array[2], $row['ar_mas'], $percent_temp); endif;
			$adjective_quality_array['ar_mas'][$percent_temp."_".random_number(10)] = $row['ar_mas'];
			$options_temp[$row['ar_mas']] = $row['option_id']; endif;
		if (!(empty($row['en']))):
			$similarity_temp = similar_text($name_array[0], $row['en'], $percent_temp);
			$adjective_quality_array['en'][$percent_temp."_".random_number(10)] = $row['en'];
			$similarity_temp = similar_text($name_array[1], $row['en'], $percent_temp);
			$adjective_quality_array['en'][$percent_temp."_".random_number(10)] = $row['en'];
			$options_temp[$row['en']] = $row['option_id']; endif;
		if (!(empty($row['ku']))):
			$similarity_temp = similar_text($name_array[1], $row['ku'], $percent_temp);
			$adjective_quality_array['ku'][$percent_temp."_".random_number(10)] = $row['ku'];
			$similarity_temp = similar_text($name_array[2], $row['ku'], $percent_temp);
			$adjective_quality_array['ku'][$percent_temp."_".random_number(10)] = $row['ku'];
			$options_temp[$row['ku']] = $row['option_id']; endif;
		if (!(empty($row['tr']))):
			$similarity_temp = similar_text($name_array[0], $row['tr'], $percent_temp);
			$adjective_quality_array['tr'][$percent_temp."_".random_number(10)] = $row['tr'];
			$similarity_temp = similar_text($name_array[1], $row['tr'], $percent_temp);
			$adjective_quality_array['tr'][$percent_temp."_".random_number(10)] = $row['tr'];
			$options_temp[$row['tr']] = $row['option_id']; endif;
	elseif ($row['part'] == "adjective_color"):
		if (!(empty($row['ar_fem']))):
			$similarity_temp = similar_text($name_array[1], $row['ar_fem'], $percent_temp);
			$adjective_color_array['ar_fem'][$percent_temp."_".random_number(10)] = $row['ar_fem'];
			$options_temp[$row['ar_fem']] = $row['option_id']; endif;
		if (!(empty($row['ar_mas']))):
			$similarity_temp = similar_text($name_array[1], $row['ar_mas'], $percent_temp); endif;
			$adjective_color_array['ar_mas'][$percent_temp."_".random_number(10)] = $row['ar_mas'];
			$options_temp[$row['ar_mas']] = $row['option_id']; endif;
		if (!(empty($row['en']))):
			$similarity_temp = similar_text($name_array[1], $row['en'], $percent_temp);
			$adjective_color_array['en'][$percent_temp."_".random_number(10)] = $row['en'];
			$options_temp[$row['en']] = $row['option_id']; endif;
		if (!(empty($row['ku']))):
			$similarity_temp = similar_text($name_array[2], $row['ku'], $percent_temp);
			$adjective_color_array['ku'][$percent_temp."_".random_number(10)] = $row['ku'];
			$options_temp[$row['ku']] = $row['option_id']; endif;
		if (!(empty($row['tr']))):
			$similarity_temp = similar_text($name_array[1], $row['tr'], $percent_temp);
			$adjective_color_array['tr'][$percent_temp."_".random_number(10)] = $row['tr'];
			$options_temp[$row['tr']] = $row['option_id']; endif;
		endif;
	endwhile;

foreach ($possible_languages_array as $lang_temp):
	krsort($noun_temp[$lang_temp]);
	krsort($adjective_quality[$lang_temp]);
	krsort($adjective_color[$lang_temp]);
	endforeach;

$possible_names = [];

foreach ($possible_languages_array as $lang_temp):

	$adjective_quality_temp = array_slice($adjective_quality_array[$lang_temp],0,1);

	$count_temp = 1;
	$adjective_wildcard_temp = array_slice($adjective_quality_array[$lang_temp],$count_temp,1);
	while ( ($adjective_quality_temp == $adjective_wildcard_temp) && ($count_temp < 100) ):
		$count_temp++;
		$adjective_wildcard_temp = array_slice($adjective_quality_array[$lang_temp],$count_temp,1);
		endwhile;
	$noun_temp = array_slice($noun_array[$lang_temp],0,1);
	$name_temp = username_combine($adjective_quality_temp[0], $adjective_wildcard_temp[0], $noun_temp[0]);
	$similarity_temp = similar_text($_POST['name'], $name_temp, $percent_temp);
	$possible_names[$percent_temp."_".random_number(10)] = [
		"adjective_quality" => $options_temp[$adjective_quality_temp[0]],
		"adjective_wildcard" => $options_temp[$adjective_wildcard_temp[0]],
		"noun" => $options_temp[$noun_temp[0]],
		"combined" => $name_temp,
		];

	$adjective_quality_temp = array_slice($adjective_quality_array[$lang_temp],0,1);
	$adjective_wildcard_temp = array_slice($adjective_color_array[$lang_temp],1,1);
	$noun_temp = array_slice($noun_array[$lang_temp],0,1);
	$name_temp = username_combine($adjective_quality_temp[0], $adjective_wildcard_temp[0], $noun_temp[0]);
	$similarity_temp = similar_text($_POST['name'], $name_temp, $percent_temp);
	$possible_names[$percent_temp."_".random_number(10)] = [
		"adjective_quality" => $options_temp[$adjective_quality_temp[0]],
		"adjective_wildcard" => $options_temp[$adjective_wildcard_temp[0]],
		"noun" => $options_temp[$noun_temp[0]],
		"combined" => $name_temp,
		];

	endforeach;

krsort($possible_names);

print_r($possible_names);

// If an exact match then check passcode against name...

// If signin failed because no username, then just output a passcode failure...
// If signin failed because passcode did not match, then just output a passcode failure...

// If signin passed, then update the session database and make cookies and give a positive response and redirect...

?>
