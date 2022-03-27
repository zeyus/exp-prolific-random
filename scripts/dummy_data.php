<?php

require(dirname(__FILE__) . '/../webroot/lib/inc/config.php');
require(dirname(__FILE__) . '/../webroot/lib/inc/db.php');
$config = get_config();
db_connect_mysqli($config);

# populate with fake filenames
for ($i = 1; $i <= 3000; $i++) {
  $image_prompt = array(
    'times_rated' => rand(0, 10),
    'image_type' => 'image',
    'image_uri' => 'http://example.com/image_' . $i . '.jpg',
  );
  print("Adding dummy image prompt id {$i}\n");
  add_image_prompt($image_prompt);
}


?>