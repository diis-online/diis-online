<? header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

$count_temp = random_number(1);

$counter_temp = 0;

$max_temp = 2;
if (!(empty($_POST))): $max_temp = '3'; endif;

while ($counter_temp < $max_temp):
	$counter_temp++;
	$count_temp++;
	$json_result['items'][] = [
		"id" => $count_temp*random_number(2),
		"name" => "Article ".($count_temp+1),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];
	endwhile;

if (!(empty($_POST))):

	$count_temp = $_POST['page'] + 2;

	$json_result['morepages'] = "false";
//	$json_result['page'] = $_POST['page']+1;

	endif;

echo json_encode($json_result);

?>
