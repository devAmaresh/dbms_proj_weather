<?php
$conn=mysqli_connect("localhost", "root", "", "dbms", 3306);
if(mysqli_connect_errno()){
echo '<script>alert("Connection Fail")</script>';
}
