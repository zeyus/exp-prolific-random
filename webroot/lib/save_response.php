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

if(!isset($data['slider_creative']) ||
  !isset($data['slider_abstract']) ||
  !isset($data['slider_symmetric']) ||
  !isset($data['prompt_id']) ||
  !isset($data['user_prompt_id'])) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid json data.');
}


add_image_ratings_for_user($user, $data['prompt_id'], $data['user_prompt_id'], $data['slider_creative'], $data['slider_abstract'], $data['slider_symmetric']);
