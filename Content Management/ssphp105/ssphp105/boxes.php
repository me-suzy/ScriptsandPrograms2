<?php

    function smbox($topcolor,$title,$content)
    {
        $top = "smtop-" . $topcolor . ".jpg";
        
        $top = "smtop.gif";
        
        $retval = "
            <table width=129 border=0 cellpadding=0 cellspacing=0> 
            <tr>
            <div style='position:absolute'>
            <td background='$top' height=41 class='a'>
            <div style='position:relative; margin-left: -5px'>
            <center>$title</center>
            </div></div>
            
            </td></tr>
            <tr>
            <div style='position:absolute'>
            <td background='smbg.gif' > 
            <div style='position:relative; margin-left: 5px; margin-right: 8px'>
            $content
            </div></div>
            </td></tr>
            <tr><td background='smbot2.gif' height=20>&nbsp</td></tr>
            </table><BR>
            ";//<tr><td background='smbot.jpg' height=36>&nbsp</td></tr>
        
        return $retval;
    }

    function lgbox($title,$content)
    {
        $retval = "
            <table width=511 border=0 cellpadding=0 cellspacing=0> 
            <tr>
            <div style='position:absolute'>
            <td background='lgtop.gif' height=32 class='a'>
            <div style='position:relative; margin-top: -5px; margin-left: -5px'>
            <center>$title</center>
            </div></div>
            
            </td></tr>
            <tr>
            <div style='position:absolute'>
            <td background='lgbg2.gif'> 
            <div style='position:relative; margin-left: 10px; margin-right: 10px'>
            $content
            </div></div>
            </td></tr>
            <tr><td background='lgbot.gif' style='height: 5px'></td></tr>
            </table><BR>
            ";
        return $retval;
    }
    
?>