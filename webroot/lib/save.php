<?php
require('inc/config.php');

$config = get_config();


// ensure post request method and contains data
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid request method.');
}


$post_data = json_decode(file_get_contents('php://input'), true);

if(!$post_data) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid json data.');
}

$data = $post_data['filedata'];
$file = uniqid("session-");
$name = "{$config['data_dir']}/{$file}.csv"; 
file_put_contents($name, $data);
?>