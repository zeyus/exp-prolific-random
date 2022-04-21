<?php
require('./config.php');
require('./db.php');
require('./user.php');

declare(strict_types=1);

function prepare_images(ProlificUser &$user, $n_img = 50): void {
  $config = get_config();
  db_connect_mysqli($config);
  $db_user = get_user_id($user);

  if (!$db_user) {
    // if user doesn't exist we will create it and get some images
    $db_user = add_prolific_user($user);
  }

  $user->set_db_user_id($db_user);
  
  // $image_list = get_image_list($user);

}




?>