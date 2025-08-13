/*	$Id: clsPartnerAd.h,v 1.1.26.1 1999/08/01 03:02:10 barry Exp $	*/
//
//	File:	clsPartnerAd.h
//
//  Class:	clsPartnerAd
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

#ifndef CLSPARTNERAD_INCLUDED

#include "clsAd.h"

class clsPartnerAd : public clsAd
{
public:
	clsPartnerAd() : 
		clsAd(0, NULL, NULL),
		mSiteId(-1),
		mPartnerId(-1),
		mPageType(PageTypeUnknown),
		mSecondaryPageType(PageTypeUnknown),
		mContextSensitiveValue(-1)
	{;
	}

	clsPartnerAd(int id,
				 const char *pName,
				 int siteId, 
				 int partnerId, 
				 PageTypeEnum pageType, 
				 PageTypeEnum secondaryPageType, 
				 int contextSensitiveValue, 
				 const char *pText) :
		clsAd(id, pName, pText),
		mSiteId(siteId),
		mPartnerId(partnerId),
		mPageType(pageType),
		mSecondaryPageType(secondaryPageType),
		mContextSensitiveValue(contextSensitiveValue)	
	{;
	}

	clsPartnerAd(const char *pName,
				 int siteId,
				 int partnerId, 
				 PageTypeEnum pageType, 
				 PageTypeEnum secondaryPageType, 
				 int contextSensitiveValue, 
				 const char *pText) :
		clsAd(0, pName, pText),
		mSiteId(siteId),
		mPartnerId(partnerId),
		mPageType(pageType),
		mSecondaryPageType(secondaryPageType),
		mContextSensitiveValue(contextSensitiveValue)	
	{;
	}

	virtual ~clsPartnerAd() {;}

	int				GetSiteId()	{ return mSiteId; }
	int				GetPartnerId() { return mPartnerId; }
	PageTypeEnum	GetPageType() { return mPageType;}
	PageTypeEnum	GetSecondaryPageType() { return mSecondaryPageType; }
	int				GetContextSensitiveValue() { return mContextSensitiveValue; } 

	void			SetSiteId(int siteId) { mSiteId = siteId; }
	void			SetPartnerId(int partnerId) { mPartnerId = partnerId; }
	void			SetPageType(PageTypeEnum pageType) { mPageType = pageType; }
	void			SetSecondaryPageType(PageTypeEnum secondaryPageType) { mSecondaryPageType = secondaryPageType; }
	void			SetContextSensitiveValue(int contextSensitiveValue) { mContextSensitiveValue = contextSensitiveValue; }
	
protected:
	int				mSiteId;
	int				mPartnerId;
	PageTypeEnum	mPageType;
	PageTypeEnum	mSecondaryPageType;
	int				mContextSensitiveValue;	// context depends on mPageType, mSecondaryPageType
};

typedef vector<clsPartnerAd *> PartnerAdVector;

// comparing ad
bool partner_ad_comp(clsPartnerAd *pAd1, clsPartnerAd *pAd2);


#define CLSPARTNERAD_INCLUDED
#endif /* CLSPARTNERAD_INCLUDED */
