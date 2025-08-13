/* $Id: clsItemLinkWidget.cpp,v 1.4 1998/12/06 05:22:41 josh Exp $ */
#include "widgets.h"
#include "clsItemLinkWidget.h"

struct clsItemLinkWidgetOptionsStruct
{
	int32_t mItemNumber;
    int32_t mExpansionOffset;
};

clsItemLinkWidget::clsItemLinkWidget(clsWidgetHandler *pHandler,
                                       clsMarketPlace *pMarketPlace,
                                       clsApp *pApp)
                                       : clseBayWidget(pHandler, pMarketPlace, pApp),
									   mItemNumber(0)
{
}

clsItemLinkWidget::~clsItemLinkWidget()
{
}

void clsItemLinkWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	if (mItemNumber)
		*pStream << " ITEM=" << mItemNumber;

	*pStream << ">";
}

void clsItemLinkWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pText;

	if ((pText = GetParameterValue("ITEM", pvArgs)))
		mItemNumber = atoi(pText);
	else
		mItemNumber = 0;
}

void clsItemLinkWidget::SetParams(const void *pData,
                                   const char * /* pStringBase */,
                                   bool fixBytes)
{
    clsItemLinkWidgetOptionsStruct *pOptions;

    pOptions = (clsItemLinkWidgetOptionsStruct *) pData;

    if (fixBytes)
    {
        pOptions->mItemNumber = clsUtilities::FixByteOrder32(pOptions->mItemNumber);
    }

	mItemNumber = pOptions->mItemNumber;
}

long clsItemLinkWidget::GetBlob(clsDataPool *pDataPool, bool fixBytes)
{
	clsItemLinkWidgetOptionsStruct theOptions;

	theOptions.mItemNumber = mItemNumber;
	if (fixBytes)
	{
		theOptions.mItemNumber = clsUtilities::FixByteOrder32(theOptions.mItemNumber);
		theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
	}

	return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

bool clsItemLinkWidget::EmitHTML(ostream *pStream)
{
	clsItem *pItem;
	char *pCleanTitle;
	clsMarketPlace *pMarketPlace;

	if (!mItemNumber)
		return true;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

	pItem = pMarketPlace->GetItems()->GetItem(mItemNumber, true);

	if (!pItem)
		return true;

	if (pItem->IsAdult())
	{
		delete pItem;
		return true;
	}

	pCleanTitle = clsUtilities::StripHTML(pItem->GetTitle());

	*pStream << "<A HREF=\""
			 << pMarketPlace->GetCGIPath(PageViewItem)
			 << "eBayISAPI.dll?ViewItem&item="
			 << mItemNumber
			 << "\">"
			 << pCleanTitle
			 << "</A>";

	delete [] pCleanTitle;
	delete pItem;
	
	return true;
}

