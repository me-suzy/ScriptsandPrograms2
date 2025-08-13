/*	$Id: clsAdWidget.cpp,v 1.3 1999/04/07 05:42:20 josh Exp $	*/
//
//	File:		clsAdWidget.cc
//
//	Class:		clsAdWidget
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//		Widget to create ad tag on a html page
//
//
//	Modifications:
//				- 11/14/97 Wen	- Created
//
//////////////////////////////////////////////////////////////////////

#include "widgets.h"
#include "clsAdWidget.h"
#include "clsAdRelated.h"
#include "clsDailyAd.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsAdWidget::clsAdWidget(clsMarketPlace *pMarketPlace, clsApp *pApp)
	: clseBayWidget(pMarketPlace, pApp)
{
	mWidth  = 468;
	mHeight = 60;
	mBorder = 1;

	mpPageViews		= NULL;
	mpAdVectorArray = NULL;

	mpTopAds		= NULL;
	mpBottomAd	= NULL;

	mNumberTopAds = 1;
	mCatId = 0;
	mMaxCategoryId = 0;
}

clsAdWidget::~clsAdWidget()
{
	int	i;

	assert(mpTopAds);
	for (i = 0; i < mNumberTopAds; i++)
	{
		delete mpTopAds[i];
	}
	delete [] mpTopAds;

	delete mpBottomAd;

	mpPageViews = NULL;
	mpAdVectorArray = NULL;

//	Cleanp();
}

//
// Initialize
//
void clsAdWidget::Initialize()
{
	// clsCategories*	pCategories;
	// clsAdRelated*	pAdRelated;

	mpTopAds = new clsDailyAd*[mNumberTopAds];


	mpTopAds[0] = new clsDailyAd(0, 0, 60, 
		"http://ad.doubleclick.net/jump/www.ebay.com",
		"http://ad.doubleclick.net/ad/www.ebay.com");


	mpBottomAd	= new clsDailyAd(0, 2, 10,
		"http://ad.linkexchange.com/16/X961116/gotoad.map",
		"http://ad.linkexchange.com/16/X961116/logoshowad?free",
		"Internet Link Exchange",
		"<br><font size=1><a href=\"http://www.linkexchange.com/\">Member of the Internet Link Exchange</a></font>");
/*

	// get categories
	pCategories = mpMarketPlace->GetCategories();

	// clean up before loading the new information
	CleanUp();

	// Get the maximum category id
	mMaxCategoryId = pCategories->GetMaxCategoryId();

	pAdRelated = mpMarketPlace->GetAdRelated();

	// Retrieve page view information for categories
	mpPageViews = new int[mMaxCategoryId+1];
	memset(mpPageViews, 0, (mMaxCategoryId + 1) * sizeof(mpPageViews[0]));
	pAdRelated->GetPageViews(0, mpPageViews);

	// Retrieve ad information for categories
	mpAdVectorArray = new void*[mMaxCategoryId + 1];
	memset(mpAdVectorArray, 0, (mMaxCategoryId + 1) * sizeof(mpAdVectorArray[0]));
	pAdRelated->GetDailyAds(0, mpAdVectorArray);
*/
}

//
// clean up
//
void clsAdWidget::CleanUp()
{
/*
	int	i;
	DailyAdVector*	pvAd;
	DailyAdVector::iterator	iAd;

	// clean the page view array
	delete [] mpPageViews;

	// clean up the Ad vector array
	if (mpAdVectorArray)
	{
		for (i = 0; i <= mMaxCategoryId; i++)
		{
			pvAd = (DailyAdVector*)mpAdVectorArray[i];

			if (pvAd == NULL) continue;

			for (iAd = pvAd->begin(); iAd != pvAd->end(); iAd++)
			{
				delete *iAd;
			}
			delete pvAd;
		}
		delete [] mpAdVectorArray;
	}
*/
}

//
// Emit the HTML for this widget to the specified stream.
//
bool clsAdWidget::EmitHTML(ostream *pStream)
{
	int	Odd;
	int SumImpressions;
	DailyAdVector*	pvAd;
	DailyAdVector::iterator iAd;

	assert(mpPageViews);
	if (mpPageViews[mCatId] == 0)
	{
		// or display the default ad
		return false;
	}

	// generate the random number
	Odd = rand() % mpPageViews[mCatId];

	// pick an ad
	assert(mpAdVectorArray);
	pvAd = (DailyAdVector*) mpAdVectorArray[mCatId];
	if (pvAd ==  NULL)
	{
		// or display the default ad
		return false;
	}

	SumImpressions = 0;
	for (iAd = pvAd->begin(); iAd != pvAd->end(); iAd++)
	{
		SumImpressions += (*iAd)->GetImpressions();

		if (Odd < SumImpressions)
		{
			EmitAdTag(*iAd, pStream);
			return true;
		}
	}

	// or display default ad
	return false;

}

//
// Emit the ad tag
//
void clsAdWidget::EmitAdTag(clsDailyAd* pAd, ostream* pStream)
{
	// get url
	if (pAd->GetURL() != NULL)
	{
		*pStream	<< "<A HREF=\""
					<< pAd->GetURL()
					<< "\">\n";
	}

	// get image source
	if (pAd->GetImageSource() != NULL)
	{
		*pStream	<< "<IMG width="
					<< mWidth
					<< " height="
					<< mHeight
					<< " border="
					<< mBorder;

		if (pAd->GetAlt() != NULL)
		{
			*pStream << " alt=\""
					 << pAd->GetAlt()
					 << "\"";
		}

		*pStream	<< " SRC=\""
					<< pAd->GetImageSource()
					<< "\">\n";
	}
	*pStream	<< "</A>";

	// other description whicn is in html format
	if (pAd->GetOther() != NULL)
	{
		*pStream	<< pAd->GetOther();
	}
}

//
// Emit the variable ad tag
//
void clsAdWidget::EmitVariableAdTag(clsDailyAd* pAd, ostream* pStream)
{
	// get url
	if (pAd->GetURL() != NULL)
	{
		*pStream	<< "<A HREF=\""
					<< pAd->GetURL()
					<< "/cat"
					<< mCatId
					<< "\">\n";
	}

	// get image source
	if (pAd->GetImageSource() != NULL)
	{
		*pStream	<< "<IMG width="
					<< mWidth
					<< " height="
					<< mHeight
					<< " border="
					<< mBorder;

		if (pAd->GetAlt() != NULL)
		{
			*pStream << " alt=\""
					 << pAd->GetAlt()
					 << "\"";
		}

		*pStream	<< " SRC=\""
					<< pAd->GetImageSource()
					<< "/cat"
					<< mCatId
					<< "\">\n";
	}
	*pStream	<< "</A>";

	// other description whicn is in html format
	if (pAd->GetOther() != NULL)
	{
		*pStream	<< pAd->GetOther();
	}
}

//
// Emit the ad tag on the top of the page
//
void clsAdWidget::EmitHTMLOnTop(ostream* pStream)
{
	int	AdId;

	AdId = rand() % 10;

	if (AdId > 0)
		return;

	assert(mpTopAds);
	EmitVariableAdTag(mpTopAds[AdId], pStream);
}

//
// Emit the ad tag at the bottom of the page
//
void clsAdWidget::EmitHTMLAtBottom(ostream* pStream)
{
	EmitAdTag(mpBottomAd, pStream);
}


void clsAdWidget::SetCategoryId(int CatId)
{
	mCatId = CatId;
}

bool clsAdWidget::IsComputer()
{
	clsCategories*	pCategories;
	clsCategory*	pCategory;

	pCategories = mpMarketPlace->GetCategories();
	pCategory = pCategories->GetCategory(mCatId);
	
	return (pCategory && (pCategory->GetId()		== 160 ||
			pCategory->GetLevel1()	== 160 ||
			pCategory->GetLevel2()	== 160 ||
			pCategory->GetLevel3()	== 160 ||
			pCategory->GetLevel4()	== 160 ));
}
