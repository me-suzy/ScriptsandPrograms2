<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage util
 */

class phpMyDIR {

    /**
     *  @access   public
     *  @param    string
     */
    var $baseRoot           = './';

    /**
     *  @access   public
     *  @param    string
     */
    var $globalDateFormat   = '%d. %B %Y | %H:%M';
    /**
     *  @access   public
     *  @param    array
     */
    var $globalDateDays     = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
    /**
     *  @access   public
     *  @param    array
     */
    var $globalDateMonths   = array('Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sept', 'Okt', 'Nov', 'Dez');

    /**
     *  @access   public
     *  @param    integer
     */
    var $globalSizeRound    = 2;
    /**
     *  @access   public
     *  @param    array
     */
    var $globalSizeUnits    = array('bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb');

    /**
     *  @access   public
     *  @param    string
     */
    var $globalSepThousands = '.';
    /**
     *  @access   public
     *  @param    string
     */
    var $globalSepDecimals  = ',';

    /**
     *  @access   public
     *  @param    string
     */
    var $fileMask           = '*';
    /**
     *  @access   public
     *  @param    bool
     */
    var $fileShowTime       = 1;
    /**
     *  @access   public
     *  @param    bool
     */
    var $fileShowSize       = 1;
    /**
     *  @access   public
     *  @param    bool
     */
    var $fileConvertTime    = 1;
    /**
     *  @access   public
     *  @param    bool
     */
    var $fileConvertSize    = 1;

    /**
     *  @access   public
     *  @param    string
     */
    var $folderMask         = '*';
    /**
     *  @access   public
     *  @param    bool
     */
    var $folderOnly         = 0;
    /**
     *  @access   public
     *  @param    bool
     */
    var $folderShowTime     = 1;
    /**
     *  @access   public
     *  @param    bool
     */
    var $folderConvertTime  = 1;

    /**
     *  @access   public
     *  @param    bool
     */
    var $spiderRecursive    = 0;
    /**
     *  @access   public
     *  @param    integer
     */
    var $spiderLevel        = 1;

    /**
     *  @access   public
     *  @param    array
     */
    var $resultMixed        = array();
    /**
     *  @access   public
     *  @param    array
     */
    var $resultFiles        = array();
    /**
     *  @access   public
     *  @param    array
     */
    var $resultFolder       = array();

    /**
     *  @access   private
     *  @param    mixed
     */
    var $_maskArray         = false;

    /**
     *  getResult()
     *
     *  start the search and return true or false based on search  success
     *
     *  @access   public
     *  @return   bool
     *
     **/

    function getResult()
    {
        if (!ereg("/$", $this->baseRoot))
        {
            $this->baseRoot = $this->baseRoot."/";
        }

        $this->_search($this->baseRoot);

        ksort ($this->resultMixed);
        sort ($this->resultFiles);
        sort ($this->resultFolder);

        return true;
    }

    /**
     *  fileMatch()
     *
     *  compare the given file with the file-mask
     *
     *  @access   public
     *  @param    string
     *  @return   bool
     *
     **/

    function fileMatch($file)
    {
        if ($this->_maskArray == 0)
        {
    	    $mask = $this->fileMask;
            $mask = str_replace(".", "\.", $mask);
            $mask = str_replace("*", "(.*)", $mask);
            $mask_array = explode(",",$mask);
            $this->_maskArray = array_map("trim", $mask_array);
        }

        foreach ($this->_maskArray as $valid)
        {
            if( eregi("^$valid", $file) )
            {
                return true;
            }
        }
        return false;
    }

    /**
     *  getResult()
     *
     *  read the contenst of the given root
     *
     * @access   private
     * @param    string
     * @param    integer
     *
     **/

    function _search($dir, $pos = 0)
    {
        $handle = @opendir($dir);
        while ($file = @readdir ($handle))
        {
            if (eregi("^\.{1,2}$",$file))
            {
                continue;
            }
            if (is_dir($dir.$file))
            {
                $this->_folder($dir,$file,$pos);
            }
            if (is_file($dir.$file))
            {
                $this->_files($dir,$file,$pos);
            }
        }
        @closedir($handle);
    }

    /**
     *
     *  _folder($dir,$file,$pos)
     *
     * prepare a folder
     *
     * @access   private
     * @param    string
     * @param    string
     * @param    integer
     *
     **/

    function _folder($dir,$file,$pos)
    {
        $sdir = "/".str_replace($this->baseRoot, "", $dir.$file)."/";
        $this->resultFolder[] = $sdir;
	
        $tmp = array();
        $tmp["level"] = $pos;
//        $tmp["_name"]  = $sdir;
//        $tmp["_type"]  = "folder";

        if ($this->folderShowTime == 1)
        {
//            $tmp["_time"] = filemtime($dir.$file);
        }

        $this->resultMixed[$sdir] = $tmp;

        if ($this->spiderRecursive == 1 && ($pos < $this->spiderLevel || $this->spiderLevel == 0))
        {
            $this->_search($dir.$file."/", $pos + 1);
        }

    }

    /**
     *
     *  _files($dir,$file,$pos)
     *
     * prepare a file
     *
     * @access   private
     * @param    string
     * @param    string
     * @param    integer
     *
     **/

    function _files($dir,$file,$pos)
    {
        $sdir = "/".str_replace($this->baseRoot, "", $dir.$file)."/";
        if ( $this->fileMatch($file) == 1 && $this->folderOnly == 0 )
        {
            $this->resultFiles[] = $sdir.$file;

            $tmp = array();
            $tmp["level"] = $pos;
            $tmp["name"]  = $file;

            if ($this->fileShowTime == 1)
            {
                $tmp["time"] = filemtime($dir.$file);
                if ($this->fileConvertTime == 1)
                {
                    $tmp["time"] = $this->humanReadableTime($tmp["time"]);
                }
                if ($this->fileConvertTime == 2)
                {
                    $tmp["time"] = date ("Y-m-d H:i:s",$tmp["time"]);
                }
            }

            if ($this->fileShowSize == 1)
            {
                $tmp["size"] = filesize($dir.$file);
                if ($this->fileConvertSize == 1)
                {
                    $tmp["size"] = $this->humanReadableSize($tmp["size"]);
                }
            }

            $sdir = "/".str_replace($this->baseRoot, "", $dir);

            $this->resultMixed[$sdir][$file] = $tmp;
        }
    }

    /**
     *
     *  humanReadable($size=0)
     *
     *  convert bytes to kb, mb, gb
     *
     *  @access   public
     *  @param    integer     the size
     *  @return   string
     *
     **/

    function humanReadableSize($size = 0)
    {
        $faktor = $tmpfaktor = 1024;

        for ($i=0; $i < count($this->globalSizeUnits); $i++)
        {
            if ( ($size/$tmpfaktor) < 1 )
            {
                return round($size/($tmpfaktor/$faktor), $this->globalSizeRound)." ".$this->globalSizeUnits[$i];
            }
            $tmpfaktor = $faktor*$tmpfaktor;
        }
    }

    /**
     *
     *  @access   public
     *  @param    integer   the current timestamp
     *  @return   string    the formatted date
     *
     */

    function humanReadableTime($timestamp = -1)
    {
        if ($timestamp == -1)
        {
            $timestamp = time();
        }

        $date = ereg_replace('%[aA]', $this->globalDateDays[(int)strftime('%w', $timestamp)], $this->globalDateFormat);
        $date = ereg_replace('%[bB]', $this->globalDateMonths[(int)strftime('%m', $timestamp)-1], $date);

        return strftime($date, $timestamp);
    }

}

?>