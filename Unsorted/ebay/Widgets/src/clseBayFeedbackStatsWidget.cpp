/*	$Id: clseBayFeedbackStatsWidget.cpp,v 1.3.290.1 1999/08/01 02:51:24 barry Exp $	*/
//
//	File:	clseBayFeedbackStatsWidget.cpp
//
//	Class:	clseBayFeedbackStatsWidget
//
//	Author:	Barry
//
//	Function:
//			Widget that shows statistics for feedback on our site.
//
// Modifications:
//			- 07/01/99	nsacco	- use GetPicsPath() for img urls
//
 
#include "widgets.h"
#include "clseBayFeedbackStatsWidget.h"

clseBayFeedbackStatsWidget::clseBayFeedbackStatsWidget(clsMarketPlace *pMarketPlace) :
	clseBayWidget(pMarketPlace)
{
}

clseBayFeedbackStatsWidget::~clseBayFeedbackStatsWidget()
{
}

bool clseBayFeedbackStatsWidget::EmitHTML(ostream *pStream)
{

	int         limits[] = {0, 9, 99, 499, 999, 9999};
	int         numUsers[6];
	int         i;

	clsUsers	*pUsers;
	vector<int> vIds;
	clsMarketPlace *pMarketPlace;

	// Get the marketplace's clsItems object
	pMarketPlace = NULL;
	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace)
		pUsers = pMarketPlace->GetUsers();
	else
		return false;

	// Get all feedback >= the start of each range.
	for (i = 0; i < 6; i++)
	{
		pUsers->GetUserIdsByFeedback(limits[i], &vIds);
		numUsers[i] = vIds.size();
		vIds.clear();
	}

	*pStream <<
		"<TABLE WIDTH=\"270\" BORDER=\"4\" CELLPADDING=\"0\" CELLSPACING=\"0\"> \n"
		"<TR> \n"
		"<TD> \n"		
		"<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"5\" CELLSPACING=\"0\"> \n"
		"<TR BGCOLOR=\"#000099\"> \n"
		"<TH WIDTH=\"60%\" ALIGN=\"LEFT\" VALIGN=\"TOP\" COLSPAN=\"2\"><FONT COLOR=\"#FFFFFF\">&nbsp;&nbsp;Feedback Level</FONT></TH> \n"
		"<TH WIDTH=\"40%\" ALIGN=\"CENTER\" VALIGN=\"TOP\"><FONT COLOR=\"#FFFFFF\">Members</FONT></TH> \n";

	// Do the subtractions to find those in each range
	// and output the HTML.

	*pStream <<
		"<TR> \n"
		"<TD BGCOLOR=\"#FFCC00\" WIDTH=\"10%\" ALIGN=\"CENTER\" HEIGHT=\"25\"><IMG SRC=\""
	<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	<<	"transparent-1pixel.gif\" WIDTH=\"23\" HEIGHT=\"23\" ALIGN=\"ABSMIDDLE\"></TD> \n"
		"<TD BGCOLOR=\"#99CC00\" WIDTH=\"50%\" ALIGN=\"CENTER\">1 or more</TD> \n"
		"<TD BGCOLOR=\"#FF0000\" WIDTH=\"40%\" ALIGN=\"CENTER\">"
	<<  numUsers[0]
	<<  "</TD> \n"
		"</TR> \n";

	*pStream <<		
		"<TR> \n"
		"<TD BGCOLOR=\"#FFCC00\" WIDTH=\"10%\" ALIGN=\"CENTER\" HEIGHT=\"25\"><IMG SRC=\""
	<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	<<	"star-1.gif\" WIDTH=\"23\" HEIGHT=\"23\" ALIGN=\"ABSMIDDLE\"></TD> n"
		"<TD BGCOLOR=\"#99CC00\" WIDTH=\"50%\" ALIGN=\"CENTER\">10 to 99</TD> \n"
		"<TD BGCOLOR=\"#FF0000\" WIDTH=\"40%\" ALIGN=\"CENTER\">"
	<<  numUsers[1] - numUsers[2]
	<<  "</TD> \n"
		"</TR> \n";

	*pStream <<
		"<TR> \n"
		"<TD BGCOLOR=\"#FFCC00\"  WIDTH=\"10%\" ALIGN=\"CENTER\" HEIGHT=\"25\"><IMG SRC=\""
	<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	<<	"star-2.gif\" WIDTH=\"23\" HEIGHT=\"23\" ALIGN=\"ABSMIDDLE\"></TD> \n"
		"<TD BGCOLOR=\"#99CC00\" WIDTH=\"50%\" ALIGN=\"CENTER\">100 to 499</TD> \n"
		"<TD BGCOLOR=\"#FF0000\" WIDTH=\"40%\" ALIGN=\"CENTER\">"
	<<  numUsers[2] - numUsers[3]
	<<  "</TD> \n"
		"</TR> \n";

	*pStream <<
		"<TR> \n"
		"<TD BGCOLOR=\"#FFCC00\"  WIDTH=\"10%\" ALIGN=\"CENTER\" HEIGHT=\"25\"><IMG SRC=\""
	<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	<<	"star-3.gif\" WIDTH=\"23\" HEIGHT=\"23\" ALIGN=\"ABSMIDDLE\"></TD> \n"
		"<TD BGCOLOR=\"#99CC00\" WIDTH=\"50%\" ALIGN=\"CENTER\">500 to 999</TD> \n"
		"<TD BGCOLOR=\"#FF0000\" WIDTH=\"40%\" ALIGN=\"CENTER\">"
	<<  numUsers[3] - numUsers[4]
	<<  "</TD> \n"
		"</TR> \n";

	*pStream <<
		"<TR> \n"
		"<TD BGCOLOR=\"#FFCC00\"  WIDTH=\"10%\" ALIGN=\"CENTER\" HEIGHT=\"25\"><IMG SRC=\""
	<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	<<	"star-4.gif\" WIDTH=\"23\" HEIGHT=\"23\" ALIGN=\"ABSMIDDLE\"></TD> \n"
		"<TD BGCOLOR=\"#99CC00\"  WIDTH=\"50%\" ALIGN=\"CENTER\">1,000 to 9,999</TD> \n"
		"<TD BGCOLOR=\"#FF0000\" WIDTH=\"40%\" ALIGN=\"CENTER\"> \n"
	<<  numUsers[4] - numUsers[5]
	<<  "</TD> \n"
		"</TR> \n";

	*pStream <<
		"<TR> \n"
		"<TD BGCOLOR=\"#FFCC00\" WIDTH=\"10%\" ALIGN=\"CENTER\" HEIGHT=\"25\"><IMG SRC=\""
	<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	<<	"star-5.gif\" WIDTH=\"23\" HEIGHT=\"23\" ALIGN=\"ABSMIDDLE\"></TD> \n"
		"<TD BGCOLOR=\"#99CC00\" WIDTH=\"50%\" ALIGN=\"CENTER\">10,000 or higher</TD> \n"
		"<TD BGCOLOR=\"#FF0000\" WIDTH=\"40%\" ALIGN=\"CENTER\"> \n"
	<<  numUsers[5]
	<<  "</TD> \n"
		"</TR> \n";

	*pStream <<
		"</TABLE> \n"
		"</TD> \n"
		"</TR> \n"
		"</TABLE> \n";


	return true;
}