/*	$Id: clsHeader.h,v 1.1.26.1 1999/08/01 03:02:08 barry Exp $	*/
//
//	File:	clsHeader.h
//
//  Class:	clsHeader
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class to hold header information 
//
// Modifications:
//				- 05/20/99 wen	- Created
//

#ifndef CLSHEADER_INCLUDED

#include "clsPartnerAd.h"


class clsHeader
{
public:
	clsHeader() :
		mSiteId(-1),
		mPartnerId(-1),
		mPageType(-1),
		mSecondaryPageType(-1),
		mpText(NULL),
		mpvPartnerAds(NULL)
	{
	}

	clsHeader(int SiteId, 
			  int PartnerId, 
			  int PageType, 
			  int SecondaryPageType, 
			  const char *pText,
			  PartnerAdVector *pvAds);

	~clsHeader()
	{
		delete [] mpText;
	}

	int		GetSiteId()		{ return mSiteId; }
	int		GetPartnerId()	{ return mPartnerId; }
	int		GetPageType()	{ return mPageType; }
	int		GetSecondaryPageType()	{ return mSecondaryPageType; }
	char*	GetText()	{ return mpText; }
	PartnerAdVector *	GetAds()	{ return mpvPartnerAds; }
//	clsPartnerAd *		GetAd(int index)	{ return (index < mpvPartnerAds->size() ? mpvPartnerAds[index] : NULL); }

	void	SetSiteId(int SiteId) { mSiteId = SiteId; }
	void	SetPartnerId(int PartnerId) { mPartnerId = PartnerId; }
	void	SetPageType(int PageType) { mPageType = PageType;}
	void	SetSecondaryPageType(int SecondaryPageType) { mSecondaryPageType = SecondaryPageType;}
	void	SetText(const char* pText);
	void	SetAds(PartnerAdVector *pvAds) { mpvPartnerAds = pvAds; }

protected:
	int					mSiteId;
	int					mPartnerId;
	int					mPageType;
	int					mSecondaryPageType;
	char*				mpText;
	PartnerAdVector *	mpvPartnerAds;
};

typedef vector<clsHeader *> HeaderVector;

// comparing header
bool header_comp(clsHeader* pHeader1, clsHeader* pHeader2);

#define CLSHEADER_INCLUDED
#endif /* CLSHEADER_INCLUDED */
