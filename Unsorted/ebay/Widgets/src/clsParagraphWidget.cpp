/* $Id: clsParagraphWidget.cpp,v 1.3 1998/12/06 05:22:44 josh Exp $ */
#include "widgets.h"
#include "clsParagraphWidget.h"

struct clsParagraphWidgetOptionsStruct
{
    int32_t mHeaderOffset;
    int32_t mTextOffset;
	int32_t mHeaderColorOffset;
    int32_t mExpansionOffset;
};

clsParagraphWidget::clsParagraphWidget(clsWidgetHandler *pHandler,
                                       clsMarketPlace *pMarketPlace,
                                       clsApp *pApp)
                                       : clseBayWidget(pHandler, pMarketPlace, pApp),
                                       mpText(NULL), mpHeader(NULL), mpHeaderColor(NULL)
{
}

clsParagraphWidget::~clsParagraphWidget()
{
    delete [] mpHeader;
    delete [] mpText;
	delete [] mpHeaderColor;
}

void clsParagraphWidget::SetText(const char *pText)
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

void clsParagraphWidget::SetHeader(const char *pHeader)
{
	delete [] mpHeader;
	if (pHeader)
	{
		mpHeader = new char [strlen(pHeader) + 1];
		strcpy(mpHeader, pHeader);
	}
	else
		mpHeader = NULL;
}

void clsParagraphWidget::SetHeaderColor(const char *pHeaderColor)
{
	delete [] mpHeaderColor;
	if (pHeaderColor)
	{
		mpHeaderColor = new char [strlen(pHeaderColor) + 1];
		strcpy(mpHeaderColor, pHeaderColor);
	}
	else
		mpHeaderColor = NULL;
}

void clsParagraphWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	if (mpText)
	{
		*pStream << " TEXT=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpText);
		*pStream << "\"";
	}

	if (mpHeader)
	{
		*pStream << " CAPTION=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpHeader);
		*pStream << "\"";
	}

	/*
	if (mpHeaderColor)
	{
		*pStream << " COLOR=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpHeaderColor);
		*pStream << "\"";
	}
	*/

	*pStream << ">";
}

void clsParagraphWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pText;

	pText = GetParameterValue("CAPTION", pvArgs);
	SetHeader(pText);

	pText = GetParameterValue("TEXT", pvArgs);
	SetText(pText);

	pText = GetParameterValue("COLOR", pvArgs);
	SetHeaderColor(pText);
}

void clsParagraphWidget::SetParams(const void *pData,
                                   const char *pStringBase,
                                   bool fixBytes)
{
    clsParagraphWidgetOptionsStruct *pOptions;

    pOptions = (clsParagraphWidgetOptionsStruct *) pData;

    if (fixBytes)
    {
        pOptions->mHeaderOffset = clsUtilities::FixByteOrder32(pOptions->mHeaderOffset);
        pOptions->mTextOffset = clsUtilities::FixByteOrder32(pOptions->mTextOffset);
        pOptions->mHeaderColorOffset = clsUtilities::FixByteOrder32(pOptions->mHeaderColorOffset);
    }

	if (pOptions->mHeaderOffset == -1)
		SetHeader(NULL);
	else
		SetHeader(pStringBase + pOptions->mHeaderOffset);

	if (pOptions->mTextOffset == -1)
		SetText(NULL);
	else
		SetText(pStringBase + pOptions->mTextOffset);

	if (pOptions->mHeaderColorOffset == -1)
		SetText(NULL);
	else
		SetText(pStringBase + pOptions->mHeaderColorOffset);
}

long clsParagraphWidget::GetBlob(clsDataPool *pDataPool, bool fixBytes)
{
	clsParagraphWidgetOptionsStruct theOptions;

	if (!mpHeader)
		theOptions.mHeaderOffset = -1;
	else
		theOptions.mHeaderOffset = pDataPool->AddString(mpHeader);

	if (!mpText)
		theOptions.mTextOffset = -1;
	else
		theOptions.mTextOffset = pDataPool->AddString(mpText);

	if (!mpHeaderColor)
		theOptions.mHeaderColorOffset = -1;
	else
		theOptions.mHeaderColorOffset = pDataPool->AddString(mpHeaderColor);

	theOptions.mExpansionOffset = -1;

	if (fixBytes)
	{
		theOptions.mHeaderOffset = clsUtilities::FixByteOrder32(theOptions.mHeaderOffset);
		theOptions.mTextOffset = clsUtilities::FixByteOrder32(theOptions.mTextOffset);
		theOptions.mHeaderColorOffset = clsUtilities::FixByteOrder32(theOptions.mHeaderColorOffset);
		theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
	}

	return pDataPool->AddData(&theOptions, sizeof (theOptions));
}
 
bool clsParagraphWidget::EmitHTML(ostream *pStream)
{
	if (mpHeader)
		*pStream << "<DIV ALIGN=LEFT><STRONG><FONT SIZE=\"5\" COLOR=\""
				 << "red" // mpHeaderColor
				 << "\">"
				 << mpHeader
				 << "</FONT></STRONG></DIV>\n";

	if (mpText)
		*pStream << "<P>\n"
				 << mpText
				 << "</P>\n";

	return true;
}
