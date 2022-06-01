<?php

echo "\n";
# make sure script runs in CLI
if (php_sapi_name() != 'cli') {
    die('This script must be run from the command line.');
}

# read in conf/config.json
$config_file = implode(DIRECTORY_SEPARATOR,
  array(dirname(__FILE__), "..", "conf", "config.json")
);
$config_json = file_get_contents($config_file);
$config = json_decode($config_json, true);


// schema
$schema = array(
  // create database
  "CREATE DATABASE IF NOT EXISTS {$config['db_name']};",

  "USE {$config['db_name']};",

  /* prolific users to get unique results */
  "CREATE TABLE IF NOT EXISTS `survey_users` (`id` int(11) NOT NULL AUTO_INCREMENT, `prolific_id` varchar(255) NOT NULL, `session_id` varchar(255) NOT NULL, `study_id` varchar(255) NOT NULL, `start_time` datetime NOT NULL, `end_time` datetime NULL, PRIMARY KEY (`id`), INDEX (`prolific_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
  /* image_prompts: images to be displayed in the survey */
  "CREATE TABLE IF NOT EXISTS `image_prompts` (`id` int(11) NOT NULL AUTO_INCREMENT, `image_uri` varchar(255) NOT NULL, `image_type` varchar(255) NOT NULL, `times_rated` int(11) NULL, PRIMARY KEY (`id`), INDEX (`times_rated`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
  /* image_prompts_by_user: linking table with datestamp and image_prompts */
  "CREATE TABLE IF NOT EXISTS `image_prompts_by_user` (`id` int(11) NOT NULL AUTO_INCREMENT, `survey_user_id` int(11) NOT NULL, `image_prompt_id` int(11) NOT NULL, `created` datetime NOT NULL, `rated` datetime NULL, PRIMARY KEY (`id`), INDEX(`survey_user_id`, `image_prompt_id`, `rated`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);

// connect to mysql
$mysqli = new mysqli($config['mysql_server'], $config['mysql_user'], $config['mysql_pass'], null, $config['mysql_port']);

// check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// create database
foreach ($schema as $query) {
  $mysqli->query($query);
  if ($mysqli->errno) {
      echo "Failed to create database: (" . $mysqli->errno . ") " . $mysqli->error;
  }
}


// do {
//     if ($result = $mysqli->store_result()) {
//       var_dump($result->fetch_all(MYSQLI_ASSOC));
//         $result->free();
//     }
// } while ($mysqli->next_result());
$mysqli->commit();
$mysqli->close();