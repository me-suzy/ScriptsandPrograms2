<?php
include("common.php");
if (!empty($_GET['fid']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
    $fid = $_GET['fid'];

    switch ($action) {
        case "top" :
            $top = 1;
            $result = db_query("select forumid from {$tableprefix}tblforum where sticky=1");
            $num_rows = db_num_rows($result);
            if ($num_rows > 0) $top = $num_rows + 1;

            $result1 = db_query("update {$tableprefix}tblforum set position=NULL where position=" . intval($top));
            $result2 = db_query("update {$tableprefix}tblforum set position=$top where forumid=" . intval($fid));
            break;

        case "bottom" :
            $result = db_query("select count(*) from {$tableprefix}tblforum");
            $row = db_fetch_array($result);
            $max = $row[0];

            $result2 = db_query("update {$tableprefix}tblforum set position=NULL where position=" . intval($max));
            $result3 = db_query("update {$tableprefix}tblforum set position=" . intval($max) . " where forumid=" . intval($fid));
            break;

        case "both" :
            if ($_GET["pos"]) {
                $pos = $_GET["pos"];

                $min = 1;
                $result = db_query("select forumid from {$tableprefix}tblforum where sticky=1");
                $num_rows = db_num_rows($result);
                if ($num_rows > 0) $min = $num_rows + 1;

                $result = db_query("select count(*) from {$tableprefix}tblforum");
                $row = db_fetch_array($result);
                $max = $row[0];

                if ($pos >= $min && $pos <= $max) {
                    $result3 = db_query("update {$tableprefix}tblforum set position=NULL where position=" . intval($pos));

                    $result4 = db_query("update {$tableprefix}tblforum set position=" . intval($pos) . " where forumid=" . intval($fid));
                } 
            } 
            break;

        case "kill" :
            $result = db_query("update {$tableprefix}tblforum set position=NULL where forumid=" . intval($fid));
            break;

        case "stick" :
            $result = db_query("update {$tableprefix}tblforum set position=position+1");

            $result1 = db_query("update {$tableprefix}tblforum set position=1, sticky=1 where forumid=" . intval($fid));
            break;

        case "unstick" :
            $result = db_query("select position from {$tableprefix}tblforum where forumid=" . intval($fid));
            $row = db_fetch_array($result);
            $pos = $row[0];

            $result1 = db_query("update {$tableprefix}tblforum set position=position-1 where position > " . intval($pos));

            $result2 = db_query("update {$tableprefix}tblforum set position=NULL, sticky=0 where forumid=" . intval($fid));
    } 
    header('location:admin.php');
} 

?>
<?php ob_end_flush();

?>
