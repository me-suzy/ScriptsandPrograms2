/*	$Id: clseBayAppRegisterShow.cpp,v 1.9.66.2.42.1 1999/08/01 03:01:25 barry Exp $	*/
//
//	File:	clseBayAppChangeRegistrationShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Emits the registration form. This is a program, as 
//		opposed to a form, because information like categories
//		for interests, and (later) valid countries, cities, and
//		states will come from the database.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 12/17/97 charles added user id field
//				- 11/20/98 bb - decomissioned this file and RegisterShow().
//				- 03/31/99 vicki -- take it back for diff propose, now it's only doing redirect thing
//				- 06/09/99 lou -- Use this as the main entry for Registration, then branch


#include "ebihdr.h"

void clseBayApp::RegisterShow(CEBayISAPIExtension *pServer,
							   CHttpServerContext* pCtxt)
{
	int	nPartnerId = 0;
	char newURL[512];	

	nPartnerId = mpMarketPlace->GetCurrentPartnerId();

	//Check to see if it's a AOL Co-brand partner, AOL need to be on a Secure Server
	if (PARTNER_AOL == nPartnerId)
	{
		strcpy(newURL, mpMarketPlace->GetSSLCGIPath(PageAOLRegisterShow));
		strcat(newURL, "eBayISAPI.dll?AOLRegisterShow");
	}
	else
	{
		//All others for now
		sprintf(newURL, "%sservices/registration/register.html", mpMarketPlace->GetHTMLPath());
	}

	pServer->EbayRedirect(pCtxt, newURL);
	return;

}


