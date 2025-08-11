<?php
/**
* Arabic Queary Class
* Copyright (C) 2005 by Khaled Al-Shamaa.
* Filename: arquery.class.php
* Original  Author(s): Khaled Al-Sham'aa <khaled@al-shamaa.com>
* Purpose:  Build WHERE condition for SQL statement using MySQL REGEXP and Arabic lexial rules
*/

class ArQuery {
    /**
    * @return 1 if success, and 0 if fail
    * @param param :  value to be saved in $fields array [in XML format]
    * @desc setXmlFields : Setting value for $fields
    */
    function setXmlFields($xmlConfig) {
        $flag = 1;

        // Load XML document
        $xml = simplexml_load_string($xmlConfig);

        // Get fields array
        $this->fields = $xml->xpath('/table/field');

        // Error check!
        if(count($this->fields) == 0){ $flag = 0; }

        return $flag;
    }

    /**
    * @return 1 if success, and 0 if fail
    * @param param :  value to be saved in $fields array [in Array format]
    * @desc setArrFields : Setting value for $fields
    */
    function setArrFields($arrConfig) {
        $flag = 1;

        // Get fields array
        $this->fields = $arrConfig;

        // Error check!
        if(count($this->fields) == 0){ $flag = 0; }

        return $flag;
    }

    /**
    * @return 1 if success, and 0 if fail
    * @param param :  value to be saved in $fields array [in String format]
    * @desc setStrFields : Setting value for $fields
    */
    function setStrFields($strConfig) {
        $flag = 1;

        // Get fields array
        $this->fields = explode(",",$strConfig);

        // Error check!
        if(count($this->fields) == 0){ $flag = 0; }

        return $flag;
    }

    /**
    * @return 1 if success, and 0 if fail
    * @param param :  value to be saved in variable $mode
    * @desc setMode : Setting value for $mode that refer to search mode [0 for OR logic | 1 for AND logic]
    */
    function setMode($mode) {
        $flag = 1;

        // Set search mode [0 for OR logic | 1 for AND logic]
        $this->mode = $mode;

        // Error check!
        if(!isset($this->mode)){ $flag = 0; }

        return $flag;
    }

    /**
    * @return returns value of variable $mode
    * @desc getMode : Getting value for variable $mode that refer to search mode [0 for OR logic | 1 for AND logic]
    */
    function getMode() {
        // Get search mode value [0 for OR logic | 1 for AND logic]
        return $this->mode;
    }

    /**
    * @return returns value of $fields array in XML format
    * @desc getXmlFields : Getting value for $fields array in XML format
    */
    function getXmlFields() {
        $fields = "<fields>\n\t<field>";
        $fields .= implode("</field>\n\t<field>", $this->fields);
        $fields .= "</field>\n<fileds>";

        return $fields;
    }

    /**
    * @return returns value of $fields array in Array format
    * @desc getArrFields : Getting value for $fields Array in array format
    */
    function getArrFields() {
        $fields = $this->fields;

        return $fields;
    }

    /**
    * @return returns value of $fields array in String format
    * @desc getStrFields : Getting value for $fields array in String format
    */
    function getStrFields() {
        $fields = implode(",", $this->fields);

        return $fields;
    }

    /**
    * @return String of WHERE section in SQL statement
    * @param param :  String that user search for in the database
    * @desc getWhereCondition :  Build WHERE section of the SQL statement using defind lex's rules, search mode [AND | OR], and handle also phrases (inclosed by "") using normal LIKE condition to match it as it is.
    */
    function getWhereCondition($arg) {
        // Check if there are phrases in $arg should handle as it is
        $phrase = explode("\"", $arg);
        if (count($phrase)>2){
            // Re-init $arg variable (It will contain the rest of $arg except phrases).
            $arg = "";
            for($i=0; $i<count($phrase); $i++){
                if($i % 2 == 0 && $phrase[$i] != ""){
                   // Re-build $arg variable after restricting phrases
                   $arg .= $phrase[$i];
                }elseif($i % 2 == 1 && $phrase[$i] != ""){
                   // Handle phrases using reqular LIKE matching in MySQL
                   $this->wordCondition[] = $this->getWordLike($phrase[$i]);
                }
            }
        }

        // Handle normal $arg using lex's and regular expresion
        $words = explode(" ",$arg);

        foreach($words as $word){
            if($word != ""){ $this->wordCondition[] = $this->getWordRegExp($word); }
        }

        if($this->mode == 0){
           $sql = "(" . implode(") OR (", $this->wordCondition) . ")";
        }elseif($this->mode == 1){
           $sql = "(" . implode(") AND (", $this->wordCondition) . ")";
        }

        return $sql;
    }

    /**
    * @return String (SQL condition)
    * @param param :  String (one word) that you want to build a condition for
    * @desc getWordRegExp :  Search condition in SQL format for one word in all defind fields using REGEXP clause and lex's rules
    */
    function getWordRegExp($arg) {
        $arg = $this->lex($arg);
        $sql = "`" . implode("` REGEXP '$arg' OR `", $this->fields) . "` REGEXP '$arg'";

        return $sql;
    }

    /**
    * @return String (SQL condition)
    * @param param :  String (one word) that you want to build a condition for
    * @desc getWordRegExp :  Search condition in SQL format for one word in all defind fields using normal LIKE clause
    */
    function getWordLike($arg) {
        $sql = "`" . implode("` LIKE '$arg' OR `", $this->fields) . "` LIKE '$arg'";

        return $sql;
    }

    /**
    * @return String in a regex format to be used in MySQL query statement
    * @param param :  String of one word user want to search for
    * @desc lex :  Implementing various regex rules based on Arabic lexial rules
    */
    function lex($arg) {
        $length = strlen($arg);

        $arg = preg_replace("/ø|ó|ð|õ|ñ|ö|ò|ú/", "(ø|ó|ð|õ|ñ|ö|ò|ú)?", $arg);

        if($length > 5){ $arg = preg_replace("/Êíä/", "(Êíä|É)", $arg); }
        if($length > 4){ $arg = preg_replace("/íä/", "(íä)?", $arg); }
        if($length > 4){ $arg = preg_replace("/æä/", "(æä)?", $arg); }
        if($length > 4){ $arg = preg_replace("/Çä/", "(Çä)?", $arg); }
        if($length > 3){ $arg = preg_replace("/ÇÊ/", "(É)", $arg); }

        $arg = preg_replace("/(É|å)/", "(É|å)", $arg);
        $arg = preg_replace("/É/", "(É|ÇÊ|Ê)?", $arg);

        $arg = preg_replace("/Çá/", "(Çá)?", $arg);
        $arg = preg_replace("/(Ç|Ã|Å|Â)/", "(Ç|Ã|Å|Â)", $arg);

        $arg = preg_replace("/(í|ì)/", "(í|ì)", $arg);
        $arg = preg_replace("/(Æ|ìÁ)/", "(Æ|ìÁ)", $arg);

        return $arg;
    }

    /**
    * @desc __construct :  Inside constructor
    */
    function __construct() {

    }

    /**
    * @desc __destruct :  Destroying object
    */
    function __destruct() {

    }

    /**
    * @desc __clone :  Object is being cloned
    */
    function __clone() {

    }
}
?>
