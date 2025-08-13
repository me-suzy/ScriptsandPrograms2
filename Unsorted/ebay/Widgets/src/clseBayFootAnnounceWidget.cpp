/*	$Id: clseBayFootAnnounceWidget.cpp,v 1.4 1999/03/07 08:15:16 josh Exp $	*/
//	File:	clseBayFootAnnounceWidget.cpp
//
//	Class:	clseBayFootAnnounceWidget
//
//	Author:	Tini
//
//	Function:
//			Widget that emits the eBay footer announcements.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/24/97	Tini - Created
//


#include "clseBayFootAnnounceWidget.h"
#include "clsMarketPlace.h"


clseBayFootAnnounceWidget::clseBayFootAnnounceWidget(clsMarketPlace *pMarketPlace, int iType) : 
	clseBayWidget(pMarketPlace)
{
		mType = iType;
}



bool clseBayFootAnnounceWidget::EmitHTML(ostream *pStream)
{

	unsigned char *pAnnounce;

	// Get footer announcement for this type 
//	gApp->GetDatabase()->GetAnnounceDescription(
//		mpMarketPlace->GetId(), mType, 1, &pAnnounce);

	// home link
	*pStream <<		"\n"
			 <<		pAnnounce
			 <<		"\n";

	return true;
}

