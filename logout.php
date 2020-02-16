<?php

session_start();
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['pseudo']);
unset($_SESSION['type_compte']);
session_destroy();
header("Location: index.php");

?>