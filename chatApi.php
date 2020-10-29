<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);
include_once('../inc/global.inc.php');

Tools::login_user($_REQUEST['user_id']);

header("Location: chat.php?cidReq=".$_REQUEST['code']);
