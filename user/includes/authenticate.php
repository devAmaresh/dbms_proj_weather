<?php
if (!isset($_SESSION['isLoggedU']) || $_SESSION['isLoggedU'] !== true) {
 header("Location: login.php");
 exit;
}