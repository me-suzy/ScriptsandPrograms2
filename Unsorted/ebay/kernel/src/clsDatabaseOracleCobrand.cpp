/*	$Id: clsDatabaseOracleCobrand.cpp,v 1.4.66.1.98.5 1999/08/10 19:08:09 phofer Exp $	*/
//
//	File:	clsDatabaseOracleCobrand.cc
//
//	Class:	clsDatabaseOracle
//
//	Author:	EineKleineNacht Musick (chad@ebay.com)
//
//	Function: Database functions for cobranding.
//
//
// Modifications:
//			12/31/97 Chad	- created
//			05/25/99 nsacco	- Modified LoadPartnerHeaderAndFooter to take a siteId
//							  added LoadSites and LoadSite.
//			06/21/99 nsacco	- CreateCobrandPartner takes siteId and parsed string now
//			07/09/99 nsacco - Switch to new db schema and new cobrand tables
//			07/15/99 nsacco	- Added new columns to ebay_sites
//			07/19/99 petra	- add locale and time zone


#include "eBayKernel.h"
#include <stdio.h>
#include "clsPartners.h"
// nsacco 05/25/99
#include "clsSites.h"

#include "clsHeader.h"
#include "clsFooter.h"


#define COBRAND_FETCH_SIZE 200

static const char *SQL_GetCobrandNextPartnerId =
 "select ebay_partners_sequence.nextval from dual";	// nsacco 07/09/99

// GetCobrandNextPartnerId
// Gets the next cobrand id in line to be used (that's not used)
//
int clsDatabaseOracle::GetCobrandNextPartnerId()
{
	int unique_id;

	OpenAndParse(&mpCDAOneShot, SQL_GetCobrandNextPartnerId);

	Define(1, &unique_id);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return unique_id;
}

// nsacco 06/21/99 added site_id and parsed_string
// nsacco 07/09/99 switch to new table
static const char *SQL_CreateCobrandPartner =
 "insert into ebay_partners			\
	( id, partner_name, partner_desc, site_id, parsed_string )		\
	values									\
	(										\
	:id,									\
	:partner_name, :partner_desc, :site_id, :parsed_string)";

// CreateCobrandPartner
// Creates just the bare minimum for a cobrand partner --
// doesn't create any of the headers and footers, just
// the partner name and description.
//
// nsacco 06/21/99 added siteId and pParsedString
int clsDatabaseOracle::CreateCobrandPartner(const char *pName,
											const char *pDescription,
											int siteId,
											const char *pParsedString)
{
	int id;

	id = GetCobrandNextPartnerId();

	OpenAndParse(&mpCDAOneShot, SQL_CreateCobrandPartner);

	Bind(":id", &id);
	Bind(":partner_name", (char *) pName);
	Bind(":partner_desc", (char *) pDescription);
	// nsacco 06/21/99
	Bind(":site_id", &siteId);
	Bind(":parsed_string", (char *) pParsedString);

	Execute();
	
	if (!CheckForNoRowsUpdated())
		Commit();
	else
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return -1;
	}
	
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return id;
}

// nsacco 07/09/99 switch to new table
// kakiyama 06/23/99 added parsed_string
// nsacco 06/01/99 added site_id
static const char *SQL_GetCobrandPartners =
 "select id, partner_name, partner_desc, parsed_string from ebay_partners \
 where site_id=:siteid";

// LoadPartners
// Loads all cobrand partners from the database into the clsPartner *
// vector -- does not load any of the headers, that happens on demand --
// only loads the ids and names.
// nsacco 06/01/99 added site id
//
void clsDatabaseOracle::LoadPartners(vector<clsPartner *> *pvPartners, int SiteId)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		names[COBRAND_FETCH_SIZE][256];
	char		descs[COBRAND_FETCH_SIZE][256];
	int			partnerId[COBRAND_FETCH_SIZE];
	char		parsedString[COBRAND_FETCH_SIZE][256];

	clsPartner*	pPartner;

	if (IIS_Server_is_down()) return; //new outage code
	
	// Let's open the cursor
	OpenAndParse(&mpCDALoadPartners,
				 SQL_GetCobrandPartners);

	// Bind
	// nsacco 06/01/99
	Bind(":siteid", &SiteId);

	// Define
	Define(1, (int *) partnerId);
	Define(2, (char *) names, sizeof(names[0]));
	Define(3, (char *) descs, sizeof (descs[0]));
	Define(4, (char *) parsedString, sizeof(parsedString[0]));

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDALoadPartners);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDALoadPartners);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pPartner = new clsPartner(names[i], partnerId[i], descs[i], SiteId, parsedString[i]);
			pvPartners->push_back(pPartner);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDALoadPartners);
	SetStatement(NULL);


	return;
}

// nsacco 07/09/99 new tables
static const char *SQL_GetPartnerHeaderAndFooter =
 "select								\
	a.page_type,						\
	a.secondary_page_type,				\
	a.header_type,						\
	a.header_unq_id,					\
	b.header_length						\
	from ebay_headers a,		\
	ebay_headers_text b			\
	where a.partner_id = :id and		\
	a.site_id = :siteid and \
	b.header_unq_id	= a.header_unq_id	\
	order by page_type, secondary_page_type";

// nsacco 07/09/99 new table
static const char *SQL_GetPartnerHeaderText =
 "select header_text from ebay_headers_text where	\
	header_unq_id = :unique_id";

// LoadPartnerHeaderAndFooter
// nsacco 05/25/99 -added site id
// Loads into the vectors the headers and footers for the given
// partner id and site id.
// pvHeaders contains the headers (page group is index)
// pvFooters contains the footers (page group is index)
// pvDeletes contains all allocated strings, to be deleted.
//
void clsDatabaseOracle::LoadPartnerHeaderAndFooter(int partnerId,
			vector<char *> *pvHeaders, vector<char *> *pvFooters,
			vector<char *> *pvDeletes,
			int siteId)
{
	char *pHeader;

	int pageType[COBRAND_FETCH_SIZE];
	int secondaryPageType[COBRAND_FETCH_SIZE];
	int headerType[COBRAND_FETCH_SIZE];
	int headerUniqueId[COBRAND_FETCH_SIZE];
	int headerLengths[COBRAND_FETCH_SIZE];

	int indexInHeaders;
	int indexInFooters;

	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	int			uniqueIndex;

	vector<int> vHeaderIds;
	vector<int> vFooterIds;
	vector<int> vUniqueIds;
	vector<int> vUniqueLengths;
	vector<char *> vUniqueDescs;
	vector<int>::iterator j;

	OpenAndParse(&mpCDAOneShot, SQL_GetPartnerHeaderAndFooter);

	// nsacco 05/25/99
	Bind(":id", &partnerId);
	Bind(":siteid", &siteId);

	// Define
	Define(1, (int *) pageType);
	Define(2, (int *) secondaryPageType);
	Define(3, (int *) headerType);
	Define(4, (int *) headerUniqueId);
	Define(5, (int *) headerLengths);
	
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot, true);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	indexInHeaders = pvHeaders->size() - 1;
	indexInFooters = pvFooters->size() - 1;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			uniqueIndex = clsPartner::GetUniqueIndex((PageTypeEnum)pageType[i], (PageTypeEnum)secondaryPageType[i]);

			if (headerType[i] == 1)
			{
				while (indexInHeaders < uniqueIndex)
				{
					pvHeaders->push_back((char *) NULL);
					vHeaderIds.push_back(-1);
					++indexInHeaders;
				}
				vHeaderIds[uniqueIndex] = headerUniqueId[i];
			}

			if (headerType[i] == 0)
			{
				while (indexInFooters < uniqueIndex)
				{
					pvFooters->push_back((char *) NULL);
					vFooterIds.push_back(-1);
					++indexInFooters;
				}
				vFooterIds[uniqueIndex] = headerUniqueId[i];
			}

			// Only add it if we haven't seen it before.
			if (find(vUniqueIds.begin(), vUniqueIds.end(), headerUniqueId[i]) ==
				vUniqueIds.end())
			{
				vUniqueIds.push_back(headerUniqueId[i]);
				vUniqueLengths.push_back(headerLengths[i]);
				vUniqueDescs.push_back((char *) NULL);
				pvDeletes->push_back((char *) NULL);
			}

		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (vUniqueIds.empty())
		return;

	// Now get the descriptions, one of each of them.
	i = vUniqueIds.size();
	while (i--)
	{

		if (vUniqueIds[i] == -1 ||
			vUniqueLengths[i] <= 0)
			continue;

		OpenAndParse(&mpCDAOneShot, SQL_GetPartnerHeaderText);

		pHeader = new char [vUniqueLengths[i] + 1];

		Bind(":unique_id", &(vUniqueIds[i]));

		DefineLongRaw(1, (unsigned char *) pHeader, vUniqueLengths[i]);

		ExecuteAndFetch();

		*(pHeader + vUniqueLengths[i]) = '\0';

		vUniqueDescs[i] = (char *) pHeader;

		(*pvDeletes)[i] = pHeader;

		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	i = vHeaderIds.size();
	while (i--)
	{
		j = find(vUniqueIds.begin(), vUniqueIds.end(), vHeaderIds[i]);
		if (j == vUniqueIds.end())
			continue;

		(*pvHeaders)[i] = vUniqueDescs[(int) (j - vUniqueIds.begin())];
	}

	i = vFooterIds.size();
	while (i--)
	{
		j = find(vUniqueIds.begin(), vUniqueIds.end(), vFooterIds[i]);
		if (j == vUniqueIds.end())
			continue;

		(*pvFooters)[i] = vUniqueDescs[(int) (j - vUniqueIds.begin())];
	}

	vUniqueIds.erase(vUniqueIds.begin(), vUniqueIds.end());
	vUniqueLengths.erase(vUniqueLengths.begin(), vUniqueLengths.end());
	vHeaderIds.erase(vHeaderIds.begin(), vHeaderIds.end());
	vFooterIds.erase(vFooterIds.begin(), vFooterIds.end());
	vUniqueDescs.erase(vUniqueDescs.begin(), vUniqueDescs.end());

	return;
}

// nsacco 07/09/99 new table
static const char *SQL_GetNumberOfHeaderReferences =
 "select count(*) from ebay_headers where		\
	header_unq_id = :unique_id";

// GetNumberOfHeaderReferences
// Gets the number of references to a header text by counting
// the actual references in the database.
int clsDatabaseOracle::GetNumberOfHeaderReferences(int unique_id)
{
	int numRefs;

	OpenAndParse(&mpCDAOneShot, SQL_GetNumberOfHeaderReferences);

	Bind(":unique_id", &unique_id);

	Define(1, &numRefs);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return numRefs;
}

// nsacco 07/09/99 new table
static const char *SQL_ChangeCobrandHeaderText =
 "update ebay_headers_text			\
	set header_text = :new_text,			\
	header_length = :header_length			\
	where header_unq_id = :unique_id";

// ChangeCobrandHeaderText
// Changes the actual text of a header reference -- this will
// have the effect of changing the text of anything which
// refers to it.
void clsDatabaseOracle::ChangeCobrandHeaderText(int unique_id,
												const char *pNewText)
{
	int header_length;

	OpenAndParse(&mpCDAOneShot, SQL_ChangeCobrandHeaderText);

	header_length = strlen(pNewText) + 1;

	Bind(":unique_id", &unique_id);
	BindLongRaw(":new_text", (unsigned char *) pNewText,
		header_length);
	Bind(":header_length", &header_length);

	Execute();

	if (!CheckForNoRowsUpdated())
		Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// nsacco 07/09/99 new table
static const char *SQL_GetCobrandHeaderTextId =
 "select header_unq_id from ebay_headers	\
	where											\
	partner_id =: partner_id and					\
	header_type =: is_header and					\
	page_type =: page_type	 and					\
	secondary_page_type =: secondary_page_type and	\
	site_id =: site_id";

// GetCobrandHeaderTextId
// Gets the unique id of a header (or footer) for a partner and page type
// isHeader should be 1 if it is a header and 0 if it is a footer
//
int clsDatabaseOracle::GetCobrandHeaderTextId(int partnerId,
											  int isHeader,
											  PageTypeEnum page_type,
											  PageTypeEnum secondary_page_type,
											  int siteId)
{
	int unique_id = 0;

	OpenAndParse(&mpCDAOneShot, SQL_GetCobrandHeaderTextId);

	isHeader = !!isHeader; // Make it 1 or 0.

	Bind(":partner_id", &partnerId);
	Bind(":is_header", &isHeader);
	Bind(":page_type", (int *) &page_type);
	Bind(":secondary_page_type", (int *) &secondary_page_type);
	Bind(":site_id", &siteId);

	Define(1, &unique_id);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return unique_id;
}

// nsacco 07/09/99 new table
static const char *SQL_NewCobrandHeaderReference =
 "insert into ebay_headers_text			\
	( header_text,								\
	  header_length,							\
	  header_desc,								\
	  header_unq_id)							\
	values										\
	( :header_text,								\
	  :header_length,							\
	  :header_desc,								\
	  :header_unq_id )";

// NewCobrandHeaderReference
// Creates a new text block to which references can be made.
//
int clsDatabaseOracle::NewCobrandHeaderReference(const char *pDescription,
												 const char *pNewText)
{
	int header_length;
	int uniqueId;
	
	header_length = strlen(pNewText) + 1;

	uniqueId = GetCobrandNextHeaderId();

	OpenAndParse(&mpCDAOneShot, SQL_NewCobrandHeaderReference);

	BindLongRaw(":header_text", (unsigned char *) pNewText, header_length);
	Bind(":header_length", &header_length);
	Bind(":header_desc", (char *) pDescription);
	Bind(":header_unq_id", &uniqueId);

	Execute();
	
	if (!CheckForNoRowsUpdated())
		Commit();
	
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return uniqueId;
}

// TODO - new table?

static const char *SQL_GetCobrandNextHeaderId =
 "select ebay_cobrand_unq_sequence.nextval from dual";

// GetCobrandNextHeaderId
// Gets the next header unique id to be used (that's unused)
//
int clsDatabaseOracle::GetCobrandNextHeaderId()
{
	int unique_id;

	OpenAndParse(&mpCDAOneShot, SQL_GetCobrandNextHeaderId);

	Define(1, &unique_id);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return unique_id;
}

// nsacco 07/09/99 new table
static const char *SQL_RemoveCobrandHeaderReference =
 "delete from ebay_headers_text where	\
	header_unq_id = :header_unq_id";

// RemoveCobrandHeaderReference
// Removes the text which matches uniqueId
//
void clsDatabaseOracle::RemoveCobrandHeaderReference(int uniqueId)
{
	OpenAndParse(&mpCDAOneShot, SQL_RemoveCobrandHeaderReference);

	Bind(":header_unq_id", &uniqueId);

	Execute();

	if (!CheckForNoRowsUpdated())
		Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// SetCobrandHeader
// Makes a new cobrand header, then sets to that
//
void clsDatabaseOracle::SetCobrandHeader(int partnerId, int isHeader,
										PageTypeEnum pageType,
										PageTypeEnum secondaryPageType,
										const char *pDescription,
										const char *pText,
										int SiteId)
{
	int uniqueId;

	uniqueId = NewCobrandHeaderReference(pDescription, pText);

	UpdateCobrandHeaderReference(partnerId, isHeader, pageType, secondaryPageType, uniqueId, SiteId);
}

// nsacco 07/09/99 new table
static const char *SQL_UpdateNewCobrandHeaderReference =
 "insert into ebay_headers				\
	(header_unq_id, header_type, page_type,		\
	partner_id, secondary_page_type, site_id)	\
	values										\
	(:unique_id, :is_header, :page_type,		\
	:partner_id, :secondary_page_type, :site_id)";

// nsacco 07/09/99 new table
static const char *SQL_UpdateCobrandHeaderReference =
 "update ebay_headers					\
	set header_unq_id = :unique_id				\
	where header_type = :is_header and			\
	page_type = :page_type and					\
	partner_id = :partner_id and				\
	secondary_page_type = :secondary_page_type and \
	site_id = :site_id";

// UpdateCobrandHeaderReference
// Updates the reference number of a header or footer.
// (Doesn't affect the text at all, except that the old
// text will be removed if it is no longer referenced.)
//
void clsDatabaseOracle::UpdateCobrandHeaderReference(int partnerId,
													 int isHeader,
													 PageTypeEnum pageType,
													 PageTypeEnum secondaryPageType,
													 int uniqueId,
													 int siteId)
{
	int oldId;

	oldId = GetCobrandHeaderTextId(partnerId, isHeader, pageType, secondaryPageType, siteId);

	if (oldId)
		OpenAndParse(&mpCDAOneShot, SQL_UpdateCobrandHeaderReference);
	else
		OpenAndParse(&mpCDAOneShot, SQL_UpdateNewCobrandHeaderReference);

	isHeader = !!isHeader; // Make it 1 or 0.

	Bind(":partner_id", &partnerId);
	Bind(":is_header", &isHeader);
	Bind(":page_type", (int *) &pageType);	
	Bind(":unique_id", &uniqueId);
	Bind(":secondary_page_type", (int *) &secondaryPageType);
	Bind(":site_id", &siteId);

	Execute();

	if (!CheckForNoRowsUpdated())
		Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (oldId && GetNumberOfHeaderReferences(oldId) <= 0)
		RemoveCobrandHeaderReference(oldId);

	return;
}

// CopyCobrandHeaderReference
// Copies a reference, without copying the text.
//
void clsDatabaseOracle::CopyCobrandHeaderReference(int partnerIdOriginal,
												   int isHeaderOriginal,
												   PageTypeEnum pageTypeOriginal,
												   PageTypeEnum secondaryPageOriginal,
												   int siteidOriginal,
												   int partnerIdNew,
												   int isHeaderNew,
												   PageTypeEnum pageTypeNew,
												   PageTypeEnum secondaryPageTypeNew,
												   int siteidNew)
{
	UpdateCobrandHeaderReference(partnerIdNew,
								 isHeaderNew,
								 pageTypeNew,
								 secondaryPageTypeNew,
								 GetCobrandHeaderTextId(partnerIdOriginal,
														isHeaderOriginal,
														pageTypeOriginal,
														secondaryPageOriginal,
														siteidOriginal),
								 siteidNew
								);
	return;
}

// nsacco 07/09/99 new table
static const char *SQL_ChangeCobrandHeaderDesc =
 "update ebay_headers_text set		\
	header_desc = :header_desc				\
	where header_unq_id = :unique_id";

// ChangeCobrandHeaderDesc
// Changes the description for header text
void clsDatabaseOracle::ChangeCobrandHeaderDesc(int uniqueId,
												const char *pNewDesc)
{
	OpenAndParse(&mpCDAOneShot, SQL_ChangeCobrandHeaderDesc);

	Bind(":header_desc", (char *) pNewDesc);
	Bind(":unique_id", &uniqueId);

	Execute();

	if (!CheckForNoRowsUpdated())
		Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// nsacco 07/15/99 added timezone_id and default_listing_currency
// kakiyama 06/23/99 added parsed_string
// nsacco 05/25/99 added
// petra 08/02/99 added locale
static const char *SQL_GetSites =
 "select site_id, name, parsed_string, timezone_id, default_listing_currency, locale_id from ebay_sites";

// LoadSites
// Loads all sites from the database into the clsSite *
// vector - only loads the ids and names.
//
void clsDatabaseOracle::LoadSites(vector<clsSite *> *pvSites)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;
	clsSite*	pSite;

	// Pointers to arrays of things
	char		names[COBRAND_FETCH_SIZE][256];
	int			siteId[COBRAND_FETCH_SIZE];
	char		parsedString[COBRAND_FETCH_SIZE][256];
	// nsacco 07/15/99
	int			timeZone[COBRAND_FETCH_SIZE];
	int			listingCurrency[COBRAND_FETCH_SIZE];
	int			localeId[COBRAND_FETCH_SIZE];	// petra

	if (IIS_Server_is_down()) return; //new outage code
	
	// Let's open the cursor
	OpenAndParse(&mpCDALoadSites,
				 SQL_GetSites);

	// Define
	Define(1, (int *) siteId);
	Define(2, (char *) names, sizeof(names[0]));
	Define(3, (char *) parsedString, sizeof(parsedString[0]));
	// nsacco 07/15/99
	Define(4, (int *) timeZone);
	Define(5, (int *) listingCurrency);
	Define(6, (int *) localeId);		// petra
	
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDALoadSites);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDALoadSites);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// nsacco 07/15/99
			pSite = new clsSite(names[i], siteId[i], parsedString[i], 
								timeZone[i], 
								localeId[i],		// petra
								listingCurrency[i]);
			pvSites->push_back(pSite);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDALoadSites);
	SetStatement(NULL);


	return;
}


// kakiyama 06/23/99 added parsed_string and order by site_id
// nsacco 05/25/99 added
static const char *SQL_GetAllMinimalSites =
 "select site_id, name, parsed_string from ebay_sites order by site_id";

// GetAllMinimalSites
// Loads all sites from the database into the clsSite *
// vector - only gets the ids and names.  Calls the clsSite
// default constructor so that the site partner vector doesn't
// get loaded.
//
void clsDatabaseOracle::GetAllMinimalSites(vector<clsSite *> *pvSites)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		names[COBRAND_FETCH_SIZE][256];
	int			siteId[COBRAND_FETCH_SIZE];
	char		parsedString[COBRAND_FETCH_SIZE][256];
	int			onIndex;

	if (IIS_Server_is_down()) return; //new outage code
	
	// Let's open the cursor
	// kakiyama 06/23/99 use SQL_GetAllMinimalSites
	OpenAndParse(&mpCDAGetAllMinimalSites,
				 SQL_GetAllMinimalSites);

	// Define
	Define(1, (int *) siteId);
	Define(2, (char *) names, sizeof(names[0]));
	Define(3, (char *) parsedString, sizeof(parsedString[0]));
	
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetAllMinimalSites);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	onIndex = -1;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetAllMinimalSites);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			if (onIndex < siteId[i])
			{
				do
				{
					pvSites->push_back((clsSite *) NULL);
					++onIndex;
				} while (onIndex < siteId[i]);
			}

			// Call the default constructor so we don't load up all
			// the partners for the site.  We don't need them...
			(*pvSites)[siteId[i]] = new clsSite;

			// Now set the id and name explicitly.
			(*pvSites)[siteId[i]]->SetId(siteId[i]);
			(*pvSites)[siteId[i]]->SetName(names[i]);
			(*pvSites)[siteId[i]]->SetParsedString(parsedString[i]);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAGetAllMinimalSites);
	SetStatement(NULL);


	return;
}


// Retrieve all headers and footers for specific site from database
//
// nsacco 07/09/99 new tables
static const char *SQL_GetSiteHeadersAndFooters =
"select "
"	a.partner_id, "
"	a.page_type, "
"	a.secondary_page_type, "
"	a.header_type, "
"	a.header_unq_id, "
"	b.header_length "
"	from ebay_headers a, "
"	ebay_headers_text b	"
"	where a.site_id=:site_id and "
"	a.header_unq_id=b.header_unq_id ";

void clsDatabaseOracle::GetSiteHeadersAndFooters(int SiteId,
												 vector<clsHeader*>* pvHeaders, 
												 vector<clsFooter*>* pvFooters)
{
	int partnerid[COBRAND_FETCH_SIZE];
	int pageType[COBRAND_FETCH_SIZE];
	int secondaryPageType[COBRAND_FETCH_SIZE];
	int headerType[COBRAND_FETCH_SIZE];
	int headerUniqueId[COBRAND_FETCH_SIZE];
	int headerLengths[COBRAND_FETCH_SIZE];

	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	int			HeaderInitIndex;
	int			FooterInitIndex;

	vector<int> vHeaderIds;
	vector<int> vFooterIds;
	vector<int> vHeaderLengths;
	vector<int> vFooterLengths;

	clsHeader*	pHeader;
	clsFooter*	pFooter;
	char*		pText;

	// return if we don't have two valid vector pointers
	if (pvHeaders == NULL || pvFooters == NULL)
		return;

	HeaderInitIndex = pvHeaders->size();
	FooterInitIndex = pvFooters->size();

	OpenAndParse(&mpCDAGetSiteHeadersAndFooters, SQL_GetSiteHeadersAndFooters);

	Bind(":site_id", &SiteId);

	// Define
	Define(1, (int *) partnerid);
	Define(2, (int *) pageType);
	Define(3, (int *) secondaryPageType);
	Define(4, (int *) headerType);
	Define(5, (int *) headerUniqueId);
	Define(6, (int *) headerLengths);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetSiteHeadersAndFooters);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetSiteHeadersAndFooters);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{
			if (headerType[i] == 1)
			{
				// save the header information
				pHeader = new clsHeader(SiteId, partnerid[i], pageType[i], secondaryPageType[i], NULL, NULL);
				pvHeaders->push_back(pHeader);
				vHeaderIds.push_back(headerUniqueId[i]);
				vHeaderLengths.push_back(headerLengths[i]);
			}
			if (headerType[i] == 0)
			{
				// save footer information
				pFooter = new clsFooter(SiteId, partnerid[i], pageType[i], secondaryPageType[i], NULL, NULL);
				pvFooters->push_back(pFooter);
				vFooterIds.push_back(headerUniqueId[i]);
				vFooterLengths.push_back(headerLengths[i]);
			}
		}
	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAGetSiteHeadersAndFooters);
	SetStatement(NULL);

	// Now get the descriptions, one of each of them.

	// get heaader description first
	i = vHeaderIds.size();
	while (i--)
	{
		if (vHeaderLengths[i] <= 0)
			continue;

		OpenAndParse(&mpCDAGetPartnerHeaderText, SQL_GetPartnerHeaderText);

		pText = new char [vHeaderLengths[i] + 1];

		Bind(":unique_id", &(vHeaderIds[i]));

		DefineLongRaw(1, (unsigned char *) pText, vHeaderLengths[i]);

		ExecuteAndFetch();

		*(pText + vHeaderLengths[i]) = '\0';
		(*pvHeaders)[HeaderInitIndex + i]->SetText(pText);

		Close(&mpCDAGetPartnerHeaderText);
		SetStatement(NULL);
	}

	// get footer
	i = vFooterIds.size();
	while (i--)
	{
		if (vFooterLengths[i] <= 0)
			continue;

		OpenAndParse(&mpCDAGetPartnerHeaderText, SQL_GetPartnerHeaderText);

		pText = new char [vFooterLengths[i] + 1];

		Bind(":unique_id", &(vFooterIds[i]));

		DefineLongRaw(1, (unsigned char *) pText, vFooterLengths[i]);

		ExecuteAndFetch();

		*(pText + vFooterLengths[i]) = '\0';
		(*pvFooters)[FooterInitIndex + i]->SetText(pText);

		Close(&mpCDAGetPartnerHeaderText);
		SetStatement(NULL);
	}

	vHeaderIds.erase(vHeaderIds.begin(), vHeaderIds.end());
	vFooterIds.erase(vFooterIds.begin(), vFooterIds.end());
	vHeaderLengths.erase(vHeaderLengths.begin(), vHeaderLengths.end());
	vFooterLengths.erase(vFooterLengths.begin(), vFooterLengths.end());

	// just to be safe
	Close(&mpCDAGetPartnerHeaderText);
	SetStatement(NULL);
	return;

}


// Retrieve all headers and footers for specific site and partner from database
//
// nsacco 07/09/99 new tables
static const char *SQL_GetSitePartnerHeadersAndFooters =
"select "
"	a.page_type, "
"	a.secondary_page_type, "
"	a.header_type, "
"	a.header_unq_id, "
"	b.header_length "
"	from ebay_headers a, "
"	ebay_headers_text b	"
"	where a.site_id = :site_id and "
"	a.partner_id = :partner_id and "
"	a.header_unq_id = b.header_unq_id ";

void clsDatabaseOracle::GetSitePartnerHeadersAndFooters(int siteId,
													    int partnerId,
													    vector<clsHeader*>* pvHeaders, 
													    vector<clsFooter*>* pvFooters)
{
	int pageType[COBRAND_FETCH_SIZE];
	int secondaryPageType[COBRAND_FETCH_SIZE];
	int headerType[COBRAND_FETCH_SIZE];
	int headerUniqueId[COBRAND_FETCH_SIZE];
	int headerLengths[COBRAND_FETCH_SIZE];

	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	int			HeaderInitIndex;
	int			FooterInitIndex;

	vector<int> vHeaderIds;
	vector<int> vFooterIds;
	vector<int> vHeaderLengths;
	vector<int> vFooterLengths;

	clsHeader*	pHeader;
	clsFooter*	pFooter;
	char*		pText;

	// Return if we don't have two valid vector pointers
	if (pvHeaders == NULL || pvFooters == NULL)
		return;

	HeaderInitIndex = pvHeaders->size();
	FooterInitIndex = pvFooters->size();

	OpenAndParse(&mpCDAGetSitePartnerHeadersAndFooters, SQL_GetSitePartnerHeadersAndFooters);

	// Define
	Define(1, (int *) pageType);
	Define(2, (int *) secondaryPageType);
	Define(3, (int *) headerType);
	Define(4, (int *) headerUniqueId);
	Define(5, (int *) headerLengths);

	Bind(":site_id", &siteId);
	Bind(":partner_id", &partnerId);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetSitePartnerHeadersAndFooters);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetSitePartnerHeadersAndFooters);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			if (headerType[i] == 1)
			{
				// save the header information
				pHeader = new clsHeader(siteId, partnerId, pageType[i], secondaryPageType[i], NULL, NULL);
				pvHeaders->push_back(pHeader);
				vHeaderIds.push_back(headerUniqueId[i]);
				vHeaderLengths.push_back(headerLengths[i]);
			}
			if (headerType[i] == 0)
			{
				// save footer information
				pFooter = new clsFooter(siteId, partnerId, pageType[i], secondaryPageType[i], NULL, NULL);
				pvFooters->push_back(pFooter);
				vFooterIds.push_back(headerUniqueId[i]);
				vFooterLengths.push_back(headerLengths[i]);
			}
		}
	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAGetSitePartnerHeadersAndFooters);
	SetStatement(NULL);

	// Now get the descriptions, one of each of them.

	// get header description first
	i = vHeaderIds.size();
	while (i--)
	{
		if (vHeaderLengths[i] <= 0)
			continue;

		OpenAndParse(&mpCDAGetPartnerHeaderText, SQL_GetPartnerHeaderText);

		pText = new char [vHeaderLengths[i] + 1];

		Bind(":unique_id", &(vHeaderIds[i]));

		DefineLongRaw(1, (unsigned char *) pText, vHeaderLengths[i]);

		ExecuteAndFetch();

		*(pText + vHeaderLengths[i]) = '\0';
		(*pvHeaders)[HeaderInitIndex + i]->SetText(pText);

		Close(&mpCDAGetPartnerHeaderText);
		SetStatement(NULL);
	}

	// get footer
	i = vFooterIds.size();
	while (i--)
	{
		if (vFooterLengths[i] <= 0)
			continue;

		OpenAndParse(&mpCDAGetPartnerHeaderText, SQL_GetPartnerHeaderText);

		pText = new char [vFooterLengths[i] + 1];

		Bind(":unique_id", &(vFooterIds[i]));

		DefineLongRaw(1, (unsigned char *) pText, vFooterLengths[i]);

		ExecuteAndFetch();

		*(pText + vFooterLengths[i]) = '\0';
		(*pvFooters)[HeaderInitIndex + i]->SetText(pText);

		Close(&mpCDAGetPartnerHeaderText);
		SetStatement(NULL);
	}

	vHeaderIds.erase(vHeaderIds.begin(), vHeaderIds.end());
	vFooterIds.erase(vFooterIds.begin(), vFooterIds.end());
	vHeaderLengths.erase(vHeaderLengths.begin(), vHeaderLengths.end());
	vFooterLengths.erase(vFooterLengths.begin(), vFooterLengths.end());

	// just to be safe
	Close(&mpCDAGetPartnerHeaderText);
	SetStatement(NULL);
	return;

}

// nsacco 07/15/99 added timezone_id and default_listing_currency
// kakiyama 06/23/99 added parsed_string
// nsacco 05/25/99 added 
// petra 08/02/99 added localeId
static const char *SQL_GetSiteByName =
"select site_id, parsed_string, timezone_id, default_listing_currency, locale_id \
from ebay_sites where name=:siteName ";

// LoadSite
// Loads a sites from the database into the clsSite *
//
void clsDatabaseOracle::LoadSite(const char *pName, clsSite **pSite)
{
	int			siteId;
	char		parsedString[256];
	// nsacco 07/15/99
	int			timeZone;
	int			listingCurrency;
	int			localeId;	// petra

	if (IIS_Server_is_down()) return; //new outage code
	
	// Let's open the cursor
	OpenAndParse(&mpCDALoadSite,
				 SQL_GetSiteByName);

	// Bind
	Bind(":siteName", (char *) pName);

	// Define
	Define(1, &siteId);
	Define(2, (char *) parsedString, sizeof(parsedString));
	// nsacco 07/15/99
	Define(3, &timeZone);
	Define(4, &listingCurrency);
	Define(5, &localeId);		// petra

	
	ExecuteAndFetch();
	
	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDALoadSite);
		SetStatement(NULL);
		return;
	}

	
	// Construct the site object
	// nsacco 07/15/99
	*pSite = new clsSite((char *)pName, siteId, (char *)parsedString, timeZone, 
						localeId,		// petra
						listingCurrency);

	// Close 
	Close(&mpCDALoadSite);
	SetStatement(NULL);


	return;
}

// nsacco 07/15/99 added timezone_id and default_listing_currency
// kakiyama 06/23/99 added parsed_string
// nsacco 05/25/99 added \
// petra 08/02/99 added localeId
static const char *SQL_GetSiteById =
"select name, parsed_string, timezone_id, default_listing_currency, locale_id \
from ebay_sites where site_id=:site_id";

// LoadSite
// Loads a site from the database into the clsSite *
//
void clsDatabaseOracle::LoadSite(int siteId, clsSite **pSite)
{
	char		name[256];
	char		parsedString[256];
	// nsacco 07/15/99
	int			timeZone;
	int			listingCurrency;
	int			localeId;	// petra
	
	if (IIS_Server_is_down()) return; //new outage code
	
	// Let's open the cursor
	OpenAndParse(&mpCDALoadSite,
				 SQL_GetSiteById);

	// Bind
	Bind(":site_id", &siteId);
	
	// Define
	Define(1, (char *) name, sizeof(name));
	Define(2, (char *) parsedString, sizeof(parsedString));
	// nsacco 07/15/99
	Define(3, &timeZone);
	Define(4, &listingCurrency);
	Define(5, &localeId);		// petra
	
	

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDALoadSite);
		SetStatement(NULL);
		return;
	}

	// construct site
	// nsacco 07/15/99
	*pSite = new clsSite((char *)name, siteId, (char *)parsedString, timeZone,
						 localeId,		// petra
						 listingCurrency);

	// Close 
	Close(&mpCDALoadSite);
	SetStatement(NULL);


	return;
}

// kakiyama 06/23/99 added
static const char *SQL_GetForeignSites =
 "select site_id, name, parsed_string from ebay_sites \
  where parsed_string is not null order by site_id";


// GetForeignSites
// Loads foreign sites from the database into the clsSite *
// vector - gets site id, name, and parsed_string.  Calls the clsSite
// default constructor so that the site partner vector doesn't
// get loaded.
//
void clsDatabaseOracle::GetForeignSites(vector<clsSite *> *pvSites)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		names[COBRAND_FETCH_SIZE][256];
	int			siteId[COBRAND_FETCH_SIZE];
	char		parsedString[COBRAND_FETCH_SIZE][256];

	if (IIS_Server_is_down()) return; //new outage code


	  OpenAndParse(&mpCDAGetForeignSites,
				SQL_GetForeignSites);
	// Define
	Define(1, (int *) siteId);
	Define(2, (char *) names, sizeof(names[0]));
	Define(3, (char *) parsedString, sizeof(parsedString[0]));
	
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetForeignSites);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, COBRAND_FETCH_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetForeignSites);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{

			// Call the default constructor so we don't load up all
			// the partners for the site.  We don't need them...
			pvSites->push_back((clsSite *) NULL);
			(*pvSites)[i] = new clsSite;

			// Now set the id and name explicitly.
			(*pvSites)[i]->SetId(siteId[i]);
			(*pvSites)[i]->SetName(names[i]);
			(*pvSites)[i]->SetParsedString(parsedString[i]);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAGetForeignSites);
	SetStatement(NULL);


	return;
}

// petra 08/09/99 added because it's handy for EndOfAuctionNotice
// (don't know whether the 'distinct' clause is really necessary - is site_id unique?)
static const char *SQL_GetNumberOfSites =
"select count(*) from ebay_sites where site_id in (select distinct site_id from ebay_sites)";

// GetNumberOfSites
//
int clsDatabaseOracle::GetNumberOfSites()
{
	int numSites;

	OpenAndParse(&mpCDAOneShot, SQL_GetNumberOfSites);

	Define(1, &numSites);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return numSites;
}
