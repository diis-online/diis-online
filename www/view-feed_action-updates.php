<? header("Content-type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://diis.online");
header("AMP-Access-Control-Allow-Source-Origin: https://diis.online");

if (!(empty($_POST))): ?>

{
  "items": [{
    "id": 1,
    "img": "/img/product1_640x426.jpg",
    "name": $_POST['paging']."dfdfg",
    "price": "1.99",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "attribution": "visualhunt",
    "url": "#",
    "color": "green"
  }, {
    "id": 2,
    "img": "/img/product2_640x426.jpg",
    "name": "dfgdfgg",
    "attribution": "visualhunt",
    "price": "0.99",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9734;",
    "url": "#",
    "color": "orange"
  }, {
    "id": 3,
    "img": "/img/product3_640x426.jpg",
    "name": "dfgdfg",
    "attribution": "visualhunt",
    "price": "1.50",
    "stars": "&#9733;&#9733;&#9733;&#9734;&#9734;",
    "url": "#",
    "color": "green"
  }, {
    "id": 4,
    "img": "/img/product4_640x426.jpg",
    "name": "dfgdfgdg",
    "attribution": "pixabay",
    "price": "1.50",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "url": "#",
    "color": "yellow"
  }, {
    "id": 5,
    "img": "/img/product5_640x408.jpg",
    "name": "Watdfgdfgddfgermelon",
    "attribution": "pixabay",
    "price": "4.50",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "url": "#",
    "color": "red"
  }, {
    "id": 6,
    "img": "/img/product6_640x424.jpg",
    "name": "Meldfgdfgdfgdfgdfgon",
    "attribution": "pixabay",
    "price": "3.50",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "url": "#",
    "color": "yellow"
  }]
}

<? exit; endif; ?>

{
  "items": [{
    "id": 1,
    "img": "/img/product1_640x426.jpg",
    "name": "Apple",
    "price": "1.99",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "attribution": "visualhunt",
    "url": "#",
    "color": "green"
  }, {
    "id": 2,
    "img": "/img/product2_640x426.jpg",
    "name": "Orange",
    "attribution": "visualhunt",
    "price": "0.99",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9734;",
    "url": "#",
    "color": "orange"
  }, {
    "id": 3,
    "img": "/img/product3_640x426.jpg",
    "name": "Pear",
    "attribution": "visualhunt",
    "price": "1.50",
    "stars": "&#9733;&#9733;&#9733;&#9734;&#9734;",
    "url": "#",
    "color": "green"
  }, {
    "id": 4,
    "img": "/img/product4_640x426.jpg",
    "name": "Banana",
    "attribution": "pixabay",
    "price": "1.50",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "url": "#",
    "color": "yellow"
  }, {
    "id": 5,
    "img": "/img/product5_640x408.jpg",
    "name": "Watermelon",
    "attribution": "pixabay",
    "price": "4.50",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "url": "#",
    "color": "red"
  }, {
    "id": 6,
    "img": "/img/product6_640x424.jpg",
    "name": "Melon",
    "attribution": "pixabay",
    "price": "3.50",
    "stars": "&#9733;&#9733;&#9733;&#9733;&#9733;",
    "url": "#",
    "color": "yellow"
  }]
}
<? // Simple API for latest in feed... 
exit;
$count_temp = random_number(1);

$counter_temp = 0;

while ($counter_temp < 5):
	$counter_temp++;
	$count_temp++;
	$json_result['items'][] = [
		"id" => $count_temp*random_number(2),
		"name" => "Article ".($count_temp+1),
		"body" => random_number(10)." ".random_number(8)." ".random_number(3)." ".random_number(5),
		];
	endwhile;


if (!(empty($_POST)) || !(empty($_REQUEST))):

	$_POST['page'] = $_POST['page'] ?? $_REQUEST['page'] ?? random_number(3);

	$count_temp = $_POST['page'] + 2;

	$json_result['next'] = "true";
	$json_result['page'] = $_POST['page']+1;

	endif;

echo json_encode($json_result);

?>
