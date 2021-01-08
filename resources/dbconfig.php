<?php
// used to get mysql database connection

require_once 'db.class.php';

//Fill these in with your MySQL info
DB::$host = 'localhost';
DB::$user = '';
DB::$password = '';
DB::$dbName = '';

DB::$error_handler = 'my_error_handler'; // runs on mysql query errors
DB::$nonsql_error_handler = 'my_error_handler'; // runs on library errors (bad syntax, etc)

function my_error_handler($params) {
  echo "Error: " . $params['error'] . "<br>\n";
  echo "Query: " . $params['query'] . "<br>\n";
  //return "errDB";
  die; // don't want to keep going if a query broke
}

