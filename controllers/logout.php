<?php
session_start();
session_destroy();
header("Location: ../FirstPage.php");
?>