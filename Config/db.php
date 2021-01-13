<?php

//Default values for Ammps
define('DB_SERVER' ,'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'mysql');
define('DB_NAME','directory');


//Connecting to  DataBase

$connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

//Check connection
if($connection == false) {
  die('ERROR: Could not connect.' . mysqli_connect_error());
} elseif ($connection == true) {
//  echo "everything is looking GREAT";
}
 ?>
