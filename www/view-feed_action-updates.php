<? // Simple API for latest in feed... 

$json_result['items'][] = [
		"name" => "Article 1",
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

$json_result['items'][] = [
		"name" => "Article 2",
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

$json_result['items'][] = [
		"name" => "Article 3",
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

$json_result['items'][] = [
		"name" => "Article 4",
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];

echo json_encode($json_result);

?>
