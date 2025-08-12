<?php //1.2.1pl2//please don't change anything in this line!//Bitte in dieser Zeile nichts ändern!//

class defaults {
	function defaults() {
		global $PHP, $PHPCMS;
		if(!defined("_DEFAULTS_")) {
			define("_DEFAULTS_", TRUE);
		}
		
		$this->DOMAIN_NAME = $PHP->GetDomainName();
		$this->PAGE_EXTENSION = '.htm';
		$this->PAGE_DEFAULTNAME = 'index';
		$this->TEMPEXT = '.tpl';
		$this->GLOBAL_PROJECT_FILE = '/template/home.ini';
		$this->GLOBAL_PROJECT_HOME = '/';
		$this->PLUGINDIR = '/parser/plugs';


		$this->START_FIELD = '{';
		$this->STOP_FIELD = '}';
		$this->MENU_DELIMITER = ';';
		$this->TAG_DELIMITER = ',';
		$this->PAX = 'off';
		$this->PAXTAGS = 'off';
		$this->MAIL2CRYPT = 'on';
		$this->MAIL2CRYPT_JS = '/';
		$this->MAIL2CRYPT_IMG = '/parser/gif/';


		$this->CACHE_STATE = 'off';
		$this->CACHE_DIR = '/parser/cache';
		$this->CACHE_CLIENT = 'off';
		$this->PROXY_CACHE_TIME = 60*60*24*7;


		$this->GZIP = 'off';
		$this->STEALTH = 'off';
		$this->STEALTH_SECURE = 'off';
		$this->NOLINKCHANGE = '.gif;.jpg;.css;.js;.php;.txt;.zip;.pdf';
		$this->DEBUG = 'on';
		$this->ERROR_PAGE = '/error.htm';
		$this->ERROR_PAGE_404 = '/404.htm';
		$this->TAGS_ERROR = 'on';


		$this->P3P_HEADER = 'off';
		$this->P3P_POLICY = '';
		$this->P3P_HREF = '/w3c/p3p.xml';


		$this->STATS = 'off';
		$this->STATS_DIR = '/parser/stat';
		$this->STATS_CURRENT = '/parser/stat/current';
		$this->STATS_FILE = 'stat.txt';
		$this->STATS_BACKUP = '/parser/stat/backup';
		$this->STATS_REFERER_COUNT = '100';
		$this->STATS_REFERER_IGNORE = '10.10.10.10;0';
		$this->STATS_IP_COUNT = '200';
		$this->STATS_IP_IGNORE = '10.10.10.10;0';
		$this->STATS_URL_COUNT = '200';
		$this->REFERRER = 'off';
		$this->REFERRER_DIR = '/parser/stat';
		$this->REFERRER_FILE = 'referrer.txt';
		$this->REF_RELOAD_LOCK = 60;


		$this->FILEMANAGER_STARTDIR = '/parser/';
		$this->FILEMANAGER_DIRSIZE = 'off';
		$this->FILEMANAGER_AREA_SIZE = '85,21';
		$this->FILEMANAGER_SHORTNAME_LENGTH = '22';
		$this->CACHEVIEW_SHORTNAME_LENGTH = '45';


		$this->LANGUAGE = 'en';
		$this->PASS = 'YourPasswordHere';
		$this->PASS_MIN_LENGTH = 6;
		$this->ENABLE_ONLINE_EDITOR = 'off';
		$this->UPDATE = 'off';


		$this->VERSION = $PHPCMS->VERSION.$PHPCMS->RELEASE;


		$this->PROJECT = 'PROJECT';
		$this->TAGFILE = '';
		$this->TEMPLATE = '';
		$this->MENU = '';
		$this->MENUTEMPLATE = '';


		$this->COMMENT = ';';
		$this->DYN_EXTENSION = '.dyn';
		$this->PROJECTFILENAME = '';
		$this->DOCUMENT_ROOT = $PHP->GetDocRoot();
		$this->SCRIPT_PATH = $PHP->GetScriptPath();
		$this->SCRIPT_NAME = $PHP->GetScriptName();


		$this->FIX_PHP_OB_BUG = 'off';
		$this->ERROR_ALL = 'off';
	}
}
?>