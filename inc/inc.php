<?php
error_reporting(E_ALL);
session_start();

require_once('PHPMailer/PHPMailerAutoload.php');

function my_autoload($className) {
    require_once __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'Blackpearl99');
define('DB_NAME', 'study3');

define('CHAR_SET', 'UTF-8');
define('SMTP_SEC', 'ssl');
define('MAIL_HOST', 'smtp.yandex.ru');
define('MAIL_PORT', 465);
define('MAIL_USERNAME', 'al.oz2015@yandex.ru');
define('MAIL_PASSWORD', 'Paradise90');

spl_autoload_register('my_autoload');