/*	$Id: clseBayAppShowCobrandHeaders.cpp,v 1.4.204.1.94.2 1999/08/05 20:42:20 nsacco Exp $	*/
//
// File Name: clseBayAppShowCobrandHeaders.cpp
//
// Description:  Shows the headers and footers for the given
//               partner in a form that allows them to be changed
//               individually.
//
//
//		- 04/08/99 vicki	- Added secondary page type entries 
//		- 05/25/99 nsacco	- MarketPlace uses Site for Partners now
//		- 07/12/99 nsacco	- Return siteId for footers.
//		- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

void clseBayApp::ShowCobrandHeaders(eBayISAPIAuthEnum authLevel, int partnerId, int siteId)
{
	clsPartner *pPartner;
//	clsPartners *pPartners;
	char *pTemp;
	char *pHeader;
    int pageType;
	int secondaryPageType;

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

	*mpStream << "Headers for "
              << pSite
              << "<P>";

    *mpStream << "Headers for "
              << pPartner->GetDesc()
              << "<P>";

	// show 0-6 primary page types
	// show 0-12 secondary page types
	
    for (pageType = 0; pageType < 7; ++pageType)
    {
        
		//for secondary pagetype (6 types) 
		for(secondaryPageType = 0; secondaryPageType < 13; ++secondaryPageType)
		{
			pHeader = (char *) pPartner->GetHeaderWithoutAnnouncement((PageTypeEnum) pageType, (PageTypeEnum) secondaryPageType );//Vicki);
			*mpStream <<	pHeader;

			*mpStream << "<FORM METHOD=POST ACTION=\""
					<< mpMarketPlace->GetCGIPath(PageChangeCobrandHeader)
					<< "eBayISAPI.dll?ChangeCobrandHeader\">\n";

			*mpStream << "Header for " << flush
					<< clsPartners::GetPageDescription((PageTypeEnum) pageType)
					<< " " << pageType 
					<< " + "
					<< secondaryPageType
					<< "<BR><TEXTAREA COLS=70 ROWS=15 NAME=\"header\">";

//			pHeader = (char *) pPartner->GetHeaderWithoutAnnouncement((PageTypeEnum) pageType, (PageTypeEnum) secondaryPageType );//Vicki);
			pTemp = clsUtilities::StripHTML(pHeader);

			*mpStream << pTemp
					<< "</TEXTAREA>\n";

			delete pTemp;

			*mpStream << "<INPUT TYPE=\"hidden\" NAME=\"isheader\" VALUE=\"1\">\n"
						"<INPUT TYPE=\"hidden\" NAME=\"pagetype\" VALUE=\""
					<< pageType << "\">\n"
						"<INPUT TYPE=\"hidden\" NAME=\"partner\" VALUE=\""
					<< partnerId << "\">\n"
					"<INPUT TYPE=\"hidden\" NAME=\"siteId\" VALUE=\""
					<< siteId << "\">\n";

			*mpStream << "<INPUT TYPE=\"hidden\" NAME=\"pagetype2\" VALUE=\""
					<< secondaryPageType
					<< "\">\n";

			*mpStream << "<INPUT TYPE=\"Submit\" VALUE=\"Change Header\"></FORM><P>" << flush;
		} //end test code
        *mpStream << "<FORM METHOD=POST ACTION=\""
                  << mpMarketPlace->GetCGIPath(PageChangeCobrandHeader)
				  << "eBayISAPI.dll?ChangeCobrandHeader\">\n";

		*mpStream << "Footer for "
			      << clsPartners::GetPageDescription((PageTypeEnum) pageType)
				  << " " << pageType
				  << "<BR><TEXTAREA COLS=70 ROWS=15 NAME=\"header\">";

		pHeader = (char *) pPartner->GetFooter((PageTypeEnum) pageType, (PageTypeEnum)0 );
		pTemp = clsUtilities::StripHTML(pHeader);

		*mpStream << pTemp
				  << "</TEXTAREA>\n";

		delete pTemp;

		*mpStream << "<INPUT TYPE=\"hidden\" NAME=\"isheader\" VALUE=\"0\">\n"
				     "<INPUT TYPE=\"hidden\" NAME=\"pagetype\" VALUE=\""
				  << pageType << "\">\n"
				     "<INPUT TYPE=\"hidden\" NAME=\"partner\" VALUE=\""
				  << partnerId << "\">\n"
				  // nsacco 07/12/99 added siteId
				  "<INPUT TYPE=\"hidden\" NAME=\"siteId\" VALUE=\""
					<< siteId << "\">\n";

		*mpStream << "<INPUT TYPE=\"Submit\" VALUE=\"Change Footer\"></FORM><P>";
	}

	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
}
