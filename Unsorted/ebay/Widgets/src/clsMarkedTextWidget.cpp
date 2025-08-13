/* $Id: clsMarkedTextWidget.cpp,v 1.3 1998/12/06 05:22:43 josh Exp $ */
#include "widgets.h"
#include "clsMarkedTextWidget.h"

struct clsMarkedTextWidgetOptionsStruct
{
    int32_t mMarker;
	int32_t mTextOffset;
    int32_t mExpansionOffset;
};

clsMarkedTextWidget::clsMarkedTextWidget(clsWidgetHandler *pHandler,
                                       clsMarketPlace *pMarketPlace,
                                       clsApp *pApp)
                                       : clseBayWidget(pHandler, pMarketPlace, pApp),
                                       mpText(NULL), mMarker(0)
{
}

clsMarkedTextWidget::~clsMarkedTextWidget()
{
	delete [] mpText;
}

void clsMarkedTextWidget::SetText(const char *pText)
{
    delete [] mpText;
    if (pText)
    {
        mpText = new char [strlen(pText) + 1];
        strcpy(mpText, pText);
    }
    else
        mpText = NULL;
}

void clsMarkedTextWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	if (mpText)
	{
		*pStream << " TEXT=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpText);
		*pStream << "\"";
	}

	if (mMarker)
		*pStream << " MARKER=" << mMarker;

	*pStream << ">";
}

void clsMarkedTextWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pText;

	pText = GetParameterValue("TEXT", pvArgs);
	SetText(pText);

	if ((pText = GetParameterValue("MARKER", pvArgs)))
		mMarker = atoi(pText);
}

void clsMarkedTextWidget::SetParams(const void *pData,
                                   const char *pStringBase,
                                   bool fixBytes)
{
    clsMarkedTextWidgetOptionsStruct *pOptions;

    pOptions = (clsMarkedTextWidgetOptionsStruct *) pData;

    if (fixBytes)
    {
        pOptions->mMarker = clsUtilities::FixByteOrder32(pOptions->mMarker);
        pOptions->mTextOffset = clsUtilities::FixByteOrder32(pOptions->mTextOffset);
    }

	mMarker = pOptions->mMarker;

	if (pOptions->mTextOffset == -1)
		SetText(NULL);
	else
		SetText(pStringBase + pOptions->mTextOffset);
}

long clsMarkedTextWidget::GetBlob(clsDataPool *pDataPool, bool fixBytes)
{
	clsMarkedTextWidgetOptionsStruct theOptions;

	if (!mpText)
		theOptions.mTextOffset = -1;
	else
		theOptions.mTextOffset = pDataPool->AddString(mpText);

	theOptions.mMarker = mMarker;

	theOptions.mExpansionOffset = -1;
	if (fixBytes)
	{
        theOptions.mMarker = clsUtilities::FixByteOrder32(theOptions.mMarker);
        theOptions.mTextOffset = clsUtilities::FixByteOrder32(theOptions.mTextOffset);
		theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
	}

	return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

bool clsMarkedTextWidget::EmitHTML(ostream *pStream)
{
	if (!mpText)
		return true;

	*pStream << mpText;
	return true;
}
