<?php
$_mysqli = null;
function db_connect_mysqli(array &$config = null): mixed {
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


function db_query(string $query, bool $insert = false): mixed {
  $mysqli = db_connect_mysqli();
  $result = $mysqli->query($query);
  if (!$result) {
    throw new Exception("MySQL error: " . $mysqli->error);
  }
  if ($insert) {
    return $mysqli->insert_id;
  }
  return $result;
}

function db_query_all(string $query): array {
  $result = db_query($query);
  $rows = array();
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }
  $result->free();
  return $rows;
}

function db_query_one(string $query): array|null {
  $result = db_query($query);
  $row = $result->fetch_assoc();
  $result->free();
  return $row;
}

function get_all_image_prompts(int $limit = null): array {
  // randomly order the prompts but show least rated items first.
  $strLimit = '';
  if ($limit) {
    $strLimit = " LIMIT $limit";
  }
  $query = "SELECT id as prompt_id FROM image_prompts ORDER BY times_prepared ASC, times_rated ASC, RAND()$strLimit";
  return db_query_all($query);
}

function get_images_for_user(int $user_id): array {
  $query = "SELECT image_prompts_by_user.id as user_prompt_id, image_prompts_by_user.rated as rated, image_prompts.id as prompt_id, image_prompts.image_uri as image_uri FROM image_prompts_by_user INNER JOIN image_prompts ON image_prompts.id=image_prompts_by_user.image_prompt_id WHERE survey_user_id = $user_id ORDER BY created ASC";
  return db_query_all($query);
}

function get_check_images(array $exclude, int $limit): array {
  $query = "SELECT id as prompt_id, image_uri FROM image_prompts WHERE id NOT IN (" . implode(',', $exclude) . ") ORDER BY RAND() LIMIT $limit";
  return db_query_all($query);
}

function add_images_for_user(ProlificUser &$user, array $images): void {
  $user_id = $user->get_db_user_id();
  $query = "INSERT INTO image_prompts_by_user (survey_user_id, image_prompt_id, created) VALUES ";
  $values = [];
  $image_ids = [];
  foreach ($images as $image) {
    $values[] = "('{$user_id}', '{$image['prompt_id']}', NOW())";
    $image_ids[] = $image['prompt_id'];
  }
  $query .= implode(',', $values);
  db_query($query, true);
  // now update times_prepared for the added images
  $query = "UPDATE image_prompts SET times_prepared = times_prepared + 1 WHERE id IN (" . implode(',', $image_ids) . ")";
  db_query($query);
}

function add_image_ratings_for_user(ProlificUser &$user, int $prompt_id, int $user_prompt_id, int $creative, int $abstract, int $symmetry, int $rt) {
  $query = "UPDATE image_prompts_by_user SET rated = NOW(), rating_creative = {$creative}, rating_abstract = {$abstract}, rating_symmetry = {$symmetry}, rt = {$rt} WHERE survey_user_id = {$user->get_db_user_id()} AND id = {$user_prompt_id} AND image_prompt_id = {$prompt_id}";
  db_query($query);
  $query = "UPDATE image_prompts SET times_rated = times_rated + 1 WHERE id = {$prompt_id}";
  db_query($query);
}

function add_attention_check_response_for_user($user, int $correct_response, array $options, int $response, int $rt): void {
  $query = "INSERT INTO attention_check_responses (survey_user_id, correct_response, options, response, created, rt) VALUES ('{$user->get_db_user_id()}', '{$correct_response}', '" . implode(',', $options) . "', '{$response}', NOW(), '{$rt}')";
  db_query($query, true);
}

function prepare_images_for_user(ProlificUser &$user): array {
  $images = get_all_image_prompts($user->get_num_images());
  add_images_for_user($user, $images);
  return get_images_for_user($user->get_db_user_id());
}

function get_user_id(ProlificUser &$user) {
  $query = "SELECT id FROM survey_users WHERE prolific_id = '{$user->get_prolific_subject_id()}' AND session_id = '{$user->get_prolific_session_id()}' AND study_id = '{$user->get_prolific_study_id()}'";
  $row = db_query_one($query);
  if (!$row) {
    return false;
  }
  return $row['id'];
}

function add_prolific_user(ProlificUser &$user): int {
  $query = "INSERT INTO survey_users (prolific_id, session_id, study_id, start_time) VALUES ('{$user->get_prolific_subject_id()}', '{$user->get_prolific_session_id()}', '{$user->get_prolific_study_id()}', NOW())";
  return db_query($query, true);
}

function add_image_prompt_dummy(array $image_prompt) {
  $query = "INSERT INTO image_prompts (image_type, image_uri, times_rated) VALUES ('$image_prompt[image_type]', '$image_prompt[image_uri]', $image_prompt[times_rated])";
  db_query($query, true);
  return db_query($query, true);
}

function add_image_prompt(int $id, string $image_uri) {
  $query = "INSERT INTO image_prompts (id, image_type, image_uri, times_rated) VALUES ($id, 'shape', '$image_uri', 0) ON DUPLICATE KEY UPDATE image_type='shape', image_uri = '$image_uri', times_rated=0";
  return db_query($query, true);
}





?>