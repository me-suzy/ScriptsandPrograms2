<?php

// export.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: export.php,v 1.27.2.1 2005/09/02 11:22:54 fgraf Exp $, Norbert Ku:ck

$path_pre = "../";
$include_path = $path_pre."lib/lib.inc.php";
include_once $include_path;

if (!$medium) die("<html><body>Please select an export format!</body></html>!");

// first check whether all available records should be exported or just a selection
if ($ID) {
    settype($ID, "array");
    $wherein = " AND ID IN ('".implode("','", $ID)."') ";
}
else {
    $wherein = '';
}

switch ($file){
    case "timecard":
      $fields = array("datum",     "anfang",   "ende"   );
      $f_lang = array(__('Date'),__('Begin'),__('End'));
      $query = "select ".implode(",",$fields)."
                  from ".DB_PREFIX."timecard
                 where users = '$user_ID' and
                       datum like '%-$month-%' and
                       datum like '$year-%'
              order by datum";
      $export_array = make_export_array($query);
      break;

    case "timecard_admin":
      check_admin_perm();
      $fields = array("datum",     "anfang",   "ende"   );
      $f_lang = array(__('Date'),__('Begin'),__('End'));
      $query = "select ".implode(",",$fields)."
                  from ".DB_PREFIX."timecard
                 where users = '$pers_ID' and
                       datum like '%-$month-%' and
                       datum like '$year-%'
              order by datum";
      $export_array = make_export_array($query);
    break;

    case "users":
      $fields = array("anrede",    "vorname",  "nachname",  "kurz",   "firma",    "email","tel1",        "tel2", "fax", "strasse", "plz",       "stadt",     "land"   );
      $f_lang = array(__('Salutation'),__('First Name'),__('Family Name'),__('Short Form'),__('Company'),"Email",__('Phone')."1",__('Phone')."2",__('Street'),__('Zip code'),__('City'),__('Country'));
      $query = "select ".implode(",",$fields)."
                  from ".DB_PREFIX."users
                 where $sql_user_group
              order by nachname";
      $export_array = make_export_array($query);
      break;

    case "contacts":
      if ($ID_s) {
        settype($ID_s, "array");
        $wherein = " and ID in ('".implode("','", $ID_s)."') ";
      }
      else $wherein = '';
      $tablename['contacts'] = 'contacts';
      if ($firstchar <> "") { $where = "and nachname like '$firstchar%'"; }
      else if ($keyword) {
         if ($filter == "all" or $filter == '') { $where = "and (nachname like '%$keyword%' or firma like '%$keyword%' or email like '%$keyword%' or stadt like '%$keyword%' or land like '%$keyword%' or kategorie like '%$keyword%' or bemerkung like '%$keyword%')"; }
         else { $where = "and $filter like '%$keyword%'"; }
      }
      require_once($path_pre.'lib/dbman_lib.inc.php');
      $fields = build_array('contacts', $ID, 'forms');
      foreach($fields as $field_name => $field) {
          $fields2[] = $field_name;
          $f_lang[] = enable_vars($field['form_name']);
        }

      $query = "select ".implode(",",$fields2)."
                  from ".DB_PREFIX."contacts
                 where (acc_read like 'system' or ((von = $user_ID or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group))
                       $where
                       $wherein
              order by nachname";

      $export_array = make_export_array($query);
      break;

    case "projects":
      $tablename['projects'] = 'projekte';
      if ($keyword) {
         if ($filter == "all" or $filter == '') { $where = "and (name like '%$keyword%' or ende like '%$keyword%' or anfang like '%$keyword%')"; }
         else { $where = "and $filter like '%$keyword%'"; }
      }
      require_once($path_pre.'lib/dbman_lib.inc.php');
      $fields = build_array('projects', $ID, 'forms');
      foreach($fields as $field_name => $field) {
        $fields2[] = $field_name;
        $f_lang[] = enable_vars($field['form_name']);
      }
      $query = "select ID, ".implode(",",$fields2)."
                  from ".DB_PREFIX."projekte
                 where (acc like 'system' or ((von = $user_ID or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))
                       $where
                       $wherein";
      $level = 0;
      $export_array = make_export_array_projects($query,0);
      break;

    case "bookmarks":
      $fields = array("url","bezeichnung","bemerkung");
      $f_lang = array("url",__('Description'),   __('Comment'));
      $query = "select ".implode(",",$fields)."
                  from ".DB_PREFIX."lesezeichen
                 where gruppe = $user_group
              order by bezeichnung";
      $export_array = make_export_array($query);
      break;

    case "timeproj":
      $fields = array(DB_PREFIX.'timeproj.datum',DB_PREFIX.'projekte.name',DB_PREFIX.'timeproj.h',DB_PREFIX.'timeproj.m',DB_PREFIX.'timeproj.note');
      $f_lang = array(           __('Date'),                  __('Project Name'),            __('Hours'),            __('minutes'),           __('Comment'));
      $query = "select ".implode(",",$fields)."
                  from ".DB_PREFIX."timeproj, ".DB_PREFIX."projekte
                 where ".DB_PREFIX."timeproj.projekt = ".DB_PREFIX."projekte.ID and
                       ".DB_PREFIX."timeproj.users = $user_ID and
                       ".DB_PREFIX."timeproj.datum like '$year-$month-%'
              order by ".DB_PREFIX."timeproj.datum asc";
      $export_array = make_export_array($query);
      break;

    case "project_stat":
      if ($userlist and $projectlist) {
        foreach ($userlist as $person) {
          foreach ($projectlist as $project) {
            $fields = array(DB_PREFIX.'users.vorname',DB_PREFIX.'users.nachname',DB_PREFIX.'projekte.name',DB_PREFIX.'timeproj.datum',DB_PREFIX.'timeproj.h',DB_PREFIX.'timeproj.m',DB_PREFIX.'timeproj.note');
            $f_lang = array(           __('First Name'),              __('Family Name'),                 __('Project Name'),                __('Date'),           __('Hours'),           __('minutes'),            __('Comment'));
            $query = "select ".implode(",",$fields)."
                        from ".DB_PREFIX."users, ".DB_PREFIX."projekte, ".DB_PREFIX."timeproj
                       where ".DB_PREFIX."timeproj.projekt = ".DB_PREFIX."projekte.ID and
                             ".DB_PREFIX."timeproj.users = ".DB_PREFIX."users.ID and
                             ".DB_PREFIX."timeproj.projekt = '$project' and
                             ".DB_PREFIX."timeproj.users = '$person' and
                             ".DB_PREFIX."timeproj.datum >= '$anfang' and
                             ".DB_PREFIX."timeproj.datum <= '$ende'
                    order by ".DB_PREFIX."timeproj.datum";
            $export_array = array_merge($export_array, make_export_array($query));
          }
        }
      }
      break;
          case "project_stat_date":
      if ($userlist and $projectlist) {
            $f_lang[]= __('Date');
            $f_lang[]=__('Project Name');
             foreach ($userlist as $person) {
                $resultuser = db_query("select vorname,nachname
                              from ".DB_PREFIX."users
                             where ID = '$person'") or db_die();
                $rowuser = db_fetch_row($resultuser);
                $f_lang[]=$rowuser[0]." ".$rowuser[1];
             }

                $f_lang[]=__('Sum');

          foreach ($projectlist as $project) {

                unset($line);
                foreach($project as $datum => $projID){
                    if($datum >=$anfang and $datum <= $ende){
                    $result = db_query("select name
                              from ".DB_PREFIX."projekte
                             where ID = '$projID'");
                     $row = db_fetch_row($result);
                        $line[]=$datum;
                        $line[]=$row[0];
                      foreach ($userlist as $person) {
                            $result2 = db_query("SELECT datum, h, m, note
                             FROM ".DB_PREFIX."timeproj
                                WHERE projekt = '$projID'
                                AND users = '$person'
                                AND datum = '$datum'
                                ORDER BY datum")or db_die();
                            while ($row2 = db_fetch_row($result2)) {
                                $books.= $row2[1].' : '.$row2[2].'  '.$row2[3]."<br>";
                                $sum1  = $sum1 + $row2[1]*60+$row2[2];

                            }
                            $line[]=$books;
                            $books='';
                        }

                    $h = floor($sum1/60);
                    $m = $sum1 - $h*60;
                    $line[]="$h : $m                    ";
                    $sum1=0;
                    $export_array[] =$line;
                }
                }
             }
      }
      break;


    case 'todo':
      $fields = array(DB_PREFIX."todo.remark","note",     "deadline","anfang",   "priority","progress",);
      $f_lang = array(__('Assigned from'),__('delegated to'),__('Title'), __('Comment'),__('Deadline'),  __('Begin'),__('Priority'),   __('progress'));
       $query = "select A.nachname as from_nachname, B.nachname as to_nachname, ".implode(",",$fields)."
                  from ".DB_PREFIX."todo , ".DB_PREFIX."users as A
             left join ".DB_PREFIX."users as B on B.nachname = ext
                 where (".DB_PREFIX."todo.acc like 'system' or ((von = $user_ID or ext = $user_ID or
                        ".DB_PREFIX."todo.acc like 'group' or ".DB_PREFIX."todo.acc like '%\"$user_kurz\"%') and
                        ".DB_PREFIX."todo.gruppe = $user_group)) and
                        A.ID = ".DB_PREFIX."todo.von
              order by deadline desc";

      $export_array = make_export_array($query);
      break;

    case "notes":
      if ($ID_s) {
        settype($ID_s, "array");
        $wherein = " and notes.ID in ('".implode("','", $ID_s)."') ";
      }
      else $wherein = '';
      $fields = array(DB_PREFIX.'notes.name','bemerkung',DB_PREFIX.'notes.kategorie',DB_PREFIX.'notes.div1',DB_PREFIX.'notes.div2');
      $f_lang = array(__('Description'),             __('Comment'),__('Category'),__('added'),          __('changed'),__('Contact'),__('Projects'));
      $query = "select ".implode(",",$fields).",
                       A.nachname,B.name
                  from ".DB_PREFIX."notes
             left join ".DB_PREFIX."contacts as A on A.ID = ".DB_PREFIX."notes.contact
             left join ".DB_PREFIX."projekte as B on B.ID = ".DB_PREFIX."notes.projekt
                 where (".DB_PREFIX."notes.acc like 'system' or ((".DB_PREFIX."notes.von = $user_ID or ".DB_PREFIX."notes.acc like 'group' or ".DB_PREFIX."notes.acc like '%\"$user_kurz\"%') and ".DB_PREFIX."notes.gruppe = $user_group))
                       $wherein
              order by ".DB_PREFIX."notes.name";
      $export_array = make_export_array($query);
      // replace the date strings like 20040101142000 with 2004-01-01 14:20
      for ($i=0; $i < count($export_array); $i++) {
        $export_array[$i][3] = show_iso_date1($export_array[$i][3]);
        $export_array[$i][4] = show_iso_date1($export_array[$i][4]);
      }
      break;


    case 'calendar':
    case 'calendar_detail':
      $fields = array("termine.ID", "termine.event",
                      "termine.datum", "termine.anfang", "termine.ende", "termine.remark",
                      "contacts.vorname", "contacts.nachname", "contacts.firma", "contacts.email",
                      "termine.visi");
      $f_lang = array('ID', __('Text'), __('Date'), __('From'), __('Until'), __('Remark'),
                      __('Contacts').'_'.__('First Name'), __('Contacts').'_'.__('Family Name'),
                      __('Contacts').'_'.__('Company'), __('Contacts').'_Email', __('Visibility'));
      $query = "select ".implode(", ", $fields)."
                  from ".DB_PREFIX."termine as termine
             left join ".DB_PREFIX."contacts as contacts on termine.contact = contacts.ID
                 where termine.an = '$user_ID' ";
      if ($file == "calendar_detail") $query .= " AND termine.ID = '$ID[0]' ";
      $export_array = make_export_array($query);
    break;

    case 'filemanager':
      die('Sorry, no file export available!');
    break;

    default:
      die("You are not allowed to do this!");
}


// **********
// set header
// print and html should be shown inline, the rest delivered as attachment
if (ereg("html|print", $medium)) {
  $file_download_type = "inline";
  $name = $file.".html";
}
else {
  $name = $file.".".$medium;
  $file_download_type = "attachment";
}
if (!ereg("pdf", $medium))$include_path = $path_pre."lib/get_contenttype.inc.php";
include_once $include_path;
// end set header


switch ($medium) {

  // ***********************
  // iCal export
  case "ics":
  if ($file != 'calendar') die("ical/vcal export not supported for this file");
  error_reporting(E_ALL);
  error_log(__LINE__);
  echo export_user_cal($export_array, 'ical');
  error_log(__LINE__);
  break;

  case "vcs":
  if ($file != 'calendar' and $file != 'calendar_detail') die("ical/vcal export not supported for this file");

  echo export_user_cal($export_array, 'vcal');
  break;


  // **********
  // pdf output
  case "pdf":
    $table = array();

    if (   !is_file($path_pre.'lib/pdf/class.ezpdf.php')) { die("Panic - cannot find the required pdf classes! Read the faq_install.html for the required steps to install this library or disable the pdf support in the config or choose another export format"); }
    else { include ($path_pre.'lib/pdf/class.ezpdf.php'); }
    if($file == 'contacts'){
        $wordwrap_map = array(
            0 => 4,
            1 => 8,
            2 => 8,
            3 => 14,
            4 => 12,
            5 => 17,
            6 => 17,
            7 => 17,
            8 => 20,
            9 => 12,
            10 => 8,
            11 => 12,
            12 => 20,
            13 => 12,
            14 => 14,
            15 => 17,
            16 => 14,
            17 => 10,
            18 => 10,
            19 => 10
        );
        $e_count = 0;
        foreach($export_array as $exp_entry){
            $c_count = 0;
            foreach($exp_entry as $col){
                $export_array[$e_count][$c_count] = wordwrap ($col, $wordwrap_map[$c_count], "\n");
                $c_count ++;
            }
            $e_count ++;
        }
        $pdf = new Cezpdf(array(0,0,1441.89,595.28),'landscape');
    }
    else{
        $pdf = new Cezpdf('A4','landscape');
    }

    $pdf->selectFont($path_pre.'lib/pdf/fonts/Helvetica');
     foreach ($export_array as $line) {
      $line2 = array();
      for ($i=0;$i < count($line); $i++) {
        $line2[replace_special_chars($f_lang[$i])] = $line[$i];
      }
      $table[] = $line2;
    }
    $pdf->ezTable($table,'','PHProjekt export file',array('fontSize'=>8));
    $pdf->ezStream();
  break;

  // ************
  // chart output
  // ************

  case "chart":
  break;

  // **********
  // xml output
  case "xml":
    $xmlstring = "<?xml version=\"1.0\"?>\n";
    $xmlstring .= "<table>\n";
    if ($export_array) {
      foreach ($export_array as $line) {
        $xmlstring .= "  <record>\n";
        for ($i=0; $i < count($line); $i++) {
          $xmlstring .= "    <".wellformed($f_lang[$i])."><![CDATA[".$line[$i]."]]></".wellformed($f_lang[$i]).">\n";
        }
        $xmlstring .= "  </record>\n";
      }
    }
    $xmlstring .= "</table>";
    echo $xmlstring;
  break;

   // **********
  // rtf output
  case "rtf":
    $rtfstring = "{\\rtf1\\ansi\\ansicpg1252\\deff0\\deflang2055{\\fonttbl{\\f0\fnil\\fcharset0 Helvetica;}}\n";
    $rtfstring .= "\\viewkind4\\uc1\\pard\\f0\\fs20";
    // first the header ...
    for ($i=0; $i<count($f_lang)-1;$i++) {
      $rtfstring .= "\\b $f_lang[$i]\\b0\\tab";
    }
    $rtfstring .= "\\b $f_lang[$i]\\b0\\par\n";

    // now the content
    foreach ($export_array as $line) {
      for ($i=0;$i < count($line)-1; $i++) {
        $rtfstring .= " $line[$i] \\tab";
      }
      $rtfstring .= " $line[$i]\\par\n";
    }
    $rtfstring .="}";
    echo replace_special_chars($rtfstring);
  break;

   // **********
  // doc output - same as rtf, but only theheader is slightly different :-)
  case "doc":
    $rtfstring = "{\\rtf1\\ansi\\ansicpg1252\\deff0\\deflang2055{\\fonttbl{\\f0\fnil\\fcharset0 Helvetica;}}\n";
    $rtfstring .= "\\viewkind4\\uc1\\pard\\f0\\fs20";
    // first the header ...
    for ($i=0; $i<count($f_lang)-1;$i++) {
      $rtfstring .= "\\b $f_lang[$i]\\b0\\tab";
    }
    $rtfstring .= "\\b $f_lang[$i]\\b0\\par\n";

    // now the content
    foreach ($export_array as $line) {
      for ($i=0;$i < count($line)-1; $i++) {
        if (!$line[$i]) $line[$i] = " ";
        $rtfstring .= " $line[$i]\\tab";
      }
      if (!$line[$i]) $line[$i] = " ";
      $rtfstring .= " $line[$i]\\par\n";
    }
    $rtfstring .="}";
    echo replace_special_chars($rtfstring);
  break;

  // ****************
  // normal print page
  case "print":
  // begin body of html page and table
  echo "<html><body bgcolor=ffffff onLoad='self.print()'><table border=1 cellpadding=1 cellspacing=0>\n";
  // first put the colums
  echo "<tr>";
  for ($i=0; $i<count($f_lang);$i++) { echo "<td>$f_lang[$i]</td>\n"; }
  echo "</tr>\n";
  // now the content
  foreach ($export_array as $line) {
    echo "<tr>\n";
    foreach ($line as $element) {
      echo "<td>$element&nbsp;</td>";
    }
    echo "</tr>\n";
  }
  // end of table and html page
  echo "</table></body></html>";
  break;

  // ***********************
  // xls export - very similar to csv
  case "xls":
    // begin file
    $xlsstring = pack( "ssssss", 0x809, 0x08, 0x00,0x10, 0x0, 0x0 );
    // write 1. line = field names
    for ($i=0; $i<count($f_lang);$i++) {
      $xlsstring .= pack( "s*", 0x0204, 8+strlen($f_lang[$i]), 0, $i, 0x00, strlen($f_lang[$i]) );
      $xlsstring .= $f_lang[$i];
    }
    // write content
    $a=0;
    foreach ($export_array as $line) {
      for ($i=0;$i < count($line); $i++) {
        // special patch for xl since it doesn't understand \r as the line ende
        $line[$i] = str_replace("\r", "", $line[$i]);
        $xlsstring .= pack( "s*", 0x0204, 8+strlen($line[$i]), $a+1, $i, 0x00, strlen($line[$i]) );
        $xlsstring .= $line[$i];
      }
      $a++;
    }
    $xlsstring .= pack("ss", 0x0A, 0x00);
    echo $xlsstring;
    break;

  // ***********************
  // html - similar to print page
  case "html":
    // begin body of html page and table
    echo "<html><body bgcolor=".PHPR_BGCOLOR3."><table border=1 cellpadding=1 cellspacing=0>\n";
    // first put the colums
    echo "<tr bgcolor=".PHPR_BGCOLOR2.">";
    for ($i=0; $i<count($f_lang);$i++) { echo "<td><b>$f_lang[$i]</b></td>\n"; }
    echo "</tr>\n";
    // now the content
    if ($export_array) {
      foreach ($export_array as $line) {
        // alternate bgcolor
        if (($cnr/2) == round($cnr/2)) { $color = PHPR_BGCOLOR1; $cnr++;}
        else { $color = PHPR_BGCOLOR2; $cnr++; }
        echo "<tr bgcolor=$color>\n";
          foreach ($line as $element) {
          echo "<td>$element&nbsp;</td>";
        }
        echo "</tr>\n";
      }
    }
    // end of table and html page
    echo "</table></body></html>";
    break;

  // ***********************
  // default case: csv export
  default:
    foreach ($export_array as $line) {
       foreach ($line as $element) {
        /*
        if (ereg(":{",$element)) {
          $element = unserialize($element);
          $element = implode(":", $element);
        }*/
        // delete end of lines in data
        $element = rtrim(eregi_replace("\n|\r"," ",$element));

        // mask doublequotes in data for reimport
        $element = ereg_replace('"','""',$element);
        echo "\"$element\"";
        if ( $i < (count($line)-1) ) { echo ","; $i++; }
        else { echo "\n";  $i = 0; }

      }
    }
    break;
}


// check whether this admin has the permission to export the timecard from this user
function check_admin_perm() {
  global $user_group, $user_access, $pers_ID;

  // 1. check: is this user an admin?
  if (!ereg("a",$user_access)) { die("you are not allowed to do this!"); }

  // 2. check for the right group - only if it is a group admin
  if ($user_group > 0) {
    // loop over all groups where the mentioned user is member
    $result = db_query("select grup_ID
                          from ".DB_PREFIX."grup_user
                         where user_ID = '$pers_ID'") or db_die();
    while ($row = db_fetch_row($result)) {
      // one entry matches the group of the admin? -> fine :-)
      if ($row[0] == $user_group) { $ok = 1; }
    }
    // no entry found -> die ...
    if (!$ok) { die("you are not allowed to do this!"); }
  }
}

function make_export_array($query) {
  $result = db_query($query) or db_die();
  while ($row = db_fetch_row($result)) {
    $line = array();
    foreach($row as $element) { $line[] = $element; }
    $export_array[] = $line;
  }
  return $export_array;
}



/**
function make_export_array_projects($query,$parent) {
  global $export_array, $fields2, $level, $path;

  $categories = array( "1" => __('offered'), "2" => __('ordered'), "3" => __('Working'), "4" => __('ended'),
                     "5" => __('stopped'), "6" => __('Re-Opened'), "7" => __('waiting'));


  $result = db_query($query." and parent = '$parent' order by name") or db_die();
  while ($row = db_fetch_row($result)) {
    $line = array();
    $i = -1;
    $line[0] = $level;
    foreach($row as $element) {
      $element2 = '';
      //patch for project leader
      if ($fields2[$i] == 'chef') {  $element2 = slookup('users','nachname','ID',$element); }
      elseif ($fields2[$i] == 'personen') {
        $persons = unserialize($element);
        foreach($persons as $pers) $element2 .= slookup('users','nachname','kurz',$pers).',';
      }
      elseif ($fields2[$i] == 'kategorie') $element2 = $categories[$element];
      else $element2 = $element;
      $line[] = $element2;
      $i++;
    }
    $path[] = $row[1];
    $line[] = implode('|',$path);
    $export_array[] = $line;
    $level++;
    make_export_array_projects($query,$row[0]);
    $level--;
    $tmp1 = array_pop($path);
  }
  return $export_array;
}
*/
function make_export_array_projects($query,$parent) {
  global $export_array, $fields2, $proj_text20, $proj_text21, $proj_text23, $proj_text24, $proj_text25, $proj_text26, $proj_text27;
  $categories = array( "1" => "$proj_text20", "2" => "$proj_text21", "3" => "$proj_text23", "4" => "$proj_text24",
                     "5" => "$proj_text25", "6" => "$proj_text26", "7" => "$proj_text27");


  $result = db_query($query." and parent = '$parent' order by name") or db_die();
  while ($row = db_fetch_row($result)) {
    $line = array();
    $i = -1;

    foreach($row as $element) {
        if($i>=0){
      $element2 = '';
      //patch for project leader
      if ($fields2[$i] == 'chef') {  $element2 = slookup('users','nachname','ID',$element); }
      elseif ($fields2[$i] == 'personen') {
        $persons = unserialize($element);
        foreach($persons as $pers) $element2 .= slookup('users','nachname','kurz',$pers).',';
      }
      elseif ($fields2[$i] == 'kategorie') $element2 = $categories[$element];
      else $element2 = $element;
      $line[] = $element2;
     }
      $i++;
    }
    $export_array[] = $line;
    make_export_array_projects($query,$row[0]);
  }
  return $export_array;
}

function wellformed($str) {
  return preg_replace('#[^a-zA-Z0-9]#','_',$str);
}


/**
* Build an ical/vcal-export-string from $export_array (generated by export.php)
*
* @author Franz Graf
* @see http://www.faqs.org/rfcs/rfc2445.html Internet Calendaring and Scheduling Core Object Specification (iCalendar)
* @see http://www.imc.org/pdi/pdiproddev.html vcalendar specification
*
* @param array $export_array as created by export.php for file=calendar
* @param bool $toUTF8 whether output should be converted to UTF8 or not
* @param string ical|vcal
* @return string ical- or vcal-export-file as string
*/
function export_user_cal($export_array, $format) {
  $end = chr(13).chr(10);

  // Vcal and ical are _almost_ the same.
  // ical default:
  $encoding = "";
  $version  = "2.0";

  // vcal diffenrences
  if ($format=='vcal') {
    $version = "1.0";
    $encoding = ";ENCODING=8-bit";
  }

  // Converts a value to a corrrect vcs/ics-value
  // At least Mozilla Sunbird looses trailing umlauts when importing ICS-files
  // Don't know whether that's a bug of kde-korganizer or sunbird
  function prepareString ($string) {
    global $end;
    $string = addslashes($string);
    $string = str_replace($end, "\n", $string);
    $string = str_replace("\n", "\\n", $string);
    return $string;
  }

  // Format of export-array:
  // 0 : id
  // 1 : event (one line w/out \n)
  // 2 : date
  // 3 : begin
  // 4 : end
  // 5 : remark
  // 6 : contact-vorname
  // 7 : contact-nachname
  // 8 : contact-firma
  // 9 : contact-email
  // 10: visibility

  // create header
  $outString  = 'BEGIN:VCALENDAR'.$end;
  $outString .= 'VERSION:'.$version.$end;
  $outString .= 'X-WR-CALNAME:projekt//'.$end;
  $outString .= 'X-WR-TIMEZONE:Europe/Paris'.$end;
  $outString .= 'CALSCALE:GREGORIAN'.$end;

  // process all events
  foreach ($export_array as $line) {

    // remove "-" from date
    $date = str_replace('-', '', $line[2]);

    // build "head" of an event
    $outString .= 'BEGIN:VEVENT'.$end;
    $outString .= 'DTSTART;TZID=Europe/Paris:'.$date.'T'.$line[3].'00'.$end;
    $outString .= 'UID:PHPCal-'.$line[0] .$end;
    $outString .= 'DTEND;TZID=Europe/Paris:'.$date.'T'.$line[4] . '00'. $end;
    $outString .= 'SUMMARY'.$encoding.':'.prepareString($line[1]).$end;

    // visibility
    if ($line[10]==1) { $outString .= 'CLASS:PRIVATE'.$end; }
    if ($line[10]==2) { $outString .= 'CLASS:PUBLIC'.$end; }

    // prepare remark
    if ( !empty($line[5]) ) {
      $outString .= 'DESCRIPTION'.$encoding.':'.prepareString($line[5]).$end;
    }

    // prepare contacts
    if ( !empty($line[6]) or !empty($line[7]) or !empty($line[8]) ) {
      // vorname name (firma)
      $name = prepareString($line[6])." ".prepareString($line[7]);
      if ( !empty($line[8]) ) { $name .= " (".prepareString($line[8]).")" ; }
      $outString .= 'ATTENDEE;CN="'.$name.'"';
      unset($name);

      // email
      if ( !empty($line[9]) ){ $outString .= ':mailto:'.prepareString($line[9]); }
      // iCal requires a colon if email is missing, bug?
	  else {$outString .= ':';}
      $outString .= $end;
    }// end contacts
    $outString .= 'END:VEVENT'.$end.$end;

  } // end foreach
  $outString .= 'END:VCALENDAR'.$end;

  return  utf8_encode($outString);
}
/**
 * replace some escaped chars to "printable" chars
 *
 * @author Alex Haslberger
 * @return string $str
 */
function replace_special_chars($str){
    $search = array(
        '°&auml;°',
        '°&ouml;°',
        '°&uuml;°',
        '°&Auml;°',
        '°&Ouml;°',
        '°&Uuml;°',
    );
    $replace = array(
        'ä',
        'ö',
        'ü',
        'Ä',
        'Ö',
        'Ü',
    );
    return(preg_replace($search, $replace, $str));
}
?>
