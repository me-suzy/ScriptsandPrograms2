<?php /* ***** Orca Search - Javascript Writer Plugin ***************
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
*
* See the readme.txt file for installation instructions.
****************************************************************** */

include "config.php";


function array_size($arr) {
  ob_start();
  print_r($arr);
  $mem = ob_get_contents();
  ob_end_clean();
  $mem = preg_replace("/\n +/", "", $mem);
  $mem = strlen($mem);
  return $mem;
}

/* *************************************************************** */
/* ***** Javascript File Write *********************************** */
header("OrcaScript: Search_JWriter");

if ((isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] && $_SERVER['QUERY_STRING'] == $vData['jw.key']) || $_SERVER['QUERY_STRING'] == "secret") {
  $jData['replaceExtensions'] = array_map("trim", explode("\n", $vData['jw.extfrom']));
  $jData['stripBaseURIs'] = array_filter(array_map("trim", explode("\n", $vData['jw.remuri'])));

  $jData['memupdate'] = true;

  $jData['entities'] = array("&" => "&amp;",
                             "" => "&trade;",
                             "®" => "&reg;",
                             "±" => "&plusmn;",
                             "°" => "&deg;",
                             ">" => "&gt;",
                             "<" => "&lt;",
                             "\"" => "&quot;",
                             "©" => "&copy;");

  if ((int)ini_get("memory_limit")) {
    $dData['memlimit'] = (ini_set("memory_limit", ((int)ini_get("memory_limit") + 1)."M")) ? true : false;
  } else $dData['memlimit'] = NULL;

  $cData['indextable'] = mysql_fetch_assoc(mysql_query("SHOW TABLE STATUS LIKE '%{$dData['tablename']}%';"));
  $cData['indexmem'] = $cData['indextable']['Data_length'] / 1048576;

  $lq = ($vData['s.orphans'] == "show") ? " AND (`status`='OK' OR `status`='Orphan')" : " AND `status`='OK'";

  $nq = "";
  $sData['noSearch'] = array_filter(array_map("trim", explode("\n", $vData['s.ignore'])));
  foreach ($sData['noSearch'] as $noSearch)
    $nq .= " AND `uri` NOT ".(($noSearch{0} == "*") ? "REGEXP '".substr($noSearch, 1)."'": " LIKE '%{$noSearch}%'");

  $select = mysql_query("SELECT `uri`, `title`, `category`, `description`, `keywords`, `body` FROM `{$dData['tablename']}` WHERE `unlist`!='true'{$lq}{$nq};");

  ob_start();

?>/* ******************************************************************
* <?php echo $dData['userAgent']; ?> 
*    - Offline Javascript File
*
* Generated <?php echo date("r"); ?> 
****************************************************************** */

/* ***** User Options ******************************************** */
var os_template = "<?php echo str_replace("\n", '\n', addslashes($vData['jw.template'])); ?>";
var os_resultspp = <?php echo $vData['jw.pagination']; ?>;


/* ***** Begin Timing ******************************************** */
var os_mark = new Date();
var os_then = os_mark.getTime();


/* ***** Entry Object Constructor ******************************** */
function os_entry(category, title, uri, description, keywords, text) {
  this.category = category;
  this.uri = uri;
  this.title = (title) ? title : uri;
  this.keywords = keywords;
  this.description = (description) ? description : text.substr(0, 200);
  this.text = text;
  this.matchtext = " [[[strong]]]...[[[/strong]]] ";
  this.relevance = 0;
  this.words = -1;
}


/* ***** Heapsort ************************************************ */
function heapsort() {
  if (this.length <= 1) return;
  this.unshift("");
  var ir = this.length - 1;
  var l = (ir >> 1) + 1;
  while (1) {
    if (l <= 1) {
      var rra = this[ir];
      this[ir] = this[1];
      if (--ir == 1) {
        this[1] = rra;
        this.shift();
        return this;
      }
    } else var rra = this[--l];
    var i = l;
    var j = l << 1;
    while (j <= ir) {
      if ((j < ir) && (this[j].relevance < this[j + 1].relevance)) j++;
      if (rra.relevance < this[j].relevance) {
        this[i] = this[j];
        j += (i = j);
      } else j = ir + 1;
    }
    this[i] = rra;
  }
}
Array.prototype.heapsort = heapsort;


/* ***** Number Format ******************************************* */
function numFormat(num, decimalNum, bolLeadingZero, bolParens) {
  var tmpNum = num;
  tmpNum *= Math.pow(10, decimalNum);
  tmpNum = Math.round(tmpNum);
  tmpNum /= Math.pow(10, decimalNum);
  var tmpStr = new String(tmpNum);
  if (!bolLeadingZero && num < 1 && num > -1 && num !=0) 
    tmpStr = (num > 0) ? tmpStr.substring(1, tmpStr.length) : "-" + tmpStr.substring(2, tmpStr.length);                        
  if (bolParens && num < 0) tmpStr = "(" + tmpStr.substring(1, tmpStr.length) + ")";
  return tmpStr;
}


/* ***** Website Entry Database ********************************** */
var os_entries = [
  <?php $ignored = array();
  $good = array();
  $done = 0;
  $rowcount = mysql_num_rows($select);

  while ($row = mysql_fetch_assoc($select)) {
    $bodylist = str_replace(array("\n", "\r", "  "), " ", $row['body']);
    $bodylist = array_unique(explode(" ", $bodylist));
    unset($row['body']);
    $row['compact'] = "";

    foreach ($bodylist as $body) {
      $body = trim($body, "\n\r .,;:?&[]{}()0123456789/\\-_=+*^%$#@!`~<>|\"'");
      if (strlen($body) >= $vData['s.termlength']) {
        if (!in_array($body, $good)) {
          if (!in_array($body, $ignored)) {
            list($count) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `{$dData['tablename']}` WHERE `unlist`!='true' AND `body` LIKE '%".addslashes($body)."%'{$lq}{$nq};"));
            if ($count < $rowcount / 3) {
              $good[] = $body;
              $row['compact'] .= $body." ";
            } else $ignored[] = $body;
          }
        } else $row['compact'] .= $body." ";
      }
    }


    if (time() % 20 == 19) set_time_limit(30);
    set_vData("jw.progress", ($done++ / mysql_num_rows($select)) * 100);

    if (time() % 2 == 0) {
      if ($jData['memupdate']) {
        set_vData("jw.memory", $cData['indextable']['Data_length'] + array_size($GLOBALS));
        $jData['memupdate'] = false;
      }
    } else $jData['memupdate'] = true;


    foreach ($jData['stripBaseURIs'] as $stripBaseURIs)
      $row['uri'] = preg_replace("/^".preg_quote($stripBaseURIs, "/")."/i", "", $row['uri']);

    if ($row['uri'] == "" || $row['uri']{strlen($row['uri']) - 1} == "/") $row['uri'] .= $vData['jw.index'];

    if ($vData['jw.extto']) {
      foreach ($jData['replaceExtensions'] as $replaceExtensions)
        $row['uri'] = preg_replace("/\.{$replaceExtensions}($|\?)/i", ".{$vData['jw.extto']}$1", $row['uri']);

      $row['uri'] = preg_replace("/(^|\/)([^.]*?)($|\?)/i", "$1$2.{$vData['jw.extto']}$3", $row['uri']);
    }

    $row['title'] = strtr($row['title'], $jData['entities']);
    $row['description'] = strtr($row['description'], $jData['entities']);

    $row = array_map("addslashes", $row);
    ?>new os_entry('<?php echo $row['category']; ?>', '<?php echo $row['title']; ?>', '<?php echo $row['uri']; ?>', '<?php echo $row['description']; ?>', '<?php echo $row['keywords']; ?>', '<?php echo $row['compact']; ?>')<?php if ($done != $rowcount) echo ","; ?> 
  <?php }
?>];


/* ***** Variable Migration from PHP ***************************** */
var os_resultlimit = <?php echo $vData['s.resultlimit']; ?>;
var os_termlimit = <?php echo $vData['s.termlimit']; ?>;
var os_termlength = <?php echo $vData['s.termlength']; ?>;
var os_weighttitle = <?php echo $vData['s.weight'][0]; ?>;
var os_weightbody = <?php echo $vData['s.weight'][1]; ?>;
var os_weightkeywords = <?php echo $vData['s.weight'][2]; ?>;
var os_bonusmulti = <?php echo $vData['s.weight'][4]; ?>;
var os_bonusimportant = <?php echo $vData['s.weight'][5]; ?>;
var os_matchlimit = <?php echo $vData['s.matchingtext']; ?>;


/* ***** Compile Category List *********************************** */
for (var x = 0, os_categories = new Array(); x < os_entries.length; x++) {
  for (var y = 0, found = false; y < os_categories.length; y++) if (os_entries[x].category == os_categories[y]) found = true;
  if (!found) os_categories[os_categories.length] = os_entries[x].category;
}


/* ***** Parse the Query String ********************************** */
var os_query = window.location.search.substr(1).replace(/\+/g, " ");
var os_qbits = os_query.split("&");

for (var x = 0, os_keyring = new Array(), os_category = "", os_start = 1; x < os_qbits.length; x++) {
  os_qbit = os_qbits[x].split("=");
  for (var y = 0; y < os_qbit.length; y++) os_qbit[y] = unescape(os_qbit[y]);
  if (os_qbit[0] == "q") os_keyring = os_qbit[1].replace(/(^\s+|\s+$)/g, "").replace(/\s{2,}/g, " ");
  if (os_qbit[0] == "c") os_category = os_qbit[1];
  if (os_qbit[0] == "start") os_start = Number(os_qbit[1]);
}
if (os_category == "" || os_categories.length < 2) os_category = "";


/* ***** Begin Output ******************************************* */
var os_xhtml = "";
if (os_keyring.length > 0) {

  /* ***** Search Entries *************************************** */
  var os_keys = os_keyring.toLowerCase().split(" ").slice(0, os_termlimit - 1);

  // Filter the entry list of negative and important matches
  for (var x = 0, os_keys2 = new Array(), os_ignored = new Array(); x < os_keys.length; x++) {
    if (os_keys[x].substr(0, 1) == "!" || os_keys[x].substr(0, 1) == "-") {
      os_keys[x] = os_keys[x].substr(1).replace(/["']/g, "");
      for (var y = 0, os_entries2 = new Array(); y < os_entries.length; y++) {
        if (os_entries[y].title.toLowerCase().indexOf(os_keys[x]) == -1 &&
            os_entries[y].text.toLowerCase().indexOf(os_keys[x]) == -1 &&
            os_entries[y].keywords.toLowerCase().indexOf(os_keys[x]) == -1)
          os_entries2[os_entries2.length] = os_entries[y];
      }
      os_entries = os_entries2;
    } else if (os_keys[x].substr(0, 1) == "+") {
      os_keys2[os_keys2.length] = os_keys[x].replace(/["']/g, "");
      for (var y = 0, os_entries2 = new Array(); y < os_entries.length; y++) {
        if (os_entries[y].title.toLowerCase().indexOf(os_keys[x]) != -1 ||
            os_entries[y].text.toLowerCase().indexOf(os_keys[x]) != -1 ||
            os_entries[y].keywords.toLowerCase().indexOf(os_keys[x]) != -1)
          os_entries2[os_entries2.length] = os_entries[y];
      }
      os_entries = os_entries2;
    } else if (os_keys[x].replace(/["']/g, "").length >= os_termlength) {
      os_keys2[os_keys2.length] = os_keys[x].replace(/["']/g, "");
    } else os_ignored[os_ignored.length] = os_keys[x].replace(/["']/g, "");
  }
  os_keys = os_keys2;

  // Filter the entry list of excluded categories
  if (os_category != "") {
    for (var y = 0, os_entries2 = new Array(); y < os_entries.length; y++)
      if (os_entries[y].category == os_category) os_entries2[os_entries2.length] = os_entries[y];
    os_entries = os_entries2;
  }

  // Search the entries for items from the query string and apply relevance values
  for (var y = 0; y < os_entries.length; y++) {
    for (var x = 0; x < os_keys.length; x++) {
      var os_relevance = os_entries[y].relevance;
      if (os_keys[x].substr(0, 1) == "+") {
        os_importance = os_bonusimportant;
        os_keys[x] = os_keys[x].substr(1);
      } else os_importance = 1;
      var os_titlesplit = os_entries[y].title.toLowerCase().split(os_keys[x]);
      os_entries[y].relevance += os_weighttitle * (os_titlesplit.length - 1) * os_importance;
      if (os_titlesplit.length > 1) {
        for (var z = 0, os_tdist = 0, os_title = ""; z < os_titlesplit.length - 1; z++) {
          os_title += os_titlesplit[z];
          os_tdist += os_titlesplit[z].length;
          if (z) os_tdist += os_keys[x].length;
          os_title += "<strong>" + os_entries[y].title.substr(os_tdist, os_keys[x].length) + "</strong>";
        }
        os_title += os_titlesplit[os_titlesplit.length - 1];
        os_entries[y].title = os_title;
      }


      os_entries[y].relevance += os_weightkeywords * (os_entries[y].keywords.toLowerCase().split(os_keys[x]).length - 1) * os_importance;
      var os_bodysplit = os_entries[y].text.toLowerCase().split(os_keys[x]);
      os_entries[y].relevance += os_weightbody * (os_bodysplit.length - 1) * os_importance;
      if (os_bodysplit.length > 1 && os_entries[y].matchtext.length < os_matchlimit) {
        var os_term = os_entries[y].text.substr(os_bodysplit[0].length, os_keys[x].length);
        var os_matchtext = os_entries[y].text.substr(Math.max(0, os_bodysplit[0].length - 80), Math.min(os_bodysplit[0].length, 80));
        os_matchtext += "[[[strong]]]" + os_term + "[[[/strong]]]";
        os_matchtext += os_entries[y].text.substr(os_bodysplit[0].length + os_keys[x].length, os_keys[x].length + 80);
        os_entries[y].matchtext += os_matchtext + " [[[strong]]]...[[[/strong]]] ";
      }
      if (os_relevance != os_entries[y].relevance) os_entries[y].words++;
    }
    os_entries[y].relevance *= Math.pow(os_bonusmulti, os_entries[y].words);
    if (os_entries[y].matchtext != " [[[strong]]]...[[[/strong]]] ") {
      os_entries[y].matchtext = os_entries[y].matchtext.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\[\[\[strong\]\]\]/g, "<strong>").replace(/\[\[\[\/strong\]\]\]/g, "</strong>");
    } else os_entries[y].matchtext = os_entries[y].description;
  }


  // Sort the entries
  if (os_entries.length > 1)
    var os_entries = os_entries.heapsort().reverse();

  // Remove entries below the relevance threshold
  for (var x = 0, os_matches = new Array(); x < os_entries.length; x++)
    if (os_entries[x].relevance > 0) os_matches[os_matches.length] = os_entries[x];
  if (os_matches.length > os_resultlimit) os_matches = os_matches.slice(0, os_resultlimit - 1);


  /* ***** Compile Output **************************************** */
  if (os_matches.length) {
    // Find start and end values for this range of matches
    var os_start = (os_matches.length <= os_resultspp) ? 1 : os_start;
    var os_end = Math.min(os_start + os_resultspp - 1, os_matches.length);

    // Draw the upper result information bar
    os_xhtml += "  <p id=\"os_resultbar\">";
    var os_mark = new Date();
    os_xhtml += "    Results <strong>" + os_start + "</strong> - <strong>" + os_end + "</strong> of <strong>" + os_matches.length + "</strong>";
    os_xhtml += "    for <strong>" + os_keyring + "</strong>";
    os_xhtml += "    (<strong>FAKEMD5</strong> seconds)";
    os_xhtml += "  </p>";

    // Mention Ignored terms
    if (os_ignored.length) os_xhtml += "    <p class=\"os_msg\">These terms were ignored: <strong>" + os_ignored.join(" ") + "</strong>";

    // Write results
    os_xhtml += "  <ol id=\"os_results\" start=\"" + os_start + "\">";
    for (var x = os_start - 1; x < os_end; x++) {
      var os_templatex = os_template.replace(/\{R_NUMBER\}/g, x + 1).replace(/\{R_RELEVANCE\}/g, numFormat(os_matches[x].relevance, 1, true, false));
      os_templatex = os_templatex.replace(/\{R_URI\}/g, os_matches[x].uri).replace(/\{R_CATEGORY\}/g, os_matches[x].category);
      os_templatex = os_templatex.replace(/\{R_TITLE\}/g, os_matches[x].title).replace(/\{R_DESCRIPTION\}/g, os_matches[x].description);
      os_templatex = os_templatex.replace(/\{R_MATCH\}/g, os_matches[x].matchtext);
      os_xhtml += "    <li>";
      os_xhtml += "      " + os_templatex;
      os_xhtml += "    </li>";
    }
    os_xhtml += "  </ol>";

    // Pagination
    if (os_matches.length > os_resultspp) {
      var os_common = unescape(window.location.search).substr(1).replace(/&start=\d+/i, "");
      os_xhtml += "  <div id=\"os_pagination\">";
      os_xhtml += "    <div id=\"os_pagin1\">";
      if (os_start > 1) {
        var os_prev = Math.max(1, os_start - os_resultspp);
        os_xhtml += "      <a href=\"?" + os_common + "&start=" + os_prev + "\" title=\"Previous\">&lt;&lt; Previous</a>";
      } else os_xhtml += "      &nbsp;";
      os_xhtml += "    </div>";
      os_xhtml += "    <div id=\"os_pagin3\">";
      if (os_end < os_matches.length) {
        var os_next = os_end + 1;
        os_xhtml += "      <a href=\"?" + os_common + "&start=" + os_next + "\" title=\"Next\">Next &gt;&gt;</a>";
      } else os_xhtml += "      &nbsp;";
      os_xhtml += "    </div>";
      os_xhtml += "    <div id=\"os_pagin2\">";
      var pagemax = Math.ceil(os_matches.length / os_resultspp);
      for (var x = 1; x <= pagemax; x++) {
        var os_list = (x - 1) * os_resultspp + 1;
        if (os_list == os_start) {
          os_xhtml += "      <strong>" + x + "</strong>";
        } else {
          var os_title = os_list + " - " + Math.min(os_list + os_resultspp - 1, os_matches.length);
          os_xhtml += "      <a href=\"?" + os_common + "&start=" + os_list + "\" title=\"" + os_title + "\">" + x + "</a>";
        }
      }
      os_xhtml += "    </div>";
      os_xhtml += "  </div>";
    }

  } else {
    os_xhtml += "  <p id=\"os_resultbar\">&nbsp;</p>";
    os_xhtml += "  <p class=\"os_msg\">No matches found for <strong>" + os_keyring + "</strong>";
    if (os_ignored.length) os_xhtml += "    <br />These terms were ignored: <strong>" + os_ignored.join(" ") + "</strong>";
    if (os_category != "") os_xhtml += "    <br /><br />Try this search in <a href=\"" + window.location.pathname + "?q=" + os_keyring + "\">all categories</a>?";
    os_xhtml += "    </p>";
  }

} else {
  os_xhtml += "  <p id=\"os_resultbar\">&nbsp;</p>";
  os_xhtml += "  <p class=\"os_msg\">Please enter a search query</p>";
}


/* ***** Search Form ********************************************* */
os_xhtml += "  <form action=\"" + window.location.pathname + "\" method=\"get\" id=\"os_search\">";
os_xhtml += "    <div>";
os_xhtml += "      <input type=\"text\" name=\"q\" value=\"" + os_keyring + "\" />";
if (os_categories.length > 1) {
  os_xhtml += "        <label>";
  os_xhtml += "          &nbsp; in";
  os_xhtml += "          <select name=\"c\" size=\"1\">";
  os_xhtml += "            <option value=\"\">all categories</option>";
  for (var x = 0; x < os_categories.length; x++)
    os_xhtml += "            <option value=\"" + os_categories[x] + "\"" + ((os_category == os_categories[x]) ? " selected=\"selected\"" : "") + ">" + os_categories[x] + "</option>";
  os_xhtml += "          </select>";
  os_xhtml += "        </label>";
}
os_xhtml += "      <input type=\"submit\" value=\"Go\" />";
os_xhtml += "    </div>";
os_xhtml += "  </form>";


/* ***** Tag Line ************************************************ */
os_xhtml += "  <div style=\"text-align:center;font:italic 80% Arial,sans-serif;\">";
os_xhtml += "    <hr style=\"width:60%;margin:10px auto 2px auto;\" />";
os_xhtml += "    An <em>Orca</em> Script";
os_xhtml += "  </div>";

os_xhtml += "</div>";


/* ***** Write to Page ******************************************* */
var os_mark = new Date();
var os_marked = (os_mark.getTime() - os_then) / 1000;
os_xhtml = os_xhtml.replace(/FAKEMD5/, numFormat(os_marked, 2, true, false));

document.write("<div id=\"os_main\"></div>");
document.getElementById('os_main').innerHTML = os_xhtml;
<?php
  $egg = ob_get_contents();
  ob_end_clean();
  $shell = fopen($vData['jw.egg'], "w");
  fwrite($shell, $egg);
  fclose($shell);
  set_vData("jw.progress", 100);
}

?>