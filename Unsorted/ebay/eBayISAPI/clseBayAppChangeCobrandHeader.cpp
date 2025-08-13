/*	$Id: clseBayAppChangeCobrandHeader.cpp,v 1.4.204.1.94.2 1999/08/05 20:42:11 nsacco Exp $	*/
//
// File Name: clseBayAppChangeCobrandHeader.cpp
//
// Description: Changes a cobrand header (or footer)
//              and prints out a confirmation page.
//
// Author:      Chad Musick
//
//		- 04/06/99 Vicki	- Added secondary page type entries 
//		- 05/25/99 nsacco	- Switch to using site in MarketPlace to get partners.
//		- 06/11/99 vicki	- added siteId
//		- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::ChangeCobrandHeader(eBayISAPIAuthEnum authLevel,
									const char *pNewDescription,
									int isHeader,
									int pageType,
									int partnerId,
									int pageType2,
									int siteId)
{
	clsPartner *pPartner;
//	clsPartners *pPartners;
	clsSite *		pSite = NULL;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Cobranding Header Administration"
			  <<	"</TITLE></head>"
			  <<	flush;
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Cobranding Header Administration</h2>"
			  <<	"<br>\n";

	pSite = mpMarketPlace->GetSites()->GetSite(siteId);
	if (pSite == NULL)
	{
		*mpStream	<<	"<h2>Invalid Site</h2>"
						"Sorry, the eBay site you selected is invalid. Please report this "
						"problem to <a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>."
						"<p>"
					<<	mpMarketPlace->GetFooter();

		// Do NOT delete pAd cuz it points into a cache

		CleanUp();
		return;
	}

	pPartner = pSite->GetPartners()->GetPartner(partnerId);

    if (pPartner == NULL)
    {
        *mpStream << "No such partner.<BR>";
        *mpStream << mpMarketPlace->GetFooter();

        CleanUp();
        return;
    }

	if (pageType < 0 || pageType >= PageTypeLast)
	{
		*mpStream << "Invalid Page Type.<BR>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (pageType2 < 0 || pageType2 >= PageTypeLast)
	{
		*mpStream << "Invalid Sencondary Page Type.<BR>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	isHeader = !!isHeader;

	if (isHeader)
		pPartner->SetHeader((PageTypeEnum) pageType, (PageTypeEnum) pageType2, pNewDescription);
	else
		pPartner->SetFooter((PageTypeEnum) pageType, (PageTypeEnum) pageType2, pNewDescription);

	if (isHeader)
		*mpStream << "Header set.<P>";
	else
		*mpStream << "Footer set.<P>";

	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
	return;
}
