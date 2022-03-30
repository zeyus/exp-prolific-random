<?php

require(dirname(__FILE__) . '/../webroot/lib/inc/config.php');
require(dirname(__FILE__) . '/../webroot/lib/inc/db.php');
$config = get_config();
db_connect_mysqli($config);

# populate with fake filenames
for ($i = 1; $i <= 3000; $i++) {
  $image_prompt = array(
    'times_rated' => rand(0, 10),
    'image_type' => 'dummy',
    'image_uri' => 'https://placeholder.pics/svg/800/DEDEDE/555555/Image ID: ' . $i,
  );
  print("Adding dummy image prompt id {$i}\n");
  add_image_prompt_dummy($image_prompt);
}


?>