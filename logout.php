<?php
session_destroy();
session_start();
unset($_SESSION["user"]);
header("Location: index.php");
?>