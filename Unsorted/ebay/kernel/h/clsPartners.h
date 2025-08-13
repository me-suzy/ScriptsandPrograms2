/*	$Id: clsPartners.h,v 1.4.208.1.78.3 1999/08/10 01:19:51 nsacco Exp $	*/
//
// File Name: clsPartners.h
//
// Description: Header for the clsPartners object.
//
// Author:  Chad Musick
//
//			05/25/99 nsacco	- Added mSiteId and 
//							  FindPartnerFromServerName()
//			06/21/99 nsacco - Added siteId and pParsedString to CreatePartner()
//
#ifndef CLSPARTNERS_INCLUDE
#define CLSPARTNERS_INCLUDE

#include "clsPartner.h"

#include "vector.h"

#define INVALID_PARTNER -1

class clsPartners
{
public:
	// nsacco 05/28/99
	clsPartners(int siteId);
	~clsPartners();

	clsPartner *GetCurrentPartner();

	clsPartner *GetPartner(const char *pName);
	clsPartner *GetPartner(int id);

	void GetAllPartners(vector<clsPartner *> *pvPartners);
	const char *GetCurrentHeader(PageEnum ePage, bool withAnnouncements=true);
	const char *GetCurrentFooter(PageEnum ePage, bool getAds = false);
	const char *GetCurrentCGIPath(PageEnum ePage);
	const char *GetCurrentHTMLPath(PageEnum ePage);
	const char *GetCurrentPicsPath(PageEnum ePage);
	const char *GetCurrentCGIRelativePath();
	const char *GetCurrentHTMLRelativePath();
	const char *GetCurrentPicsRelativePath();
	const char *GetCurrentListingPath();
	const char *GetCurrentListingRelativePath();
	const char *GetMembersPath();
// kakiyama 07/20/99
	const char *GetCurrentSearchPath(PageEnum ePage);
	const char *GetCurrentGalleryListingPath();
	// nsacco 08/09/99
	const char *GetCurrentAdPicsPath();

	static const char *GetPageDescription(PageTypeEnum ePage);

	// nsacco 06/21/99 new params siteId amd pParsedString
	int CreatePartner(const char *pName, const char *pDesc, int siteId, const char *pParsedString);
	PageTypeEnum GetPageTypeFromPageEnum(PageEnum ePage);
	void ResetCurrentPartner() { mpCurrentPartner = NULL; }

private:
	void LoadPartners();
	clsPartner* mpCurrentPartner;
	vector<clsPartner *> *mpvPartners;	
	clsPartner *mpDefaultPartner;
	// nsacco 05/25/99
	int mSiteId;
};
#endif
