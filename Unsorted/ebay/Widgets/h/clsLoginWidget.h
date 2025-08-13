/*	$Id: clsLoginWidget.h,v 1.2 1999/05/19 02:34:07 josh Exp $	*/
//
//	File:	clsLoginWidget.h
//
//	Class:	clsLoginWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a login page.
//		usage example:
//			clsLoginWidget	Login(mpMarketPlace)
//			char Action[255];
//			clsNameValuePair theNameValuePairs[2];
//			sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageMultipleEmails));
//
//			theNameValuePairs[0].SetName("MfcISAPICommand");
//			theNameValuePairs[0].SetValue("MultipleEmails");
//			theNameValuePairs[1].SetName("userids");
//			theNameValuePairs[1].SetValue(pRequestedUserIds);
//			Login.SetParas(Action, 2, theNameValuePairs);
//			Login.EmitHTML(mpStream);
//							
// Modifications:
//				- 2/2/99	Wen - Created
//
#ifndef CLSLOGINWIDGET_INCLUDED
#define CLSLOGINWIDGET_INCLUDED

#include "clsNameValue.h"

class clsLoginWidget
{
public:

	// Hot item widget requires having access to the marketplace
	clsLoginWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clsLoginWidget();

	// set parameters
	void SetParams(char* pAction,
				  int Pairs, 
				  clsNameValuePair* pNameValue,
				  bool FieldsOnly/*=false*/);

	// emit html
	void EmitHTML(ostream *pStream);

protected:
	clsMarketPlace*	mpMarketPlace;
	const char*		mpPrompt;
	const char*		mpCookie;
	bool			mShowRememberMe;
	const char*		mpAction;
	int				mPairs;
	clsNameValuePair*	mpNameValues;
	bool			mFieldOnly;
};

#endif // CLSLOGINWIDGET_INCLUDED
