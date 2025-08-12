<?php
    function getDelayColor($nDays)
    {
		global $DELAY_NORMAL, $DELAY_INDERMIDIATE;

        if ($nDays <= $DELAY_NORMAL)
        {
            return "#019A10";
        }
        else if ($nDays <= $DELAY_INDERMIDIATE)
        {
            return "#FF6C00";
        }
        else
        {
            return "#F70415";
        }
    }
    
    function getFileNameFromPath($strPath)
    {
        $arrPathParts = split("[/\]", $strPath);        
        
        $strFileName = $arrPathParts[sizeof($arrPathParts)-1];
        /*$nPos = strpos($strFileName, "_");
        
        $strFileName = substr($strFileName, $nPos+1);*/
        
        return $strFileName;
    }
?>