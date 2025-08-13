/*	$Id: clsPSLoginWidget.h,v 1.2 1999/05/19 02:34:08 josh Exp $	*/
//
//	File:	clsPSLoginWidget.h
//
//	Class:	clsPSLoginWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a login page for Personal Shopper.
//		The class is derived from clsLoginWidget
//							
// Modifications:
//				- 2/2/99	Wen - Created
//
#ifndef CLSPSLOGINWIDGET_INCLUDED
#define CLSPSLOGINWIDGET_INCLUDED

#include "clsLoginWidget.h"

class clsPSLoginWidget : public clsLoginWidget
{
public:

	// Hot item widget requires having access to the marketplace
	clsPSLoginWidget(clsMarketPlace *pMarketPlace);
	~clsPSLoginWidget();

protected:
	char*	mpTempPrompt;

};

#endif // CLSPSLOGINWIDGET_INCLUDED