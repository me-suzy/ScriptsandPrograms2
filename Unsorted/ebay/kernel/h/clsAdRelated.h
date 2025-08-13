/*	$Id: clsAdRelated.h,v 1.2 1998/06/23 04:27:31 josh Exp $	*/
//
//	File:		clsAdRelated.h
//
// Class:	clsAdRelated
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Ad related information
//
// Modifications:
//				- 12/04/97 Wen	- Created
//
#ifndef CLSADRELATED_INCLUDE
#define CLSADRELATED_INCLUDE

class clsMarketPlace;

class clsAdRelated
{
public:
	clsAdRelated(clsMarketPlace* pMarketplace);
	clsAdRelated() {;}

	void GetPageViews(int PageType, int* pPageViews);

	void GetDailyAds(int PageType, void** pAdVectorArray);

protected:
	clsMarketPlace*	mpMarketPlace;
};

#endif // CLSADRELATED_INCLUDE
