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

echo "<amp-install-serviceworker src='https://diis.online/service-worker.js' layout='nodisplay'></amp-install-serviceworker>";

echo "<amp-img id='diis-logo-header' alt='⨟' src='diis-logo-20180903.jpg' width='1' height='1' layout='responsive' sizes='(min-width: 300px) 240px, 80vw' amp-fx='parallax' data-parallax-factor='1.5'></amp-img>";

echo "<h1>Coming soon</h1>";

echo "<p>Diis is the activist's platform for the most challenging stories in the most difficult environments.</p>";

echo "<h2>Unsilenced.</h2>";

echo "<p>We are a small team of journalists and officials based in Baghdad, Erbil, and Istanbul who are commited to creating a safe platform where minority voices in the Middle East can be read loud and clear.</p>";  

echo "<h2>Safety first.</h2>";

echo "<p>Diis is the leader in security and anonymity, with high-level protection of all data and end-to-end encryption for all traffic. Diis is also video-free, ad-free, and mobile-friendly for access on weak connections.</p>";

echo "<h2>Become a publisher.</h2>";

echo "<p class='home-window-list'><b>&#120793;</b><br>Create your account.</p>";

echo "<p class='home-window-list'><b>&#120794;</b><br>Submit your writing.</p>";

echo "<p class='home-window-list'><b>&#120795;</b><br>Wait for approval.</p>";

echo "<p>Ready? Click below to get started.</p>";

echo "<a href='?view=register'><span id='home-window-register-button'>Register</span></a>";

echo "</body></html>"; ?>
