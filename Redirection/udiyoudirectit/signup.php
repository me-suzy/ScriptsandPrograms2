<?php 

// uDi - You Direct It, written by, and copyright Mike Cheesman.

require "config.php";
include "$header";
if (!$do || $do=="") {
signup();
} else if ($do == signup) {
creaccnt();
} else {
signup();
}
include "$footer"; ?>