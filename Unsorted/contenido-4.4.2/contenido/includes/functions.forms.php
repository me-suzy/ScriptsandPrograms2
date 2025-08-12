<?php
/*****************************************
* File      :   $RCSfile: functions.forms.php,v $
* Project   :   Contenido
* Descr     :   Contenido Form Element Generator
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   20.05.2003
* Modified  :   $Date: 2003/08/14 12:47:27 $
*
* © four for business AG, www.4fb.de
*
* $Id: functions.forms.php,v 1.3 2003/08/14 12:47:27 timo.hummel Exp $
******************************************/


/**
 * Generates textial Input Form elements
 *
 * @param $type       Either "text", "password" or "textbox"
 * @param $name       Name of the field
 * @param $initvalue  Init value of the field
 * @param $size       Size of the field
 * @param $maxlen     Maximum length of the field
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG <http://www.4fb.de>
 *
 * @return Generated field code
 *
 */
function formGenerateField ($type, $name, $initvalue, $width, $maxlen)
{
    switch ($type)
    {
        case "text":
            return ('<input class="text_medium" type="text" name="'.$name.'" size="'.$width.'" maxlength="'.$maxlen.'" value="'.$initvalue.'">');
            break;
        case "password":
            return ('<input class="text_medium" type="password" name="'.$name.'" size="'.$width.'" maxlength="'.$maxlen.'" value="'.$initvalue.'">');
            break;
        case "textbox":
            return ('<textarea class="text_medium" name="'.$name.'" rows="'.$maxlen.'" cols="'.$width.'">'.$initvalue.'</textarea>');
            break;
        default:
            return('');
            break;
    }
        

}

/**
 * Generates check box elements
 *
 * @param $name       Name of the checkbox
 * @param $value      Value of the checkbox
 * @param $checked    Initially checked?
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @copyright four for business AG <http://www.4fb.de>
 *
 * @return Generated field code
 *
 */
function formGenerateCheckbox ($name, $value, $checked, $caption = "")
{
	if (strlen($caption) > 0)
	{
		$label = '<label for="'.$name.$value.'">'.$caption.'</label>';
	}
    if ($checked) {
        return('<input class="text_medium" id="'.$name.$value.'" type="checkbox" name="'.$name.'" value="'.$value.'" checked>'.$label);
    } else {
        return('<input class="text_medium" id="'.$name.$value.'" type="checkbox" name="'.$name.'" value="'.$value.'">'.$label);
    }

}
?>