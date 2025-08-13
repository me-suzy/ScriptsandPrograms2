/* $Id: clsWidgetPage.cpp,v 1.3 1998/12/06 05:22:49 josh Exp $ */
// Translates a parsed widget blob into text.
#include "widgets.h"

clsWidgetPage::clsWidgetPage() : mpHeader(NULL), mpWidgets(NULL), mpText(NULL),
	mBytesWrong(false), mpMarketPlace(gApp->GetMarketPlaces()->GetCurrentMarketPlace()),
	mWidgetHandler(mpMarketPlace, gApp), mpWidgetContext(mWidgetHandler.GetWidgetContext())
{
	mpOriginalText = NULL;
}

clsWidgetPage::~clsWidgetPage()
{
	mpHeader = NULL;
	mpWidgets = NULL;
	mpText = NULL;
	mpOriginalText = NULL;
	mpMarketPlace = NULL;
	mpWidgetContext = NULL;
}

#undef FIX_BYTE_ORDER32
//lint -emacro(704, FIX_BYTE_ORDER32) otherwise it will complain about the shifts.
#define FIX_BYTE_ORDER32(x)	((x) = ((((x) >> 24) & 0xFF) | \
				       (((x) >> 16) & 0xFF) << 8 | \
				       (((x) >> 8) & 0xFF) << 16 | \
					((x) & 0xFF) << 24))

bool clsWidgetPage::SetPage(void *pBlob)
{
	widgetHeader *pHeader;

	if (!pBlob)
		return false;

	pHeader = (widgetHeader *) pBlob;

	// Make sure we have the correct byte order!
	if (pHeader->byteOrder != 1)
	{
		int32_t i;
		int32_t newByte = pHeader->byteOrder;
		int32_t *pInts;

		FIX_BYTE_ORDER32(newByte);

		// Ack! Bad! We don't know this format!
		if (newByte != 1)
			return false;

		// Otherwise, we must reverse our byte order, and set the appropriate flag.
		mBytesWrong = true;

		// First, fix the headers variables.
		FIX_BYTE_ORDER32(pHeader->numWidgets);
		FIX_BYTE_ORDER32(pHeader->textOffset);
		FIX_BYTE_ORDER32(pHeader->widgetOffset);
		FIX_BYTE_ORDER32(pHeader->byteOrder);
        FIX_BYTE_ORDER32(pHeader->originalText);

		// Now, fix the individual widget structures.
		// Sadly, they'll have to fix their own internal variables.
		// First, we get a pointer to the widget entry structures.
		pInts = (int32_t *) (((char *) pBlob) + pHeader->widgetOffset);
		// Then we iterate and repair.
		for (i = 0; i < pHeader->numWidgets; ++i)
		{
			// Fix both the widget type and the blob offset.
			FIX_BYTE_ORDER32(*pInts); ++pInts;
			FIX_BYTE_ORDER32(*pInts); ++pInts;
		}
	}
	else
		mBytesWrong = false; // Make sure we know our byte order was okay.

	// Now we can set our other variables, and then we're done.
	mpHeader = pHeader;
	mpWidgets = (widgetEntry *) (((char *) pBlob) + mpHeader->widgetOffset);
	mpText = ((char *) pBlob) + mpHeader->textOffset;
    mpOriginalText = mpText + mpHeader->originalText;

	return true;
}

#undef FIX_BYTE_ORDER32

bool clsWidgetPage::Draw(ostream *pStream)
{
	int32_t i;
	const widgetEntry *pCurrentWidget = NULL;
	clseBayWidget *pWidget = NULL;
	bool widgetReturn;
	assert(mpHeader);
	for (i = 0, pCurrentWidget = mpWidgets; i < mpHeader->numWidgets; ++i, ++pCurrentWidget)
	{
		assert(pCurrentWidget);
		pWidget = mWidgetHandler.GetWidget((eBayKnownWidgets) (pCurrentWidget->widgetType));
		if (!pWidget)
			return false;

		assert(mpText);
		pWidget->SetParams((const void *) (mpText + pCurrentWidget->blobOffset),
			mpText, mBytesWrong);

		widgetReturn = pWidget->EmitHTML(pStream);

		delete pWidget;
		if (!widgetReturn)
			return false;
	}

	return true;
}

