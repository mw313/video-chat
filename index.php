<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);

include_once('../inc/global.inc.php');
include_once('./classes/Date.class.php');
include_once('./classes/Reports.class.php');

Display::display_header();
Reports::displayMenu();
Reports::routing();
Display::display_footer();

?>