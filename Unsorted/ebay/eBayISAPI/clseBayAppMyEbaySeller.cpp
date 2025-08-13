/*	$Id: clseBayAppMyEbaySeller.cpp,v 1.5 1998/09/30 02:56:47 josh Exp $	*/
//
//	File:	clseBayAppMyEbaySeller.cpp
//
//	Class:	clseBayAppMyEbaySeller
//
//	Author:	Alex Poon (poon@ebay.com)
//
//	Function:
//
//		Displays the my ebay seller detail page
//
// Modifications:
//				- 11/18/97 poon	- Created
//

#include "ebihdr.h"
#include "clseBayUserSellingDetailWidget.h"

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

#include "hash_map.h"



//
// MyEbay
//
void clseBayApp::MyEbaySeller(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 char *pPass,
							 int sort,
							 int daysSince)
{
	clseBayUserSellingDetailWidget *usdw;

	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Title
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	" My eBay Selling Details Page for "
			  <<	pUserId
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	flush;


	/*
	// TEMPORARILY DISABLE EBAY
	*mpStream <<	"<h1>Sorry!</h1>"
					"My eBay, which is in testing, is currently not available!"
					"<br>"
			  <<	mpMarketPlace->GetFooter();
	return;
	*/

	// The last parameter allows the method to check if the password 
	// is the encrypted one stored in the database
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL,
													false, false, false, true);
	if (!mpUser)
	{
		*mpStream	<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Ok, at this point, we are authenticated, so let's continue

	// Output seller list
	usdw = new clseBayUserSellingDetailWidget(mpMarketPlace);
	usdw->SetUser(mpUser);
	usdw->SetSortCode((ItemListSortEnum)sort);
	usdw->SetHeaderColor("#FFCC99");
	usdw->SetCellSpacing(10);
	usdw->SetDaysSince(daysSince);
	usdw->SetIncremental(true);
	usdw->EmitHTML(mpStream);
	delete usdw;

	*mpStream	<<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

