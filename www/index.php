<? include_once('configuration.php');

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
echo "<meta name='theme-color' content='#555'>";
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

function footer() {
	echo "<div id='footer-spacer'></div>";
	echo "</body></html>"; }

echo "<amp-install-serviceworker src='https://diis.online/service-worker.js' layout='nodisplay'></amp-install-serviceworker>";

// If there is the edit view, then show the edit

// If there is the history view, then show the history

echo "<div id='footer-bar'>";
echo "<span id='footer-bar-us'>Us</span>";
echo "<span id='footer-bar-read'>Read</span>";
echo "<span id='footer-bar-you'>You</span>";
echo "</div>";

// If there is no cookie, then show the info

include_once('view_info.php');

// If there is the info view, then show the info

include_once('view_info.php');

// If there is a cookie or the read view, then show the reading list

// If there is the article view, then show the article

// If there is the author view, then show the author's articles

// If there is a cookie and the view is register or account, then show the account info

// If there is no cookie and the view is register, then show the registration form

// If there is no cookie and the view is account, then show the login form

footer(); ?>
