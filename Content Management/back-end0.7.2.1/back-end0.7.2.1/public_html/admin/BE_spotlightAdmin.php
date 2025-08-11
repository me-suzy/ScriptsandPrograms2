<?php
    /**
    *
    * @package     Back-End on phpSlash
    * @copyright   2003 - Ian Clysdale, Canadian Union of Public Employees
    * @version     $Id: BE_spotlightAdmin.php,v 1.17 2005/04/13 15:05:14 mgifford Exp $
    *
    */

    require('./config.php');

    $pagetitle = pslgetText('Edit Spotlight Stories'); // The name to be displayed in the header
    $xsiteobject = pslgetText('Administration');       // Defines The META TAG Page Type
    $_PSL['metatags']['object'] = $xsiteobject;

    $content = null;
    $ary = array();
    $subsiteID = 0;

    $db = pslNew('BEDB');
    $tpl = pslNew('slashTemplate', $_PSL['templatedir']);
    $tpl->set_file('editSpotlight', 'BE_editSpotlight.tpl');

    global $BE_subsite, $BE_currentLanguage;

    if (isset($BE_subsite['subsite_id'])) {
       $subsiteID = $BE_subsite['subsite_id'];
    }

    if ($perm->have_perm('spotlight') || $perm->have_perm('root')) {
        $showList = true;
        $vars = array();
        $vars = clean($_POST);
        $vars['submit'] = pslgetText($vars['submit'], '', true);
        $varNames = array('submit', 'id');
        foreach ($varNames as $key) {
            if (empty($vars[$key])) {
               if (empty($_GET[$key])) {
                  $vars[$key] = NULL;
               } else {
                  $vars[$key] = (isset($_GET[$key])) ? clean($_GET[$key])  : '';
               }
            }
        }

        switch ($vars['submit']) {
            case 'update':
            //$query = "select article.articleID, text.title from be_articles article left join be_articleText text using (articleID) where text.spotlight=1 and article.subsiteID = '$subsiteID' and text.URLname<>'' ";
            $query = "select distinct article.articleID, article.hide, article.priority from be_articles article left join be_articleText text using (articleID) where text.spotlight=1 and article.subsiteID = '$subsiteID' AND text.URLname!=''";
            $db->query($query);
            $insertDB = pslNew('BEDB');
            while ($db->next_record()) {
                $articleID = $db->Record['articleID'];
                $hideSelected = clean($_POST['hide_'.$articleID]);
                if ($hideSelected != '1') $hideSelected = '0';
                $englishSpotSelected = clean($_POST['en_spotlight_'.$articleID]);
                if ($englishSpotSelected != '1') $englishSpotSelected = '0';
                $frenchSpotSelected = clean($_POST['fr_spotlight_'.$articleID]);
                if ($frenchSpotSelected != '1') $frenchSpotSelected = '0';
                $priority = clean($_POST['priority_'.$articleID]);
                if ($priority == '') $priority = 0;
                $query = "update be_articles set hide = $hideSelected, priority=$priority where articleID = $articleID";
                $insertDB->query($query);
                $query = "update be_articleText set spotlight = $englishSpotSelected where articleID = '$articleID' and languageID='en'";
                $insertDB->query($query);
                $query = "update be_articleText set spotlight = $frenchSpotSelected where articleID = '$articleID' and languageID='fr'";
                $insertDB->query($query);
            }

            $blockCache = pslNew('BE_BlockCache');
            $blockCache->flushBlockType($_BE['blockTypeID']['BE_spotlightArticles']);

            default:
            $tpl->set_block('editSpotlight', 'each_spotlight', 'spotlight_rows');
            $query = "select distinct article.articleID, article.hide, article.priority from be_articles article left join be_articleText text using (articleID) where text.spotlight=1 and article.subsiteID = '$subsiteID' ORDER BY article.priority DESC";
            //$query = "select distinct be_articles.articleID,be_articles.hide,be_articles.priority from be_articles,be_articleText where be_articles.articleID=be_articleText.articleID and be_articleText.spotlight=1 and be_articles.subsiteID = '$subsiteID' order by be_articles.priority DESC";
            $db->query($query);
            $textDB = pslNew('BEDB');
            while ($db->next_record()) {
                $articleID = $db->Record['articleID'];
                $title = '';
                $hideSelected = '';
                $enSpot = '';
                $frSpot = '';
                if ($db->Record['hide'] == 1) $hideSelected = ' checked="checked" ';
                // The order by means that we also use the English headline
                // if it's available.
                $order = 'ASC';
                if ($BE_currentLanguage == 'fr') $order = 'DESC';
                $textQuery = "SELECT title, spotlight,languageID FROM be_articleText WHERE articleID='$articleID' ORDER BY languageID $order";
                $textDB->query($textQuery);
                while ($textDB->next_record()) {
                    if ($title == '' && $textDB->Record['title'] != '') {
                        $title = $textDB->Record['title'];
                    }
                    $languageID = $textDB->Record['languageID'];
                    $spotlight = $textDB->Record['spotlight'];
                    if ($languageID == 'en') {
                        $enSelected = '';
                        $text = pslgettext('English Spotlight');
                        if ($spotlight == 1) $enSelected = ' checked="checked" ';
                        $enSpot = "<small>$text</small><input name='en_spotlight_$articleID' type='checkbox' value='1' $enSelected>";
                    }
                    if ($languageID == 'fr') {
                        $frSelected = '';
                        $text = pslgettext('French Spotlight');
                        if ($spotlight == 1) $frSelected = ' checked="checked" ';
                        $frSpot = "<small>$text</small><input name=\"fr_spotlight_$articleID\" type=\"checkbox\" value=\"1\" $frSelected>";
                    }
                }
                $tpl->set_var(array(
                    'HIDESELECTED' => $hideSelected,
                    'ENSPOT' => $enSpot,
                    'FRSPOT' => $frSpot,
                    'ARTICLE_ID' => $articleID,
                    'TITLE' => $title,
                    'PRIORITY' => $db->Record['priority']
                 ));
                $tpl->parse('spotlight_rows', 'each_spotlight', true);
            }

            $tpl->set_var(array(
            'ACTION_URL' => $_PSL['adminurl'].'/BE_spotlightAdmin.php' ));
            $tpl->parse('OUT', 'editSpotlight', TRUE);
            $content = $tpl->get('OUT');
            break;
        }

    } else {
        $content .= getTitlebar('100%', 'Error! Invalid Privileges');
        $content .= getError(pslgetText('Sorry. You do not have the necessary privilege to view this page.'));
    }

    $ary['section'] = 'admin';

    $chosenTemplate = getUserTemplates('',$ary['section']);

    $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $pagetitle;

    // generate the page
    generatePage($ary, $pagetitle, $breadcrumb, $content);

    page_close();

?>
