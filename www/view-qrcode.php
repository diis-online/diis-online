<? if (empty($script_code)): exit; endif;

echo '<?xml version="1.0" standalone="yes"?>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-50 0 200 100">
<g id="qrcode"/>
<foreignObject x="-50" y="0" width="100" height="100">

<body xmlns="http://www.w3.org/1999/xhtml" style="padding:0; margin:0">

<!-- From https://davidshimjs.github.io/qrcodejs/ -->
<script type="application/ecmascript" src="view-qrcode.js"></script>
  
<script type="application/ecmascript">
	var elem = document.getElementById("qrcode");
	var qrcode = new QRCode(elem, {
		width : 100,
   		height : 100
		});
	function makeCode () {
		var elText = "'. $parameter_request .'";
		qrcode.makeCode(elText);
		}
	makeCode();
	</script>

</body>

</foreignObject>
</svg>'; ?>
