<?php

require(dirname(__FILE__) . '/../webroot/lib/inc/config.php');
require(dirname(__FILE__) . '/../webroot/lib/inc/db.php');
$config = get_config();
db_connect_mysqli($config);

print("Clearing dummy image prompts\n");
// delete dummy data
db_query("DELETE FROM image_prompts WHERE image_type != 'shape'");

// read in CSV from ../data/touchscreen_shapes.csv
$csv_file = dirname(__FILE__) . '/../data/touchscreen_shapes.csv';
$csv_data = file_get_contents($csv_file);
$csv_lines = explode("\n", $csv_data);
print("Adding prompts from csv file...\n");
$n_success = 0;
$n_fail = 0;
$n = 0;
foreach ($csv_lines as $csv_line) {
  $n++;
  // skip header
  if ($n == 1) {
    continue;
  }
  $image_id = trim($csv_line);
  if (empty($image_id)) {
    continue;
  }
  // find png file matching {$image_id}_{0-9}

  $image_file = glob(dirname(__FILE__) . '/../webroot/img/survey/' . $image_id . '_*.png');
  if (empty($image_file)) {
    $n_fail++;
    print("WARNING: no image file found for {$image_id}\n");
    continue;
  }
  $image_file = $image_file[0];
  $image_uri = 'img/survey/' . basename($image_file);
  // print("Adding image prompt id {$image_id} ({$image_uri})\n");
  add_image_prompt($image_id, $image_uri);
  $n_success++;
  
}

print("Added {$n_success} image prompts\n");
print("Failed to add {$n_fail} image prompts\n");

?>