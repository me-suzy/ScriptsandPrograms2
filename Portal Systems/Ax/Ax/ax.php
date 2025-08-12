<?
/// Ax : mini power CMS without DATABASE
/// PIERRE JEAN DUVIVIER
/// VISIT http://www.agora-fr.org for AGORA SUNRISE POWERFULL CMS (COMPLETE AND FULL)
/// VISIT http://ax.agora-fr.org for Ax Website.
/// CONTACT Me at contact@pjduvivier.com
/// VERSION V1.3 (01/19/2004) - 19 janvier 2003 - french format
/// VISIT http://www.odebi.org. The freedom in France is threaten, help us to let know to the world that !


/// DECLARATION ZONE

session_start();

Global $menu;
Global $var_data_folder,$var_global_data;
Global $depth ;
Global $write;
Global $code;
Global $var_width_table,$var_cellpadding,$var_cellspacing,$var_border,$var_wleft,$var_wright;
Global $url,$article_online;
Global $body;
Global $var_position,$var_inside;
Global $var_edition;
Global $e_title,$e_home_text,$e_body_text,$e_position,$onsuite;
Global $var_border_incell,$var_cellpadding_incell,$var_cellspacing_incell,$var_no_limit;
Global $nombre_element,$stop_display;

/// LANGUAGE FILE

DEFINE("_MODIF","Modif");
DEFINE("_DELETE","Delete");
DEFINE("_ERROR","Error");
DEFINE("_Title","Title");
DEFINE("_HomeText","First Text");
DEFINE("_BodyText","Second Text");
DEFINE("_Position","Where ?");
DEFINE("_PASSWORD","Password");
DEFINE("_Admin","Admin");
DEFINE("_center","Center");
DEFINE("_left","Left");
DEFINE("_right","Right");

///



//// CONFIGURATION FILE ////

/// PLACE HERE YOUR AX CONFIGURATION FILE ///#

/// GENERAL PARAMATERS

$var_title="Ax v1.3 - an simple XML content management system without database -Visit ax.agora-fr.org";
$var_file="ax.php"; // NAME OF THE SCRIPT RUNNING (Ax.PHP by default) & NAME OF THE SCRIPT WHERE REDIRECT AFTER AN ADMIN OPERATION
$write="true";      // WRITE BY DEFAULT FOR XML BALISE
$var_background_color="white"; // COLOR OF THE WHOLE BACKGROUND
$var_admin_background_color="#EFEFEF"; // COLOR THE ADMIN BACKGROUND
/// TABLES PARAMETERS

// MAIN TABLE

$var_width_table="100%";        // WIDTH OF THE MAIN TABLE
$var_cellpadding=0;             // CELLPADDING OF THE MAIN TABLE
$var_cellspacing=3;  // CELLSPACING OF THE MAIN TABLE
$var_border=0;       // BORDER OF THE MAIN TABLE
$var_left_block=1;   // IF YOU WANT LEFT BLOCK SET 0 IF YOU DON T WANT IT
$var_right_block=1; // IF YOU WANT RIGHT BLOCK SET 0 IF YOU DON T WANT IT


// -----------

// CELLS INSIDE THE MAIN TABLE WITH THE DATA (EACH ARTICLE IN FACT IS A TABLE)

$var_border_incell=0;    // WITH OF THE BORDER OF THE CELL
$var_cellpadding_incell=0;    // CELLSPADDING OF THE CELL
$var_cellspacing_incell=0;   // CELLSPACING OF THE CELL
$var_bgcolor_title="#F78A1A";  // BACKGROUND COLOR OF THE TITLE
$var_bgcolor_time="white";  // BACKGROUND COLOR OF THE TIME
$var_bgcolor_home="white"; // BACKGROUND COLOR OF THE FIRST TEXT
$var_bgcolor_body="white"; // BACKGROUND COLOR OF THE SECOND TEXT
$var_bgcolor_suite="white"; // BACKGROUND COLOR OF THE URL TEXT (SUITE, NEXT)

// COLS RIGHT AND LEFT

$var_wleft="15%";     // WIDTH OF THE LEFT COLS
$var_bgcolor_left="white";   // COLOR OF THE BACKGROUND OF THE LEFT COLS
$var_wright="15%";    // WIDTH OF THE RIGHT COLS
$var_bgcolor_right="white"; // COLOR OF THE BACKGROUND OF THE RIGHT COLS

// -------------------

/// END OF THE TABLES PARAMETERS

/// FOLDER OF THE AX DATA
$var_maximal_article=4; // NUMBER OF ARTICLE MAX FOR THE FIRST PAGE
$var_data_folder['main']="data/"; // FOLDER WHERE THE DATA ARE (RELATIVE PATH)
$var_data_folder['central']="central/"; // FOLDER OF THE CENTRAL DATA (RELATIVE PATH TO MAIN FOLDER)
$var_data_folder['right']="right/"; // FOLDER OF THE DATA OF THE RIGHT BLOCK (RELATIVE PATH TO MAIN FOLDER)
$var_data_folder['left']="left/"; // FOLDER OF THE DATA OF THE LEFT BLOCK   (RELATIVE PATH TO MAIN FOLDER)
$var_admin_password="axpassword"; // PASSWORD OF THE ADMIN SECTION (CHANGE IT OFTEN)
$var_global_data="globaldata/";
/// ---------------------

/// LIST OF VARIABLE TRANSMITED BETWEEN PAGES

if (isset($_REQUEST['url']))
{
    $url=$_REQUEST['url'];
}

if (isset($_REQUEST['var_edition']))
{
    $var_edition=$_REQUEST['var_edition'];
}

if (isset($_REQUEST['delete']))
{
    $delete=$_REQUEST['delete'];
}


if (isset($_REQUEST['modif']))
{
   $modif=$_REQUEST['modif'];
}


/// END OF VARIABLE TRANMISTED BY PAGE



/// BEGINING LIST OF FUNCTIONS

function aff_test($in)
{
    $in=ereg_replace("<","#",$in);
    echo $in;
}

 
function debutElement($parser, $name, $attrs) {

    Global $stop_display,$depth,$write,$code,$url,$article_online,$body,$nombre_element,$var_maximal_article;
    Global $var_border_incell,$var_cellpadding_incell,$var_cellspacing_incell;
    Global $var_bgcolor_title,$var_bgcolor_time,$var_bgcolor_home,$var_bgcolor_body,$var_bgcolor_suite;
    Global $var_no_limit,$url,$on_affichage;
    Global $var_in_side;




    
   if ($var_no_limit!=1)
   {

   if (($nombre_element)>$var_maximal_article)
       {

           $stop_display=1;
       }
   }
   if (isset($depth[$parser])) { $depth[$parser]++;}
   else {$depth[$parser]=1;}
   if ($stop_display!=1)
   {
   switch ($name)
   {


       case "DATA":
       $body="";


       break;
       
       case "ID":
       $code="id";
       $write=false;

       break;

       case "TITLE":
       if ($url=="")
       {
       Echo"<td bgcolor=$var_bgcolor_title class='titre'>";
       $write=true;
       }
       else
       {
           if ($on_affichage==1)
           {
             Echo"<td bgcolor=$var_bgcolor_title class='titre'>";
             $write=true;
           }
       }
       break;
       
       case "TIME":
       if ($url=="")
       {
       Echo"<tr>";
       Echo"<td bgcolor=$var_bgcolor_time class='time'>";
       $code="time";
       $write=false;
       }
       else
       {
           if ($on_affichage==1)
           {
             Echo"<tr>";
             Echo"<td bgcolor=$var_bgcolor_time class='time'>";
             $code="time";
             $write=false;
           }
       }

       break;
       
       case "HOME":
       if ($url=="")
       {
       Echo"<tr>";
       Echo"<td bgcolor=$var_bgcolor_home>";
       $write=true;
       }
       else
       {
        if ($on_affichage==1)
        {
          Echo"<tr>";
          Echo"<td bgcolor=$var_bgcolor_home>";
          $write=true;
        }
       }
       break;
       
       case "BDY":
       $code="body";
       if ($url==$article_online)
       {
       Echo"<tr>";
       Echo"<td bgcolor=$var_bgcolor_body>";
       $write=true;
       }
       else
       {
       $write=false;
       }
       break;
       
       case "URL";
       if ($on_affichage==1)
       {
       Echo"<tr>";
       Echo"<td bgcolor=$var_bgcolor_suite>";
       $write=false;
       $code="url";
       }
       break;
       
       
       
       default:
       $write=false;
       break;
       
   }
   }
   else
   {
       $write=false;
   }
}

function finElement($parser, $name) {
    global $depth,$url,$article_online,$stop_display,$nombre_element,$on_affichage,$var_in_side;


    $depth[$parser]--;
    if ($stop_display!=1)
    {
       switch ($name)
      {
       case "DATA":
       if ($url!="")
       {
           if ($on_affichage==1)
           {
               Echo"</table>";
           }
       }
       else
       {
           Echo"</table>";
       }

     if ($var_in_side==""){ $nombre_element++;}

       break;

       case "TITLE":
       if ($url=="")
       {
       Echo"</td>";
       Echo"</tr>";
       }
       else
       {
         if ($on_affichage==1)
         {
             Echo"</td>";
             Echo"</tr>";
         }
       }
       break;

       case "HOME":
       if ($url=="")
       {
       Echo"</td>";
       Echo"</tr>";
       }
       else
       {
         if ($on_affichage==1)
         {
             Echo"</td>";
             Echo"</tr>";
         }
       }
       break;
       
       case "TIME":
       if ($url=="")
       {
       Echo"</td>";
       Echo"</tr>";
       }
       else
       {
         if ($on_affichage==1)
         {
             Echo"</td>";
             Echo"</tr>";
         }
       }
       break;
       
       case "URL":
       
       if ($url=="")
       {
           if ($on_affichage==1)
           {
       Echo"</td>";
       Echo"</tr>";
           }
       }
       else
       {
         if ($on_affichage==1)
         {
             Echo"</td>";
             Echo"</tr>";
         }
       }
       break;
       
       case "BDY";

       if ($url==$article_online)
       {
       Echo"</td>";
       Echo"</tr>";
       }
       break;


     }
    }

}

function characterData($parser, $data) {
   Global $write,$stop_display,$var_in_side,$code,$on_affichage,$url,$article_online,$body,$var_position,$var_edition,$var_admin_password,$modif,$delete,$onsuite;
   Global $var_border_incell,$var_cellpadding_incell,$var_cellspacing_incell;
   Global $var_bgcolor_title,$var_bgcolor_time,$var_bgcolor_home,$var_bgcolor_body,$var_bgcolor_suite;

  if ($stop_display!=1)
  {

  if ($url=="") { if ($write) { Echo stripslashes($data);}}
  else
  {
      if (($on_affichage==1) || ($var_in_side!=""))
      {
         if ($write) { Echo "<p align=justify>".stripslashes($data)."</p>";}
      }
  }

   switch ($code)
  {
      case "time":
      if ($var_position=="central")
      {

      $time_aff=gmstrftime("%A %d/%m/%y %H:%M:%S",$data);
      if ($url=="") { Echo $time_aff;}
      else
       {
         if (($on_affichage==1) || ($var_in_side!=""))
         {
             Echo $time_aff;
         }
       }
      }
      break;
      
      case "url":
      if ($url!=$article_online)
      {
          if ($body!="")
          {
      Echo"<a href=$data>....</a>";
          }
      }
      break;
      
      case "id":

      Echo"<table width=100% border=$var_border_incell cellpadding=$var_cellpadding_incell cellspacing=$var_cellspacing_incell>";
      Echo"<tr>";

      $article_online=$data;
      if ($url!="")
      {
      if ($data==$url)
          {

          $on_affichage=1;
          }
          else
          {
          $on_affichage=0;
          }
      }
      else
      {
         $on_affichage=1;
      }
      
      if ($on_affichage==1)
      {
        Echo"<table width=100% border=$var_border_incell cellpadding=$var_cellpadding_incell cellspacing=$var_cellspacing_incell>";
        Echo"<tr>";
      }
      
      if ($var_edition==$var_admin_password)
      {
          Echo"<td>";
          Echo"<a href=?url=$data&modif=1&var_edition=$var_admin_password&menu=edition>"._MODIF."</a> | <a href=?url=$data&delete=1&var_edition=$var_admin_password&menu=delete>"._DELETE."</a>";
          Echo"</td>";
          Echo"</tr>";
          Echo"<tr>";
          if ($data==$url)
          {

          $onsuite=1;
          }
          else
          {
          $onsuite=0;
          }
      }
      break;
      
      case "body":
      $body="<p align=justif>".$data."</p>";
      break;
      
  }

  $code="";
  }
}
function parsing_xml($where)    // CLASSICAL PARSER XML
 {

  Global $var_data_folder,$var_position,$nombre_element,$var_maximal_article,$stop_display,$var_no_limit;
  $var_path=$var_data_folder['main'].$var_data_folder[$where];
  $var_position=$where;
  $xml_parser = xml_parser_create();
  xml_set_element_handler($xml_parser, "debutElement", "finElement");
  xml_set_character_data_handler($xml_parser, "characterData");
  
  if (!($fp = fopen("$var_path/data.xml", "r"))) {
    die("Impossible d'ouvrir le fichier XML");
                                   }
  $stop_display=0;
  while ($data = fread($fp, 4096)) {
      if (!xml_parse($xml_parser, $data, feof($fp))) {
        die(sprintf("erreur XML : %s &agrave; la ligne %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
       }



  }
   xml_parser_free($xml_parser);


 }
 

 function save_post($title,$home_text,$body_text,$position,$modif,$url_modif)      // SAVE THE POST OF THE ADMIN SECTION
 {
      Global $var_data_folder,$var_global_data;

      if ($position=="") {$position="central";}      // IF NO POSITION CHECKED, IT S THE CENTRAL POSITION
      
      $var_path=$var_data_folder['main'].$var_data_folder[$position];

      $block="";
      $time=time();
      $codage=$time."AAAA".$title.$home_text;
      $codage=md5($codage);
     if ($modif!=2) // IF IT S NOT DELETE
     {
      if ($modif!=1) // IF IT S NOT EDIT
      {
      $block.="<DATA>";
      $block.="<ID>$codage</ID>";
      }
      else
      {      // IF IT S EDIT.
      $block.="<ID>$url_modif</ID>";
      }


      $home_text=ereg_replace("Â"," ",$home_text);
      $title=ereg_replace("Â"," ",$title);
      $body_text=ereg_replace("Â"," ",$body_text);

      $block.="<TITLE><![CDATA[ $title ]]></TITLE>";
      $block.="<TIME>$time</TIME>";
      $block.="<HOME><![CDATA[$home_text]]></HOME>";
      $block.="<BDY><![CDATA[$body_text]]></BDY>";

      if ($modif==1) {$codagex=$url_modif;} else {$codagex=$codage;}
      
      $block.="<URL>?url=$codagex</URL>";
      if ($modif!=1)
      {
      $block.="</DATA>";
      }
     }  // END IF IT S NOT DELETE
      $contenu="";
      /// AJOUT DU HAUT ET DU BAS.
      $file=fopen("$var_path/data.xml","r");
      if ($file)
      {
          $l=0;
          while (!feof($file))
          {
              $lignes=fgets($file,4096);

              $l++;
              if ($l==1 ){ $first_line=$lignes;}
              else {$contenu.=$lignes;}
              
              
          }
      }

      
      fclose($file);

     if (($modif!=1) && ($modif!=2)) // IF IT S NOT DELETE AND IT S NOT EDIT
      {       /// 1st INSERT - NO MODIF ////
      $contenu=ereg_replace("<DOCUMENT>","",$contenu);
      $contenu=$first_line."<DOCUMENT>".$block.$contenu;
      
      $file=fopen("$var_path/data.xml","w");

      if ($file)
      {
          fputs($file,$contenu);
      }
      
      fclose($file);

      ### AJOUT DU FICHIER    pour des traitements ultérieurs
      
     } // END OF 1st INSERT NO MODIF
     else
     { // MODIF AND DELETE

      $contenu=$first_line.$contenu;
     #  aff_test($contenu);
      $Ids=explode("<ID>",$contenu);
      $nb_Ids=count($Ids);

      $reconst="";
      for ($a=0;$a<$nb_Ids;$a++)
      {
          $Id_v=$Ids[$a];
          $Id_vv=explode("</ID>",$Id_v);
          $Idss=$Id_vv[0];
         if ($Idss==$url_modif)
         {
            $Idsv=$Id_vv[1];
            $pattern=explode("</DATA>",$Idsv);
            $choix_pattern=$pattern[0];
          if ($modif==1) {  $reconst.=$block."</DATA><DATA>";} // IF IT S A MODIF
          if ($modif==2) {  $reconst.="";} // IF IT S A DELETE
         }
         else
         {
            if ($a!=0)
            {
            $reconst.="<ID>".$Id_v;
            }
            else
            {
            $reconst.=$Id_v;
            }
         }
      }

      $length_reconst=strlen($reconst);       // TO DETECT IF THERE IS ONLY ONE ARTICLE PUBLISHED AND CORRECT THE GENERAL CASE
      $detect_fin=substr($reconst,$length_reconst-6,$length_reconst);
      

      if ($detect_fin=="<DATA>")
      {
           $reconst=substr($reconst,0,$length_reconst-6)."</DOCUMENT>";
      }

     $file=fopen("$var_path/data.xml","w");     // WE SAVE HERE THE NEW XML

      if ($file)
      {
         fputs($file,$reconst);
      }

      fclose($file);

     }




      $ex_data="$title|$home_text|$body_text|$position";    // WE SAVE HERE THE FILE TEXT TO ALLOW AN MODIFICATION LATER

      if ($url_modif!="") {$codage=$url_modif;}
      
      $name=$var_data_folder['main']."/$var_global_data/$codage.txt";

      $file=fopen($name,"w");
      if ($file)
      {
          fputs($file,$ex_data);
      }
      fclose($file);
      
      ####

      

 }
 function head()                 // THE HEADER OF THE PAGE
 {
   global $var_file;
   Echo"<table width=100% cellpadding=0 cellspacing=0 border=0>";
   Echo"<tr>";
   Echo"<td>";
   Echo"<a href=$var_file><img src=logo/good_logo.gif border=0></a>";
   Echo"</td>";
   Echo"</tr>";
   Echo"</table>";
 }
 function foot()                 // THE FOOTER OF THE PAGE
 {
     Global $var_file,$var_edition,$var_admin_password;
     

  Echo"<br><center><a href=$var_file>[ X ]</a>  <a href=$var_file?menu=admin>["._Admin."]</a><br>";
  if ( isset($_SESSION['axpassword']))
  {
      $axpassword=$_SESSION['axpassword'];
      
  }   else {$axpassword="";}
  
  if ($axpassword==$var_admin_password)
  {
  Echo"<br><center><a href=$var_file?var_edition=$var_admin_password>[ "._MODIF." & "._DELETE." ]</a><br>";
  }
  Echo"<a href=http://ax.agora-fr.org target=1>[ || Powered by {Ax} v1.3.1 || ]</a>";
  Echo"</center>";
 }
 
 
 function display_index()        // DISPLAY THE WEBSITE
 {
    global $var_no_limit,$var_left_block,$var_right_block,$var_in_side,$url,$var_title,$var_width_table,$var_cellpadding,$var_cellspacing,$var_border,$var_wleft,$var_wright,$var_background_color;
    Echo"<!doctype html public \"-//W3C//DTD HTML 4.0 //EN\">";
    Echo"<html><head>";
    Echo"<link href=\"css.css\" rel=\"stylesheet\" type=\"text/css\">";
    Echo"<TITLE>$var_title</TITLE>";
    echo"<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\">";
    echo"</head>";
    Echo"<body bgProperties=fixed  leftMargin=0  topMargin=0 marginheight=0 marginwidth=0 bgcolor=$var_background_color>";
    head();
    Echo"<Table width=$var_width_table cellpadding=$var_cellpadding cellspacing=$var_cellspacing border=$var_border>";
    Echo"<tr>";
     if ($var_left_block==1)
    {
    if ($url=="")
    {
    Echo"<td valign=top width=$var_wleft>";// COL 1  - LEFT
    $var_no_limit=1;
    $var_in_side="left";
    parsing_xml("left");
    Echo"</td>";
    }
     }
    Echo"<td valign=top>"; // CENTRAL COLS - CENTRAL
    if ($url=="")
    {
    $var_no_limit=0;
    }
    else
    {
    $var_no_limit=1;
    }
    $var_in_side="";
    if ($url=="")
    {
    parsing_xml("central");
    }
    else
    {
     parsing_xml("left");
     parsing_xml("central");
     parsing_xml("right");
    }
    Echo"</td>";
     if ($var_right_block==1)
    {
    if ($url=="")
    {
    Echo"<td valign=top width=$var_wright>";
    $var_no_limit=1;
    $var_in_side="right";

    parsing_xml("right");  // COL 3 - RIGHT

    Echo"</td>";
     }
    }
    Echo"</tr>";
    Echo"</table>";
    foot();

    Echo"</body>";
    Echo"</html>";
 }
 
 function admin_manager()         // WHERE YOU POST A NEW ARTICLE  & MANAGE THE WEBSITE
 {
     Global $var_admin_password,$e_title,$var_file,$e_position,$e_home_text,$e_body_text,$url,$modif,$var_edition,$var_data_folder,$var_global_data;
     Global $var_background_color;
     Global $var_admin_background_color;

         include('html_area_2.php');


        // IF IT S A MODIF I LOAD THE DATA OF THE FILES TO MODIDY
           $contenu="";
           if ($modif==1)
          {
             $name=$var_data_folder['main']."/$var_global_data/$url.txt";
             $file=fopen($name,"r");
             if ($file)
             {

                while (!feof($file))
                   {
                    $contenu.=fgets($file,4096);
                   }
             }
             else
             {
              Echo _ERROR;
             }
             
          $divide=explode("|",$contenu);
          $e_title=stripslashes($divide[0]);
          $e_home_text=stripslashes($divide[1]);
          $e_body_text=stripslashes($divide[2]);
          $e_position=$divide[3];
          }
          
        // ---- END OF THE MODIF QUERY
        $block="";
         $block.="<link href=\"css.css\" rel=\"stylesheet\" type=\"text/css\">";
         $block.="<body bgProperties=fixed  leftMargin=0  topMargin=0 marginheight=0 marginwidth=0 bgcolor=$var_admin_background_color>";
        $block.="<body onload=\"initEditor()\">";
        $block.="<form name=\"form1\" method=\"post\" action=\"\">
  <table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
      <td><div align=\"center\">"._Title."</div></td>
      <td> <div align=\"center\">
          <input name=\"title\" type=\"text\" id=\"title\" size=\"60\" maxlength=\"255\" value=\"$e_title\">
        </div></td>
    </tr>
    <tr>
      <td><div align=\"center\">"._HomeText."</div></td>
      <td><div align=\"center\">
          <textarea name=\"home_text\" cols=\"90\" rows=\"15\" id=\"home_text\">$e_home_text</textarea>
        </div></td>
    </tr>
    <tr>
      <td><div align=\"center\">"._BodyText."</div></td>
      <td><div align=\"center\">
          <textarea name=\"body_text\" cols=\"90\" rows=\"15\" id=\"body_text\">$e_body_text</textarea>
        </div></td>
    </tr>";
    $block.="
    <tr>
      <td><div align=\"center\">"._Position."</div></td>
      <td> <div align=\"center\">";

          if ($e_position=="central") {$check_c="checked";} else {$check_c="";}
          if ($e_position=="left") {$check_l="checked";} else {$check_l="";}
          if ($e_position=="right"){$check_r="checked";} else {$check_r="";}


      $block.="<input type=\"radio\" name=\"position\" value=\"central\" $check_c>"._center."
          <input type=\"radio\" name=\"position\" value=\"left\" $check_l>"._left."

          <input type=\"radio\" name=\"position\" value=\"right\" $check_r>"._right."
         </div>";
    $block.="</td>
    </tr>
    <tr>
      <td colspan=\"2\"><div align=\"center\"></div>
        <div align=\"center\">
          <input type=\"hidden\" name=\"modif\" value=$modif>
          <input type=\"hidden\" name=\"url_modif\" value=\"$url\">
          <input type=\"hidden\" name=\"menu\" value=\"register\">
          <input type=\"submit\" name=\"Submit\" value=\"Submit\">
        </div></td>
    </tr>
  </table>
  </form>";
  $block.="<br><center><a href=$var_file?var_edition=$var_admin_password>"._MODIF." & "._DELETE."</a> <a href=$var_file>AX</a></center>";
  $block.="</BODY>";
  return $block;
 }

/// END OF THE FUNCTION LIST



/// BEGINNING OF THE MAIN PHP SCRIPT

 if (isset($_REQUEST['menu']))
  {
    $menu=$_REQUEST['menu'];
  }
 if (isset($_POST['menu']))
  {
    $menu=$_POST['menu'];
  }

switch ($menu)
{
    case 'delete':    /// MENU TO DELETE AN ARTICLE
    
    if ($var_edition==$var_admin_password)
    {
      if ($delete==1) {$modif=2;} // MODIF=2 MEANS THAT THE URL HAVE TO BE DELETED
      $pos[0]="central";$pos[1]="left";$pos[2]="right"; // BECAUSE WE DON'T KNOW WHERE THE ARTICLE IS...
      $title="";$home_text="";$body_text="";
      for ($a=0;$a<=2;$a++)
      {
      $retour=save_post($title,$home_text,$body_text,$pos[$a],$modif,$url); // WE USE THE SAME FUNCTION TO SAVE, EDIT OR DELETE AN URL !
      if ($retour==1) { break;}
      }
    }
      header("location:$var_file");
      #Echo" <META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$var_file\">";
    
    break;



    case 'parse':      /// JUST FOR TEST
    
    if (isset($_REQUEST['position']))
    {
        $position=$_REQUEST['position'];
    }
       parsing_xml("$position");
    
    break;



    case 'admin':     /// MENU TO INSERT A NEW ARTICLE

     if ( (isset($_REQUEST['axpassword'])) || (isset($_POST['axpassword'])) || (isset($_SESSION['axpassword'])) )
     {
        if (isset($_POST['axpassword']))
        {
        $axpassword=$_POST['axpassword'];
        }
        if (isset($_REQUEST['axpassword']))
        {
        $axpassword=$_REQUEST['axpassword'];
        }
        if (isset($_SESSION['axpassword']))
        {
        $axpassword=$_SESSION['axpassword'];
        }
        
        
        if ($axpassword==$var_admin_password)
           {
               $display=admin_manager();
               echo $display;
               $_SESSION['axpassword']=$var_admin_password;
           }
     }
     else
     {
     $block="";
     $block.="<form name=\"form1\" method=\"post\" action=\"\">
  <table width=\"600\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
      <td><div align=\"center\">"._PASSWORD."</div></td>
      <td> <div align=\"center\">
          <input name=\"axpassword\" type=\"password\" id=\"axpassword\" size=\"40\" maxlength=\"40\">
        </div></td>
    </tr> <tr><td colspan=2 align=center><input type=\"submit\" name=\"ok\" value=\"ok\"></td></tr></table>";
    $block.="<input type=\"hidden\" name=\"menu\" value=\"admin\">";

    $block.="</form>";
      Echo $block;
     }
    break;
    
    
    case 'register':  // AFTER THE FORM PROCESS, WHERE THE INSERT(or the EDIT) WILL BE DONE.

     if (isset($_POST['title']))
      {
        $title=$_POST['title'];
      }
     if (isset($_POST['home_text']))
      {
        $home_text=$_POST['home_text'];
      }
     if (isset($_POST['body_text']))
     {
        $body_text=$_POST['body_text'];

     }
     if (isset($_POST['position']))
     {
        $position=$_POST['position'];
     }
     if (isset($_POST['modif']))
     {
         $modif=$_POST['modif'];
     }
     if (isset($_POST['url_modif']))
     {
         $url_modif=$_POST['url_modif'];
     }

     save_post($title,$home_text,$body_text,$position,$modif,$url_modif);
     Echo" <META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$var_file\">";
    break;
    
    case 'edition':         // TO EDIT ....
     if ($var_edition==$var_admin_password)
     {

     $admin=admin_manager();
     Echo $admin;
     }
     else
     {
      Echo _ERROR;
     }

    break;
    

    default:
    display_index();    // DISPLAY THE WHOLE WEBSITE
    
}
?>
