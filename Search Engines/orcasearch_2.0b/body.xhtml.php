<?php /* ***** Orca Search - Search Engine XHTML Output *************
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
*
* See the readme.txt file for installation instructions.
* 
* To modify this file to deliver HTML, just do a global replace of
*  " />" with ">" (without the quotes, include the space!)
****************************************************************** */ 

/* *************************************************************** */
/* ***** Setup *************************************************** */
$resultTemplate = <<<ORCA
<h3><a href="{R_URI}" title="{R_DESCRIPTION}">{R_TITLE}</a> - <small>{R_CATEGORY}</small></h3>
<blockquote>
  <p>
    {R_MATCH}<br />
    <cite>{R_URIMATCH}</cite> <small>({R_RELEVANCE})</small>
  </p>
</blockquote> 
ORCA;

$resultsPerPage = 10;

if (isset($_QUERY)) {
  $ignoredTerms = array_diff($_QUERY['allterms'], $_QUERY['terms']);
  $ignoredTerms = ($ignoredTermsCount = count($ignoredTerms)) ? implode(" ", $ignoredTerms) : "";
}
if (!isset($_QUERY['category'])) $_QUERY['category'] = (isset($_GET['c'])) ? $_GET['c'] : "";
if (!isset($_QUERY['original'])) $_QUERY['original'] = "";


// $vData['s.termlength'] is used in the help text

/* *************************************************************** */
/* ***** Output ************************************************** */
?><div id="os_main">

  <!-- Orca Search v<?php echo $dData['version']; ?> -->

  <p id="os_resultbar"><?php
    if ($dData['online'] && count($_RESULTS)) { 
      $_GET['start'] = (!isset($_GET['start'])) ? 1 : ((count($_RESULTS) <= $resultsPerPage) ? 1 : (int)$_GET['start']);
      $_GET['end'] = min($_GET['start'] + $resultsPerPage - 1, count($_RESULTS)); ?> 
      Results <strong><?php echo $_GET['start']; ?></strong> - <strong><?php echo $_GET['end']; ?></strong> of <strong><?php echo count($_RESULTS); ?></strong>
      for <strong><?php echo trim(htmlspecialchars($_QUERY['original'])); ?></strong>
      (<strong><?php printf("%01.2f", array_sum(explode(" ", microtime())) - $dData['now']); ?></strong> seconds)<?php
    } else { ?> 
      &nbsp;<?php
    } ?> 
  </p>


  <?php /* ***** No DB Connection ******************************** */
  if (!$dData['online']) { ?> 
    <p class="os_msg">Could not establish a connection to the database.<br /><em><?php echo $dData['errno'], ": ", $dData['error']; ?></em></p>

  <?php /* ***** DB Locked *************************************** */
  } else if ($_RESULTS === NULL) { ?> 
    <p class="os_msg">The search database is currently being updated; please try your search again in a few minutes.</p>

  <?php /* ***** List Results ************************************ */
  } else if (count($_RESULTS)) { 
    if ($ignoredTermsCount) { ?> 
      <p class="os_msg">
        These terms were ignored: <strong><?php echo htmlspecialchars($ignoredTerms); ?></strong>
      </p><?php
    } ?> 

    <ol id="os_results" start="<?php echo $_GET['start']; ?>">
      <?php $sData['find'] = array("{R_NUMBER}", "{R_RELEVANCE}", "{R_URI}", "{R_URIMATCH}", "{R_CATEGORY}", "{R_TITLE}", "{R_DESCRIPTION}", "{R_MATCH}");
      $sData['root'] = "http://{$_SERVER['HTTP_HOST']}{$dData['thisLocation']}";
      for ($x = $_GET['start'] - 1; $x < $_GET['end']; $x++) {
        $_RESULTS[$x]['uri'] = str_replace($sData['root'], "/", $_RESULTS[$x]['uri']);
        $_RESULTS[$x]['matchURI'] = str_replace($sData['root'], "/", $_RESULTS[$x]['matchURI']);
        $sData['repl'] = array($x + 1, sprintf("%01.1f", $_RESULTS[$x]['relevance']), htmlspecialchars($_RESULTS[$x]['uri']), $_RESULTS[$x]['matchURI'], htmlspecialchars($_RESULTS[$x]['category']), ($_RESULTS[$x]['title']) ? $_RESULTS[$x]['title'] : htmlspecialchars($_RESULTS[$x]['uri']), $_RESULTS[$x]['description'], $_RESULTS[$x]['matchText']); ?> 
        <li>
          <?php echo str_replace($sData['find'], $sData['repl'], $resultTemplate); ?> 
        </li>
      <?php } ?> 
    </ol>

    <?php /* ***** Show Pagination Links ************************* */
    if (count($_RESULTS) > $resultsPerPage) {
      $qstr = preg_replace("/&start=\d+/i", "", $_SERVER['QUERY_STRING']); ?> 
      <div id="os_pagination">
        <div id="os_pagin1">
          <?php if ($_GET['start'] > 1) {
            $prev = max(1, ($_GET['start'] - $resultsPerPage)); ?> 
            <a href="<?php echo $_SERVER['PHP_SELF'], "?{$qstr}&amp;start=$prev"; ?>" title="Previous">&lt;&lt; Previous</a>
          <?php } else echo "&nbsp;"; ?> 
        </div>
        <div id="os_pagin3">
          <?php if ($_GET['end'] < count($_RESULTS)) {
            $next = $_GET['end'] + 1; ?> 
            <a href="<?php echo $_SERVER['PHP_SELF'], "?{$qstr}&amp;start=$next"; ?>" title="Next">Next &gt;&gt;</a>
          <?php } else echo "&nbsp;"; ?> 
        </div>
        <div id="os_pagin2">
          <?php $pagemax = ceil(count($_RESULTS) / $resultsPerPage);
          for ($x = 1; $x <= $pagemax; $x++) {
            $list = ($x - 1) * $resultsPerPage + 1;
            if ($list == $_GET['start']) { ?> 
              <strong><?php echo $x; ?></strong>
            <?php } else {
              $title = $list." - ".min($list + $resultsPerPage - 1, count($_RESULTS)); ?> 
              <a href="<?php echo $_SERVER['PHP_SELF'], "?{$qstr}&amp;start=$list"; ?>" title="<?php echo $title; ?>"><?php echo $x; ?></a>
            <?php }
          } ?> 
        </div>
      </div>
    <?php }

  /* ***** No Query or No Results ******************************** */
  } else { ?> 
    <p class="os_msg"><?php
      if ($_QUERY['original'] != "") { ?> 
        No matches found for <strong><?php echo trim(htmlspecialchars($_QUERY['original'])); ?></strong><?php
        if ($ignoredTermsCount) { ?> 
          <br />These terms were ignored: <strong><?php echo htmlspecialchars($ignoredTerms); ?></strong><?php
        }
        if ($_QUERY['category'] != "") { ?> 
          <br /><br />Try this search in <a href="?q=<?php echo htmlspecialchars($_QUERY['original']); ?>">all categories</a>?<?php
        }
      } else { ?> 
        Please enter your search terms below.<?php
      }
    ?></p>
    <ul>
      <li>Search terms with fewer than <?php echo $vData['s.termlength']; ?> characters are ignored</li>
      <li>Enclose groups of terms in quotes (&quot;&quot;) to search for phrases</li>
      <li>Prefix terms with a plus-sign (+) to make them important</li>
      <li>Prefix terms with a minus-sign (-) or exclamation point (!) to exclude terms</li>
    </ul>
  <?php } ?> 

  <?php /* ***** Search Form ********************************** */ ?> 
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="os_search">
    <div>
      <input type="text" name="q" value="<?php echo htmlspecialchars($_QUERY['original']); ?>" /><?php
      if (isset($sData['categories']) && count($sData['categories']) > 1) { ?> 
        <label>
          &nbsp; in
          <select name="c" size="1">
            <option value=""<?php if ($_QUERY['category'] == "") echo " selected=\"selected\""; ?>>all categories</option><?php
            foreach ($sData['categories'] as $category) { ?> 
              <option value="<?php echo $category; ?>"<?php if ($_QUERY['category'] == $category) echo " selected=\"selected\""; ?>><?php echo $category; ?></option><?php
            } ?> 
          </select>
        </label><?php
      } ?> 
      <input type="submit" value="Go" />
    </div>
  </form>

  <div style="text-align:center;font:italic 80% Arial,sans-serif;">
    <hr style="width:60%;margin:10px auto 2px auto;" />
    An <a href="http://www.greywyvern.com/" title="GreyWyvern.com">Orca</a> Script
  </div>
</div>
