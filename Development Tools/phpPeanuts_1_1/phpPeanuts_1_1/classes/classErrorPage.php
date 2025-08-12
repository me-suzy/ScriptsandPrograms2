<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntErrorPage', 'pnt/web/pages');

/** page used by ErrorHandler to show error message to end user.
* @see http://www.phppeanuts.org/site/index_php/Pagina/32
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass.
* This class may be copied to an application folder to
* make application specific overrides.
* @see http://www.phppeanuts.org/site/index_php/Pagina/64
*/
class ErrorPage extends PntErrorPage {

}
?>