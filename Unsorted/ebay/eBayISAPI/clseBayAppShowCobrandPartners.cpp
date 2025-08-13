/*	$Id: clseBayAppShowCobrandPartners.cpp,v 1.4.382.2 1999/08/05 20:42:20 nsacco Exp $	*/
//
// File Name: clseBayAppShowCobrandPartners.cpp
//
// Description: Contains the function to display the 'opening page'
//              for cobrand management -- has links to all the
//              current cobrand partners (for changing their
//              headers and footers), as well as a form to create
//              new cobrand partners.
//
// Author:      Chad Musick
// Modifications:
//	06/21/99	nsacco	- Added siteId to ShowCobrandPartners
//	07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//	08/05/99	nsacco	- Added html and head tags.

#include "ebihdr.h"

// nsacco 06/21/99
void clseBayApp::ShowCobrandPartners(eBayISAPIAuthEnum authLevel, int siteId)
{
	vector<clsPartner *> vPartners;
	vector<clsPartner *>::iterator i;
	clsPartners *pPartners;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<HTML><HEAD><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Cobranding Administration"
			  <<	"</TITLE></HEAD>"
			  <<	flush;
#if 0
	// Let's see if we're allowed to do this
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


	// nsacco 06/21/99 use the site passed in to retrieve partners
	// rather than the current site. This allows the admin tool to 
	// look at partners on other sites.
	pPartners = mpMarketPlace->GetSites()->GetSite(siteId)->GetPartners();
	pPartners->GetAllPartners(&vPartners);

	for (i = vPartners.begin(); i != vPartners.end(); ++i)
	{
		if (!*i)
			continue;

		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetCGIPath(PageShowCobrandHeaders)
				  << "eBayISAPI.dll?ShowCobrandHeaders&partner="
				  << (*i)->GetId()
				  // nsacco 06/21/99
				  << "&siteid="
				  << siteId
				  << "\">"
				  << (*i)->GetDesc() 
				  << "</A><BR>";
	}

	*mpStream << "<BR><BR>or<BR>\n"
				 "<FORM METHOD=POST ACTION=\""
			  << mpMarketPlace->GetCGIPath(PageCreateCobrandPartner)
			  << "ebayISAPI.dll?CreateCobrandPartner\">"
				 "Create a new cobrand partner.<BR>Name: "
				 "<INPUT TYPE=\"text\" SIZE=\"60\" MAXLENGTH=\"120\" NAME=\"newname\"><BR>Description: "
				 "<INPUT TYPE=\"text\" SIZE=\"60\" MAXLENGTH=\"120\" NAME=\"desc\"><BR>Directory name:"
				 // nsacco 06/21/99
				 "<INPUT TYPE=\"text\" SIZE=\"10\" MAXLENGTH=\"120\" NAME=\"dirname\"><BR>" 
				 "<INPUT TYPE=\"HIDDEN\" NAME=\"siteid\" value=\""
			  << siteId
			  << "\">"
				 "<INPUT TYPE=\"submit\"><BR>";
	
	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
}

