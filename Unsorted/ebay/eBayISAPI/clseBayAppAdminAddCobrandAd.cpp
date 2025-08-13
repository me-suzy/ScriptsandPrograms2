/*	$Id: clseBayAppAdminAddCobrandAd.cpp,v 1.1.8.1 1999/06/13 21:48:16 wwen Exp $	*/
//
//	File:		clseBayAppAdminAddCobrandAd.cpp
//
//	Class:		clseBayApp
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:	clseBayAppAdminAddCobrandAd
//
//
//	Modifications:
//				- 05/31/99 mila		- Created
//


#include "ebihdr.h"
#include "clsAd.h"
#include "clsAds.h"


void clseBayApp::AdminAddCobrandAd(CEBayISAPIExtension *pThis,
								   char *pName,
								   char *pText,
								   eBayISAPIAuthEnum authLevel)
{
	clsAds	ads;
	clsAd *	pAd = NULL;
	bool	success = false;	

	// Setup
	SetUp();	
				
	// Title
	EmitHeader("Create Cobrand Ad");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pName))
	{
		*mpStream	<< "<h2>Ad Name Is Missing</h2>\n"
					<< "Sorry, you did not enter a name for your ad. Please go back "
					<< "and enter a name for your ad."
					<< "<p>"
					<< mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pText))
	{
		*mpStream	<< "<h2>Ad Description Is Missing</h2>\n"
					<< "Sorry, you did not enter a description for your ad. Please go "
					<< "back and enter a description for your ad."
					<< "<p>"
					<< mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	pAd = ads.GetAd(pName);
	if (pAd != NULL)
	{
		*mpStream	<< "<h2>Ad Already Exists</h2>\n"
					<< "<b>Sorry</b>, there is already an ad in the database with the name \""
					<< pName
					<< "\".  Please go back and enter another name for your ad."
					<< "<p>"
					<< mpMarketPlace->GetFooter();
		delete pAd;
		CleanUp();
		return;
	}

	pAd = new clsAd(pName, pText);

	success = ads.AddAd(pAd);

	if (success)
	{
		*mpStream <<	"<h2>Success!</h2>"
						"Your ad has been entered into the database.";
	}
	else
	{
		*mpStream <<	"<h2>Error!</h2>"
						"An error occurred while trying to enter your ad "
						"information into the database. Please report this to "
						"<a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>.";
	}

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

