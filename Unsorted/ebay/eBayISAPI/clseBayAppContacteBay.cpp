
//
//	File:		clseBayAppContacteBay.cc
//
//	Class:		clseBayApp
//
//	Author:		Steve Yan (stevey@ebay.com)
//
//	Function:
//
//				the "Conatct eBay" page
//
//	Modifications:
//				- 4/5/99 Steve	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

#include "stdafx.h"
#include <AFXISAPI.H>


// kakiyama 07/09/99 - TODO(???):
const char UrlAnnouncementBoard[] = "<a href=\"http://calculus.ebay.com/aw-cgi/announce.shtml\">Announcement Board</a>";


void clseBayApp::ContacteBay(CEBayISAPIExtension *pServer, int itemID)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Contact eBay"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Contact eBay</h2>\n";


	// legal, rule
	*mpStream	<< "<font size=\"3\">" 

				<< "If you need help or need an answer to a question from eBay, "
				<< "try these resources for fast answers to your questions:<br>"
				<< "<UL>";
				
//	*mpStream	<< "<LI>Search our Help Index on the " 
//				<< "<a href = \""
//				<< mpMarketPlace->GetHTMLPath()
//				<< "help/help-support.html\">"
//				<< "Got A Question?</a> page.\n";

	*mpStream	<< "<LI>Keep up on the latest system happenings and changes on the "
				<< UrlAnnouncementBoard << ".\n";

	*mpStream	<< "<LI>Still got a question? Post a question to the "
				<< "<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewBoard)
				<<	"eBayISAPI.dll?viewboard&name=support"
				<<	"\">Support Q&A Board</a>."
				<< "</UL><P>";

	*mpStream	<< "If you would like to report an item as potentially illegal or infringing, click "
				<< "<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageReportQuestionableItemShow)
				<<	"eBayISAPI.dll?ReportQuestionableItemShow&item="
				<< itemID
				<<	"\">here</a>.";

	*mpStream	<< "</font>\n";



	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}


