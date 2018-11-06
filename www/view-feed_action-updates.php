<? // Simple API for latest in feed... 

$json_result['items'][] = [
		"name" => "Article 1",
		"body" => "sdfgsdglfkdnjfg sdkflg dlskjfg sfgsdjlfg sdfgklsdfjgsd sdfkg sdkflg dskf g sdfgklsdfgk dskfg dsfkgdfkg dfkg sdkfg sdfklgj . dsfklg ds",
		];

$json_result['items'][] = [
		"name" => "Article 2",
		"body" => "sdfgfgertw fgg mdsflkgoert werotpert ewrtk dldgf; sdfkgdfg sdfgkj dfg 3ert.",
		];

echo json_encode($json_result);

?>
