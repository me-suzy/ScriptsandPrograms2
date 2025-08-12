<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntDialogWidget', 'pnt/web/widgets');

/** FormWidget that generates html specifying textfield
* and a button. Both will react to a click by open a Dialog.
* When the dialog is closed it calls a funcion specified by
* this Widget to set the new value and label in this Widget. 
*
* This concrete subclass is here to keep de application developers
* code separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass.
* @package widgets
*/
class DialogWidget extends PntDialogWidget {

}
?>