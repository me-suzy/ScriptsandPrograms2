<?php

require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-lib_inc-common.php");

?>
         
body,td  {
   font-family: <?php echo  $NETJUKE_SESSION_VARS["font_face"] ?>;
   font-size: <?php echo  $NETJUKE_SESSION_VARS["font_size"] ?>px;
}

a:hover {
   text-decoration: none;
}
         
input, select, textarea  {
   font-family: <?php echo  $NETJUKE_SESSION_VARS["font_face"] ?>;
   font-size: <?php echo  $NETJUKE_SESSION_VARS["font_size"] - 1 ?>px;
}

.border {
   background: #<?php echo  $NETJUKE_SESSION_VARS["td_border"] ?>;
}

.no-border {
   border: none;
}
.header {
   background: #<?php echo  $NETJUKE_SESSION_VARS["td_header"] ?>;
   color: #<?php echo  $NETJUKE_SESSION_VARS["td_header_fc"] ?>;
   font-family: <?php echo  $NETJUKE_SESSION_VARS["font_face"] ?>;
   font-size: <?php echo  $NETJUKE_SESSION_VARS["font_size"] ?>px;
   font-weight: bold;
}

a.header {
   color: #<?php echo  $NETJUKE_SESSION_VARS["td_header_fc"] ?>;
}

.content {
   background: #<?php echo  $NETJUKE_SESSION_VARS["td_content"] ?>;
   font-family: <?php echo  $NETJUKE_SESSION_VARS["font_face"] ?>;
   font-size: <?php echo  $NETJUKE_SESSION_VARS["font_size"] ?>px;
}

.input_content {
    background-color:#FFFFFF;
    color: #333333;
    border:1px #<?php echo  $NETJUKE_SESSION_VARS["td_border"] ?> inset;
    padding-top: 0.1em;
    padding-left: 0.1em;
    padding-right: 0.1em;
}

.btn_content {
    background-color:#<?php echo  $NETJUKE_SESSION_VARS["td_header"] ?>;
    color: #<?php echo  $NETJUKE_SESSION_VARS["td_header_fc"] ?>;
    font-weight: bold;
    border:1px #<?php echo  $NETJUKE_SESSION_VARS["td_border"] ?> outset;
    padding-top: 0.1em;
    padding-bottom: 0.1em;
    padding-left: 0.2em;
    padding-right: 0.2em;
}

.btn_content:hover {
    background-color:#<?php echo  $NETJUKE_SESSION_VARS["td_content"] ?>;
    color: #<?php echo  $NETJUKE_SESSION_VARS["text"] ?>;
    font-weight: bold;
    border:1px #<?php echo  $NETJUKE_SESSION_VARS["td_border"] ?> inset;
    padding-top: 0.1em;
    padding-bottom: 0.1em;
    padding-left: 0.2em;
    padding-right: 0.2em;
}

.btn_header {
    background-color:#<?php echo  $NETJUKE_SESSION_VARS["td_content"] ?>;
    color: #<?php echo  $NETJUKE_SESSION_VARS["text"] ?>;
    font-weight: bold;
    border:1px #<?php echo  $NETJUKE_SESSION_VARS["td_border"] ?> outset;
    padding-top: 0.1em;
    padding-bottom: 0.1em;
    padding-left: 0.2em;
    padding-right: 0.2em;
}

.btn_header:hover {
    background-color:#<?php echo  $NETJUKE_SESSION_VARS["td_header"] ?>;
    color: #<?php echo  $NETJUKE_SESSION_VARS["td_header_fc"] ?>;
    font-weight: bold;
    border:1px #<?php echo  $NETJUKE_SESSION_VARS["td_border"] ?> inset;
    padding-top: 0.1em;
    padding-bottom: 0.1em;
    padding-left: 0.2em;
    padding-right: 0.2em;
}

.error-row {
   background: #FFCCCC;
   font-family: <?php echo  $NETJUKE_SESSION_VARS["font_face"] ?>;
   font-size: <?php echo  $NETJUKE_SESSION_VARS["font_size"] ?>px;
}
