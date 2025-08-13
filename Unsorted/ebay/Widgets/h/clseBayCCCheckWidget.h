/*	$Id: clseBayCCCheckWidget.h,v 1.2 1999/02/21 02:28:31 josh Exp $	*/
//
//	File:	clseBayCCCheckWidget.h
//
//	Class:	clseBayCCCheckWidget
//
//	Author:	Sam
//
//	Function:
//			Widget that checks Credit Card Expiry
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 02/17/98	Sam - Created
//
#ifndef CLSEBAYCCCHECKWIDGET_INCLUDED
#define CLSEBAYCCCHECKWIDGET_INCLUDED

#include "clseBayWidget.h"
#include "clsApp.h"
#include "clsAccount.h"


class clseBayCCCheckWidget : public clseBayWidget
{

public:

	// CC Check widget needs ID and days to expiry.
	clseBayCCCheckWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayCCCheckWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)

	{ return (clseBayWidget *) new clseBayCCCheckWidget(pMarketPlace); }
	
	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);
	void SetDaysToExpiry(int expiry) {mCCExpiry = expiry;}
	void SetAccount (clsAccount *pAccount) {mpAccount = pAccount;}

protected:

private:
	int			mCCExpiry;
	clsAccount	*mpAccount;
};

#endif // CLSEBAYCCCHECKWIDGET_INCLUDED
