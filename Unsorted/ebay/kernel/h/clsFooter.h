/*	$Id: clsFooter.h,v 1.1.26.1 1999/08/01 03:02:08 barry Exp $	*/
//
//	File:	clsFooter.h
//
//  Class:	clsFooter
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class to hold Footer information 
//
// Modifications:
//				- 05/20/99 wen	- Created
//

#ifndef CLSFOOTER_INCLUDED

#include "clsPartnerAd.h"

class clsFooter
{
public:
	clsFooter() :
		mSiteId(-1),
		mPartnerId(-1),
		mPageType(-1),
		mSecondaryPageType(-1),
		mpText(NULL),
		mpvAds(NULL)
	{
	}

	clsFooter(int SiteId, 
			  int PartnerId, 
			  int PageType, 
			  int SecondaryPageType, 
			  const char* pText,
			  PartnerAdVector *pvAds);

	~clsFooter()
	{
		delete [] mpText;
	}

	int		GetSiteId()		{return mSiteId;}
	int		GetPartnerId()	{return mPartnerId;}
	int		GetPageType()			{return mPageType;}
	int		GetSecondaryPageType()			{return mSecondaryPageType;}
	char*	GetText()	{return mpText;}
	PartnerAdVector *	GetAds()	{ return mpvAds; }
//	clsPartnerAd *		GetAd(int index)	{ return (index < mpvAds->size() ? mpvAds[index] : NULL); }

	void	SetSiteId(int SiteId) { mSiteId = SiteId; }
	void	SetPartnerId(int PartnerId) {mPartnerId = PartnerId; }
	void	SetPageType(int PageType) {mPageType = PageType;}
	void	SetSecondaryPageType(int SecondaryPageType) {mSecondaryPageType = SecondaryPageType;}
	void	SetText(const char* pText);
	void	SetAds(PartnerAdVector *pvAds) { mpvAds = pvAds; }
	
protected:
	int					mSiteId;
	int					mPartnerId;
	int					mPageType;
	int					mSecondaryPageType;
	char*				mpText;
	PartnerAdVector *	mpvAds;
};

typedef vector<clsFooter *> FooterVector;

// comparing footer
bool footer_comp(clsFooter* pFooter1, clsFooter* pFooter2);

#define CLSFOOTER_INCLUDED
#endif /* CLSFOOTER_INCLUDED */
