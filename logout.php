<?php

// get current session
session_start();

// remove all session variables
session_unset();

// end current session
session_destroy();

// redirect page to signin screen
header("Location: signin.html");
exit();
?>