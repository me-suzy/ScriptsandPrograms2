/*	$Id: clsDatabaseOracleAd.cpp,v 1.4 1998/09/30 02:59:20 josh Exp $	*/
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

#include "eBayKernel.h"
#include "clsDailyAd.h"

#define NUMBER_CAT	400

//
// GetPageViews
//
static const char *SQL_GetPageViews =
 "	select	categoryid,					\
			page_view					\
	from ebay_traffic_info				\
	where marketplace = :marketplace and \
		  page_type = :pagetype";


void clsDatabaseOracle::GetPageViews(int Marketplace, 
									 int Pagetype,
									 int* pPageViews)
{
	int		catid[NUMBER_CAT];
	int		pageview[NUMBER_CAT];

	int		rowsFetched;
	int		rc;
	int		i,n;

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_GetPageViews);

	// Bind the input variable
	Bind(":marketplace", (int *)&Marketplace);
	Bind(":pagetype", (int *)&Pagetype);


	// Bind the outputs
	Define(1, catid);
	Define(2, pageview);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return;
	}

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, NUMBER_CAT);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pPageViews[catid[i]] = pageview[i];
		}
	} while (!CheckForNoRowsFound());
	
	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// GetPageViews
//
static const char *SQL_GetAds =
 "	select	a.adid							\
			a.categoryid,					\
			a.impressions,					\
			b.url,							\
			b.image,						\
			b.alt,							\
			b.other							\
	from ebay_daily_ad_info a, ebay_ad_info	b	\
	where page_type = :pagetype	and		\
		  adid in						\
			(select id from ebay_ad_info \
			where start_date => TO_DATE(:start, 'YYYY-MM-DD') and \
			end_date < TO_DATE(:end, 'YYYY-MM-DD'))";

void clsDatabaseOracle::GetAds(int PageType,
							   time_t Start,
							   time_t tEnd,
							   void** pAdVectorArray)
{
	int		adid[NUMBER_CAT];
	int		catid[NUMBER_CAT];
	int		impressions[NUMBER_CAT];
	char	url[NUMBER_CAT][255];
	char	image[NUMBER_CAT][255];
	char	alt[NUMBER_CAT][128];
	char	other[NUMBER_CAT][255];

	sb2		url_ind[NUMBER_CAT];
	sb2		image_ind[NUMBER_CAT];
	sb2		alt_ind[NUMBER_CAT];
	sb2		other_ind[NUMBER_CAT];

	char*	pURL;
	char*	pImage;
	char*	pAlt;
	char*	pOther;

	char	start_time[20];
	char	end_time[20];
	struct tm* pTheTime;

	int		rowsFetched;
	int		rc;
	int		i,n;

	DailyAdVector*	pAdVector;
	clsDailyAd*	pAd;

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_GetAds);

	// convert time
	pTheTime	= localtime(&Start);
	TM_STRUCTToORACLE_DATE(pTheTime,   start_time);
	pTheTime	= localtime(&tEnd);
	TM_STRUCTToORACLE_DATE(pTheTime,   end_time);


	// Bind the input variable
	Bind(":pagetype", (int *)&PageType);
	Bind(":start", (char*)start_time);
	Bind(":tEnd", (char*)end_time);

	// Bind the outputs
	Define(1, adid);
	Define(2, catid);
	Define(3, impressions);
	Define(4, url[0], sizeof(url[0]), url_ind);
	Define(5, image[0], sizeof(image[0]), image_ind);
	Define(6, alt[0], sizeof(alt[0]), alt_ind);
	Define(7, other[0], sizeof(other[0]), other_ind);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return;
	}

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, NUMBER_CAT);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if ((pAdVector = (DailyAdVector*) pAdVectorArray[catid[i]]) == NULL)
			{
				// create advector
				pAdVector = new DailyAdVector;
				pAdVectorArray[catid[i]] = pAdVector;
			}

			if (url_ind[i] == -1)
				pURL = NULL;
			else
				pURL = url[i];

			if (image_ind[i] == -1)
				pImage = NULL;
			else
				pImage = image[i];

			if (alt_ind[i] == -1)
				pAlt = NULL;
			else
				pAlt = alt[i];

			if (other_ind[i] == -1)
				pOther = NULL;
			else
				pOther = other[i];

			pAd = new clsDailyAd(catid[i],
							adid[i],
							impressions[i],
							pURL,
							pImage,
							pAlt,
							pOther);

			pAdVector->push_back(pAd);
		}
	} while (!CheckForNoRowsFound());
	
	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}
