/*	$Id: clseBayHeadAnnounceWidget.cpp,v 1.4 1999/03/07 08:15:16 josh Exp $	*/
//	File:	clseBayHeadAnnounceWidget.cpp
//
//	Class:	clseBayHeadAnnounceWidget
//
//	Author:	Tini
//
//	Function:
//			Widget that emits the eBay header announcements.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/24/97	Tini - Created
//


#include "clseBayHeadAnnounceWidget.h"
#include "clsMarketPlace.h"


clseBayHeadAnnounceWidget::clseBayHeadAnnounceWidget(clsMarketPlace *pMarketPlace, int iType) : 
	clseBayWidget(pMarketPlace)
{
		mType = iType;
}



bool clseBayHeadAnnounceWidget::EmitHTML(ostream *pStream)
{

	unsigned char *pAnnounce;

	// Get header announcement for this type 
//	gApp->GetDatabase()->GetAnnounceDescription(
//		mpMarketPlace->GetId(), mType, 0, &pAnnounce);

	// home link
	*pStream <<		"\n"
			 <<		pAnnounce
			 <<		"\n";

	return true;
}

