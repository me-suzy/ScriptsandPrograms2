<?php
/*****************************************
* $Id: class.article.php,v 1.6 2003/10/20 17:10:32 jan Exp $
*
* File      :   $RCSfile: class.article.php,v $
*
* Project   :   Contenido
*
* Descr     :   Contenido API
*				Article object
*				Article collection               
*
* Author    :   $Author: jan $
* Modified  :   $Date: 2003/10/20 17:10:32 $
*
* © four for business AG, www.4fb.de
******************************************/

/**
 * Contenido API - Article Object
 *
 * This object represents a Contenido article 
 * 
 * Create object with
 * $obj = new Article(idart, client, lang [, idartlang]);
 * 
 * You can now read the article properties with
 * $obj->get(property) or $obj->getField(property);
 *
 * List of article properties: 
 *
 * idartlang		 - Language dependant article id
 * idart			 - Language indepenant article id 
 * idclient			 - Id of the client
 * idtplcfg 		 - Template configuration id
 * title			 - Internal Title
 * pagetitle		 - HTML Title
 * summary			 - Article summary
 * created			 - Date created
 * lastmodified 	 - Date lastmodiefied
 * author 			 - Article author (username)
 * online			 - On-/offline
 * redirect 		 - Redirect
 * redirect_url 	 - Redirect URL 
 * artsort			 - Article sort key
 * timemgmt			 - Time management
 * datestart		 - Time management start date
 * dateend			 - Time management end date
 * status			 - Article status
 * free_use_01  	 - Free to use 
 * free_use_02  	 - Free to use
 * free_use_03		 - Free to use
 * time_move_cat	 - Move category after time management 
 * time_target_cat   - Move category to this cat after time management
 * time_online_move  - Set article online after move
 * external_redirect - Open article in new window
 * locked            - Article is locked for editing
 * 
 * You can extract article content with the 
 * $obj->getContent(contype [, number]) method. 
 * 
 * To extract the first headline you can use:
 * 
 * $headline = $obj->getContent("htmlhead", 1);
 *  
 * If the second parameter is ommitted the method
 * returns an array with all available content of
 * this type. The array has the following schema:
 *
 * array( number => content ); 
 * 
 * $headlines = $obj->getContent("htmlhead"); 
 * 
 * $headlines[1] First headline
 * $headlines[2] Second headline
 * $headlines[6] Sixth headline
 * 
 * Legal content type string are defined in the Contenido
 * system table 'con_type'. Default content types are: 
 *
 * NOTE: This parameter is case insesitive, you can use
 * html or cms_HTML or CmS_HtMl. Your don't need start with
 * cms, but it won't crash if you do so. 
 *
 * htmlhead		- HTML Headline
 * html			- HTML Text
 * headline		- Headline (no HTML)
 * text			- Text (no HTML)
 * img			- Upload id of the element 
 * imgdescr		- Image description
 * link			- Link (URL)
 * linktarget	- Linktarget (_self, _blank, _top ...)
 * linkdescr	- Linkdescription
 * swf			- Upload id of the element	
 *
 * @package Contenido_API
 * @version 1.0
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG 2003
 */
class Article extends Item
{
    /**
     * Config array
     * @var array
     */
    var $tab;
    
    /**
     * Article content
     * @var array
     */
    var $content;

    /**
     * Constructor
     *
     * @param int $idart Article Id
     * @param int $lang Language Id
     *
     * @return void
     */
    function Article($idart, $client, $lang, $idartlang = 0)
    {
        global $cfg;
        
        $this->tab = $cfg['tab'];

        parent::Item($this->tab['art_lang'], 'idartlang');
        
        $idartlang = ($idartlang == 0) ? $this->_getIdArtLang($idart, $lang) : $idartlang;

        $this->loadByPrimaryKey($idartlang);
        $this->_getArticleContent();
    }

    /**
     * Extract 'idartlang' for a specified 'idart' and 'lang'
     *
     * @param int Article id
     * @param int Language id
     *
     * @access private
     * @return int Language dependant article id
     */
    function _getIdArtLang($idart, $lang)
    {
        $sql = 'SELECT idartlang FROM '.$this->tab['art_lang'].' WHERE idart="'.$idart.'" AND idlang="'.$lang.'"';

        $this->db->query($sql);
        $this->db->next_record();

        return $this->db->f('idartlang');
    }
    
    /**
     * Load the articles content and stores
     * it in the 'content' property of the
     * article object
     *
     * $article->content[type][number] = value;
     *
     * @param none
     * @return void
     */
    function _getArticleContent()
    {
        $sql = 'SELECT
                    b.type, a.typeid, a.value
                FROM
                    '.$this->tab['content'].' AS a,
                    '.$this->tab['type'].' AS b
                WHERE
                    a.idartlang = "'.$this->get('idartlang').'" AND
                    b.idtype = a.idtype
                ORDER BY
                    a.idtype, a.typeid';

        $this->db->query($sql);
        
        while ($this->db->next_record())
        {
            $this->content[strtolower($this->db->f('type'))][$this->db->f('typeid')] = urldecode($this->db->f('value'));
        }
    }
    
    /**
     * Get the value of an article property
	 *
     * List of article properties: 
     *
     * idartlang		 - Language dependant article id
     * idart			 - Language indepenant article id 
     * idclient			 - Id of the client
     * idtplcfg 		 - Template configuration id
     * title			 - Internal Title
     * pagetitle		 - HTML Title
     * summary			 - Article summary
     * created			 - Date created
     * lastmodified 	 - Date lastmodiefied
     * author 			 - Article author (username)
     * online			 - On-/offline
     * redirect 		 - Redirect
     * redirect_url 	 - Redirect URL 
     * artsort			 - Article sort key
     * timemgmt			 - Time management
     * datestart		 - Time management start date
     * dateend			 - Time management end date
     * status			 - Article status
     * free_use_01  	 - Free to use 
     * free_use_02  	 - Free to use
     * free_use_03		 - Free to use
     * time_move_cat	 - Move category after time management 
     * time_target_cat   - Move category to this cat after time management
     * time_online_move  - Set article online after move
     * external_redirect - Open article in new window
     * locked            - Article is locked for editing
     *
     * @param string Property name
     * @return mixed Property value
     */
    function getField($name)
    {
        return urldecode($this->values[$name]);
    }
    
    /**
     * Get content(s) from an article
     *
     * Returns the specified content element or an array("id"=>"value")
     * if the second parameter is omitted.
	 *
     * Legal content type string are defined in the Contenido
     * system table 'con_type'. Default content types are: 
     *
     * NOTE: Parameter is case insesitive, you can use
     * html or cms_HTML or CmS_HtMl. Your don't need start with
     * cms, but it won't crash if you do so. 
     *
     * htmlhead		- HTML Headline
     * html			- HTML Text
     * headline		- Headline (no HTML)
     * text			- Text (no HTML)
     * img			- Upload id of the element 
     * imgdescr		- Image description
     * link			- Link (URL)
     * linktarget	- Linktarget (_self, _blank, _top ...)
     * linkdescr	- Linkdescription
     * swf			- Upload id of the element	
	 *
     * @param string CMS_TYPE - Legal cms type string
     * @param int Id of the content
     *
     * @return mixed String/Array Content Data
     */
    function getContent($type, $id = 0)
    {	
        if ($type == '')
        {
            return 'Class ' . get_class($this) . ': content-type must be specified!';
        }
        
        $type = strtolower($type);
        
        if (!strstr($type, 'cms_'))   
        {
       		$type = 'cms_' . $type;	
       	}

        if ($id == 0)
        { // return Array
            return $this->content[$type];
        }

        // return String
        return $this->content[$type][$id];
    }
    
    /**
     * Store -DISABLED-
     *
     * This Article Object is READ ONLY
     *
     * @param none
     * @access private
     * @return none
     */
    function store()
    {
        return false;
    }

} // Article


/**
 * Contenido API - Article Object Collection
 *
 * This class is used to manage multiple Contenido
 * article objects in a collection.
 * 
 * The constructor awaits an associative array
 * as parameter with the following schema:
 * 
 * array( string paramname => mixed value ); 
 *
 * The parameter idcat is required: array('idcat'=>n)
 *
 * Legal parameter names are: 
 *
 *  idcat  	  - Contenido Category Id
 *  lang 	  - Language Id, active language if ommited
 *  client 	  - Client Id, active client if ommited
 *  start 	  - include start article in the collection, defaults to false
 *  order 	  - articles will be orderered by this article property, defaults to 'created'
 *  direction - Order direcion, 'asc' or 'desc' for ascending/descending, defaults to 'asc'
 *
 * You can easy create article lists/teasers with this class.
 * 
 * To create an article list of category 4 (maybe a news category) use:
 * 
 * $myList = new ArticleCollection(array("idcat"=>4);
 *
 * while ($article = $myList->nextArticle())
 * {
 *    // Fetch the first headline
 *    $headline = $article->getContent('htmlhead', 1);	
 *	  $idart    = $article->get('idart');
 *
 *    // Create a link     
 *	  echo '<a href="front_content.php?idcat='.$myList->idcat.'&idart='.$idart.'">'.$headline.'</a><br/>';
 * }
 * 
 * @package Contenido API
 * @version 1.0
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
class ArticleCollection
{
    /**
     * Database Object
     * @var object
     */
    var $db;
    
    /**
     * Result Counter
     * @var int
     */
    var $cnt = 0;
    
    /**
     * Language id
     * @var int
     */
    var $lang;
    
    /**
     * Client ID
     * @var int
     */
    var $client;
    
    /**
     * Config array
     * @var array
     */
    var $tab;

    /**
     * Articles
     * @var array
     */
    var $articles;

    /**
     * Include the Start-Article
     * @var int
     */
    var $start;
    
    /**
     * Id of the start article
     * @var int
     */
    var $startId;


    /**
     * Sort order
     * @var string
     */
    var $order;
    
    /**
     * Sort direction
     * @var string
     */
    var $direction;
    
    /**
     * Articles in collection
     * @var int
     */
    var $count;

    /**
     * Article Collection Constructor
     *
     * @param array Options array with schema array("option"=>"value");
     *  idcat (required) - Contenido Category Id
     *  lang - Language Id, active language if ommited
     *  client - Client Id, active client if ommited
     *  start - include start article in the collection, defaults to false
     *  order - articles will be orderered by this property, defaults to 'created'
     *  direction - Order direcion, 'asc' or 'desc' for ascending/descending
     *
     * @return void
     */
    function ArticleCollection($options)
    {
        global $cfg;
        
        $this->tab = $cfg['tab'];
        $this->db = new DB_Contenido;
        
        $this->_setObjectProperties($options);
        $this->_getArticlesByCatId($this->idcat);
    }
    
    /**
     * Set the Object properties
     *
     * @param array Options array with schema array("option"=>"value");
     *  idcat (required) - Contenido Category Id
     *  lang - Language Id, active language if ommited
     *  client - Client Id, active client if ommited
     *  start - include start article in the collection, defaults to false
     *  order - articles will be ordered by this property, defaults to 'created'
     *  direction - Order direcion, 'ASC' or 'DESC' for ascending/descending
     *
     * @access private
     * @return void
     */
    function _setObjectProperties($options)
    {
        global $client, $lang;

        if (!array_key_exists('idcat', $options))
        {
            return 'idcat has to be defined';
        }
        
        $this->idcat     = $options['idcat'];
        $this->lang      = (array_key_exists('lang',   $options))    ? $options['lang']      : $lang;
        $this->client    = (array_key_exists('client', $options))    ? $options['client']    : $client;
        $this->start     = (array_key_exists('start',  $options))    ? $options['start']     : false;
        $this->offline   = (array_key_exists('offline',$options))    ? $options['offline']   : false;
        $this->order     = (array_key_exists('order',  $options))    ? $options['order']     : 'created';
        $this->direction = (array_key_exists('direction', $options)) ? $options['direction'] : 'DESC';
    }
    
    /**
     * Extracts all articles from a specified
     * category id and stores them in the
     * internal article array
     *
     * @param int Category Id
     *
     * @access private
     * @return void
     */
    function _getArticlesByCatId($idcat)
    {
        $sql = 'SELECT
                    a.idart,
                    c.is_start
                FROM
                    '.$this->tab['art_lang'].' AS a,
                    '.$this->tab['art'].' AS b,
                    '.$this->tab['cat_art'].' AS c
                WHERE
                    c.idcat = '.$idcat.' AND
                    b.idclient = '.$this->client.' AND
                    b.idart = c.idart AND
                    a.idart = b.idart AND
                    a.idlang = '.$this->lang.'';

        if (!$this->offline)
        {
            $sql .= ' AND a.online = 1 ';
        }

        $sql .= ' ORDER BY a.'.$this->order.' '.$this->direction.'';

        $this->db->query($sql);

        while ($this->db->next_record())
        {
            if ($this->db->f('is_start') == 1)
            {
                $this->startId = $this->db->f('idart');
                
                if ($this->start)
                {
                    $this->articles[] = $this->db->f('idart');
                }
            }
            else
            {
                $this->articles[] = $this->db->f('idart');
            }
        }

        $this->count = count($this->articles);
    }
    
    /**
     * Iterate to the next article, 
	 * return object of type Contenido Article Object
	 * if an article is found. False otherwhise.
     *
     * @param none
     *
     * @return object Contenido Article Object
     */
    function nextArticle()
    {
        if ($this->cnt < count($this->articles))
        {
            $idart = $this->articles[$this->cnt];
            
            if (is_numeric($idart))
            {
                $this->cnt ++;
                return new Article($idart, $this->lang, $this->client);
            }
        }
        return false;
    }
    
    /**
     * Return ONLY the Start-Article
     *
     * @param non
     * @return object Contenido Article Object
     * @access public
     */
    function startArticle()
    {
        return new Article($this->startId, $this->lang, $this->client);
    }

} // ArticleCollection

?>