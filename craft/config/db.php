<?php

/**
 * Database Configuration
 *
 * All of your system's database configuration settings go in here.
 * You can see a list of the default settings in craft/app/etc/config/defaults/db.php
 */

if(getenv("CLEARDB_DATABASE_URL")) {
  $url=parse_url(getenv("CLEARDB_DATABASE_URL"));

  return array(
    'server' => $url["host"],
    'user' => $url["user"],
    'password' => $url["pass"],
    'database' => substr($url["path"],1),
    'tablePrefix' => 'craft'
  );
} else {
  return array(
    '*' => array(
  		'tablePrefix' => 'craft',
      'database' => 'literary_calendar_craft'
    ),
    '.dev' => array(
      'server' => 'localhost',
      'user' => 'root',
      'password' => 'root'
    )
  );
}
