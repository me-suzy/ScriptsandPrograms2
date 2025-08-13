/*	$Id: clseBayAppCreateCobrandPartner.cpp,v 1.4.382.2 1999/08/05 20:42:12 nsacco Exp $	*/
//
// File Name: clseBayAppCreateCobrandPartner.cpp
//
// Description: Does the actual creation (via clsPartners)
//              of a new cobranding partner -- invoked from
//              the ShowCobrandPartners page, and creates
//              a link to the ShowCobrandHeaders page.
//
// Author:      Chad Musick
//			05/25/99	nsacco	- MarketPlace uses Site for Partners now
//			06/21/99	nsacco	- Add siteId when linking to ShowCobrandHeaders and
//								  pass additional information into CreateCobrandPartner
//			07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()								  
//
#include "ebihdr.h"

// 06/21/99 new params siteId and pParsedString
void clseBayApp::CreateCobrandPartner(eBayISAPIAuthEnum authLevel,
									  const char *pName,
									  const char *pDesc,
									  int siteId,
									  const char *pParsedString)
{
	clsPartners *pPartners;
	int partnerId;

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
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Cobranding Administration</h2>"
			  <<	"<br>\n";
	
	// nsacco 06/21/99 use the siteId passed in rather than the current one
	pPartners = mpMarketPlace->GetSites()->GetSite(siteId)->GetPartners();
	// nsacco 06/21/99 added siteId and pParsedString
	partnerId = pPartners->CreatePartner(pName, pDesc, siteId, pParsedString);

	*mpStream << "<A HREF=\""
		      << mpMarketPlace->GetCGIPath(PageShowCobrandHeaders)
			  << "eBayISAPI.dll?ShowCobrandHeaders&partner=" 
			  << partnerId 
			  // nsacco 06/21/99
			  << "&siteid="
			  << siteId
			  << "\">"
			  << "Set Headers for " << pDesc << "</A><BR>";

	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
}
