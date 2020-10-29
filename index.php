<?php
// error_reporting(E_ERROR);
// ini_set('display_errors', 1);

include_once('../inc/global.inc.php');
include("./config.php");

Display::display_header();
Reports::displayMenu();
Reports::routing();
Display::display_footer();

?>