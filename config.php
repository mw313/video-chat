<?php

define('BEFORE_ALLOWED_TIME', '00:10:00');
define('AFTER_ALLOWED_TIME', '00:15:00');

function __autoload($class){
    include_once("./classes/{$class}.class.php");
}