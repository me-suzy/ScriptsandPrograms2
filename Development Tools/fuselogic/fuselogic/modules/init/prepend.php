<?php

SingletonQueue();
Queue('tools/_include_path');
Queue('tools/_time');
Queue('setting/_setting');
Queue('adodb/_main');
Queue('adodb/_session');
Queue('aco/_check');
Queue('cache/_start');

?>