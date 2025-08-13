/*	$Id: clsDatabaseOracleAds.cpp,v 1.1.26.1 1999/08/01 03:02:21 barry Exp $	*/
//
//	File:	clsDatabaseOracleAd.cc
//
//	Class:	clsDatabaseOracle
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 10/08/97 wen	- Created
//				- 07/09/99 nsacco - new table for ads

#include "eBayKernel.h"
#include "clsPartnerAd.h"

#define ORA_AD_ARRAYSIZE	100

//=======================================================================//
//                         Cobrand Ad Descriptions                       //
//=======================================================================//

//
// AddCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_AddCobrandAdDesc =
"insert into ebay_ad_text "
"("
"	id, "
"	name, "
"	text_len, "
"	text "
")"
"values "
"("
"	:id, "
"	:name, "
"	:text_len, "
"	:text "
")";

bool clsDatabaseOracle::AddCobrandAdDesc(clsAd *pAd)
{
	int				id;
	unsigned int	textLen;

	if (pAd == NULL)
		return false;

	id = pAd->GetId();
	textLen = pAd->GetTextLen();
	
	OpenAndParse(&mpCDAAddCobrandAdDesc, SQL_AddCobrandAdDesc);

	Bind(":id", (int *)&id);
	Bind(":name", pAd->GetName());
	Bind(":text_len", (int *)&textLen);
	BindLongRaw(":text", 
				(unsigned char *)pAd->GetText(),
				textLen);

	Execute();
	Commit();

	Close(&mpCDAAddCobrandAdDesc);
	SetStatement(NULL);

	return true;
}


//
// GetCobrandAdDescTextLen
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdDescTextLenById =
"select		text_len "
"	from	ebay_ad_text "
"	where	id = :id";

int clsDatabaseOracle::GetCobrandAdDescTextLen(int id)
{
	int	textLen = 0;

	// Open and parse SQL statement
	OpenAndParse(&mpCDAGetCobrandAdDescTextLenById, SQL_GetCobrandAdDescTextLenById);

	// Bind input variables
	Bind(":id", &id);

	// Bind output variables
	Define(1, &textLen);

	// Do it
	ExecuteAndFetch();

	// Did we find it?
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetCobrandAdDescTextLenById);
		SetStatement(NULL);
		return 0;
	}

	// Clean up
	Close(&mpCDAGetCobrandAdDescTextLenById);
	SetStatement(NULL);

	return textLen;
}


//
// GetCobrandAdDescTextLen
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdDescTextLenByName =
"select		text_len "
"	from	ebay_ad_text "
"	where	name = :name";

int clsDatabaseOracle::GetCobrandAdDescTextLen(const char *pName)
{
	int	textLen = 0;

	if (pName == NULL)
		return 0;

	// Open and parse SQL statement
	OpenAndParse(&mpCDAGetCobrandAdDescTextLenByName, SQL_GetCobrandAdDescTextLenByName);

	// Bind input variables
	Bind(":name", (char *)pName);

	// Bind output variables
	Define(1, &textLen);

	// Do it
	ExecuteAndFetch();

	// Did we find it?
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetCobrandAdDescTextLenByName);
		SetStatement(NULL);
		return 0;
	}

	// Clean up
	Close(&mpCDAGetCobrandAdDescTextLenByName);
	SetStatement(NULL);

	return textLen;
}


//
// GetCobrandAdDescText
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdDescText =
"select		text "
"	from	ebay_ad_text "
"	where	id = :id";

char * clsDatabaseOracle::GetCobrandAdDescText(int id)
{
	int				textLen;
	unsigned char *	pText = NULL;

	textLen = GetCobrandAdDescTextLen(id);

	// Allocate memory for ad text
	pText = new unsigned char [textLen + 1];

	// Open and parse statement to get ad text
	OpenAndParse(&mpCDAGetCobrandAdDescText, SQL_GetCobrandAdDescText);

	// Bind input variables
	Bind(":id", &id);

	// Bind output variables
	DefineLongRaw(1, pText, textLen);

	// Do it
	ExecuteAndFetch();

	// Terminate the string
	*(pText + textLen) = '\0';

	// Clean Up
	Close(&mpCDAGetCobrandAdDescText);
	SetStatement(NULL);

	return (char *)pText;
}


//
// GetCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdDescById =
"select		name, "
"			text "
"	from	ebay_ad_text "
"	where	id = :id";

clsAd * clsDatabaseOracle::GetCobrandAdDesc(int id)
{
	char			cName[64];
	int				textLen;
	unsigned char *	pText = NULL;
	clsAd *			pAd = NULL;

	// Get length of text
	textLen = GetCobrandAdDescTextLen(id);

	// Allocate memory for ad text
	pText = new unsigned char[textLen + 1];

	// Now get the name and text
	OpenAndParse(&mpCDAGetCobrandAdDescById, SQL_GetCobrandAdDescById);

	// Bind that input variable
	Bind(":id", &id);

	// Bind the output
	Define(1, cName, sizeof(cName));
	DefineLongRaw(2, pText, textLen);

	// Let's get it.
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetCobrandAdDescById);
		SetStatement(NULL);		
	}
	else
	{
		pAd = new clsAd(id, (char *)cName, (char *)pText);

		// Destroy pText cuz the clsAd constructor made its own copy
		delete [] pText;
			
		Close(&mpCDAGetCobrandAdDescById);
		SetStatement(NULL);
	}

	return pAd;
}


//
// GetCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdDescByName =
"select		id, "
"			text "
"	from	ebay_ad_text "
"	where	name = :name";

clsAd * clsDatabaseOracle::GetCobrandAdDesc(const char *pName)
{
	int				id = 0;
	int				textLen = 0;
	unsigned char *	pText;
	clsAd *			pAd = NULL;

	if (pName == NULL)
		return NULL;

	// Get length of text
	textLen = GetCobrandAdDescTextLen(pName);

	// Allocate memory for ad text
	pText = new unsigned char[textLen + 1];

	// mpCDAGetCobrandAdDescByName

	// Now get the name and text
	OpenAndParse(&mpCDAGetCobrandAdDescByName, SQL_GetCobrandAdDescByName);

	// Bind that input variable
	Bind(":name", (char *)pName);

	// Bind the output
	Define(1, &id);
	DefineLongRaw(2, pText, textLen);

	// Let's get it.
	ExecuteAndFetch();

	*(pText + textLen) = '\0';

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetCobrandAdDescByName);
		SetStatement(NULL);		
	}
	else
	{
		pAd = new clsAd(id, pName, (char *)pText);

		// Destroy pText cuz the clsAd constructor made its own copy
		delete [] pText;
			
		Close(&mpCDAGetCobrandAdDescByName);
		SetStatement(NULL);
	}

	return pAd;
}


//
// LoadAllCobrandAdDescs
//
// nsacco 07/09/99 new table
static const char *SQL_LoadAllCobrandAdDescs =
"select		id, "
"			name, "
"			text_len, "
"			text "
"	from	ebay_ad_text "
"	where	text_len > 0 "
"	order by id asc";

// nsacco 07/09/99 new table
static const char *SQL_GetMaxCobrandAdDescTextLen =
"select MAX(text_len) "
"	from ebay_ad_text";

void clsDatabaseOracle::LoadAllCobrandAdDescs(AdVector *pvAds)
{
	int				id[ORA_AD_ARRAYSIZE];
	char			cName[ORA_AD_ARRAYSIZE][256];
	int				textLength[ORA_AD_ARRAYSIZE];

	int				maxTextLen = 0;
	int				nTextBufferLen = 0;

	unsigned char *	pText = NULL;
	unsigned char *	pOriginalText = NULL;
	clsAd *			pAd = NULL;

	int		rowsFetched;
	int		rc;
	int		i,n;

	// Get the max text length first...
	OpenAndParse(&mpCDAGetMaxCobrandAdDescTextLen, SQL_GetMaxCobrandAdDescTextLen);

	Define(1, &maxTextLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMaxCobrandAdDescTextLen);
		SetStatement(NULL);
		return;
	}

	// Calc buffer length, add 1 char for the NULL and then calc the total buffer size
	maxTextLen ++;
	nTextBufferLen = maxTextLen * ORA_AD_ARRAYSIZE;

	// Allocate memory for all the ad text
	pText = new unsigned char[nTextBufferLen];

	// Save text pointer for delete call
	pOriginalText = pText;

	// Close this puppy and go on...
	Close(&mpCDAGetMaxCobrandAdDescTextLen);
	SetStatement(NULL);

	// Open and parse the statement to load us up
	OpenAndParse(&mpCDALoadAllCobrandAdDescs, SQL_LoadAllCobrandAdDescs);

	// Bind the outputs
	Define(1, id);
	Define(2, (char *)cName, sizeof(cName[0]));
	Define(3, textLength);
	DefineLongRaw(4, pText, maxTextLen);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDALoadAllCobrandAdDescs, true);
		SetStatement(NULL);
		return;
	}

	// Array fetch
	rowsFetched = 0;
	do
	{
		// Clear ad text buffer
		memset(pText, 0, nTextBufferLen);

		rc = ofen((struct cda_def *)mpCDACurrent, ORA_AD_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDALoadAllCobrandAdDescs, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_AD_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pAd = new clsAd(id[i], cName[i], (char *)pText);

			pvAds->push_back(pAd);

			// Increment pText pointer to next buffer spot
			pText += maxTextLen;
		}

		// Reset pText for next fetch
		pText = pOriginalText;

	} while (!CheckForNoRowsFound());
	
	delete [] pText;

	Close(&mpCDALoadAllCobrandAdDescs);
	SetStatement(NULL);

	return;
}


//
// UpdateCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_UpdateCobrandAdDescById =
"update ebay_ad_text "
"set	name = :name, "
"		text_len = :text_len, "
"		text = :text"
"where	id = :id";

bool clsDatabaseOracle::UpdateCobrandAdDesc(int id, clsAd *pAd)
{
	int		textLen;
	bool	updated = false;

	if (pAd == NULL)
		return false;

	textLen = pAd->GetTextLen();

	OpenAndParse(&mpCDAUpdateCobrandAdDescById, SQL_UpdateCobrandAdDescById);

	// Bind it, baby
	Bind(":id",	&id);
	Bind(":name", pAd->GetName());
	Bind(":text_len", &textLen);
	Bind(":text", pAd->GetText());

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAUpdateCobrandAdDescById);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAUpdateCobrandAdDescById);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}


//
// UpdateCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_UpdateCobrandAdDescByName =
"update ebay_ad_text "
"set	name = :name, "
"		text_len = :text_len, "
"		text = :text"
"where	name = :name";

bool clsDatabaseOracle::UpdateCobrandAdDesc(const char *pName, clsAd *pAd)
{
	int		textLen;
	bool	updated = false;

	if (pName == NULL || pAd == NULL)
		return false;

	textLen = pAd->GetTextLen();

	OpenAndParse(&mpCDAUpdateCobrandAdDescByName, SQL_UpdateCobrandAdDescByName);

	// Bind it, baby
	Bind(":name", pName);
	Bind(":text_len", &textLen);
	Bind(":text", pAd->GetText());

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAUpdateCobrandAdDescByName);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAUpdateCobrandAdDescByName);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}


//
// DeleteCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_DeleteCobrandAdDescById =
"delete from ebay_ad_text "
"where	id = :id";

bool clsDatabaseOracle::DeleteCobrandAdDesc(int id)
{
	OpenAndParse(&mpCDADeleteCobrandAdDescById, SQL_DeleteCobrandAdDescById);

	// do the bind
	Bind(":id", (int *)&id);

	// do it
	Execute();

	Commit();

	Close(&mpCDADeleteCobrandAdDescById);
	SetStatement(NULL);

	return true;
}


//
// DeleteCobrandAdDesc
//
// nsacco 07/09/99 new table
static const char *SQL_DeleteCobrandAdDescByName =
"delete from ebay_ad_text "
"where	name = :name";

bool clsDatabaseOracle::DeleteCobrandAdDesc(const char *pName)
{
	if (pName == NULL)
		return false;

	OpenAndParse(&mpCDADeleteCobrandAdDescByName, SQL_DeleteCobrandAdDescByName);

	// do the bind
	Bind(":name", (char *)pName);

	// do it
	Execute();

	Commit();

	Close(&mpCDADeleteCobrandAdDescByName);
	SetStatement(NULL);

	return true;
}


//
// GetNextCobrandAdDescId
//
// nsacco 07/09/99 new table
static const char *SQL_GetNextCobrandAdDescId = 
"select ebay_ad_text_sequence.nextval from dual";

int clsDatabaseOracle::GetNextCobrandAdDescId()
{
	int	nextId = 0;

	// Open and parse
	OpenAndParse(&mpCDAGetNextCobrandAdDescId, SQL_GetNextCobrandAdDescId);

	// Tell 'em what we want
	Define(1, (int *)&nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAGetNextCobrandAdDescId);
	SetStatement(NULL);

	return nextId;
}


//=======================================================================//
//                               Cobrand Ads                             //
//=======================================================================//

//
// AddCobrandAd
//
// nsacco 07/09/99 new table
static const char *SQL_AddCobrandAd =
"insert into ebay_ads "
"("
"	site_id, "
"	partner_id, "
"	ad_text_id, "
"	primary_page_type, "
"	secondary_page_type, "
"	context_value "
")"
"values "
"("
"	:site_id, "
"	:partner_id, "
"	:ad_text_id, "
"	:primary_page_type, "
"	:secondary_page_type, "
"	:context_value "
")";

void clsDatabaseOracle::AddCobrandAd(clsPartnerAd *pAd)
{
	int				siteId;
	int				partnerId;
	int				adId;
	PageTypeEnum	pageType1;
	PageTypeEnum	pageType2;
	int				contextValue;

	if (pAd == NULL)
		return;

	siteId = pAd->GetSiteId();
	partnerId = pAd->GetPartnerId();
	adId = pAd->GetId();
	pageType1 = pAd->GetPageType();
	pageType2 = pAd->GetSecondaryPageType();
	contextValue = pAd->GetContextSensitiveValue();
	
	OpenAndParse(&mpCDAAddCobrandAd, SQL_AddCobrandAd);

	Bind(":site_id", &siteId);
	Bind(":partner_id", &partnerId);
	Bind(":ad_text_id", &adId);
	Bind(":primary_pagetype", (int *)&pageType1);
	Bind(":secondary_pagetype", (int *)&pageType2);
	Bind(":context_value", &contextValue);

	Execute();
	Commit();

	Close(&mpCDAAddCobrandAd);
	SetStatement(NULL);

	return;
}


//
// LoadAllCobrandAds
//
// nsacco 07/09/99 new table
static const char *SQL_LoadAllCobrandAds =
"select		ads.site_id, "
"			ads.partner_id, "
"			ads.primary_page_type, "
"			ads.secondary_page_type, "
"			ads.context_value, "
"			text.name, "
"			text.text_len, "
"			text.text "
"	from	ebay_ads ads, "
"			ebay_ad_text text "
"			where ads.ad_text_id = text.id (+)";

void clsDatabaseOracle::LoadAllCobrandAds(PartnerAdVector *pvAds)
{
	int				adId[ORA_AD_ARRAYSIZE];
	char			cName[ORA_AD_ARRAYSIZE][256];
	int				textLen[ORA_AD_ARRAYSIZE];
	int				siteId[ORA_AD_ARRAYSIZE];
	int				partnerId[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType1[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType2[ORA_AD_ARRAYSIZE];
	int				contextValue[ORA_AD_ARRAYSIZE];
	sb2				contextValueInd[ORA_AD_ARRAYSIZE];

	int				maxTextLen = 0;
	int				nTextBufferLen = 0;

	unsigned char *	pText = NULL;
	unsigned char *	pOriginalText = NULL;
	char *			pName = NULL;
	clsPartnerAd *	pAd = NULL;

	int		rowsFetched;
	int		rc;
	int		i,n;

	// Get the max text length first...
	OpenAndParse(&mpCDAGetMaxCobrandAdDescTextLen, SQL_GetMaxCobrandAdDescTextLen);

	Define(1, &maxTextLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMaxCobrandAdDescTextLen);
		SetStatement(NULL);
		return;
	}

	// Calc buffer length, add 1 char for the NULL and then calc the total buffer size
	maxTextLen ++;
	nTextBufferLen = maxTextLen * ORA_AD_ARRAYSIZE;

	// Allocate memory for all the ad text
	pText = new unsigned char[nTextBufferLen];

	// Save text pointer for delete call
	pOriginalText = pText;

	// Close this puppy and go on...
	Close(&mpCDAGetMaxCobrandAdDescTextLen);
	SetStatement(NULL);

	// Open and parse the statement
	OpenAndParse(&mpCDALoadAllCobrandAds, SQL_LoadAllCobrandAds);

	// Bind the outputs
	Define(1, siteId);
	Define(2, partnerId);
	Define(3, (int *)pageType1);
	Define(4, (int *)pageType2);
	Define(5, contextValue, contextValueInd);
	Define(6, (char *)cName, sizeof(cName[0]));
	Define(7, textLen);
	DefineLongRaw(8, pText, maxTextLen);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDALoadAllCobrandAds, true);
		SetStatement(NULL);
		return;
	}

	rowsFetched = 0;
	do
	{
		// Clear ad text buffer
		memset(pText, 0, nTextBufferLen);

		rc = ofen((struct cda_def *)mpCDACurrent, ORA_AD_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDALoadAllCobrandAds, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_AD_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pAd = new clsPartnerAd(adId[i],
								   cName[i],
								   siteId[i], 
								   partnerId[i], 
								   pageType1[i], 
								   pageType2[i], 
								   contextValue[i], 
								   (char *)pText);
			pvAds->push_back(pAd);

			// Increment pText pointer to next buffer spot
			pText += maxTextLen;
		}

		// Reset pText for next fetch
		pText = pOriginalText;

	} while (!CheckForNoRowsFound());
	
	delete [] pText;

	Close(&mpCDALoadAllCobrandAds);
	SetStatement(NULL);

	return;
}


//
// GetCobrandAdsByAdId
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdsById =
"select		ads.site_id, "
"			ads.partner_id, "
"			ads.page_type, "
"			ads.secondary_page_type, "
"			ads.context_sensitive_value "
"	from	ebay_ads ads "
"	where	ads.ad_text_id = :ad_id";

void clsDatabaseOracle::GetCobrandAdsById(int adId, vector<clsPartnerAd *> *pvAds)
{
	int				siteId[ORA_AD_ARRAYSIZE];
	int				partnerId[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType1[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType2[ORA_AD_ARRAYSIZE];
	int				contextValue[ORA_AD_ARRAYSIZE];
	sb2				contextValueInd[ORA_AD_ARRAYSIZE];

	clsPartnerAd *	pAd = NULL;

	int		rowsFetched;
	int		rc;
	int		i,n;

	// Open and parse the statement
	OpenAndParse(&mpCDAGetCobrandAdsById, SQL_GetCobrandAdsById);

	// Bind the input variable
	Bind(":ad_id", &adId);

	// Bind the outputs
	Define(1, (int *)siteId);
	Define(2, (int *)partnerId);
	Define(3, (int *)pageType1);
	Define(4, (int *)pageType2);
	Define(5, (int *)contextValue, contextValueInd);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCobrandAdsById, true);
		SetStatement(NULL);
		return;
	}

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_AD_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCobrandAdsById, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_AD_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if (contextValueInd[i] == -1)
				contextValue[i] = -1;

			pAd = new clsPartnerAd(adId,
								   NULL,
								   siteId[i], 
								   partnerId[i], 
								   pageType1[i], 
								   pageType2[i], 
								   contextValue[i], 
								   NULL);

			pvAds->push_back(pAd);
		}
	} while (!CheckForNoRowsFound());
	
	Close(&mpCDAGetCobrandAdsById);
	SetStatement(NULL);

	return;
}


//
// GetCobrandAdsBySite
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdsBySite =
"select		text.name, "
"			ads.partner_id, "
"			ads.page_type, "
"			ads.secondary_page_type, "
"			ads.context_value, "
"			ads.ad_text_id, "
"			text.text_len, "
"			text.text "
"	from	ebay_ads ads, "
"			ebay_ad_text text "
"	where	ads.site_id = :site_id "
"	and		ads.ad_text_id = text.id (+)";

void clsDatabaseOracle::GetCobrandAdsBySite(int siteId, vector<clsPartnerAd *> *pvAds)
{
	char			cName[ORA_AD_ARRAYSIZE][256];
	int				partnerId[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType1[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType2[ORA_AD_ARRAYSIZE];
	int				contextValue[ORA_AD_ARRAYSIZE];
	sb2				contextValueInd[ORA_AD_ARRAYSIZE];
	int				textId[ORA_AD_ARRAYSIZE];
	int				textLength[ORA_AD_ARRAYSIZE];

	int				maxTextLen = 0;
	int				nTextBufferLen = 0;

	unsigned char *	pText = NULL;
	unsigned char *	pOriginalText = NULL;
	clsPartnerAd *	pAd = NULL;

	int		rowsFetched;
	int		rc;
	int		i,n;

	// Get the max text length first...
	OpenAndParse(&mpCDAGetMaxCobrandAdDescTextLen, SQL_GetMaxCobrandAdDescTextLen);

	Define(1, &maxTextLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMaxCobrandAdDescTextLen);
		SetStatement(NULL);
		return;
	}

	// Calc buffer length, add 1 char for the NULL and then calc the total buffer size
	maxTextLen ++;
	nTextBufferLen = maxTextLen * ORA_AD_ARRAYSIZE;

	// Allocate memory for all the ad text
	pText = new unsigned char[nTextBufferLen];

	// Save text pointer for delete call
	pOriginalText = pText;

	// Close this puppy and go on...
	Close(&mpCDAGetMaxCobrandAdDescTextLen);
	SetStatement(NULL);

	// Open and parse the statement
	OpenAndParse(&mpCDAGetCobrandAdsBySite, SQL_GetCobrandAdsBySite);

	// Bind the input variable
	Bind(":site_id", &siteId);

	// Bind the outputs
	Define(1, (char *)cName, sizeof(cName[0]));
	Define(2, (int *)partnerId);
	Define(3, (int *)pageType1);
	Define(4, (int *)pageType2);
	Define(5, (int *)contextValue, contextValueInd);
	Define(6, textId);
	Define(7, textLength);
	DefineLongRaw(8, pText, maxTextLen);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCobrandAdsBySite, true);
		SetStatement(NULL);
		return;
	}

	rowsFetched = 0;
	do
	{
		// Clear ad text buffer
		memset(pText, 0, nTextBufferLen);

		rc = ofen((struct cda_def *)mpCDACurrent, ORA_AD_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCobrandAdsBySite, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_AD_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if (contextValueInd[i] == -1)
				contextValue[i] = -1;

			pAd = new clsPartnerAd(textId[i],
								   cName[i],
								   siteId, 
								   partnerId[i], 
								   pageType1[i], 
								   pageType2[i], 
								   contextValue[i], 
								   (char *)pText);

			pvAds->push_back(pAd);

			// Increment pText pointer to next buffer spot
			pText += maxTextLen;
		}

		// Reset pText for next fetch
		pText = pOriginalText;

	} while (!CheckForNoRowsFound());
	
	delete [] pText;

	Close(&mpCDAGetCobrandAdsBySite);
	SetStatement(NULL);

	return;
}


//
// GetCobrandAdsBySiteAndPartner
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdsBySiteAndPartner =
"select		text.name, "
"			ads.primary_page_type, "
"			ads.secondary_page_type, "
"			ads.context_value, "
"			ads.ad_text_id, "
"			text.text_len, "
"			text.text "
"	from	ebay_ads ads, "
"			ebay_ad_text text "
"	where	ads.site_id = :site_id "
"	and		ads.partner_id = :partner_id "
"	and		ads.ad_text_id = text.id (+)";

void clsDatabaseOracle::GetCobrandAdsBySiteAndPartner(int siteId, 
													  int partnerId,
													  vector<clsPartnerAd *> *pvAds)
{
	char			cName[ORA_AD_ARRAYSIZE][256];
	PageTypeEnum	pageType1[ORA_AD_ARRAYSIZE];
	PageTypeEnum	pageType2[ORA_AD_ARRAYSIZE];
	int				contextValue[ORA_AD_ARRAYSIZE];
	sb2				contextValueInd[ORA_AD_ARRAYSIZE];
	int				textId[ORA_AD_ARRAYSIZE];
	int				textLength[ORA_AD_ARRAYSIZE];

	int				maxTextLen = 0;
	int				nTextBufferLen = 0;

	unsigned char *	pText = NULL;
	unsigned char *	pOriginalText = NULL;
	char *			pName = NULL;
	clsPartnerAd *	pAd = NULL;

	int		rowsFetched;
	int		rc;
	int		i,n;

	// Get the max text length first...
	OpenAndParse(&mpCDAGetMaxCobrandAdDescTextLen, SQL_GetMaxCobrandAdDescTextLen);

	Define(1, &maxTextLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMaxCobrandAdDescTextLen);
		SetStatement(NULL);
		return;
	}

	// Calc buffer length, add 1 char for the NULL and then calc the total buffer size
	maxTextLen ++;
	nTextBufferLen = maxTextLen * ORA_AD_ARRAYSIZE;

	// Allocate memory for all the ad text
	pText = new unsigned char[nTextBufferLen];

	// Save text pointer for delete call
	pOriginalText = pText;

	// Close this puppy and go on...
	Close(&mpCDAGetMaxCobrandAdDescTextLen);
	SetStatement(NULL);

	// Open and parse the statement
	OpenAndParse(&mpCDAGetCobrandAdsBySiteAndPartner, SQL_GetCobrandAdsBySiteAndPartner);

	// Bind the input variable
	Bind(":site_id", &siteId);
	Bind(":partner_id", &partnerId);

	// Bind the outputs
	Define(1, (char *)cName, sizeof(cName[0]));
	Define(2, (int *)pageType1);
	Define(3, (int *)pageType2);
	Define(4, (int *)contextValue, contextValueInd);
	Define(5, textId);
	Define(6, textLength);
	DefineLongRaw(7, pText, maxTextLen);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCobrandAdsBySiteAndPartner, true);
		SetStatement(NULL);
		return;
	}

	rowsFetched = 0;
	do
	{
		// Clear ad text buffer
		memset(pText, 0, nTextBufferLen);

		rc = ofen((struct cda_def *)mpCDACurrent, ORA_AD_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCobrandAdsBySiteAndPartner, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_AD_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if (contextValueInd[i] == -1)
				contextValue[i] = -1;

			pAd = new clsPartnerAd(textId[i],
								   cName[i],
								   siteId, 
								   partnerId, 
								   pageType1[i], 
								   pageType2[i], 
								   contextValue[i], 
								   (char *)pText);

			pvAds->push_back(pAd);

			// Increment pText pointer to next buffer spot
			pText += maxTextLen;
		}

		// Reset pText for next fetch
		pText = pOriginalText;

	} while (!CheckForNoRowsFound());
	
	delete [] pText;

	Close(&mpCDAGetCobrandAdsBySiteAndPartner);
	SetStatement(NULL);

	return;
}

//
// GetCobrandAdsByPage
//
// nsacco 07/09/99 new table
static const char *SQL_GetCobrandAdsByPage =
"select		ads.ad_text_id, "
"			text.name, "
"			ads.context_value, "
"			text.text_len, "
"			text.text "
"	from	ebay_ads ads, "
"			ebay_ad_text text "
"	where	ads.site_id = :site_id "
"	and		ads.partner_id = :partner_id "
"	and		ads.primary_page_type = :page_type_1 "
"	and		ads.secondary_page_type = :page_type_2 "
"	and		ads.ad_text_id = text.id (+)";

void clsDatabaseOracle::GetCobrandAdsByPage(PartnerAdVector *pvAds,
											int siteId, 
											int partnerId,
											PageTypeEnum pageType1,
											PageTypeEnum pageType2)
{
	int				textId[ORA_AD_ARRAYSIZE];
	int				textLength[ORA_AD_ARRAYSIZE];
	char			cAdName[ORA_AD_ARRAYSIZE][256];
	int				contextValue[ORA_AD_ARRAYSIZE];
	sb2				contextValueInd[ORA_AD_ARRAYSIZE];

	sb2				contextValueNull = -1;

	int				maxTextLen = 0;
	int				nTextBufferLen = 0;

	unsigned char *	pText = NULL;
	unsigned char *	pOriginalText = NULL;
	char *			pName = NULL;
	clsPartnerAd *	pAd = NULL;

	int		rowsFetched;
	int		rc;
	int		i,n;

	bool	done = false;

	// XXX do we really want to do this???
	if (pageType1 == PageTypeUnknown && pageType2 == PageTypeUnknown)
	{
		GetCobrandAdsBySiteAndPartner(siteId, partnerId, pvAds);
		return;
	}

	// Get the max text length first...
	OpenAndParse(&mpCDAGetMaxCobrandAdDescTextLen, SQL_GetMaxCobrandAdDescTextLen);

	Define(1, &maxTextLen);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetMaxCobrandAdDescTextLen);
		SetStatement(NULL);
		return;
	}

	// Calc buffer length, add 1 char for the NULL and then calc the total buffer size
	maxTextLen ++;
	nTextBufferLen = maxTextLen * ORA_AD_ARRAYSIZE;

	// Allocate memory for all the ad text
	pText = new unsigned char[nTextBufferLen];

	// Save text pointer for delete call
	pOriginalText = pText;

	// Close this puppy and go on...
	Close(&mpCDAGetMaxCobrandAdDescTextLen);
	SetStatement(NULL);

	// Open and parse the statement
	OpenAndParse(&mpCDAGetCobrandAdsByPage, SQL_GetCobrandAdsByPage);

	// Bind the input variable
	Bind(":site_id", &siteId);
	Bind(":partner_id", &partnerId);
	Bind(":page_type_1", (int *)&pageType1);
	Bind(":page_type_2", (int *)&pageType2);

	// Bind the outputs
	Define(1, textId);
	Define(2, (char *)cAdName, sizeof(cAdName[0]));
	Define(3, (int *)contextValue, contextValueInd);
	Define(4, textLength);
	DefineLongRaw(5, pText, maxTextLen);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCobrandAdsByPage, true);
		SetStatement(NULL);
		return;
	}

	// Array fetch
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_AD_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCobrandAdsByPage, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_AD_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		if (CheckForNoRowsFound())
		{
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCobrandAdsByPage, true);
			SetStatement(NULL);
			done = true;
			break;
		}

		for (i=0; i < n; i++)
		{
			if (contextValueInd[i] == -1)
				contextValue[i] = -1;

			pAd = new clsPartnerAd(textId[i],
								   cAdName[i],
								   siteId, 
								   partnerId, 
								   pageType1, 
								   pageType2, 
								   contextValue[i], 
								   (char *)pText);

			pvAds->push_back(pAd);

			// Increment pText pointer to next buffer spot
			pText += maxTextLen;
		}

		// Reset pText for next fetch
		pText = pOriginalText;

	} while (!done);
	
	delete [] pText;

	Close(&mpCDAGetCobrandAdsByPage);
	SetStatement(NULL);

	return;
}


//
// UpdateCobrandAd
//
// nsacco 07/09/99 new table
static const char *SQL_UpdateCobrandAd =
"update ebay_ads "
"set	site_id = :site_id, "
"		partner_id = :partner_id, "
"		page_type = :pagetype1, "
"		secondary_page_type = :pagetype2, "
"		context_value = :context_value "
"where	ad_text_id = :id";

bool clsDatabaseOracle::UpdateCobrandAd(clsPartnerAd *pAd)
{
	bool			updated = false;
	int				siteId;
	int				partnerId;
	PageTypeEnum	pageType1;
	PageTypeEnum	pageType2;
	int				value = -1;

	if (pAd == NULL)
		return false;

	siteId = pAd->GetSiteId();
	partnerId = pAd->GetPartnerId();
	pageType1 = pAd->GetPageType();
	pageType2 = pAd->GetSecondaryPageType();
	value = pAd->GetContextSensitiveValue();

	OpenAndParse(&mpCDAUpdateCobrandAd, SQL_UpdateCobrandAd);

	// Bind it, baby
	Bind(":site_id",	&siteId);
	Bind(":partner_id",	&partnerId);
	Bind(":pagetype1", (int *)&pageType1);
	Bind(":pagetype2", (int *)&pageType2);
	Bind(":context_value", &value);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAUpdateCobrandAd);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAUpdateCobrandAd);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}


//
// DeleteCobrandAd
//
// nsacco 07/09/99 new table
static const char *SQL_DeleteCobrandAd =
"delete from ebay_ads "
"where	ad_text_id = :ad_id, "
"		site_id = :site_id, "
"		partner_id = :partner_id, "
"		page_type = :pagetype1, "
"		secondary_page_type = :pagetype2, "
"		context_value = :context_value";

bool clsDatabaseOracle::DeleteCobrandAd(clsPartnerAd *pAd)
{
	bool			updated = false;
	int				adId;
	int				siteId;
	int				partnerId;
	PageTypeEnum	pageType1;
	PageTypeEnum	pageType2;
	int				value = -1;

	if (pAd == NULL)
		return false;

	adId = pAd->GetId();
	siteId = pAd->GetSiteId();
	partnerId = pAd->GetPartnerId();
	pageType1 = pAd->GetPageType();
	pageType2 = pAd->GetSecondaryPageType();
	value = pAd->GetContextSensitiveValue();

	OpenAndParse(&mpCDADeleteCobrandAd, SQL_DeleteCobrandAd);

	// Bind it, baby
	Bind(":ad_text_id",	&adId);
	Bind(":site_id",	&siteId);
	Bind(":partner_id",	&partnerId);
	Bind(":pagetype1", (int *)&pageType1);
	Bind(":pagetype2", (int *)&pageType2);
	Bind(":context_value", &value);

	// do it
	Execute();

	Commit();

	Close(&mpCDADeleteCobrandAd);
	SetStatement(NULL);

	return true;
}