/*	$Id: clseBayAppRewriteCobrandHeaders.cpp,v 1.4.382.2 1999/08/05 20:42:19 nsacco Exp $	*/
//
// File Name: clseBayAppRewriteCobrandHeaders.cpp
//
// Description: A simple function to write to file all of the
//              headers and footers necessary for the cobrand
//              filter -- this normally happens when each one
//              is set, but this function can be used in case
//              of file loss, corruption, etc.
//
// Author:      Chad Musick
//			05/25/99	nsacco	- MarketPlace uses Site for Partners now
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::RewriteCobrandHeaders(eBayISAPIAuthEnum authLevel)
{
	vector<clsPartner *> vPartners;
	vector<clsPartner *>::iterator i;
	clsPartners *pPartners;
	int pageType;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Cobranding Administration"
			  <<	"</TITLE></head>"
			  <<	flush;
#if 0
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

#endif
	// start the page blurb
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<hr width=50%>\n"
			  <<	"<h2 align=center>Cobranding Administration</h2>"
			  <<	"<br>\n";

	// nsacco 05/25/99 MarketPlace uses Site for Partners now
	pPartners = mpMarketPlace->GetSites()->GetCurrentSite()->GetPartners();
	pPartners->GetAllPartners(&vPartners);

	for (i = vPartners.begin(); i != vPartners.end(); ++i)
	{
		if (!*i)
			continue;

		for (pageType = 0; pageType < PageTypeLast; ++pageType)
		{
			(*i)->WriteHeaderToFile((PageTypeEnum) pageType, "/ebay/cobrandHTML/Debug");
			(*i)->WriteFooterToFile((PageTypeEnum) pageType, "/ebay/cobrandHTML/Debug");
		}
	}
	*mpStream << "Headers rewritten.\n";
	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
}
