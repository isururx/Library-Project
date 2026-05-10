<?php

session_start();

session_unset();

session_destroy();

header("Location: /Library-Project/login.php");

exit();

?>