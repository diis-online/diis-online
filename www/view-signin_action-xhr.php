<? if (empty($script_code)): exit; endif;

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

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
$statement_temp = "SELECT * FROM username_options";
$result = pg_query($database_connection, $statement_temp);
while ($row = pg_fetch_assoc($result)):
	// If it matches something exactly that's great
	// If it only matches partially then let's store those for recommendations
	endwhile;

// Give the recommendation if no exact match

// Write a function that checks the username exactly

function username_check($name_one, $name_two, $name_three, $input_name) {
	
}

// If an exact match then check passcode against name...

// If signin failed because no username, then just output a passcode failure...
// If signin failed because passcode did not match, then just output a passcode failure...

// If signin passed, then update the session database and make cookies and give a positive response and redirect...

?>
