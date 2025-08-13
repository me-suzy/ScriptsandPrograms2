/*	$Id: clsDailyAd.h,v 1.3 1998/08/25 03:20:03 josh Exp $	*/
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
#ifndef	CLSDAILYAD_INCLUDE
#define	CLSDAILYAD_INCLUDE

#include "vector.h"

class clsDailyAd
{
public:
	
	// constructor and destructor
	clsDailyAd(	int		CatId,
				int		AdId,
				int		Impressions,
				const char*	pURL,
				const char*	pImageSource,
				const char*	pAlt = NULL,
				const char*	pOther = NULL);

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

typedef vector<clsDailyAd*> DailyAdVector;

#endif // CLSDAILYAD_INCLUDE
