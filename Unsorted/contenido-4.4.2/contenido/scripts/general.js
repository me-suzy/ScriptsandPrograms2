/******************************************
* File      :   general.js
* Project   :   Contenido
* Descr     :   Defines general required
*               javascript functions
*
* Author    :   Jan Lengowski
* Created   :   25.03.2003
* Modified  :   25.03.2003
*
* © four for business AG
******************************************/

/**
 * Javascript Multilink
 *
 * @param name-value-pairs framename src
 * @return void
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copryright four for business AG <www.4fb.de>
 */
function conMultiLink() {

    for (i = 0; i < arguments.length; i += 2) {

        f = arguments[i];
        l = arguments[i + 1]

        parent.frames[f].location.href = l;
    }
}

/**
 *
 *
 *
 *
 */
window.onerror = handleErrors;

function handleErrors() {
    /* Supress errors */
    return true;
}
