<?php
   // $Id: errordocument.php,v 1.18 2005/06/17 13:53:15 mgifford Exp $
   // phpinfo(); exit;

   // don't cache this page
   $cachetimeout = -1;

   require('./config.php');

   $vars = array();
   if (!empty($_GET)) {
      $vars = clean($_GET);
   }

   $section = $article = $redirectURL = null;

   // First Try to automatically redirect the URL

   // Array of known redirects - just add to the array as required
   // This probably should this be in the in the BE_config.php file?
   $urlRedirectArray = $_BE['urlRedirectArray'];

   function record404error($redirect) {

      if(empty($redirect)) {
         return null;
      }

      // Initialize DB
      $db = & pslNew('BEDB');

      // Collect sources
      $source = (!empty($_SERVER['REDIRECT_URL'])) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'];
      $time = time();
      $referrer = $_SERVER['HTTP_REFERER'];
      $userAgent = $_SERVER['HTTP_USER_AGENT'];

      // Check if already entered
      $q1 = "SELECT count FROM be_errorlog WHERE url = '$source'";
      $db->query($q1);

      if ($db->next_record()) {
         $count = $db->Record['count'];
      }

      // Update or insert record
      if ($count > 0) {
         ++$count;
         $q2 = "UPDATE be_errorlog
                SET  dateUpdated  = '$time',
                     count        = '$count',
                     referredFrom = '$referrer',
                     redirectedTo = '$redirect',
                     userAgent    = '$userAgent'
                     WHERE url      = '$source'";
      } else {
         $q2 = "INSERT INTO `be_errorlog` ( `url` , `dateUpdated` , `count` , `referredFrom` , `redirectedTo` , `userAgent` )
            VALUES ('$source','$time','1','$referrer','$redirect','$userAgent')";
      }

      $db->query($q2);

   }

   // Strip off prefixes & suffixes & upper case characters
   $stripedDownRequest = (strstr($_SERVER['REDIRECT_URL'], '.')) ? str_replace('.', '', strtolower(strip_extension($_SERVER['REDIRECT_URL']))) : $_SERVER['REDIRECT_URL'];

   debug('errordocument - url', $_SERVER['REDIRECT_URL']);

   // does the html file, a cached directory or a php file exist??
   if (is_file($_PSL['basedir'] . $stripedDownRequest . '.html')) {
      $redirect = $_PSL['absoluteurl'] . $stripedDownRequest . '.html';
      record404error($redirect);
      Header("Location: $redirect");
      exit;
   } elseif ($_SERVER['REQUEST_URI'] == $_PSL['rooturl'] . '/errordocument.php' || $vars['list'] == 'error' || $vars['submit'] == 'clear') {
      debug('errordocument.php - request_uri', $_SERVER['REQUEST_URI']);
      $adminMode = ($perm->have_perm('root')) ? true : false;
   } elseif (!empty($stripedDownRequest) && is_dir($_PSL['basedir'] . $stripedDownRequest)) {
      $redirect = $_PSL['absoluteurl'] . $stripedDownRequest; // . '/';
      record404error($redirect);
      Header("Location: $redirect");
      exit;
   } elseif (is_file($_PSL['basedir'] . $stripedDownRequest . '.php')) {
      $redirect = $_PSL['absoluteurl'] . $stripedDownRequest . '.php';
      record404error($redirect);
      Header("Location: $redirect");
      exit;
   }

   // Replace any spaces in the URL
   $redirect = str_replace (' ', '', $_SERVER['REDIRECT_URL']);

   // Search for how to process URL
   if (isset($urlRedirectArray[$redirect])) {
      // Grab from predefined array
      $redirectURL = $urlRedirectArray[$redirect];
   } else {
      // Load Article/Section Objects
      $articleObj = pslNew('BE_Article');
      $sectionObj = pslNew('BE_Section');

      // remove article_file if in the redirect
      if (ereg($_BE['article_file'], $_SERVER['REDIRECT_URL'])) {
         $_SERVER['REDIRECT_URL'] = str_replace($_BE['article_file'], '', $_SERVER['REDIRECT_URL']);
      }

      // guess section and article
      // removing trailing slash & build array from path
      $path = explode('/', preg_replace('/\/\s*$/', '', $stripedDownRequest));
      $numberOfSubDirectories = count($path);
      for ($i=0 ; $i < $numberOfSubDirectories ; $i++) {
         if ($numberOfSubDirectories < 3 && $i==1) {
             $section = $path[$i];
         } elseif ($numberOfSubDirectories > 2 && $i == $numberOfSubDirectories - 1) {
            $article = $path[$i];
         } elseif ($numberOfSubDirectories > 2 && $i == $numberOfSubDirectories - 2) {
            $section = $path[$i];
         }
      }

      // grab request values?
      // $getRequestValue = getRequestValue($section, $article, $type);

      // Check if an article exists
      $articleExists = $articleObj->extractArticle(addslashes($article));
      $sectionExists = $sectionObj->extractSection(addslashes($section));


      if (is_array($sectionExists) && is_array($articleExists)) {
         $redirectURL = $_PSL['absoluteurl'] . '/' . $_BE['article_file'] . "/$section/$article/";
      } elseif(!is_array($sectionExists) && is_array($articleExists)) {
         $redirectURL = $_PSL['absoluteurl'] . '/' . $_BE['article_file'] . "//$article/";
      } elseif(is_array($sectionExists) && !is_array($articleExists)) {
         $redirectURL = $_PSL['absoluteurl'] . '/' . $_BE['article_file'] . "/$section/";
      } else {
         // See if the article is a section:
         $sectionExistsAsArticle = $sectionObj->extractSection(addslashes($article));
         if (is_array($sectionExistsAsArticle)) {
            $redirectURL = $_PSL['absoluteurl'] . '/' . $_BE['article_file'] . "/$article/";
         }
      }

   }

   debug('errordocument.php - redirectURL - url', $redirectURL);

   // Direct the browser to the alternate site
   if (!empty($redirectURL)) {
      // Not sure whether either of these is really appropriate
      // error_log("[Redirect " . $_SERVER['REDIRECT_URL'] . " to: $redirectURL]",0);
      // logwrite("404", Redirect " . $_SERVER['REDIRECT_URL'] . " to: $redirectURL]);
      record404error($redirectURL);
      Header("Location: $redirectURL");
      exit;
   }


   // View common error hits
   if ($adminMode) {
      $pageTitle = pslgetText('Admin for Error Documents');
      $xsiteobject = pslgetText('Admin Error Document');
      $chosenTemplate = getUserTemplates('', 'admin');
      $errors = null;
      $numberOfErrors = 0;

      // Initialize DB
      $db = & pslNew('BEDB');

      $count = (!empty($vars['count']) && is_numeric($vars['count'])) ? $vars['count'] : $_BE['defaultDisplayLimit'];
      $orderby = (!empty($vars['orderby'])) ? $vars['orderby'] : 'count';
      $logic = (!empty($vars['logic'])) ? $vars['logic'] : 'desc';
      $first = (!empty($vars['err_i']) && is_numeric($vars['err_i'])) ? $vars['err_i'] : 0;

      // Get total number of errors
      $q1 = 'SELECT COUNT(*) AS total FROM be_errorlog ';
      $db->query($q1);
      if ($db->next_record()) {
         $n = $db->Record['total'];
      }

      // Pull down errors to display
      $q2 = "SELECT * FROM be_errorlog ORDER BY $orderby $logic LIMIT $first,$count";
      $db->query($q2);

      // TODO: template this
      $errors .= '<ul>';
      while ($db->next_record()) {
         $errors .= '<li>' . $db->Record['count'] . ') ' . $db->Record['url'] . ' - ' . $db->Record['referredFrom'] . ' - ' . $db->Record['redirectedTo'] . ' - ' . $db->Record['userAgent'] . '</li>';
         ++$numberOfErrors;
      }
      $errors .= '</ul>';

      // Display pagination if there more than one page of errors
      if (($n / $count) > 1) {
         // Add pagination indecies.
         $paginator = pslNew('BE_Paginator');
         $paginator->setMaxPerPage($count);
         $url = $_PSL['phpself']. '?list=error&amp;err_i=%d&amp;err_n=' . $count;
         if (!empty($vars['orderby'])) {
            $url .= '&amp;orderby=' . rawurlencode($vars['orderby']);
         }
         if (!empty($vars['logic'])) {
            $url .= '&amp;logic=' . rawurlencode($vars['logic']);
         }

         $pagination = $paginator->generatePageIndices($url, $first, $n);
         $pageRange = '&nbsp;'.sprintf(pslgetText('page range (%s to %s of %s)'), $first + 1, $first + $numberOfErrors, $n);

         $errors .= "<p>$pageRange - $pagination</p>";

      }

      // Set up tool to clear error log
      if ($vars['submit'] == 'clear') {
         $q0 = 'TRUNCATE TABLE `be_errorlog`';
         $db->query($q0);
         $errors = pslgetText('Cleared');
      } elseif ($n > 0) {
         $errors .= '<p><a href="' . $_PSL['rooturl'] . '/errordocument.php?submit=clear">' . pslgetText('Clear Log') . '</a></p>';
      } else {
         $errors .= '<p>' . pslgetText('No Errors') . '</p>';
      }

      $storyArray = array(
         'STORY_COLUMN' => $errors,
         'ROOTDIR'      => $_PSL['rooturl'],
      );

      // generate the page
      generatePage($ary, $pageTitle, '', $storyArray, $errors);

      // close the page
      page_close();

      exit();

   }

   record404error('search');

   // If that fails, build the error page

   // log 404's if you don't have access server logs in the infolog
   // logwrite("404", "File Not Found " . $_SERVER['REDIRECT_URL']);

   $pageTitle = pslgetText('Page not found');
   $xsiteobject = pslgetText('Error Document');

   if (!empty($vars['login'])) {
      $auth->login_if($vars['login']);
   }

   // Objects
   $searchObj = pslNew($_BE['search_class']);

   $stripedDownRequest = str_replace('/', ' ', $stripedDownRequest);
   $vars['query'] = $stripedDownRequest;

   # debug('redirectURL - search query', $ary);

   $search = $searchObj->getResults($ary); // was $ary but config.php messes too much

   $template = pslNew('slashTemplate');
   // $template->debug = 1;

   $template->set_file('notFound', 'notfound.tpl');
   $template->set_var(array(
      'ROOTDIR'  => $_PSL['rooturl'],
      'IMAGEDIR' => $_PSL['imageurl'],
      'ERRORURL' => htmlentities($_SERVER['REDIRECT_URL']),
      'SEARCH'   => $search
   ));
   $errorPage = $template->parse('OUT', 'notFound');

   // $errorPage .= ($perm->have_perm('root')) ? '<p><a href="' . $_PSL['rooturl'] . '/errordocument.php?submit=clear">' . pslgetText('View Log') . '</a></p>' : '';

   $ary['section'] = $_BE['error_section'];

   $chosenTemplate = getUserTemplates('', $ary['section']);

   // $template->set_file('index', $chosenTemplate['body'] . '.tpl');

   $todaysDate = psl_dateLong(time());

   // place the output for the primary ('story') content section into an array and pass it to index*tpl
   $storyArray = array(
      'STORY_COLUMN' => $errorPage, //smartText($storyInfo),
      'ROOTDIR'      => $_PSL['rooturl'],
      'TODAYS_DATE'  => $todaysDate
   );

   // $storyText = $template->parse('OUT', 'index');

   // generate the page
   generatePage($ary, $pageTitle, '', $storyArray, $errorPage);

   debug('generated page', $storyArray);

   // close the page
   page_close();

?>
