<?php
require_once("config.php");
// destroying session and redirecting to the start page
session_start();
session_destroy();
header("Location: $start_page");
?>