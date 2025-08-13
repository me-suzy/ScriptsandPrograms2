        <?

// êîíôèãóðàöèÿ ÁÄ
        $dbhost = "localhost";
        $dbuser = "admin";
        $dbpass = "root";

        $dbsafe = "1";
//################################################################################
if(!isset($dbworks)) {
        $dbworks="";
}
switch($dbworks) {
        default:
        if($input_method == "3") {
                $dbname = $HTTP_GET_VARS["dbname"];
                $dbtable = $HTTP_GET_VARS["dbtable"];
                $dbfield = $HTTP_GET_VARS["dbfield"];
                $dbrecord = $HTTP_GET_VARS["dbrecord"];
                $dbai = $HTTP_GET_VARS["dbai"];
                $dbreturn = $HTTP_GET_VARS["dbreturn"];

                if(isset($HTTP_GET_VARS["dbsafe"])) {
                        $dbsafe = $HTTP_GET_VARS["dbsafe"];
                }
        }

        if($input_method == "4") {
                $dbname = $HTTP_POST_VARS["dbname"];
                $dbtable = $HTTP_POST_VARS["dbtable"];
                $dbrecord = $HTTP_POST_VARS["dbrecord"];
                $dbfield = $HTTP_POST_VARS["dbfield"];
                $dbai = $HTTP_POST_VARS["dbai"];
                $dbreturn = $HTTP_POST_VARS["dbreturn"];

                if(isset($HTTP_POST_VARS["dbsafe"])) {
                        $dbsafe = $HTTP_POST_VARS["dbsafe"];
                }
        }

break;

        case "init":

                $dbname = $HTTP_GET_VARS["dbname"];
                $dbtable = $HTTP_GET_VARS["dbtable"];
                $dbfield = $HTTP_GET_VARS["dbfield"];
                $dbrecord = $HTTP_GET_VARS["dbrecord"];
                $dbai = $HTTP_GET_VARS["dbai"];
                $dbreturn = $HTTP_GET_VARS["dbreturn"];

                if(isset($HTTP_GET_VARS["dbsafe"])) {
                        $dbsafe = $HTTP_GET_VARS["dbsafe"];
                }
                if($dbrecord == "") {
                        return;
                } else {

                        if($db = mysql_connect($dbhost,$dbuser,$dbpass)) {
                                if(mysql_select_db($dbname,$db)) {
                                        if($query = mysql_query("SELECT ".$dbfield." AS thatsit FROM ".$dbtable." WHERE ".$dbai."=".$dbrecord."",$db)) {
                                                $result = mysql_fetch_array($query);

                                                // return stripslashes($result[thatsit]);
                                                echo stripslashes($result[thatsit]);

                                        } else { echo "<script language=\"Javascript\">alert('Îøèáêà: Cannot perform the query check name of the table, field, record number and name of autoincremented field.');</script>";
                                        }

                                } else {
                                        echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó íàéòè áàçó äàííûõ ".$dbname.".');</script>";
                                }

                        } else {
                                echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó ñîåäèíèòüñÿ ñ áàçîé äàííûõ, ïðîâåðüòå ïðàâèëüíîñòü óêàçàíèÿ èìåíè è ïàðîëÿ.');</script>";
                        }

                        mysql_close($db);
                }

break;

case "save":
        // input is following $dbhost,$dbuser,$dbpass,$dbname,$dbtable,$dbfield,$dbrecord,$dbsafe,$dbreturn,$edited

                $edited = $HTTP_POST_VARS["EditorValue"];

                if($db = mysql_connect($dbhost,$dbuser,$dbpass)) {
                        if(mysql_select_db($dbname,$db)) {

                                if($dbsafe == "1") {
                                        $edited = addslashes($edited);
                                }
                                if($dbrecord == "") {

                                        if($query = mysql_query("INSERT INTO ".$dbtable." (".$dbfield.") VALUES ('".$edited."')",$db)) {

                                                echo "<script language=\"Javascript\">window.location = '".$dbreturn."';</script>";

                                        } else {
                                                echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó ñîçäàòü íîâûé îò÷åò ...');</script>";
                                        }

                                } else {

                                        if($query = mysql_query("UPDATE ".$dbtable." SET ".$dbfield."='".$edited."' WHERE ".$dbai."=".$dbrecord."",$db)) {

                                                echo "<script language=\"Javascript\">window.location = '".$dbreturn."';</script>";

                                        } else {
                                                echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó ìîäåðíèçèðîâàòü ID=".$dbrecord." ...');</script>";
                                        }
                                }


                        } else {
                                echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó íàéòè áàçó äàííûõ ".$dbname.".');</script>";
                        }

                } else {
                        echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó ñîåäèíèòüñÿ ñ áàçîé äàííûõ, ïðîâåðüòå ïðàâèëüíîñòü óêàçàíèÿ èìåíè è ïàðîëÿ.');</script>";
                }

                mysql_close($db);
break;
}
?>