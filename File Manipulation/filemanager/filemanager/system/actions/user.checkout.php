<?php

unset($_SESSION['s_userid']);
unset($_SESSION['s_role']);
session_unset();
session_destroy();
Utilities::redirect("index.php?action=main.login");

?>