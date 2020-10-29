<?php

# error_reporting(E_ERROR);
# ini_set('display_errors', 1);

include_once('../inc/global.inc.php');
include("./config.php");

VideoChat::handleSSL();
VideoChat::checkLogin();
VideoChat::checkClassStarted();
VideoChat::savePresence();
VideoChat::displayVideoChat();

?>