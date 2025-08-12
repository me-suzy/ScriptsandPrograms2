<?php

//Include Files @0-6CA7C540
include(RelativePath . "/Classes.php");
include(RelativePath . "/db_mysql.php");
//End Include Files

//Initialize Common Variables @0-34644573
session_start();
define("TemplatePath", "./");
define("ServerURL", "http://localhost/Alumni5/");
define("SecureURL", "");

$ShortWeekdays = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
$Weekdays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
$ShortMonths =  array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
$Months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

define("ccsInteger", 1);
define("ccsFloat", 2);
define("ccsText", 3);
define("ccsDate", 4);
define("ccsBoolean", 5);
define("ccsMemo", 6);

define("ccsGet", 1);
define("ccsPost", 2);

define("ccsYear", 1);
define("ccsMonth", 2);
define("ccsDay", 3);
define("ccsHour", 4);
define("ccsMinute", 5);
define("ccsSecond", 6);
define("ccsAmPm", 7);
define("ccsShortMonth", 8);
define("ccsFullMonth", 9);
define("ccsWeek", 10);
define("ccsGMT", 11);
//End Initialize Common Variables

//Alumni Connection Class @-BFA73587
class clsDBAlumni extends DB_MySQL
{

    var $DateFormat;
    var $BooleanFormat;
    var $LastSQL;
    var $Errors;

    var $RecordsCount;
    var $RecordNumber;
    var $PageSize;
    var $AbsolutePage;

    var $SQL = "";
    var $Where = "";
    var $Order = "";

    var $Parameters;
    var $wp;

    function clsDBAlumni()
    {
        $this->Initialize();
    }

    function Initialize()
    {
        $this->AbsolutePage = 0;
        $this->PageSize = 0;
        $this->Database = "";
        $this->Host = "localhost";
        $this->User = "";
        $this->Password = "";
        $this->Persistent = true;
        $this->RecordsCount = 0;
        $this->RecordNumber = 0;
        $this->DateFormat = Array("mm", "/", "dd", "/", "yyyy", " ", "HH", ":", "nn", ":", "ss");
        $this->BooleanFormat = Array(1, 0, "");
        $this->Errors = New clsErrors();
    }

    function MoveToPage($Page)
    {
        if($this->RecordNumber == 0 && $this->PageSize != 0 && $Page != 0)
            while($this->RecordNumber < ($Page - 1) * $this->PageSize && $this->next_record())
                $this->RecordNumber++;
    }
    function PageCount()
    {
        return ceil($this->RecordsCount / $this->PageSize);
    }

    function ToSQL($Value, $ValueType)
    {
        if(strlen($Value) || ($ValueType == ccsBoolean && is_bool($Value)))
        {
            if($ValueType == ccsInteger || $ValueType == ccsFloat)
            {
                return doubleval(str_replace(",", ".", $Value));
            }
            else if($ValueType == ccsDate)
            {
                return "'" . addslashes($Value) . "'";
            }
            else if($ValueType == ccsBoolean)
            {
                if(is_bool($Value))
                    $Value = $Value ? "1" : "0";
                else if(is_numeric($Value))
                    $Value = intval($Value);
                else if(strtoupper($Value) == "TRUE" || strtoupper($Value) == "FALSE")
                    $Value = strtoupper($Value);
                else
                    $Value = "'" . addslashes($Value) . "'";
                return $Value;
            }
            else
            {
                return "'" . addslashes($Value) . "'";
            }
        }
        else
        {
            return "NULL";
        }
    }

    function SQLValue($Value, $ValueType)
    {
        if(!strlen($Value))
        {
            return "NULL";
        }
        else
        {
            if($ValueType == ccsInteger || $ValueType == ccsFloat)
            {
                return doubleval(str_replace(",", ".", $Value));
            }
            else if($ValueType == ccsBoolean)
            {
                if(is_bool($Value))
                    $Value = $Value ? "1" : "0";
                else if(is_numeric($Value))
                    $Value = intval($Value);
                else if(strtoupper($Value) == "TRUE" || strtoupper($Value) == "FALSE")
                    $Value = strtoupper($Value);
                else
                    $Value = addslashes($Value);
                return $Value;
            }
            else
            {
                return addslashes($Value);
            }
        }
    }


}
//End Alumni Connection Class

//CCToHTML @0-93F44B0D
function CCToHTML($Value)
{
  return htmlspecialchars($Value);
}
//End CCToHTML

//CCToURL @0-88FAFE26
function CCToURL($Value)
{
  return urlencode($Value);
}
//End CCToURL

//CCGetEvent @0-25FB255C
function CCGetEvent($events, $event_name)
{
  $function_name = (is_array($events) && isset($events[$event_name])) ? $events[$event_name] : "";
  if($function_name && function_exists($function_name))
    $result = call_user_func ($function_name);
  else
    $result = $function_name;
  return $result;  
}
//End CCGetEvent

//CCGetValueHTML @0-0180C79D
function CCGetValueHTML($db, $fieldname)
{
  return htmlspecialchars($db->f($fieldname));
}
//End CCGetValueHTML

//CCGetValue @0-EAF96C23
function CCGetValue($db, $fieldname)
{
  return $db->f($fieldname);
}
//End CCGetValue

//CCGetSession @0-9BBC6D71
function CCGetSession($parameter_name)
{
    global $HTTP_SESSION_VARS;
    return isset($HTTP_SESSION_VARS[$parameter_name]) ? $HTTP_SESSION_VARS[$parameter_name] : "";
}
//End CCGetSession

//CCSetSession @0-1F870DB4
function CCSetSession($param_name, $param_value)
{
  global ${$param_name};
  if(session_is_registered($param_name)) 
    session_unregister($param_name);
  ${$param_name} = $param_value;
  session_register($param_name);
}
//End CCSetSession

//CCGetCookie @0-705AF8CB
function CCGetCookie($parameter_name)
{
    global $HTTP_COOKIE_VARS;
    return isset($HTTP_COOKIE_VARS[$parameter_name]) ? $HTTP_COOKIE_VARS[$parameter_name] : "";
}
//End CCGetCookie

//CCSetCookie @0-1E0B074A
function CCSetCookie($parameter_name, $param_value)
{
  setcookie ($parameter_name, $param_value, time() + 3600 * 24 * 366);  
}
//End CCSetCookie

//CCStrip @0-F5DAB56A
function CCStrip($value)
{
  if(get_magic_quotes_gpc() == 0)
    return $value;
  else
    return stripslashes($value);
}
//End CCStrip

//CCGetParam @0-C3E4B53D
function CCGetParam($parameter_name, $default_value)
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $parameter_value = "";
    if(isset($HTTP_POST_VARS[$parameter_name]))
        $parameter_value = CCStrip($HTTP_POST_VARS[$parameter_name]);
    else if(isset($HTTP_GET_VARS[$parameter_name]))
        $parameter_value = CCStrip($HTTP_GET_VARS[$parameter_name]);
    else
        $parameter_value = $default_value;
    return $parameter_value;
}
//End CCGetParam

//CCGetFromPost @0-1067CEA0
function CCGetFromPost($parameter_name, $default_value)
{
    global $HTTP_POST_VARS;
    return isset($HTTP_POST_VARS[$parameter_name]) ? CCStrip($HTTP_POST_VARS[$parameter_name]) : $default_value;
}
//End CCGetFromPost

//CCGetFromGet @0-BAD57EE5
function CCGetFromGet($parameter_name, $default_value)
{
    global $HTTP_GET_VARS;
    return isset($HTTP_GET_VARS[$parameter_name]) ? CCStrip($HTTP_GET_VARS[$parameter_name]) : $default_value;
}
//End CCGetFromGet

//CCToSQL @0-422F5B92
function CCToSQL($Value, $ValueType)
{
  if(!strlen($Value))
  {
    return "NULL";
  }
  else
  {
    if($ValueType == ccsInteger || $ValueType == ccsFloat)
    {
      return doubleval(str_replace(",", ".", $Value));
    }
    else
    {
      return "'" . str_replace("'", "''", $Value) . "'";
    }
  }
}
//End CCToSQL

//CCDLookUp @0-A937C7E0
function CCDLookUp($field_name, $table_name, $where_condition, $db)
{
  $sql = "SELECT " . $field_name . " FROM " . $table_name . ($where_condition ? " WHERE " . $where_condition : "");
  return CCGetDBValue($sql, $db);
}
//End CCDLookUp

//CCGetDBValue @0-417763E3
function CCGetDBValue($sql, $db)
{
  $db->query($sql);
  if($db->next_record())
    return $db->f(0);
  else 
    return "";
}
//End CCGetDBValue

//CCGetListValues @0-5D6EE70E
function CCGetListValues($db, $sql, $where = "", $order_by = "", $bound_column = "", $text_column = "")
{
    $values = "";
    if(!strlen($bound_column))
        $bound_column = 0;
    if(!strlen($text_column))
        $text_column = 1;
    if(strlen($where))
        $sql .= " WHERE " . $where;
    if(strlen($order_by))
        $sql .= " ORDER BY " . $order_by;
    $db->query($sql);
    if ($db->next_record())
    {
        do
        {
            $values[] = array($db->f($bound_column), $db->f($text_column));
        } while ($db->next_record());
    }
    return $values;
}

//End CCGetListValues

//CCBuildSQL @0-AD00EEB4
function CCBuildSQL($sql, $where = "", $order_by = "")
{
    if(strlen($where))
        $sql .= " WHERE " . $where;
    if(strlen($order_by))
        $sql .= " ORDER BY " . $order_by;
    return $sql;
}

//End CCBuildSQL

//CCGetRequestParam @0-1C3CB87C
function CCGetRequestParam($ParameterName, $Method)
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $ParameterValue = "";
    if($Method == ccsGet && isset($HTTP_GET_VARS[$ParameterName]))
        $ParameterValue = CCStrip($HTTP_GET_VARS[$ParameterName]);
    else if($Method == ccsPost && isset($HTTP_POST_VARS[$ParameterName]))
        $ParameterValue = CCStrip($HTTP_POST_VARS[$ParameterName]);
    return $ParameterValue;
}
//End CCGetRequestParam

//CCGetQueryString @0-F67D7840
function CCGetQueryString($CollectionName, $RemoveParameters)
{
    global $HTTP_POST_VARS;
    global $HTTP_GET_VARS;
    $querystring = "";
    $postdata = "";
    if($CollectionName == "Form")
        $querystring = CCCollectionToString($HTTP_POST_VARS, $RemoveParameters);
    else if($CollectionName == "QueryString")
        $querystring = CCCollectionToString($HTTP_GET_VARS, $RemoveParameters);
    else if($CollectionName == "All")
    {
        $querystring = CCCollectionToString($HTTP_GET_VARS, $RemoveParameters);
        $postdata = CCCollectionToString($HTTP_POST_VARS, $RemoveParameters);
        if(strlen($postdata) > 0 && strlen($querystring) > 0)
            $querystring .= "&" . $postdata;
        else
            $querystring .= $postdata;
    }
    else
        die("1050: Common Functions. CCGetQueryString Function. " .
            "The CollectionName contains an illegal value.");
    return $querystring;
}
//End CCGetQueryString

//CCCollectionToString @0-883F2B49
function CCCollectionToString($ParametersCollection, $RemoveParameters)
{
  $Result = ""; 
  if(is_array($ParametersCollection))
  {
    reset($ParametersCollection);
    foreach($ParametersCollection as $ItemName => $ItemValues)
    {
      $Remove = false;
      if(is_array($RemoveParameters))
      {
        for($I = 0; $I < sizeof($RemoveParameters); $I++)
        {
          if($RemoveParameters[$I] == $ItemName)
          {
            $Remove = true;
            break;
          }
        }
      }
      if(!$Remove)
      {
        if(is_array($ItemValues))
          for($J = 0; $J < sizeof($ItemValues); $J++)
            $Result .= "&" . $ItemName . "[]=" . urlencode(CCStrip($ItemValues[$J]));
        else
           $Result .= "&" . $ItemName . "=" . urlencode(CCStrip($ItemValues));
      }
    }
  }

  if(strlen($Result) > 0)
    $Result = substr($Result, 1);
  return $Result;
}
//End CCCollectionToString

//CCAddParam @0-56573314
function CCAddParam($querystring, $ParameterName, $ParameterValue)
{
    global $HTTP_GET_VARS;
    $Result = "";
    $CurrentParameterValue = isset($HTTP_GET_VARS[$ParameterName]) ? $HTTP_GET_VARS[$ParameterName] : "";
    $Result = str_replace($ParameterName . "=" . urlencode($CurrentParameterValue), "", $querystring);
    $Result .= "&" . $ParameterName . "=" . urlencode($ParameterValue);
    $Result = str_replace("&&", "&", $Result);
    if (substr($Result, 0, 1) == "&")
        $Result = substr($Result, 1);
    return $Result;
}
//End CCAddParam

//CCRemoveParam @0-9355EFB5
function CCRemoveParam($querystring, $ParameterName)
{
    global $HTTP_GET_VARS;
    $Result = "";
    $Result = str_replace($ParameterName . "=" . urlencode($HTTP_GET_VARS[$ParameterName]), "", $querystring);
    $Result = str_replace("&&", "&", $Result);
    if (substr($Result, 0, 1) == "&")
        $Result = substr($Result, 1);
    return $Result;
}
//End CCRemoveParam

//CCGetOrder @0-27B4AC18
function CCGetOrder($DefaultSorting, $SorterName, $SorterDirection, $MapArray)
{
  if(is_array($MapArray) && isset($MapArray[$SorterName]))
    if(strtoupper($SorterDirection) == "DESC")
      $OrderValue = ($MapArray[$SorterName][1] != "") ? $MapArray[$SorterName][1] : $MapArray[$SorterName][0] . " DESC";
    else
      $OrderValue = $MapArray[$SorterName][0];
  else
    $OrderValue = $DefaultSorting;

  return $OrderValue;
}
//End CCGetOrder


//CCFormatDate @0-D935F130
function CCFormatDate($DateToFormat, $FormatMask)
{
  global $ShortWeekdays;
  global $Weekdays;
  global $ShortMonths;
  global $Months;

  if($DateToFormat < 0) $DateToFormat = "";
  if(is_array($FormatMask) && strlen($DateToFormat))
  {
    $masks = array(
      "GeneralDate" => "n/j/y, h:i:s A", "LongDate" => "l, F j, Y",
      "ShortDate" => "n/j/y", "LongTime" => "g:i:s A",
      "ShortTime" => "H:i", "d" => "j", "dd" => "d",
      "m" => "n", "mm" => "m", "yy" => "y", "yyyy" => "Y",
      "h" => "g", "hh" => "h", "H" => "G", "HH" => "H",
      "nn" => "i", "ss" => "s", "AM/PM" => "A", "am/pm" => "a"
    );
    $FormatedDate = "";
    for($i = 0; $i < sizeof($FormatMask); $i++)
    {
      if(isset($masks[$FormatMask[$i]]))
      {
        $FormatedDate .= date($masks[$FormatMask[$i]], $DateToFormat);
      }
      else
      {
        switch ($FormatMask[$i])
        {
          case "ddd": 
            $FormatedDate .= $ShortWeekdays[date("w", $DateToFormat)];
            break;
          case "dddd": 
            $FormatedDate .= $Weekdays[date("w", $DateToFormat)];
            break;
          case "w": 
            $FormatedDate .= (date("w", $DateToFormat) + 1);
            break;
          case "ww": 
            $FormatedDate .= ceil((6 + date("z", $DateToFormat) - date("w", $DateToFormat)) / 7);
            break;
          case "mmm": 
            $FormatedDate .= $ShortMonths[date("n", $DateToFormat) - 1];
            break;
          case "mmmm":
            $FormatedDate .= $Months[date("n", $DateToFormat) - 1];
            break;
          case "q":
            $FormatedDate .= ceil(date("n", $DateToFormat) / 3);
            break;
          case "y":
            $FormatedDate .= (date("z", $DateToFormat) + 1);
            break;
          case "n": 
            $FormatedDate .= intval(date("i", $DateToFormat));
            break;
          case "s":
            $FormatedDate .= intval(date("s", $DateToFormat));
            break;
          case "A/P":
            $am = date("A", $DateToFormat);
            $FormatedDate .= $am[0];
            break;
          case "a/p":
            $am = date("a", $DateToFormat);
            $FormatedDate .= $am[0];
            break;
          case "GMT":
            $gmt = date("Z", $DateToFormat) / (60 * 60);
            if($gmt >= 0) $gmt = "+" . $gmt;
            $FormatedDate .= $gmt;
            break;
          default:
            $FormatedDate .= $FormatMask[$i];
            break;
        }
      }
    }
  }
  else
  {
    $FormatedDate = $DateToFormat;
  }
  return $FormatedDate;
}
//End CCFormatDate

//CCValidateDate @0-77D4F419
function CCValidateDate($ValidatingDate, $FormatMask)
{
  $IsValid = true;
  if(is_array($FormatMask) && strlen($ValidatingDate))
  {
    $RegExp = CCGetDateRegExp($FormatMask);
    $IsValid = preg_match($RegExp[0], $ValidatingDate, $matches);
  }
  else if(strlen($ValidatingDate))
  {
    $IsValid = is_numeric($ValidatingDate);
  }

  return $IsValid;
}
//End CCValidateDate

//CCParseDate @0-ECFCFB7D
function CCParseDate($ParsingDate, $FormatMask)
{
  global $ShortMonths;
  global $Months;

  if(is_array($FormatMask) && strlen($ParsingDate))
  {
    $DateArray = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $RegExp = CCGetDateRegExp($FormatMask);
    $IsValid = preg_match($RegExp[0], $ParsingDate, $matches);
    for($i = 1; $i < sizeof($matches); $i++)
      $DateArray[$RegExp[$i]] = $matches[$i];

    if($DateArray[ccsMonth] == 0 && ($DateArray[ccsFullMonth] != 0 || $DateArray[ccsShortMonth] != 0))
    {
      if($DateArray[ccsFullMonth] != 0)
        $DateArray[ccsMonth] = CCGetIndex($Months, $DateArray[ccsFullMonth], true) + 1;
      else if($DateArray[ccsShortMonth] != 0)
        $DateArray[ccsMonth] = CCGetIndex($ShortMonths, $DateArray[ccsShortMonth], true) + 1;
    }

    if($DateArray[ccsHour] < 12 && strtoupper($DateArray[ccsAmPm][0]) == "P")
      $DateArray[ccsHour] += 12;

    if($DateArray[ccsHour] == 12 && strtoupper($DateArray[ccsAmPm][0]) == "A")
      $DateArray[ccsHour] = 0;

    if(($DateArray[ccsYear] < 1970 || $DateArray[ccsYear] > 2069) && strlen($DateArray[ccsYear]) > 2)
      $DateArray[ccsYear] = substr($DateArray[ccsYear], strlen($DateArray[ccsYear]) - 3);

    $ParsingDate = mktime ($DateArray[ccsHour], $DateArray[ccsMinute], $DateArray[ccsSecond], $DateArray[ccsMonth], $DateArray[ccsDay], $DateArray[ccsYear]);
    if($ParsingDate < 0) $ParsingDate = "";
    
  }

  return $ParsingDate;
}
//End CCParseDate

//CCGetDateRegExp @0-9237C33C
function CCGetDateRegExp($FormatMask)
{
  global $ShortWeekdays;
  global $Weekdays;
  global $ShortMonths;
  global $Months;

  $RegExp = false;
  if(is_array($FormatMask))
  {
    $masks = array(
      "GeneralDate" => array("(\d{1,2})\/(\d{1,2})\/(\d{2}), (\d{2}):(\d{2}):(\d{2}) (AM|PM)", ccsMonth, ccsDay, ccsYear, ccsHour, ccsMinute, ccsSecond, ccsAmPm), 
      "LongDate" => array("(" . join("|", $Weekdays) . "), (" . join("|", $Months) . ") (\d{1,2}), (\d{4})", ccsWeek, ccsFullMonth, ccsDay, ccsYear),
      "ShortDate" => array("(\d{1,2})\/(\d{1,2})\/(\d{2})", ccsMonth, ccsDay, ccsYear), 
      "LongTime" => array("(\d{1,2}):(\d{2}):(\d{2}) (AM|PM)", ccsHour, ccsMinute, ccsSecond, ccsAmPm),
      "ShortTime" => array("(\d{2}):(\d{2})", ccsHour, ccsMinute), 
      "d" => array("(\d{1,2})", ccsDay), 
      "dd" => array("(\d{2})", ccsDay), 
      "ddd" => array("(" . join("|", $ShortWeekdays) . ")", ccsWeek), 
      "dddd" => array("(" . join("|", $Weekdays) . ")", ccsWeek), 
      "w" => array("\d"), "ww" => array("\d{1,2}"),
      "m" => array("(\d{1,2})", ccsMonth), "mm" => array("(\d{2})", ccsMonth), 
      "mmm" => array("(" . join("|", $ShortMonths) . ")", ccsShortMonth), 
      "mmmm" => array("(" . join("|", $Months) . ")", ccsFullMonth),
      "y" => array("\d{1,3}"), "yy" => array("(\d{2})", ccsYear), 
      "yyyy" => array("(\d{4})", ccsYear), "q" => array("\d"),
      "h" => array("(\d{1,2})", ccsHour), "hh" => array("(\d{2})", ccsHour), 
      "H" => array("(\d{1,2})", ccsHour), "HH" => array("(\d{2})", ccsHour),
      "n" => array("(\d{1,2})", ccsMinute), "nn" => array("(\d{2})", ccsMinute), 
      "s" => array("(\d{1,2})", ccsSecond), "ss" => array("(\d{2})", ccsSecond), 
      "AM/PM" => array("(AM|PM)", ccsAmPm), "am/pm" => array("(am|pm)", ccsAmPm), 
      "A/P" => array("(A|P)", ccsAmPm), "a/p" => array("(a|p)", ccsAmPm),
      "GMT" => array("([\+\-]\d{2})", ccsGMT)
    );
    $RegExp[0] = "";
    $RegExpIndex = 1;
    for($i = 0; $i < sizeof($FormatMask); $i++)
    {
      if(isset($masks[$FormatMask[$i]]))
      {
        $MaskArray = $masks[$FormatMask[$i]];
        $RegExp[0] .= $MaskArray[0];
        for($j = 1; $j < sizeof($MaskArray); $j++)
          $RegExp[$RegExpIndex++] = $MaskArray[$j];
      }
      else
        $RegExp[0] .= CCAddEscape($FormatMask[$i]);
    }
    $RegExp[0] = "/^" . $RegExp[0] . "$/";
  }

  return $RegExp;
}
//End CCGetDateRegExp

//CCAddEscape @0-06D50C27
function CCAddEscape($FormatMask)
{
  $meta_characters = array("\\", "^", "\$", ".", "[", "|", "(", ")", "?", "*", "+", "{", "-", "]", "/");
  for($i = 0; $i < sizeof($meta_characters); $i++)
    $FormatMask = str_replace($meta_characters[$i], "\\" . $meta_characters[$i], $FormatMask);
  return $FormatMask;
}
//End CCAddEscape

//CCGetIndex @0-8DB8E12C
function CCGetIndex($ArrayValues, $Value, $IgnoreCase = true)
{
  $index = false;
  for($i = 0; $i < sizeof($ArrayValues); $i++)
  {
    if(($IgnoreCase && strtoupper($ArrayValues[$i]) == strtoupper($Value)) || ($ArrayValues[$i] == $Value))
    {
      $index = $i;
      break;
    }
  }
  return $index;
}
//End CCGetIndex

//CCFormatNumber @0-B39A1596
function CCFormatNumber($NumberToFormat, $FormatArray)
{
  $Result = "";
  if(is_array($FormatArray) && strlen($NumberToFormat))
  {
    $IsExtendedFormat = $FormatArray[0];
    $IsNegative = ($NumberToFormat < 0);
    $NumberToFormat = abs($NumberToFormat);
    $NumberToFormat *= $FormatArray[7];
  
    if($IsExtendedFormat) // Extended format
    {
      $DecimalSeparator = $FormatArray[2];
      $PeriodSeparator = $FormatArray[3];
      $ObligatoryBeforeDecimal = 0;
      $DigitsBeforeDecimal = 0;
      $BeforeDecimal = $FormatArray[5];
      $AfterDecimal = $FormatArray[6];
      if(is_array($BeforeDecimal)) {
        for($i = 0; $i < sizeof($BeforeDecimal); $i++) {
          if($BeforeDecimal[$i] == "0") {
            $ObligatoryBeforeDecimal++;
            $DigitsBeforeDecimal++;
          } else if($BeforeDecimal[$i] == "#") 
            $DigitsBeforeDecimal++;
        }
      }
      $ObligatoryAfterDecimal = 0;
      $DigitsAfterDecimal = 0;
      if(is_array($AfterDecimal)) {
        for($i = 0; $i < sizeof($AfterDecimal); $i++) {
          if($AfterDecimal[$i] == "0") {
            $ObligatoryAfterDecimal++;
            $DigitsAfterDecimal++;
          } else if($AfterDecimal[$i] == "#")
            $DigitsAfterDecimal++;
        }
      }
  
      $NumberToFormat = number_format($NumberToFormat, $DigitsAfterDecimal, ".", "");
      $NumberParts = explode(".", $NumberToFormat);

      $LeftPart = $NumberParts[0];
      if($LeftPart == "0") $LeftPart = "";
      $RightPart = isset($NumberParts[1]) ? $NumberParts[1] : "";
      $j = strlen($LeftPart);
    
      if(is_array($BeforeDecimal))
      {
        $RankNumber = 0;
        $i = sizeof($BeforeDecimal);
        while ($i > 0 || $j > 0)
        {
          if(($i > 0 && ($BeforeDecimal[$i - 1] == "#" || $BeforeDecimal[$i - 1] == "0")) || ($j > 0 && $i < 1)) {
            $RankNumber++;
            $CurrentSeparator = ($RankNumber % 3 == 1 && $RankNumber > 3 && $j > 0) ? $PeriodSeparator : "";
            if($ObligatoryBeforeDecimal > 0 && $j < 1)
              $Result = "0" . $CurrentSeparator . $Result;
            else if($j > 0)
              $Result = $LeftPart[$j - 1] . $CurrentSeparator . $Result;
            $j--;
            $ObligatoryBeforeDecimal--;
            $DigitsBeforeDecimal--;
            if($DigitsBeforeDecimal == 0 && $j > 0)
              for(;$j > 0; $j--)
              {
                $RankNumber++;
                $CurrentSeparator = ($RankNumber % 3 == 1 && $RankNumber > 3 && $j > 0) ? $PeriodSeparator : "";
                $Result = $LeftPart[$j - 1] . $CurrentSeparator . $Result;
              }
          }
          else if ($i > 0) {
            $BeforeDecimal[$i - 1] = str_replace("##", "#", $BeforeDecimal[$i - 1]);
            $BeforeDecimal[$i - 1] = str_replace("00", "0", $BeforeDecimal[$i - 1]);
            $Result = $BeforeDecimal[$i - 1] . $Result;
          }
          $i--;
        }
      }

      // Left part after decimal
      $RightResult = "";
      $IsRightNumber = false;
      if(is_array($AfterDecimal))
      {
        $IsZero = true;
        for($i = sizeof($AfterDecimal); $i > 0; $i--) {
          if($AfterDecimal[$i - 1] == "#" || $AfterDecimal[$i - 1] == "0") {
            if($DigitsAfterDecimal > $ObligatoryAfterDecimal) {
              if($RightPart[$DigitsAfterDecimal - 1] != "0") 
                $IsZero = false;
              if(!$IsZero)
              {
                $RightResult = $RightPart[$DigitsAfterDecimal - 1] . $RightResult;
                $IsRightNumber = true;
              }
            } else {
              $RightResult = $RightPart[$DigitsAfterDecimal - 1] . $RightResult;
              $IsRightNumber = true;
            }
            $DigitsAfterDecimal--;
          } else {
            $AfterDecimal[$i - 1] = str_replace("##", "#", $AfterDecimal[$i - 1]);
            $AfterDecimal[$i - 1] = str_replace("00", "0", $AfterDecimal[$i - 1]);
            $RightResult = $AfterDecimal[$i - 1] . $RightResult;
          }
        }
      }
    
      if($IsRightNumber)
        $Result .= $DecimalSeparator ;

      $Result .= $RightResult;

      if(!$FormatArray[4] && $IsNegative && $Result)
        $Result = "-" . $Result;
    }
    else // Simple format
    {
      if(!$FormatArray[4] && $IsNegative)
        $Result = "-" . $FormatArray[5] . number_format($NumberToFormat, $FormatArray[1], $FormatArray[2], $FormatArray[3]) . $FormatArray[6];
      else
        $Result = $FormatArray[5] . number_format($NumberToFormat, $FormatArray[1], $FormatArray[2], $FormatArray[3]) . $FormatArray[6];
    }

    if(!$FormatArray[8])
      $Result = htmlspecialchars($Result);

    if(strlen($FormatArray[9]))
      $Result = "<FONT COLOR=\"" . $FormatArray[9] . "\">" . $Result . "</FONT>";
  }
  else
  {
    $Result = $NumberToFormat;
  }

  return $Result;
}
//End CCFormatNumber

//CCValidateNumber @0-D53857C4
function CCValidateNumber($NumberValue, $FormatArray)
{
  $is_valid = true;
  if(strlen($NumberValue))
  {
    $NumberValue = CCCleanNumber($NumberValue, $FormatArray);
    $is_valid = is_numeric($NumberValue);
  }
  return $is_valid;
}

//End CCValidateNumber

//CCParseNumber @0-51D95F29
function CCParseNumber($NumberValue, $FormatArray, $DataType)
{
  $NumberValue = CCCleanNumber($NumberValue, $FormatArray);
  if(is_array($FormatArray) && strlen($NumberValue))
  {

    if($FormatArray[4]) // Is use parenthesis
      $NumberValue = - abs(doubleval($NumberValue));

    $NumberValue /= $FormatArray[7];
  }

  if(strlen($NumberValue))
  {
    if($DataType == ccsFloat)
      $NumberValue = doubleval($NumberValue);
    else
      $NumberValue = round($NumberValue, 0);
  }

  return $NumberValue;
}
//End CCParseNumber

//CCCleanNumber @0-2A278526
function CCCleanNumber($NumberValue, $FormatArray)
{
  if(is_array($FormatArray))
  {
    $IsExtendedFormat = $FormatArray[0];

    if($IsExtendedFormat) // Extended format
    {
      $BeforeDecimal = $FormatArray[5];
      $AfterDecimal = $FormatArray[6];
    
      if(is_array($BeforeDecimal))
      {
        for($i = sizeof($BeforeDecimal); $i > 0; $i--) {
          if($BeforeDecimal[$i - 1] != "#" && $BeforeDecimal[$i - 1] != "0") 
          {
            $search = $BeforeDecimal[$i - 1];
            $search = ($search == "##" || $search == "00") ? $search[0] : $search;
            $NumberValue = str_replace($search, "", $NumberValue);
          }
        }
      }

      if(is_array($AfterDecimal))
      {
        for($i = sizeof($AfterDecimal); $i > 0; $i--) {
          if($AfterDecimal[$i - 1] != "#" && $AfterDecimal[$i - 1] != "0") 
          {
            $search = $AfterDecimal[$i - 1];
            $search = ($search == "##" || $search == "00") ? $search[0] : $search;
            $NumberValue = str_replace($search, "", $NumberValue);
          }
        }
      }
    }
    else // Simple format
    {
      if(strlen($FormatArray[5]))
        $NumberValue = str_replace($FormatArray[5], "", $NumberValue);
      if(strlen($FormatArray[6]))
        $NumberValue = str_replace($FormatArray[6], "", $NumberValue);
    }

    if(strlen($FormatArray[3]))
      $NumberValue = str_replace($FormatArray[3], "", $NumberValue); // Period separator
    if(strlen($FormatArray[2]))
      $NumberValue = str_replace($FormatArray[2], ".", $NumberValue); // Decimal separator

    if(strlen($FormatArray[9]))
    {
      $NumberValue = str_replace("<FONT COLOR=\"" . $FormatArray[9] . "\">", "", $NumberValue);
      $NumberValue = str_replace("</FONT>", "", $NumberValue);
    }
  }
  $NumberValue = str_replace(",", ".", $NumberValue); // Decimal separator

  return $NumberValue;
}
//End CCCleanNumber

//CCParseInteger @0-FDF2EE85
function CCParseInteger($NumberValue, $FormatArray)
{
  return CCParseNumber($NumberValue, $FormatArray, ccsInteger);
}
//End CCParseInteger

//CCParseFloat @0-C9EAEA95
function CCParseFloat($NumberValue, $FormatArray)
{
  return CCParseNumber($NumberValue, $FormatArray, ccsFloat);
}
//End CCParseFloat


//CCFormatBoolean @0-4E964142
function CCFormatBoolean($BooleanValue, $Format)
{
  $Result = $BooleanValue;

  if(is_array($Format))
  {
    if($BooleanValue)
      $Result = $Format[0];
    else if(strval($BooleanValue) == "0" || $BooleanValue === false)
      $Result = $Format[1];
    else
      $Result = $Format[2];
  }

  return $Result;
}
//End CCFormatBoolean

//CCParseBoolean @0-0C7716BC
function CCParseBoolean($Value, $Format)
{
  $Result = $Value;
  if(is_array($Format))
  {
    if(strtolower(strval($Value)) == strtolower(strval($Format[0])))
      $Result = true;
    else if(strtolower(strval($Value)) == strtolower(strval($Format[1])))
      $Result = false;
    else
      $Result = "";
  }
  return $Result;
}
//End CCParseBoolean

//CCCheckSSL @0-E299E90E
function CCCheckSSL()
{
  $protocol = getenv("SERVER_PROTOCOL");
  $protocol = substr($protocol, 0, 5);
  if($protocol != "HTTPS")
  {
    echo "SSL connection error. This page can be accessed only via secured connection.";
    exit;
  }
}

//End CCCheckSSL

//CCSecurityRedirect @0-11071578
function CCSecurityRedirect($GroupsAccess, $URL, $ReturnPage, $QueryString)
{
    $ErrorType = CCSecurityAccessCheck($GroupsAccess);
    if($ErrorType != "success")
    {
        if(!strlen($URL))
            $Link = "Login.php";
        else
            $Link = $URL;
        if(strlen($QueryString))
            $ReturnPage .= "?" . $QueryString;
        header("Location: " . $Link . "?ret_link=" . urlencode($ReturnPage) . "&type=" . $ErrorType);
        exit;
    }
}
//End CCSecurityRedirect

//CCSecurityAccessCheck @0-7B496647
function CCSecurityAccessCheck($GroupsAccess)
{
    $ErrorType = "success";
    if(!strlen(CCGetUserID()))
    {
        $ErrorType = "notLogged";
    }
    else
    {
        $GroupID = CCGetGroupID();
        if(!strlen($GroupID))
        {
            $ErrorType = "groupIDNotSet";
        }
        else
        {
            if(!CCUserInGroups($GroupID, $GroupsAccess))
                $ErrorType = "illegalGroup";
        }
    }
    return $ErrorType;
}
//End CCSecurityAccessCheck

//CCGetUserID @0-6FAFFFAE
function CCGetUserID()
{
    return CCGetSession("UserID");
}
//End CCGetUserID

//CCGetGroupID @0-89F10997
function CCGetGroupID()
{
    return CCGetSession("GroupID");
}
//End CCGetGroupID

//CCGetUserLogin @0-ACD25564
function CCGetUserLogin()
{
    return CCGetSession("UserLogin");
}
//End CCGetUserLogin

//CCGetUserPassword @0-FF9DADAF
function CCGetUserPassword()
{
    return CCGetSession("UserPassword");
}
//End CCGetUserPassword

//CCUserInGroups @0-51407098
function CCUserInGroups($GroupID, $GroupsAccess)
{
    $Result = "";
    if(strlen($GroupsAccess))
    {
        $GroupNumber = intval($GroupID);
        while(!$Result && $GroupNumber > 0)
        {
            $Result = (strpos(";" . $GroupsAccess . ";", ";" . $GroupNumber . ";") !== false);
            $GroupNumber--;
        }
    }
    else
    {
        $Result = true;
    }
    return $Result;
}
//End CCUserInGroups

//CCLoginUser @0-17E02365
function CCLoginUser($Login, $Password)
{
    $db = new clsDBAlumni();
    $SQL = "SELECT RecordNum, LevelID FROM alm_alumni WHERE LoginID=" . $db->ToSQL($Login, ccsText) . " AND Password=" . $db->ToSQL($Password, ccsText);
    $db->query($SQL);
    $Result = $db->next_record();
    if($Result)
    {
        CCSetSession("UserID", $db->f("RecordNum"));
        CCSetSession("UserLogin", $Login);
        CCSetSession("UserPassword", $Password);
        CCSetSession("GroupID", $db->f("LevelID"));
    }
    unset($db);
    return $Result;
}
//End CCLoginUser

//CCLogoutUser @0-AD68F376
function CCLogoutUser()
{
    CCSetSession("UserID", "");
    CCSetSession("UserLogin", "");
    CCSetSession("UserPassword", "");
    CCSetSession("GroupID", "");
}
//End CCLogoutUser


?>
