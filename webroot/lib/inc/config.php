<?php


function get_config() {
  global $_conf;
  if (isset($_conf) && !empty($_conf)) {
    return $_conf;
  }
  # read in conf/config.json
  $config_file = implode(DIRECTORY_SEPARATOR,
    array(dirname(__FILE__), "..", "..", "..", "conf", "config.json")
  );
  $config_json = file_get_contents($config_file);
  $config = json_decode($config_json, true);
  $_conf = $config;
  return $config;
}


?>