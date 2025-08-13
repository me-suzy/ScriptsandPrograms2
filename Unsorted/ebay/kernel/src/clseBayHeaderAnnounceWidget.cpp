/*	$Id: clseBayHeaderAnnounceWidget.cpp,v 1.6 1999/03/07 08:16:54 josh Exp $	*/
//	File:	clseBayHeaderAnnounceWidget.cpp
//
//	Class:	clseBayHeaderAnnounceWidget
//
//	Author:	Craig Huang
//
//	Function:
//			Widget that emits the eBay header announcements.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 3/13/98	Craig - Created
//

#include "eBayKernel.h"
#include "clseBayHeaderAnnounceWidget.h"
#include "clsAnnouncements.h"




clseBayHeaderAnnounceWidget::clseBayHeaderAnnounceWidget(clsMarketPlace *pMarketPlace) : 
	clseBayWidget(pMarketPlace)
{
	clsMarketPlaces *pMarketPlaces;
	if (!pMarketPlace)
	{
		pMarketPlaces	= gApp->GetMarketPlaces();	
		if (pMarketPlaces)
			mpMarketPlace	= pMarketPlaces->GetCurrentMarketPlace();
	}
	else
	{
		mpMarketPlace	= pMarketPlace;
	}		
}



bool clseBayHeaderAnnounceWidget::EmitHTML(ostream *pStream)
{
	clsAnnouncements	*pAnnouncements;
	clsAnnouncement		*pAnnouncement;
	if( mpMarketPlace )
	{
		pAnnouncements	= mpMarketPlace->GetAnnouncements();
		// emit general announcements
		pAnnouncement = pAnnouncements->GetAnnouncement(HeaderWidget, Header);
		if (pAnnouncement)
		{
			//*pStream << clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
			*pStream << pAnnouncement->GetDesc();
			//*pStream << "\n";
			//*pStream << "This is test string\n";
			delete pAnnouncement;
		};
		return true;
	}
	else
		return false;
}

