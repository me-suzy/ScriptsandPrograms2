/*	$Id: clseBayHotYahooWidget.cpp,v 1.2 1998/06/23 04:31:47 josh Exp $	*/
// clseBayHotYahooWidget.cpp: implementation of the clseBayHotYahooWidget class.
//
//////////////////////////////////////////////////////////////////////

#include "clseBayHotYahooWidget.h"
#include "clsUtilities.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

// call up to default widget
clseBayHotYahooWidget::clseBayHotYahooWidget(clsMarketPlace *pMarketPlace) :
	clseBayHotWidget(pMarketPlace)
{
}

clseBayHotYahooWidget::~clseBayHotYahooWidget()
{

}

bool clseBayHotYahooWidget::Initialize()
{
	 mNumItems = YAHOO_HOT_ITEMS;
	 return clseBayHotWidget::Initialize();
}

bool clseBayHotYahooWidget::Emit1Cell(ostrstream * pStream, int n)
{
	clsItem		*pItem;
	char		*cSuperCleanText = NULL;
	char		*cDelimitedSuperCleanText = NULL;
	char		*cSafeDelimitedSuperCleanText = NULL;
	char		cPrice[50];
	char		*cMoreLink = NULL;
	char		*cMoreLinkText = NULL;
	char		cName[255];
	
	// get the item from the vector that Initialize() prepared for me
	pItem = GetItem(n);
	
	// make the text super-clean
	cSuperCleanText = clsUtilities::SuperClean(pItem->GetTitle());
	
	// delimit the text in case it's one big word
	// cDelimitedSuperCleanText = clsUtilities::Delimit(cSuperCleanText);
	
	// make the text safe
	//	cSafeDelimitedSuperCleanText = clsUtilities::StripHTML(cDelimitedSuperCleanText);
	
	strncpy(cName, cSuperCleanText, sizeof(cName)-1);
	
	// number in stream
	*pStream <<		n + 1;
	*pStream <<		"\t";
	*pStream <<		cName;
	*pStream <<		"\t";
	
	// output the item title + price
	*pStream << REDIRECTOR 
		 << ITEM_URL
		 << pItem->GetId();
	
	// field separator
	*pStream <<		"\t";
	
	// price
	sprintf(cPrice, "%.2f", 
		((pItem->GetPrice() > 0) ? pItem->GetPrice() : pItem->GetStartPrice()));
	
	*pStream <<		cPrice;
	
	// line feed
	if((n+2) < mNumItems)
		*pStream <<		"\n";
		
	// delete the new strings
	if (cSuperCleanText) delete [] cSuperCleanText;
	if (cDelimitedSuperCleanText) delete [] cDelimitedSuperCleanText;
	if (cSafeDelimitedSuperCleanText) delete [] cSafeDelimitedSuperCleanText;
	
	return true;
}
