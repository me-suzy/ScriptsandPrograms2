<?php



header("Content-type: text/css");



?>

/**

 * PHProjekt CSS-File

 *

 * $Id: default_css_ie.php,v 1.5.2.1 2005/08/11 09:34:38 paolo Exp $

 *

 */



.navbutton,

a.navbutton,

a.navbutton:hover,

a.navbutton:visited {

    position: relative;

    top: 1px;

    left: 3px;

    padding-top: 1px;

    padding-left: 10px;

    padding-right :10px;

    margin-left:3px;

    border-width: 2px;

    color: #ffffff;

    font-size: 1em;

    font-weight:bold;

    border-style:solid;

    cursor: pointer;

    width: auto;

    text-decoration: none;

}



INPUT { border: expression((this.type=="checkbox" || this.type=="radio")  ? 'none' : '1px solid #000000'); }

INPUT { background-color: expression((this.type=="checkbox" || this.type=="radio")  ? 'rgb(239,239,239)' : ''); }



fieldset.navsearch {

    margin-left:-2px;

}

