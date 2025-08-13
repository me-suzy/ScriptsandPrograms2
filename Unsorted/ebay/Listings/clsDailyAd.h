/* $Id: clsDailyAd.h,v 1.2 1998/06/23 04:21:02 josh Exp $ */
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
//				- 3/29/98 Craig Huang
//////////////////////////////////////////////////////////////////////
#ifndef	CLSDAILYAD_INCLUDE
#define	CLSDAILYAD_INCLUDE

//#include "vector.h"
#include <stdio.h>

class clsDailyAd
{
public:
	
	// constructor and destructor
	clsDailyAd(	int		CatId,
				int		AdId,
				int		Impressions,
				char*	pURL,
				char*	pImageSource,
				char*	pAlt = NULL,
				char*	pOther = NULL);

	~clsDailyAd();

	// Sets
	

	// Gets
	int GetCatId();
	int GetAdId();
	int GetImpressions();
	const char* GetURL();
	const char* GetImageSource();
	const char* GetAlt();
	const char*	GetOther();

protected:
	int		mCatId;
	int		mAdId;
	int		mImpressions;
	char*	mpURL;
	char*	mpImageSource;
	char*	mpAlt;
	char*	mpOther;
};

//typedef vector<clsDailyAd*> DailyAdVector;

#endif // CLSDAILYAD_INCLUDE
