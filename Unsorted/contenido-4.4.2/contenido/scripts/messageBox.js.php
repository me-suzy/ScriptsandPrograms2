<?php
/******************************************
* File      :   messageBox.js
* Project   :   Contenido
* Descr     :   Message Box for errors
*               and / or confirms
*
* Author    :   Jan Lengowski
* Created   :   08.05.2003
* Modified  :   08.05.2003
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

try {

/**
 * OK and CANCEL buttons
 */
button = new Array();
button['confirm']   = '<a href="javascript:msgConfirm()" title="<?php echo i18n("Confirm"); ?>"><img src="images/but_ok.gif" width="20" height="20" border="0"></a>';
button['cancel']    = '<a href="javascript:msgCancel()" title="<?php echo i18n("Cancel"); ?>"><img src="images/but_cancel.gif" width="20" height="20" border="0"></a>';
button['ok']        = '<a href="javascript:window.close()" title="<?php echo i18n("Close window"); ?>"><img src="images/but_ok.gif" width="20" height="20" border="0"></a>';
button['warn']      = '<img src="images/but_warn.gif">';



/**
 * Default HTML Template for the
 * messageBox class
 */
defaultTemplate  = '';
defaultTemplate += '<html>';
defaultTemplate += '    <head>';
defaultTemplate += '    <title>Contenido</title>';
defaultTemplate += '        <style type="text/css">';
defaultTemplate += '            table   { border: 2px solid #C54A33 }';
defaultTemplate += '            body    { margin: 0px; background-color: #ffffff; }';
defaultTemplate += '            .head   { font-family: verdana; font-weight: bold; font-size: 12px; color: #000000 }';
defaultTemplate += '            .text   { font-family: verdana; font-size: 11px; color: #000000 }';
defaultTemplate += '        </style>';
defaultTemplate += '        <script type="text/javascript">';
defaultTemplate += '            window.onclose = msgCancel;';
defaultTemplate += '            function msgConfirm(){ {CALLBACK} window.close() }';
defaultTemplate += '            function msgCancel(){ window.close(); }';
defaultTemplate += '        </script>';
defaultTemplate += '    </head>';
defaultTemplate += '<body>';
defaultTemplate += '    <table height="100%" width="100%" cellspacing="0" cellpadding="4" border="0">';
defaultTemplate += '        <tr valign="middle">';
defaultTemplate += '            <td>{IMAGE}</td>';
defaultTemplate += '            <td class="head">{HEADLINE}</td>';
defaultTemplate += '        </tr>';
defaultTemplate += '        <tr height="100%" valign="top">';
defaultTemplate += '            <td></td>';
defaultTemplate += '            <td class="text">{MESSAGE}</td>';
defaultTemplate += '        </tr>';
defaultTemplate += '        <tr>';
defaultTemplate += '            <td></td>';
defaultTemplate += '            <td align="right">{CANCEL}&nbsp;&nbsp;&nbsp;&nbsp;{CONFIRM}</td>';
defaultTemplate += '        </tr>';
defaultTemplate += '    </table>';
defaultTemplate += '</body>';
defaultTemplate += '</html>';


/**
 * Class to display errors and notifications
 *
 * @param headline string The headline of the message
 * @param message srint The message text
 * @param htmlTemplate
 *
 *
 
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 * @version 0.9
 */
function messageBox(headline, message, htmlTemplate, width, height) {

    /* The error message
       headline */
    this.headline = headline || "";

    /* The error message /
       notification */
    this.message = message || "";

    /* HTML Template for
       the message  */
    this.html = htmlTemplate || defaultTemplate;

    /* Width of the popup,
       defaults to '200' */
    this.width = width || 200;

    /* Height of the popup,
       defaults to '150' */
    this.height = height || 150;

    /* Status of the popup,
       true  => popup open
       false => popup closed */
    this.status = false;

    /* Reference to the pop-up
       window. */
    this.winRef = false;

}

/**
 * Displays a notification
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
messageBox.prototype.notify = function(head, msg) {

    /* Some required variables */
    var template    = this.html;
    var msg         = msg || this.message;
    var head        = head || this.headline;

    /* X and Y position where the
       pop-up is centered */
    var x = parseInt( (screen.availWidth / 2) - (this.width / 2) );
    var y = parseInt( (screen.availHeight / 2) - (this.height / 2) );

    /* Replace placeholder with
       the contents  */
    template = template.replace(/{HEADLINE}/,   head);
    template = template.replace(/{MESSAGE}/,    msg);
    template = template.replace(/{IMAGE}/,      button['warn']);
    template = template.replace(/{CALLBACK}/,   "");
    template = template.replace(/{CANCEL}/,     "");
    template = template.replace(/{CONFIRM}/,    button['ok']);

    /* Open a new pop-up
       window */
    this.winRef = window.open("", "", "left="+x+",top="+y+",width="+this.width+",height="+this.height+"\"");
    this.winRef.moveTo(x, y);

    /* Write template */
    this.winRef.document.open();
    this.winRef.document.write(template);
    this.winRef.document.close();

    /* Focus the pop-up */
    this.winRef.focus();

}



/**
 * Displays a confirmation pop-up.
 *
 * @param head string Headline for the message
 * @param msg string The message
 * @param callback string Name of the function executed on confirmation
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
messageBox.prototype.confirm = function(head, msg, callback) {

    /* Some required variables */
    var template    = this.html;
    var msg         = msg || this.message;
    var head        = head || this.headline;

    /* X and Y position where the
       pop-up is centered */
    var x = parseInt( (screen.availWidth / 2) - (this.width / 2) );
    var y = parseInt( (screen.availHeight / 2) - (this.height / 2) );

    /* Replace placeholder with
       the contents  */
    template = template.replace(/{HEADLINE}/,   head);
    template = template.replace(/{MESSAGE}/,    msg);
    template = template.replace(/{IMAGE}/,      button['warn']);
    template = template.replace(/{CALLBACK}/,   "window.opener."+callback+";");
    template = template.replace(/{CANCEL}/,     button['cancel']);
    template = template.replace(/{CONFIRM}/,    button['confirm']);

    /* Open a new pop-up
       window */
    this.winRef = window.open("", "", "left="+x+",top="+y+",width="+this.width+",height="+this.height+"\"");
    this.winRef.moveTo(x, y);

    /* Write template */
    this.winRef.document.open();
    this.winRef.document.write(template);
    this.winRef.document.close();
    
    /* Focus the pop-up */
    this.winRef.focus();

}

} catch(e) {


}
