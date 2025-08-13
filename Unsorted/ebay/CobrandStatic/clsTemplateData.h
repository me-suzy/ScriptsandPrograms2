/*	$Id: clsTemplateData.h,v 1.3 1999/02/21 02:21:29 josh Exp $	*/
#ifndef clsTemplateData_h
#define clsTemplateData_h

#include "../Listings/ebay.h"

struct headerEntry
{
	int32_t textOffset;
	int32_t numPartners;
	int32_t numPageTypes;
	int32_t partnerOffset;
	int32_t headersOffset;
};

struct partnerEntry
{
	int32_t partnerNumber;
	int32_t headersOffset;
	int32_t footersOffset;
	int32_t cgiOffset;
	int32_t htmlOffset;
};

class clsMappedFile;

class clsTemplateData
{
private:
	// Cast pointers to the various parts of the file.
	char			*mpTextBase;
	partnerEntry	*mpPartners;
	int32_t			*mpTextHeadersOffset;
	headerEntry		*mpHeader;
	clsMappedFile	*mpMap;

	bool ValidatePageType(int pageType) const;
	bool ValidatePartner(int partner) const;

public:
	explicit clsTemplateData(const char *lpFileName);
	~clsTemplateData();

	const char *GetHeader(int pageType, int partner) const;
	const char *GetFooter(int pageType, int partner) const;
	const char *GetCGI(int pageType, int partner) const;
	const char *GetHTML(int pageType, int partner) const;
};

extern const clsTemplateData *gTemplates;

#endif /* clsTemplateData_h */
