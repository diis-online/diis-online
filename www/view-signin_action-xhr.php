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

$name_array = explode(" ", $_POST['name']);
foreach ($name_array as $key_temp => $name_temp):
  if (	ctype_space($name_temp) || 
				empty($name_temp) || 
				(strlen($name_temp) < 2) || 
				($name_temp == "and") ):
		unset($name_array[$key_temp]);
		continue; endif;
	$name_array[$key_temp] = str_replace([".", ","], null, $name_temp);
  endforeach;
if (count($name_array) < 3): json_output("failure", "Name too short."); endif;
if (count($name_array) > 3): json_output("failure", "Name too long."); endif;


// Identify the name...



// Check passcode against name...



// If signin failed, then just output a failure...


// If signin passed, then update the session database and make cookies and give a positive response and redirect...



?>
