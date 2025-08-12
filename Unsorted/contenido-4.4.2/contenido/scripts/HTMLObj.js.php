<?php
/*****************************************
*
* $Id: HTMLObj.js.php,v 1.8 2003/10/15 09:44:04 jan Exp $
*
* File      :   $RCSfile: HTMLObj.js.php,v $
* Project   :
* Descr     :
*
* Author    :   $Author: jan $
* Modified  :   $Date: 2003/10/15 09:44:04 $
*
* © four for business AG, www.4fb.de
******************************************/

include_once (getcwd().'/../includes/config.php');

include_once ($cfg["path"]["contenido"].$cfg["path"]["includes"] . 'functions.i18n.php');

header("Content-Type: text/javascript");

page_open(array('sess' => 'Contenido_Session',
                'auth' => 'Contenido_Challenge_Crypt_Auth',
                'perm' => 'Contenido_Perm'));

i18nInit($cfg["path"]["contenido"].$cfg["path"]["locale"], $belang);
page_close();
?>

/**
 * HTMLObj Class
 *
 * Object of an HTML Element.
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function HTMLObj(objId) {

    this.objId = objId;
    this.obj = document.getElementById( this.objId );
    this.type = this.getElementType();
    this.id = null;
    this.status = 0; /* status for images / other elements..
                        0 - normal
                        1 - out
                        2 - locked */

} // end function

/**
 * Defines the HTML Element Type
 * and calls the setMethods() method
 * to add the corresponding methods
 * to the HTMLObj instance.
 *
 * @return type string Type of the HTML Element ('image'/'select')
 */
HTMLObj.prototype.getElementType = function () {

    var type = 'undefined';

    switch ( this.obj.tagName ) {

        case 'IMG':
                type = 'image';
            break;

        case 'SELECT':
                type = 'select';
            break;
    }

    if ( 'undefined' != type ) {
        this.setMethods( type );
    }

    return type;

} // end function

/**
 * Set methods depending on
 * the HTML Element type
 */
HTMLObj.prototype.setMethods = function(type) {

    switch ( type ) {

        case 'image':

            /* .over() method */
            this.over = function() {
                if ( '' != this.oImgSrc ) {
                    this.obj.src = this.oImgSrc;
                    this.status = "over";
                }
            }

            /* .out() method */
            this.out = function() {
                if ( '' != this.nImgSrc ) {
                    this.obj.src = this.nImgSrc
                    this.status = "out";
                }
            }

            /* Set image sources */
            this.setImgSrc = function( nImgSrc, oImgSrc ) {
                this.nImgSrc = nImgSrc;
                this.oImgSrc = oImgSrc;
            }

            /* Set the intance id */
            this.setId = function(id) {
                /* JS Object */
                this.id = id;
                /* HTML Object */
                this.obj.id = id;
            }

            /* Lock the image */
            this.lock = function() {
                this.obj.src = "images/spacer.gif";
            }

            /* ATTENTION HARDCODED EVENTS =/ */
            this.obj.onmouseover    = showAction;
            this.obj.onmouseout     = hideAction;
            this.obj.onclick        = doAction;

            break;

        case 'select':

            /**
             * Select an entry
             * @param string value of the entry
             * @return void
             */
            this.select = function( selectedValue ) {

                var options = this.obj.getElementsByTagName( 'option' );
                var index = 0;

                for (i = 0; i < options.length; i ++) {
                    if ( selectedValue == options[i].value ) {
                        index = i;
                    }
                }

                this.obj.selectedIndex = index;

            }

            /**
             * Return value of the
             * select
             */
            this.getValue = function() {
                return this.obj.value;
            }

            /**
             * Return 'object HTMLCollection' for 'options'
             * @return object HTMLCollection All 'option' objects
             */
            this.getCollection = function() {
                return this.obj.getElementsByTagName( 'option' );
            }

            break;

    } // end switch

} // end function

/**
 * Controls the actions of
 * the infoBox class
 *
 * @return void
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function showAction() {

    var str = this.src;

    if ( str.indexOf('setoffline') != -1 ) {
        box.show( '<?php echo i18n("Make offline"); ?>' );

    } else if ( str.indexOf('folder_setoffline.gif') != -1 ) {
        box.show('<?php echo i18n("Make online"); ?>');

    } else if ( str.indexOf('folder_setonline.gif') != -1 ) {
        box.show('<?php echo i18n("Make online"); ?>');

    } else if ( str.indexOf('folder_lock.gif') != -1 ) {
        box.show('<?php echo i18n("Protect"); ?>');

    } else if ( str.indexOf('folder_delock.gif') != -1 ) {
        box.show('<?php echo i18n("Remove protection"); ?>');

    } else {
        /* User has no right */
        box.show('<?php echo i18n("Choose template"); ?>');
    }

}

/**
 * Controls the execution of
 * the actions depending on
 * the cfg properties
 *
 * @return void
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function doAction() {

    var str = this.src;

    if ( str.indexOf('folder_setoffline.gif') != -1 ) {
        /* offline schalten */
        str  = "";
        str += "main.php?area=con";
        str += "&action=con_makecatonline";
        str += "&frame=2";
        str += "&idcat=" + cfg.catId;
        str += "&online=" + cfg.isOnline;
        str += "&contenido=" + sid;

        /* If a category is marked, i.e. the
           property catId is set,
           set the action in the cfg class */
        if (cfg.catId != 0 && cfg.hasRight['online'] ) {

            /* set the action in the cfg class */
            cfg.setAction(str);

            /* change image source */
            if ( this.status == "out" ) {
                cfg.objRef[1].over()
                cfg.isOnline = ( cfg.isOnline == 0 ) ? 1 : 0;

            } else {
                cfg.objRef[1].out()
                cfg.isOnline = ( cfg.isOnline == 0 ) ? 1 : 0;
            }
        }


    } else if ( str.indexOf('folder_setonline.gif') != -1 ) {
        /* online schalten */
        str  = "";
        str += "main.php?area=con";
        str += "&action=con_makecatonline";
        str += "&frame=2";
        str += "&idcat=" + cfg.catId;
        str += "&online=" + cfg.isOnline;
        str += "&contenido=" + sid;

        /* If a category is marked -> the
           property catId is set,
           set the action in the cfg class */
        if (cfg.catId != 0  && cfg.hasRight['online'] ) {

            /* set the action in the cfg class */
            cfg.setAction(str);

            /* change image source */
            if ( this.status == "out" ) {
                cfg.objRef[1].over()
                cfg.isOnline = ( cfg.isOnline == 0 ) ? 1 : 0;

            } else {
                cfg.objRef[1].out()
                cfg.isOnline = ( cfg.isOnline == 0 ) ? 1 : 0;
            }

        }


    } else if ( str.indexOf('folder_lock.gif') != -1 ) {
        /* lock category */

        /* create action string */
        str  = "";
        str += "main.php?area=con";
        str += "&action=con_makepublic";
        str += "&frame=2";
        str += "&idcat=" + cfg.catId;
        str += "&public=" + cfg.isPublic;
        str += "&contenido=" + sid;

        /* If a category is marked -> the
           property catId is set,
           set the action in the cfg class */
        if (cfg.catId != 0 && cfg.hasRight['public'] ) {

            /* set action in the cfg class */
            cfg.setAction(str);

            /* change image source */
            if ( this.status == "out" ) {
                cfg.objRef[2].over()
                cfg.isPublic = ( cfg.isPublic == 0 ) ? 1 : 0;

            } else {
                cfg.objRef[2].out()
                cfg.isPublic = ( cfg.isPublic == 0 ) ? 1 : 0;
            }
        }


    } else if ( str.indexOf('folder_delock.gif') != -1 ) {
        /* unlock category */

        /* create action string */
        str  = "";
        str += "main.php?area=con";
        str += "&action=con_makepublic";
        str += "&frame=2";
        str += "&idcat=" + cfg.catId;
        str += "&public=" + cfg.isPublic;
        str += "&contenido=" + sid;

        /* If a category is marked -> the
           property catId is set,
           set the action in the cfg class */
        if (cfg.catId != 0 && cfg.hasRight['public'] ) {

            /* set action in the cfg class */
            cfg.setAction(str);

            /* change image source */
            if ( this.status == "out" ) {
                cfg.objRef[2].over();
                cfg.isPublic = ( cfg.isPublic == 0 ) ? 1 : 0;

            } else {
                cfg.objRef[2].out();
                cfg.isPublic = ( cfg.isPublic == 0 ) ? 1 : 0;

            }
        }
    }
}

/**
 * Show the default text
 * in the infoBox
 *
 * @return void
 * @author Jan Lengowski <jan.lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function hideAction() {
    box.show();
}
