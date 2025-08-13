/* $Id: clsLinkWidget.cpp,v 1.3 1998/12/06 05:22:42 josh Exp $ */
#include "widgets.h"
#include "clsLinkWidget.h"

struct clsLinkWidgetOptionsStruct
{
	int32_t mLocationOffset;
	int32_t mTextOffset;
    int32_t mExpansionOffset;
};

clsLinkWidget::clsLinkWidget(clsWidgetHandler *pHandler,
                                       clsMarketPlace *pMarketPlace,
                                       clsApp *pApp)
                                       : clseBayWidget(pHandler, pMarketPlace, pApp),
                                       mpText(NULL), mpLocation(NULL)
{
}

clsLinkWidget::~clsLinkWidget()
{
	delete [] mpText;
	delete [] mpLocation;
}

void clsLinkWidget::SetText(const char *pText)
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

void clsLinkWidget::SetLocation(const char *pLocation)
{
    delete [] mpLocation;
    if (pLocation)
    {
        mpLocation = new char [strlen(pLocation) + 1];
        strcpy(mpLocation, pLocation);
    }
    else
        mpLocation = NULL;
}

void clsLinkWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	if (mpText)
	{
		*pStream << " CAPTION=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpText);
		*pStream << "\"";
	}

	if (mpLocation)
	{
		*pStream << " HREF=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpLocation);
		*pStream << "\"";
	}

	*pStream << ">";
}

void clsLinkWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pText;

	pText = GetParameterValue("CAPTION", pvArgs);
	SetText(pText);

	pText = GetParameterValue("HREF", pvArgs);
	SetLocation(pText);
}

void clsLinkWidget::SetParams(const void *pData,
                                   const char *pStringBase,
                                   bool fixBytes)
{
    clsLinkWidgetOptionsStruct *pOptions;

    pOptions = (clsLinkWidgetOptionsStruct *) pData;

    if (fixBytes)
    {
        pOptions->mLocationOffset = clsUtilities::FixByteOrder32(pOptions->mLocationOffset);
        pOptions->mTextOffset = clsUtilities::FixByteOrder32(pOptions->mTextOffset);
    }

	if (pOptions->mTextOffset == -1)
		SetText(NULL);
	else
		SetText(pStringBase + pOptions->mTextOffset);

	if (pOptions->mLocationOffset == -1)
		SetLocation(NULL);
	else
		SetLocation(pStringBase + pOptions->mLocationOffset);
}

long clsLinkWidget::GetBlob(clsDataPool *pDataPool, bool fixBytes)
{
	clsLinkWidgetOptionsStruct theOptions;

	if (!mpText)
		theOptions.mTextOffset = -1;
	else
		theOptions.mTextOffset = pDataPool->AddString(mpText);

	if (!mpLocation)
		theOptions.mLocationOffset = -1;
	else
		theOptions.mLocationOffset = pDataPool->AddString(mpLocation);

	theOptions.mExpansionOffset = -1;
	if (fixBytes)
	{
        theOptions.mLocationOffset = clsUtilities::FixByteOrder32(theOptions.mLocationOffset);
        theOptions.mTextOffset = clsUtilities::FixByteOrder32(theOptions.mTextOffset);
		theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
	}

	return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

bool clsLinkWidget::EmitHTML(ostream *pStream)
{
	if (!mpLocation)
		return true;

	*pStream << "<A HREF=\""
			 << mpLocation
			 << "\">";

	if (!mpText)
		*pStream << mpLocation;
	else
		*pStream << mpText;

	*pStream << "</A>";

	return true;
}
