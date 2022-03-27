<?php
require('inc/config.php');
require('inc/db.php');

$config = get_config();

db_connect_mysqli($config);

// ensure post request method and contains data
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid request method.');
}

// validate json data
$json = file_get_contents('php://input');
$data = json_decode($json, true);
if(!$data) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid json data.');
}

if(!isset($data['subject_id']) || !isset($data['session_id']) || !isset($data['study_id'])) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid json data.');
}

// no logic yet, just return random 50
$image_prompt_list = get_all_image_prompts();

shuffle($image_prompt_list);
$image_promts = array_slice($image_prompt_list, 0, 5);

echo json_encode($image_promts);
exit();


?>