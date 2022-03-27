<?php
$_mysqli = null;
function db_connect_mysqli($config = null) {
  global $_mysqli;
  if (isset($_mysqli) && !empty($_mysqli)) {
    return $_mysqli;
  }
  if (empty($config)) {
    throw new Exception('No config provided.');
  }
  $mysqli = new mysqli($config['mysql_server'], $config['mysql_user'], $config['mysql_pass'], $config['db_name'], $config['mysql_port']);

  // check connection
  if ($mysqli->connect_errno) {
    throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
  }

  register_shutdown_function(function() use ($mysqli) {
    $mysqli->close();
  });
  $_mysqli = $mysqli;
  return $mysqli;
}


function db_query($query) {
  $mysqli = db_connect_mysqli();
  $result = $mysqli->query($query);
  if (!$result) {
    throw new Exception("MySQL error: " . $mysqli->error);
  }
  return $result;
}

function db_query_all($query) {
  $result = db_query($query);
  $rows = array();
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }
  $result->free();
  return $rows;
}

function db_query_one($query) {
  $result = db_query($query);
  $row = $result->fetch_assoc();
  $result->free();
  return $row;
}

function get_all_image_prompts() {
  $query = "SELECT * FROM image_prompts ORDER BY times_rated ASC";
  return db_query_all($query);
}

function get_images_for_user($user_id) {
  $query = "SELECT * FROM image_prompts_by_user WHERE survey_user_id = $user_id ORDER BY datestamp ASC";
  return db_query_all($query);
}

function get_user_id($prolific_id) {
  $query = "SELECT id FROM survey_users WHERE prolific_id = '$prolific_id'";
  $row = db_query_one($query);
  if (!$row) {
    return false;
  }
  return $row['id'];
}

function add_prolific_user($prolific_id) {
  $query = "INSERT INTO survey_users (prolific_id, start_time) VALUES ('$prolific_id', NOW())";
  db_query($query);
  return db_query_one("SELECT LAST_INSERT_ID() AS id");
}

function add_image_prompt($image_prompt) {
  $query = "INSERT INTO image_prompts (image_type, image_uri, times_rated) VALUES ('$image_prompt[image_type]', '$image_prompt[image_uri]', $image_prompt[times_rated])";
  db_query($query);
  return db_query_one("SELECT LAST_INSERT_ID() AS id");
}

?>