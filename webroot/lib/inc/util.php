<?php
declare(strict_types=1);
$current_dir = dirname(__FILE__);
require_once($current_dir . '/config.php');
require_once($current_dir . '/db.php');
require_once($current_dir . '/user.php');



function prepare_images(ProlificUser &$user): void {
  $config = get_config();
  db_connect_mysqli($config);
  $db_user = get_user_id($user);

  if (!$db_user) {
    // if user doesn't exist we will create it and get some images
    $db_user = add_prolific_user($user);
  }

  $user->set_db_user_id((int) $db_user);
  
  get_image_list($user);


}

function get_image_list(ProlificUser &$user): void {
  $config = get_config();
  db_connect_mysqli($config);
  
  $image_list = get_images_for_user($user->get_db_user_id());
  // if there are no images, it means this is a new user
  if (empty($image_list)) {
    $image_list = prepare_images_for_user($user);
  } else {
    // filter out already rated images
    foreach ($image_list as $key => $image) {
      if ($image['rated'] !== null) {
        unset($image_list[$key]);
      }
    }
  }
  // this could still be empty, but that means the user has already rated all their images
  if (empty($image_list)) {
    return;
  }
  
  $user->add_images($image_list);

  // add the attention check images
  $n_attention_checks = $user->get_num_attention_checks();
  if ($n_attention_checks > 0) {
    $check_images = get_check_images($user->get_prompt_ids(), $n_attention_checks * $user->get_num_check_images());
    $user->add_check_images($check_images);
  }
}


?>