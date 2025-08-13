/*	$Id: clseBayHeaderAnnounceWidget.cpp,v 1.3.278.1 1999/08/01 02:51:25 barry Exp $	*/
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
//				- 6/11/99	Wen	- add wrapper

#include "widgets.h"
#include "clseBayHeaderAnnounceWidget.h"
#include "clsAnnouncements.h"


clseBayHeaderAnnounceWidget::clseBayHeaderAnnounceWidget(clsMarketPlace *pMarketPlace) : 
	mpMarketPlace(NULL), clseBayWidget(pMarketPlace)
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
		pAnnouncement = pAnnouncements->GetAnnouncement(HeaderWidget, Header,
			mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
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


//
// You don't have to use it for put up announcement
// if you want, you can use EmitPrefix and EmitSuffix which are just like a wrapper
//
// To use it
//			pStream	= new ostrstream;
//			pHeaderAnnounce = new clseBayHeaderAnnounceWidget;
//			pHeaderAnnounce->EmitPrefix(pStream);
//			pHeaderAnnounce->EmitHTML(pStream);
//			pHeaderAnnounce->EmitSuffix(pStream);		
//
// emit the prefix
bool clseBayHeaderAnnounceWidget::EmitPrefix(ostream *pStream)
{
	clsAnnouncements	*pAnnouncements;
	clsAnnouncement		*pAnnouncement;
	if( mpMarketPlace )
	{
		pAnnouncements	= mpMarketPlace->GetAnnouncements();
		pAnnouncement = pAnnouncements->GetAnnouncement(HeaderWidgetPrefix, Header,
			mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
		if (pAnnouncement)
		{
			*pStream << pAnnouncement->GetDesc();
			delete pAnnouncement;
		};
		return true;
	}
	else
		return false;
}


// this is part of wrapper for the announcement
// EmitSuffix
bool clseBayHeaderAnnounceWidget::EmitSuffix(ostream *pStream)
{
	clsAnnouncements	*pAnnouncements;
	clsAnnouncement		*pAnnouncement;
	if( mpMarketPlace )
	{
		pAnnouncements	= mpMarketPlace->GetAnnouncements();
		pAnnouncement = pAnnouncements->GetAnnouncement(HeaderWidgetSuffix, Header,
			mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
		if (pAnnouncement)
		{
			*pStream << pAnnouncement->GetDesc();
			delete pAnnouncement;
		};
		return true;
	}
	else
		return false;
}

