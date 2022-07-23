<?php
require_once('inc/config.php');
require_once('inc/db.php');
require_once('inc/user.php');
require_once('inc/util.php');


session_start();

if(!isset($_SESSION['user'])) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid request method.');
}

$user = $_SESSION['user'];

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

$validated = false;
if(isset($data['slider_creative']) &&
  isset($data['slider_abstract']) &&
  isset($data['slider_symmetric']) &&
  isset($data['prompt_id']) &&
  isset($data['user_prompt_id']) &&
  isset($data['rt'])) {
  $validated = true;
  add_image_ratings_for_user($user, (int)$data['prompt_id'], (int)$data['user_prompt_id'], (int)$data['slider_creative'], (int)$data['slider_abstract'], (int)$data['slider_symmetric'], (int)$data['rt']);
}

if(isset($data['prompt_id']) &&
  isset($data['check_prompt_ids']) &&
  is_array($data['check_prompt_ids']) &&
  isset($data['check_response_id']) &&
  isset($data['rt'])) {
  $validated = true;
  // convert all check_prompt_ids to ints
  $check_prompt_ids = array_map('intval', $data['check_prompt_ids']);
  add_attention_check_response_for_user($user, (int)$data['prompt_id'], $check_prompt_ids, (int)$data['check_response_id'], (int)$data['rt']);
}

if(!$validated) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid json data.');
}
