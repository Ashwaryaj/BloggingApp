<?php

require_once('config.php');
if ($config['display_error']) {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
}
require_once('dbConnect.php');

session_start();

require_once('functions.php');