/* $Id: clsDailyAd.cpp,v 1.2 1998/06/23 04:21:01 josh Exp $ */
//
//	File:		clsAd.h
//
//	Class:		clsDailyAd
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//		class to hold daily information of an ad
//
//
//	Modifications:
//				- 11/14/97 Wen	- Created
//
//////////////////////////////////////////////////////////////////////
#include "clsDailyAd.h"
#include <string.h>

clsDailyAd::clsDailyAd(	int		CatId,
						int		AdId,
						int		Impressions,
						char*	pURL,
						char*	pImageSource,
						char*	pAlt,
						char*	pOther)
{
	mCatId		= CatId;
	mAdId		= AdId;
	mImpressions= Impressions;

	mpURL = NULL;
	if (pURL)
	{
		mpURL = new char[strlen(pURL) + 1];
		strcpy(mpURL, pURL);
	}

	mpImageSource = NULL;
	if (pImageSource)
	{
		mpImageSource = new char[strlen(pImageSource) + 1];
		strcpy(mpImageSource, pImageSource);
	}

	mpAlt = NULL;
	if (pAlt)
	{
		mpAlt = new char[strlen(pAlt) + 1];
		strcpy(mpAlt, pAlt);
	}

	mpOther = NULL;
	if (pOther)
	{
		mpOther = new char[strlen(pOther) + 1];
		strcpy(mpOther, pOther);
	}
}

clsDailyAd::~clsDailyAd()
{
	delete [] mpURL;
	delete [] mpImageSource;
	delete [] mpAlt;
	delete [] mpOther;
}

int clsDailyAd::GetCatId()
{
	return mCatId;
}

int clsDailyAd::GetAdId()
{
	return mAdId;
}

int clsDailyAd::GetImpressions()
{
	return mImpressions;
}

const char* clsDailyAd::GetURL()
{
	return mpURL;
}

const char* clsDailyAd::GetImageSource()
{
	return mpImageSource;
}

const char* clsDailyAd::GetAlt()
{
	return mpAlt;
}

const char* clsDailyAd::GetOther()
{
	return mpOther;
}