<?
if(!isset($fileworks)) {
         $fileworks = "";
}

switch($fileworks) {

        default:
        if($input_method == "5") {
                $filename = $HTTP_GET_VARS["filename"];
                $filereturn = $HTTP_GET_VARS["filereturn"];
        }
        break;

        case "init":
                $filename = $HTTP_GET_VARS["filename"];
                $filereturn = $HTTP_GET_VARS["filereturn"];
                        if($fp = fopen("../../".$filename,"r")) {

                                $filecontent = "";
                                while(!feof($fp)) {
                                        $filecontent .= fgets($fp,4096);
                                }

                                fclose($fp);
                                echo stripslashes($filecontent);

                        } else {
                                echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó îòêðûòü ôàéë ".$filename.".');</script>";
                        }

        break;

case "save":
        // input is following $dbhost,$dbuser,$dbpass,$dbname,$dbtable,$dbfield,$dbrecord,$dbsafe,$dbreturn,$edited

                $edited = stripslashes($HTTP_POST_VARS["EditorValue"]);
                $filename = $HTTP_POST_VARS["filename"];
                $filereturn = $HTTP_POST_VARS["filereturn"];

                        if($fp = fopen($filename,"w+")) {

                                if(fwrite($fp,$edited)) {

                                        echo "<script language=\"Javascript\">window.location = '".$filereturn."';</script>";

                                } else {
                                        echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó çàïèñàòü â ôàéë. Ïðîâåðüòå ðàçðåøåíèå, ôàéë äîëæåí èìåòü CHMOD 0777');</script>";
                                }


                        fclose($fp);
                        } else {
                                echo "<script language=\"Javascript\">alert('Îøèáêà: Íå ìîãó îòêðûòü ôàéë ".$filename.".');</script>";
                        }
break;
}
?>