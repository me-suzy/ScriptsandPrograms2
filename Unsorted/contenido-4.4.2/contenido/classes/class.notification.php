<?php

/**
 * class Contenido_Notification
 *
 * Class for displaying notifications
 *
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG <http://www.4fb.de>
 *
 */
class Contenido_Notification {

    /**
     * Constructor
     */
    function Contenido_Notification() {

    } # end function

    function returnNotification($level, $message)
    {

        global $cfg;
        
        switch ($level)
        {
            case "error":
                $bgColor = $cfg["color"]["notify_error"];
                $imgPath = $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."icon_fatalerror.gif";
                break;
                
            case "warning":
                $bgColor = $cfg["color"]["notify_warning"];
                $imgPath = $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."icon_warning.gif";
                break;
                
            case "info":
                $bgColor = $cfg["color"]["notify_info"];
                $imgPath = $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."icon_ok.gif";
                break;

            default:
                $bgColor = $cfg["color"]["notify"];
                $imgPath = $cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."icon_ok.gif";
                break;
        }

        $table = new Table($bgColor, "solid", 0, 2, "#FFFFFF", "#FFFFFF", "#FFFFFF", true, false);
        
        $noti .= $table->start_table();
        $noti .= $table->header_row();
        $noti .= $table->borderless_cell('<img src="'.$imgPath.'">');
        $noti .= $table->borderless_cell('<font color="'.$bgColor.'" style="{font-family: Verdana, Arial, Helvetica, Sans-Serif; font-size: 11px;">' .$message. '</font>', "left", "center");
        $noti .= $table->end_row();
        $noti .= $table->end_table();

        return $noti;
    }
    
    /**
     * Begins the new table
     * @param none
     * @return void
     */
    function displayNotification($level, $message) {

        echo $this->returnNotification($level,$message)."<br>"; 
        
        
    } # end function

} # end class

?>
