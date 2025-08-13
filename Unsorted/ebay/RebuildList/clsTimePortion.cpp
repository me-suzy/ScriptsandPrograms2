/*	$Id: clsTimePortion.cpp,v 1.3.390.3 1999/08/06 02:26:57 nsacco Exp $	*/
//	File:	clsTimePortion.cpp
//
//	Class:	clsTimePortion
//
//	Author:	Wen Wen
//
//	Function:
//			Print the creating time
//
// Modifications:
//				- 07/07/97	Wen - Created
//				- 07/22/00	petra	- changed to use clseBayTimeWidget
//
#include "clsRebuildListApp.h"
#include "clsTimePortion.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clseBayTimeWidget.h"

void clsTimePortion::Print(ostream* pOutputFile)
{
// petra	time_t CreatingTime;
// petra	struct tm*	pTime;
// petra	char   TimeString[50];

	// Get the HTML creating time
// petra	CreatingTime = ((clsRebuildListApp*) gApp)->GetCreatingTime();
// petra	pTime = localtime(&CreatingTime);

// petra	sprintf(TimeString, "%2.2d/%2.2d/%2.2d, %2.2d:%2.2d %s", 
// petra		pTime->tm_mon+1, 
// petra		pTime->tm_mday,
// petra		pTime->tm_year,
// petra		pTime->tm_hour,
// petra		pTime->tm_min,
	/* pTime->tm_sec, */
// petra		pTime->tm_isdst ? "PDT" : "PST");

	*pOutputFile << "<strong>"
				 <<	"Updated: ";

	// petra		 << TimeString
	clseBayTimeWidget timeWidget(gApp->GetMarketPlaces()->GetCurrentMarketPlace(), 1, 2,								// petra
								((clsRebuildListApp*) gApp)->GetCreatingTime());
	
	timeWidget.EmitHTML (pOutputFile);												

	*pOutputFile << "</strong> "
//				 << "<a href=\"http://cgi1.ebay.com/aw-cgi/eBayISAPI.dll?TimeShow\">Check eBay official time</a>\n"
// kakiyama 07/18/99
				 << "<a href=\""
				 << gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCGIPath(PageTimeShow)
				 << "eBayISAPI.dll?TimeShow"
				 << "\">"
				 << "Check eBay official time"
				 << "</a>\n"
//				 << "<a href=\"http://www2.ebay.com/aw/curtime.cgi\">What time is it now?</a>\n"
				 << "<br><font size=\"2\">"
				 << "Use your browser's <strong>reload</strong> button to see the latest version."
				 << "</font>\n";
}
