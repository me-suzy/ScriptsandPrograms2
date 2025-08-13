/*	$Id: clsEmailLoginWidget.h,v 1.2 1999/05/19 02:34:07 josh Exp $	*/
//
//	File:	clsEmailLoginWidget.h
//
//	Class:	clsEmailLoginWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a login page for requesting other user email.
//		The class is derived from clsLoginWidget
//							
// Modifications:
//				- 2/2/99	Wen - Created
//
#ifndef CLSEMAILLOGINWIDGET_INCLUDED
#define CLSEMAILLOGINWIDGET_INCLUDED

#include "clsLoginWidget.h"

class clsEmailLoginWidget : public clsLoginWidget
{
public:

	// Hot item widget requires having access to the marketplace
	clsEmailLoginWidget(clsMarketPlace *pMarketPlace);

};

#endif // CLSEMAILLOGINWIDGET_INCLUDED
