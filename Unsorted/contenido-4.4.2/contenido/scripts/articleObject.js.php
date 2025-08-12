<?php
/******************************************
* File      :   articleObject.php
* Project   :   Contenido
* Descr     :   Moving article related
*               logic to the front-end
*
* Author    :   Jan Lengowski
* Created   :   08.05.2003
* Modified  :   23.06.2003
*
* © four for business AG
******************************************/


include_once ('../includes/config.php');

include_once ($cfg["path"]["contenido"].$cfg["path"]["includes"] . 'functions.i18n.php');

header("Content-Type: text/javascript");

page_open(array('sess' => 'Contenido_Session',
                'auth' => 'Contenido_Challenge_Crypt_Auth',
                'perm' => 'Contenido_Perm'));

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);
page_close();
?>

/**
 * Object of an article
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function articleObject(actionFrameName, frameNumber) {

    /* Name of the Actionframe.
       Defaults to 'right_bottom' */
    this.actionFrameName = actionFrameName || 'right_bottom';

    /* Reference to the Actionframe */
    this.actionFrame = parent.frames[ this.actionFrameName ];

    /* Frame-number.
       Defaults to '4' */
    this.frame      = frameNumber || 4;

    /* Filename of the contenido
       main file - defaults to 'main.php' */
    this.filename   = "main.php?"

    /* Contenido session name -
       defaults to 'contenido' */
    this.sessionName = "contenido"

    /* Global Vars */
    this.sessid     = 0;
    this.client     = 0;
    this.lang       = 0;

    /* Article Properties*/
    this.idart      = 0;
    this.idartlang  = 0;
    this.idcat      = 0;
    this.idcatlang  = 0;
    this.idcatart   = 0;
    
    /* Menu visible / invisible */
    this.vis = 1;
    
}

/**
 * Define required global variables
 *
 * @return void
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
articleObject.prototype.setGlobalVars = function(sessid, client, lang) {
    this.sessid = sessid;
    this.client = client;
    this.lang   = lang;
}

/**
 * Reset properties
 *
 * @return void
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
articleObject.prototype.reset = function() {

    this.idart      = 0;
    this.idartlang  = 0;
    this.idcatlang  = 0;
    this.idcatart   = 0;
    
}

/**
 * Define required global variables
 *
 * @return string with attached frame & session parameters
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
articleObject.prototype.sessUrl = function(str) {

    var tmp_str = str;
    tmp_str += '&frame=' + this.frame;
    tmp_str += '&'+this.sessionName+'='+this.sessid;
    return tmp_str;
    
}

/**
 * Execute an action
 *
 * @return bool Action executes Yes/No
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
articleObject.prototype.doAction = function(str) {

    /* Flag if action will be
       executed. */
    var doAction = false;
    
    /* create messageBox
       instance */
    var box = new messageBox("", "", "", 0, 0);

    /* Notify Headline */
    var headline = "<?php echo i18n("Error"); ?>";

    /* Default error string */
    var err_str = "<?php echo i18n("Error"); ?>";

    switch (str) {

        /* Article overview mask */
        case 'con':

            /* Cehck if required parameters
               are set  */
            if ( 0 != this.idcat ) {
                url_str = this.sessUrl(this.filename + "area=" + str + "&idcat=" + this.idcat);
                doAction = true;
            } else {
                /* This ERROR should never happen,
                   i.e. the property idcat will not
                   be reseted once set. */
                err_str = "<?php echo i18n("Overview cannot be displayed"); ?>";

            }
            break;

        /* Edit article properties */
        case 'con_editart':

            /* Check if required parameters
               are set  */
            if ( 0 != this.idart && 0 != this.idcat ) {
                url_str = this.sessUrl(this.filename + "area=" + str + "&action=con_edit&idart=" + this.idart + "&idcat=" + this.idcat);
                doAction = true;
            } else {
                /* There is no selected article,
                   we do not have the neccessary
                   data to display the Article-
                   properties mask */
                err_str = "<?php echo i18n("Article can't be displayed")."<br>".i18n("No article was selected"); ?>";

                if ( parent.frames["right_top"].document.getElementById("c_0") ) {
                    menuItem = parent.frames["right_top"].document.getElementById("c_0");
                    parent.frames["right_top"].sub.click(menuItem);
                }
            }
            break;

        /* Template configuration */
        case 'con_tplcfg':

            /* Check if required parameters
               are set  */
            if ( 0 != this.idart && 0 != this.idcat ) {
                url_str = this.sessUrl(this.filename + "area=" + str + "&action=tplcfg_edit&idart=" + this.idart + "&idcat=" + this.idcat);
                doAction = true;
            } else {
                /* There is no selected article,
                   we do not have the neccessary
                   data to display the Template-
                   configuration mask */
                err_str = "<?php echo i18n("Template configuration can't be displayed")."<br>".i18n("No article was selected"); ?>";
                
				if ( parent.frames["right_top"].document.getElementById("c_0") ) {
                    menuItem = parent.frames["right_top"].document.getElementById("c_0");
                    parent.frames["right_top"].sub.click(menuItem);
                }
            }
            break;

        /* Edit article */
        case 'con_editcontent':

            /* Check if required parameters
               are set  */
            if ( 0 != this.idart && 0 != this.idartlang && 0 != this.idcat ) {
                url_str = this.sessUrl(this.filename + "area=" + str + "&action=con_editart&changeview=edit&idart=" + this.idart + "&idartlang=" + this.idartlang + "&idcat=" + this.idcat);
                doAction = true;
            } else {
                /* There is no selected article,
                   we do not have the neccessary
                   data to display the Editor */
                err_str = "<?php echo i18n("Editor can't be displayed")."<br>".i18n("No article was selected"); ?>";

                if ( parent.frames["right_top"].document.getElementById("c_0") ) {
                    menuItem = parent.frames["right_top"].document.getElementById("c_0");
                    parent.frames["right_top"].sub.click(menuItem);
                }
            }
            break;

        /* Preview article */
        case 'con_preview':

            /* Check if required parameters
               are set  */
            if ( 0 != this.idart && 0 != this.idartlang && 0 != this.idcat ) {
                url_str = this.sessUrl(this.filename + "area=con_editcontent&action=con_editart&changeview=prev&idart=" + this.idart + "&idartlang=" + this.idartlang + "&idcat=" + this.idcat);
                doAction = true;
            } else {
                /* There is no selected article,
                   we do not have the neccessary
                   data to display the Editor */
                if ( parent.frames["right_top"].document.getElementById("c_0") ) {
                    menuItem = parent.frames["right_top"].document.getElementById("c_0");
                    parent.frames["right_top"].sub.click(menuItem);
                }
                err_str = "<?php echo i18n("Preview can't be displayed")."<br>".i18n("No article was selected"); ?>";
            }
            break;

    }

    if (doAction) {
        this.actionFrame.location.href = url_str;
        return true;
        
    } else {
        box.notify(headline, err_str);
        
    }
    
    return false;
    
}


/**
 * Define article and category related properties
 *
 * @return void
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
articleObject.prototype.setProperties = function () {

    this.idart      = arguments[0];
    this.idartlang  = arguments[1];
    this.idcat      = arguments[2];
    this.idcatlang  = arguments[3];
    this.idcatart   = arguments[4];

}

/**
 * Disables the navigation
 *
 * @param none
 * @return void
 */
articleObject.prototype.disable = function() {

    var oRef = [];

    oRef[0] = document.getElementById( "c_0" );
    oRef[1] = document.getElementById( "c_1" );
    oRef[2] = document.getElementById( "c_2" );
    oRef[3] = document.getElementById( "c_3" );
    oRef[4] = document.getElementById( "c_4" );

    if ( this.vis == 1 ) {

        for (i=1; i<oRef.length; i++) {
            links = oRef[i].getElementsByTagName("a");
            links[0].style.visibility = "hidden";
        }

        sub.markedRow.style.backgroundColor = "#C6C6D5";

    }

    this.vis = 0;

} // end function


/**
 * Disables the navigation
 *
 * @param none
 * @return void
 */
articleObject.prototype.enable = function() {

    var oRef = [];

    oRef[0] = document.getElementById( "c_0" );
    oRef[1] = document.getElementById( "c_1" );
    oRef[2] = document.getElementById( "c_2" );
    oRef[3] = document.getElementById( "c_3" );
    oRef[4] = document.getElementById( "c_4" );

    if ( this.vis == 0 ) {

        for (i=0; i<oRef.length; i++) {
            links = oRef[i].getElementsByTagName("a");
            links[0].style.visibility = "visible";
        }

    }

    this.vis = 1;

} // end function
