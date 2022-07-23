<?php
require_once('inc/config.php');
require_once('inc/db.php');
require_once('inc/user.php');
require_once('inc/util.php');
session_start();
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

// $prolific_study_id = 'xxxxx'; // validate study id sent

if(!isset($data['subject_id']) || !isset($data['session_id']) || !isset($data['study_id'])) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid json data.');
}
// create and setup user
$user = new ProlificUser($data['subject_id'], $data['session_id'], $data['study_id']);
// set the number of images to show
$user->set_num_images(60);
// how many trials before an attention check?
$user->set_attention_checks_every(10);
// how many images per attention check?
$user->set_num_check_images(2);

prepare_images($user);

$_SESSION['user'] = $user;

$image_prompts = $user->get_image_list();
$attention_checks = $user->get_attention_check_images();

// shuffle($image_prompt_list);
// $image_promts = array_slice($image_prompt_list, 0, 5);
header('Content-Type: application/json');
echo json_encode([
  $image_prompts,
  $attention_checks,
  $user->get_attention_checks_every(),
]);

exit();


?>