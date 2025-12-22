<?php
// Redirect to the central logout handler in /user to avoid 404s
header("Location: /php_project/user/logout.php");
exit();
