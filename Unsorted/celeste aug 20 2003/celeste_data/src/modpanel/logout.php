<?php

$session->destroy();

mod_success_redirect('You are now logged out.', 'index.php?fid='.$forumid.'&prog=topic::list');