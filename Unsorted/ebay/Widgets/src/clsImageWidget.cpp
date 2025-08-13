/* $Id: clsImageWidget.cpp,v 1.3 1998/12/06 05:22:40 josh Exp $ */
#include "widgets.h"
#include "clsImageWidget.h"

struct clsImageWidgetOptionsStruct
{
    int32_t mHeight;
	int32_t mWidth;
	int32_t mLocationOffset;
	int32_t mAltOffset;
	int32_t mCaptionOffset;
    int32_t mExpansionOffset;
};

clsImageWidget::clsImageWidget(clsWidgetHandler *pHandler,
							   clsMarketPlace *pMarketPlace,
							   clsApp *pApp)
							   : clseBayWidget(pHandler, pMarketPlace, pApp),
							   mpLocation(NULL), mHeight(0), mWidth(0), mpAlt(NULL), mpCaption(NULL)
{
}

clsImageWidget::~clsImageWidget()
{
	delete [] mpLocation;
	delete [] mpAlt;
	delete [] mpCaption;
}

void clsImageWidget::SetLocation(const char *pLoc)
{
    delete [] mpLocation;
    if (pLoc)
    {
        mpLocation = new char [strlen(pLoc) + 1];
        strcpy(mpLocation, pLoc);
    }
    else
        mpLocation = NULL;
}

void clsImageWidget::SetAlt(const char *pAlt)
{
    delete [] mpAlt;
    if (pAlt)
    {
        mpAlt = new char [strlen(pAlt) + 1];
        strcpy(mpAlt, pAlt);
    }
    else
        mpAlt = NULL;
}

void clsImageWidget::SetCaption(const char *pCap)
{
    delete [] mpCaption;
    if (pCap)
    {
        mpCaption = new char [strlen(pCap) + 1];
        strcpy(mpCaption, pCap);
    }
    else
        mpCaption = NULL;
}


void clsImageWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments  = true */)
{
	*pStream << "<"
			 << pName;

	if (mpAlt)
	{
		*pStream << " ALT=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpAlt);
		*pStream << "\"";
	}

	if (mpLocation)
	{
		*pStream << " SRC=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpLocation);
		*pStream << "\"";
	}

	if (mpCaption)
	{
		*pStream << " CAPTION=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpCaption);
		*pStream << "\"";
	}


	if (mHeight)
		*pStream << " HEIGHT=" << mHeight;

	if (mWidth)
		*pStream << " WIDTH=" << mWidth;

	*pStream << ">";
}

void clsImageWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pText;

	pText = GetParameterValue("ALT", pvArgs);
	SetAlt(pText);

	pText = GetParameterValue("SRC", pvArgs);
	SetLocation(pText);

	if ((pText = GetParameterValue("CAPTION", pvArgs)))
		SetCaption(pText);

	if ((pText = GetParameterValue("HEIGHT", pvArgs)))
		mHeight = atoi(pText);

	if ((pText = GetParameterValue("WIDTH", pvArgs)))
		mWidth = atoi(pText);
}

void clsImageWidget::SetParams(const void *pData,
                                   const char *pStringBase,
                                   bool fixBytes)
{
    clsImageWidgetOptionsStruct *pOptions;

    pOptions = (clsImageWidgetOptionsStruct *) pData;

    if (fixBytes)
    {
        pOptions->mHeight = clsUtilities::FixByteOrder32(pOptions->mHeight);
        pOptions->mWidth = clsUtilities::FixByteOrder32(pOptions->mWidth);
        pOptions->mLocationOffset = clsUtilities::FixByteOrder32(pOptions->mLocationOffset);
        pOptions->mCaptionOffset = clsUtilities::FixByteOrder32(pOptions->mCaptionOffset);
        pOptions->mAltOffset = clsUtilities::FixByteOrder32(pOptions->mAltOffset);
    }

	if (pOptions->mAltOffset == -1)
		SetAlt(NULL);
	else
		SetAlt(pStringBase + pOptions->mAltOffset);

	if (pOptions->mLocationOffset == -1)
		SetLocation(NULL);
	else
		SetLocation(pStringBase + pOptions->mLocationOffset);

	if (pOptions->mCaptionOffset == -1)
		SetCaption(NULL);
	else
		SetCaption(pStringBase + pOptions->mCaptionOffset);
}



long clsImageWidget::GetBlob(clsDataPool *pDataPool, bool fixBytes)
{
	clsImageWidgetOptionsStruct theOptions;

	if (!mpLocation)
		theOptions.mLocationOffset = -1;
	else
		theOptions.mLocationOffset = pDataPool->AddString(mpLocation);

	if (!mpAlt)
		theOptions.mAltOffset = -1;
	else
		theOptions.mAltOffset = pDataPool->AddString(mpAlt);

	if (!mpCaption)
		theOptions.mCaptionOffset = -1;
	else
		theOptions.mCaptionOffset = pDataPool->AddString(mpCaption);
	
	theOptions.mHeight = mHeight;
	theOptions.mWidth = mWidth;

	theOptions.mExpansionOffset = -1;
	if (fixBytes)
	{
        theOptions.mHeight = clsUtilities::FixByteOrder32(theOptions.mHeight);
        theOptions.mWidth = clsUtilities::FixByteOrder32(theOptions.mWidth);
        theOptions.mLocationOffset = clsUtilities::FixByteOrder32(theOptions.mLocationOffset);
        theOptions.mCaptionOffset = clsUtilities::FixByteOrder32(theOptions.mCaptionOffset);
        theOptions.mAltOffset = clsUtilities::FixByteOrder32(theOptions.mAltOffset);
		theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
	}

	return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

bool clsImageWidget::EmitHTML(ostream *pStream)
{
	if (!mpLocation)
		return true;

	*pStream << "<IMG SRC=\""
			 << mpLocation
			 << "\"";

	if (mHeight)
		*pStream << " HEIGHT="
				 << mHeight;

	if (mWidth)
		*pStream << " WIDTH="
				 << mWidth;

	if (mpAlt)
		*pStream << " ALT=\""
				 << mpAlt
				 << "\"";

	*pStream << ">\n";

	if (mpCaption)
	{
		*pStream	<<  "<br><font size=3>"
					<<	mpCaption
					<<	"</font><br><br>"
					<<	"\n\n";
	}

	return true;
}
