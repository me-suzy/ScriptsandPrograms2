<?PHP
        if ($cgress == "UP"){
        require("../engine.inc.php");
        }
        else {
        require("engine.inc.php");
        }
        require("brand.inc.php");
        $lang_select = mysql_query ("SELECT lang FROM Backend
                                WHERE valid LIKE '1'
                                                        limit 1
                                                        ");
        $lang_selector = mysql_fetch_array($lang_select);
        $lang_set = $lang_selector["lang"];
        if ($lang_set == ""){
        $lang_set = "english";
        }
        if ($cgress == "UP"){
        require("../lang_$lang_set.php");
        }
        else {
        require("lang_$lang_set.php");
        }
?>