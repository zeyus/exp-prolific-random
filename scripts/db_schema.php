<?php

// get mysql username and password from env
$mysql_user = getenv('MYSQL_USER');
$mysql_pass = getenv('MYSQL_PASSWORD');

$mysql_server = 'localhost';
$db_name = 'survey_data';

// schema
$schema = <<<EOD
/* prolific users to get unique results */
CREATE TABLE IF NOT EXISTS `survey_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prolific_id` varchar(255) NOT NULL
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* image_prompts: images to be displayed in the survey */
CREATE TABLE IF NOT EXISTS `image_prompts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_uri` varchar(255) NOT NULL,
  `image_type` varchar(255) NOT NULL,
  `image_order` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* image_prompts_by_user: linking table with datestamp and image_prompts */
CREATE TABLE IF NOT EXISTS `image_prompts_by_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_user_id` int(11) NOT NULL,
  `image_prompt_id` int(11) NOT NULL,
  `datestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

EOD;

// connect to mysql
$mysqli = new mysqli($mysql_server, $mysql_user, $mysql_pass, $db_name);

// check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// create database
if (!$mysqli->query($schema)) {
    echo "Error creating database: (" . $mysqli->errno . ") " . $mysqli->error;
}
