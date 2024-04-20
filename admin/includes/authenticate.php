<?php
if (!isset($_SESSION['isLoggedA']) || $_SESSION['isLoggedA'] !== true) {
 header("Location: login.php");
 exit;
}