<?php

include_once('../inc/global.inc.php');
include("./config.php");

VideoChat::handleSSL();
VideoChat::checkLogin();
VideoChat::checkClassStarted();
// VideoChat::savePresence();
// VideoChat::displayVideoChat();

?>