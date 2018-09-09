<? include_once('configuration.php');

$view_request = $_REQUEST['view'] ?? null;
$share_request = $_REQUEST['share'] ?? null;
$action_request = $_REQUEST['action'] ?? null;

funtion body($title="Diis", $include=null) {
	
	echo "<!doctype html><html amp lang='en'><head><meta charset='utf-8'>";

	echo "<script async src='https://cdn.ampproject.org/v0.js'></script>";
	echo "<link rel='canonical' href='https://diis.online'>"; // must define canonical url for amp

	// PWA manifest
	// https://developers.google.com/web/fundamentals/web-app-manifest/
	echo "<link rel='manifest' href='manifest.json'>";

	// Include AMP elements
	echo "<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>";
	echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
	echo '<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>';
	echo '<script async custom-element="amp-fx-collection" src="https://cdn.ampproject.org/v0/amp-fx-collection-0.1.js"></script>';
	echo '<script async custom-element="amp-install-serviceworker" src="https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js"></script>';
	echo "<meta name='viewport' content='width=device-width,minimum-scale=1,initial-scale=1'>"; // must define viewport for amp

	// General appearance
	echo "<title>Diis</title>";
	echo "<meta name='theme-color' content='#2850AA'>";
	echo "<link rel='icon' type='image/png' href='https://diis.online/browser-icon.png'>";
	echo "<link rel='shortcut icon' type='image/png' href='https://diis.online/browser-icon.png'>";
	echo "<link rel='apple-touch-icon' type='image/png' href='https://diis.online/browser-icon.png'>";

	// Fonts and CSS style
	echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
	echo '<link href="https://fonts.googleapis.com/css?family=Libre+Baskerville" rel="stylesheet">';
	echo '<link href="https://fonts.googleapis.com/css?family=Palanquin" rel="stylesheet">';
	echo "<style amp-custom>";
	include_once('style.css');
	echo "</style>";

	echo "</head><body>";

	if (!(empty($include))): include_once($include);
	else: echo "<h1>". $title ."</h1>"; endif;
	
	footer(); }

function footer() {
	echo "<div id='footer-spacer'></div>";
	echo "</body></html>"; }

echo "<amp-install-serviceworker src='https://diis.online/service-worker.js' layout='nodisplay'></amp-install-serviceworker>";

// If there is the edit view, then show the edit

// If there is the history view, then show the history

// If there is no cookie, then show the info

$login_status = [
	"user_id" => "testing",
	"level" => "testing",
	];

if ( ($view_request == "share") && !(empty($share_request))):

	$share_info = [];

	// Look up the share
	$share_info = [
		"share_id" => "1111",
		"author_id" => "testing",
		];

	// If the action is to edit...
	if ($action_request == "edit"):
		
		// If there is no login status then they need to log in...
		if (empty($login_status)): body('Log In', 'view_login.php');

		// If this is about making a new share...
		elseif ($share_request == "create"): body('Create', 'view-share_action-edit.php');

		// ... Otherwise, if the share does not exist then issue a 404...
		elseif (empty($share_info)): body('404');

		// If the user is the author then they have access of course...
		elseif ($login_status['user_id'] == $share_info['author_id']): body('Edit', 'view-share_action-edit.php');

		// If the user is not the author but is an administator or editor...
		elseif (in_array($login_status['level'], ["administrator", "editor"]): body('Edit', 'view-share_action-edit.php');

		else: body('Bad permissions'); endif;
		
		endif;
	
	// If the share does not exist then issue a 404...
	if (empty($share_info)):
		body('404');
		endif;
			
	// If this is going to the API to save it...
	if ($action_request == "api"):

		// Then just go to the API script...
		include_once('view-share_action-edit_xfr.php');
		
		endif;
			
	// At this point, it is okay to show the shre
	body($share_info['share_id'], 'view-share.php');

	endif;

if (empty($view_request) && empty($_COOKIE['visit'])):
	body('About', 'view-info.php');
	endif;
	
if ($view_request == "info"):
	body('About', 'view-info.php');
	endif;
	
if (empty($view_request) || ($view_request == "feed")):
	body('Feed', 'view-feed.php');
	endif;

if ($view_request == "login"):
	body('Log In', 'view-login.php');
	endif;

if ($view_request == "register"):
	body('Register', 'view-register.php');
	endif;

body('404'); ?>
