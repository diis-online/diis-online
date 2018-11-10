<? if (empty($script_code)): exit; endif;

// This is for setting up two-factor

header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

if (empty($signin_status)): json_output("failure", "User not signed in."); endif;

$_POST['authenticator_key'] = trim($_POST['authenticator_key']) ?? null;
$_POST['recovery_code_one'] = trim($_POST['recovery_code_one']) ?? null;
$_POST['recovery_code_two'] = trim($_POST['recovery_code_two']) ?? null;
$_POST['recovery_code_three'] = trim($_POST['recovery_code_three']) ?? null;
$_POST['confirm_authenticator_code'] = trim($_POST['confirm_authenticator_code']) ?? null;

if (empty($_POST['authenticator_key'])): json_output("failure", "Authenticator key was empty."); endif; // If no authenticator key was provided...
if (empty($_POST['confirm_authenticator_code'])): json_output("failure", "Authenticator code confirmation was empty."); endif; // If the authenticator code is empty...
if (empty($_POST['recovery_code_one']) || empty($_POST['recovery_code_two']) || empty($_POST['recovery_code_three'])): json_output("failure", "Recovery code was empty."); endif; // If any recovery code is missing...

// Also if the authenticator code check fails then stop there...
elseif (authenticator_code_check($_POST['authenticator_key'], $_POST['confirm_authenticator_code']) !== "success"): json_output("failure", "Please check authenticator code and try again."); endif;

$user_temp['user_id'] = $signin_status['user_id'];
$user_temp['authenticator_key'] = $_POST['authenticator_key'];
$user_temp['recovery_codes'] = json_encode([$_POST['recovery_code_one'], $_POST['recovery_code_two'], $_POST['recovery_code_three']]);
?>
