<?php

function __autoload($class){
    include_once("./classes/{$class}.class.php");
}