<?php
// includes/config.php

define('DB_HOST','localhost');
define('DB_NAME','dulce_paraiso');
define('DB_USER','root');
define('DB_PASS','');

define('BASE_URL','/dulce-paraiso/public/');
define('UPLOAD_DIR', __DIR__ . '/../public/assets/uploads/');
define('UPLOAD_URL', BASE_URL . 'assets/uploads/');

session_start();
