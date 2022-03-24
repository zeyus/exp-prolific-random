<?php

echo "\n";
# make sure script runs in CLI
if (php_sapi_name() != 'cli') {
    die('This script must be run from the command line.');
}

function get_absolute_path($path) {
  $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
  $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
  $absolutes = array();
  foreach ($parts as $part) {
      if ('.' == $part) continue;
      if ('..' == $part) {
          array_pop($absolutes);
      } else {
          $absolutes[] = $part;
      }
  }
  return implode(DIRECTORY_SEPARATOR, $absolutes);
}

function get_config_file() {
  # get script path
  $script_path = dirname(__FILE__);
  $config_file = implode(DIRECTORY_SEPARATOR,
    array("{$script_path}", "..", "conf", "config.json")
  );
  return get_absolute_path($config_file);
}

# write JSON config file to ./conf
function write_config($config) {
    $config_file = get_config_file();
    $config_json = json_encode($config, JSON_PRETTY_PRINT);
    file_put_contents($config_file, $config_json);
}

function get_config_from_input() {
  # prompt user for mysql server
  echo "Enter MySQL server: [default: localhost] ";
  $mysql_server = trim(fgets(STDIN));

  # if empty use localhost
  if (empty($mysql_server)) {
      $mysql_server = 'localhost';
  }

  # prompt user for mysql username
  echo "Enter MySQL username: ";
  $mysql_user = trim(fgets(STDIN));

  # prompt user for mysql password
  echo "Enter MySQL password: ";
  $mysql_pass = trim(fgets(STDIN));

  # prompt user for database name
  echo "Enter database name: ";
  $db_name = trim(fgets(STDIN));

  while (true) {
    # prompt user for data directory
    echo "Enter data directory: ";
    $data_dir = trim(fgets(STDIN));
    # get absolute path
    $data_dir = get_absolute_path($data_dir);
    # create directory if it doesn't exist
    try {
      if (!file_exists($data_dir)) {
        mkdir($data_dir);
      }
    } catch (Exception $e) {
      echo "Error creating directory: " . $e->getMessage();
    }
    # check if directory is writeable
    if (!is_writable($data_dir)) {
      echo "Error: directory is not writeable.\n";
    } else {
      $data_dir = realpath($data_dir);
      break;
    }
  }
  return array(
    'mysql_server' => $mysql_server,
    'mysql_user' => $mysql_user,
    'mysql_pass' => $mysql_pass,
    'db_name' => $db_name,
    'data_dir' => $data_dir
  );
}

$cf = get_config_file();
if (file_exists($cf)) {
  echo "Config file already exists.\n";
  echo "Do you want to overwrite it? [y/n] ";
  $overwrite = trim(fgets(STDIN));
  if ($overwrite != 'y') {
    echo "Exiting.\n";
    exit;
  }
  if (!is_writable(get_config_file($cf))) {
    echo "Error: config file {$cf} is not writeable.\n";
    exit;
  }
}
$cfdir = dirname($cf);
if (!is_writable($cfdir)) {
  echo "Error: config file {$cf} is not writeable.\n";
  exit;
}

while (true) {
  $config = get_config_from_input();
  echo "\n\n###################################\n\n";
  echo "Config: \n";
  echo "MySQL server: {$config['mysql_server']}\n";
  echo "MySQL username: {$config['mysql_user']}\n";
  echo "MySQL password: {$config['mysql_pass']}\n";
  echo "Database name: {$config['db_name']}\n";
  echo "Data directory: {$config['data_dir']}\n";
  echo "Is this correct? [y/n] ";
  $answer = trim(fgets(STDIN));
  if ($answer == 'y') {
    echo "Writing config file...";
    write_config($config);
    echo "Done\n";
    break;
  } else {
    echo "Please try again.\n";
  }
}




