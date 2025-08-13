/*	$Id: clsLogReadData.cpp,v 1.2 1999/02/21 02:30:34 josh Exp $	*/
//
// Class Name:		clsLogReadData
//
// Description:		Reads and makes sense of access logs
//
// Author:			Chad Musick
//
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>

#include "clsLogReadData.h"
#include "clsTextHashPool.h"
#include "clsDatabase.h"
#include "clsApp.h"

#include "clsBDTallyData.h"

#include "algo.h"

#undef TEMP_HACK

// Lines are of the form:
// remote site, wait, request size, send size, result code, nt result code, 
// request, page, agent, referrer, cookie, query,
// (All on one line.)
// UNLESS
// extended logging is turned off, in which case they are of the form
// remote site, wait, request size, send size, result code, nt result code,
// request, page, query

// Except for on the first string, we should always have a leading space after
// the comma, so we increment there.
#define SAFETY_VAL(x) { x = strtok(NULL, ","); if (!(x) || ((*x) != ' ')) return 0; \
	while (*++x == ' '); }

#define NOSAFETY_VAL(x) { x = strtok(NULL, ","); if (!(x) || ((*x) != ' ' )) return 1; \
	while (*++x == ' '); }

// Constructor. Make a new hash class, and set other stuff.
clsLogReadData::clsLogReadData(int maxPartner, time_t dayStart) : 
	mDayStart(dayStart), 
	mpHash(NULL),
	mMaxPartnerNum(maxPartner + 1),
	mMaxNumberSeen(-1),
	mMaxIndexSeen(-1), 
	mHits(0)
{
	mpHash = new clsTextHashPool(1 << 15);

	// 4 tables with one for each partner, plus 2 singles.
	// Mass allocation, then set pointers internally.
	mpvCountStorage = new vector<int> [mMaxPartnerNum * 4 + 1 + 1];

	mpvAgentCounts = mpvCountStorage;
	mpvPageCounts = mpvAgentCounts + mMaxPartnerNum;
	mpvReferCounts = mpvPageCounts + mMaxPartnerNum;
	mpvCategoryCounts = mpvReferCounts + mMaxPartnerNum;
	mpvStringNumbers = mpvCategoryCounts + mMaxPartnerNum;
	mpvBrowserTranslations = mpvStringNumbers + 1;
}

// Destructor
clsLogReadData::~clsLogReadData()
{
	ClearData();
	delete [] mpvCountStorage;
}

// ClearData
// Clears all data, but leaves the object in a 'safe' state.
void clsLogReadData::ClearData()
{
	int num_vectors;
	vector<int> *pTargeted;

	delete mpHash;
	mpHash = NULL;

	for (num_vectors = 0; num_vectors < (mMaxPartnerNum * 4 + 1 + 1); ++num_vectors)
	{
		pTargeted = mpvCountStorage + num_vectors;
		pTargeted->erase(pTargeted->begin(),
			pTargeted->end());
	}

	mMaxNumberSeen = mMaxIndexSeen = -1;

	return;
}

// BreakUpLine
//
// Given a line, fills all pointers with appropriate doo-dads.
//
// N.B.: This function does not allocate any new memory for the results returned,
// as that may not always be necessary, depending on their analyzation. Calling
// this function multiple times will overwrite the previous results -- copy
// them if you want them!
bool clsLogReadData::BreakupLine(char *theLine, char **page_name,
				char **agent_name, char **referrer_name, char **cookie_val,
				char **query_string)
{
	char *walkPtr;

	*cookie_val = NULL;

	walkPtr = strtok(theLine, ",");	// remote site address
	SAFETY_VAL(walkPtr);			// auth
	SAFETY_VAL(walkPtr);			// Date
	SAFETY_VAL(walkPtr);			// Time
	SAFETY_VAL(walkPtr);			// Service
	SAFETY_VAL(walkPtr);			// Machine
	SAFETY_VAL(walkPtr);			// Server
	SAFETY_VAL(walkPtr);			// wait in milliseconds
	SAFETY_VAL(walkPtr);			// request size
	SAFETY_VAL(walkPtr);			// bytes returned
	SAFETY_VAL(walkPtr);			// result code
	if (atoi(walkPtr) / 100 != 2)
	{
		return 0;					// If the result was not successful, we don't care.
	}

	SAFETY_VAL(walkPtr);			// nt result code
	SAFETY_VAL(walkPtr);			// request value
	SAFETY_VAL(*page_name);			// The base url retrieved
	NOSAFETY_VAL(*agent_name);		// The name of the agent (browser)
	NOSAFETY_VAL(*referrer_name);		// The url of the referrer
	NOSAFETY_VAL(*cookie_val);		// The value of the cookies (if present)
	if (!**cookie_val)
		*cookie_val = NULL;
	NOSAFETY_VAL(*query_string);		// The query string

	// If we actually get to here, the caller must _reverse_ the values of
	// cookie_val and query_string, to compensate for extended vs. non-extended logs.

	return 1;
}

// NormalizeString
//
// lowercase the string
// Convert any % escape sequence to the character which it represents.
// This is primarily for commas given in URLs and such.
void clsLogReadData::NormalizeString(char *pString)
{
	char *pIn;
	char *pOut;
	int xVal;
	int newVal;

	pIn = pOut = pString;

	while (*pIn)
	{
		if ((*pIn == '%') && *(pIn + 1) && *(pIn + 2) &&
			isxdigit(*(pIn + 1)) && isxdigit(*(pIn + 2)))
		{
			xVal = tolower(*(pIn + 1));
			if (xVal >= 'a' && xVal <= 'f')
				xVal = 10 + xVal - 'a';
			else
				xVal = xVal - '0';

			newVal = xVal << 4;

			xVal = tolower(*(pIn + 2));
			if (xVal >= 'a' && xVal <= 'f')
				xVal = 10 + xVal - 'a';
			else
				xVal = xVal - '0';

			newVal += xVal;
			*pOut = newVal;
			pIn += 3;
		}
		else
		{
			*pOut = tolower(*pIn);
			++pIn;
		}

		++pOut;
	}
	*pOut = '\0';

	return;
}

// AnalyzeAgent
//
// Take the agent (browser) and turn the
// ugly string into one of our few acknowledge browser types,
// then increment the count for that type.
// Agents use an intermediate translation table, explained in
// the function.
void clsLogReadData::AnalyzeAgent(int partner)
{
	int *pCount;
	char *pVia;
	int stringId;
	int browserNum;

#ifdef TEMP_HACK
	return;
#endif

	if (!mpAgent)
		return;	
	// via means it was through a proxy -- we just really don't care. So there.
	pVia = strstr(mpAgent, " via ");
	if (pVia)
		*pVia = '\0';

	// The browser translation table works this way:
	// I have a string number, so I look at that indices
	// in the translation table, and find whatever I take
	// there if it's >= 0 and use that as the new string number.
	// This is not recursive.
	stringId = LookupString(mpAgent);
	if (stringId < mpvBrowserTranslations->size() &&
		(*mpvBrowserTranslations)[stringId] >= 0)
		stringId = (*mpvBrowserTranslations)[stringId];
	else
	{
		// Figure out what kind of browser it is.
		// This needs to be checked periodically
		// and changed when new browsers come out.
		if (strstr(mpAgent, "WebTV"))
			browserNum = LookupString("WebTV");
		else if (strstr(mpAgent, "MSIE/1"))
			browserNum = LookupString("MSIE 1");
		else if (strstr(mpAgent, "MSIE/2"))
			browserNum = LookupString("MSIE 2");
		else if (strstr(mpAgent, "MSIE/3"))
			browserNum = LookupString("MSIE 3");
		else if (strstr(mpAgent, "MSIE/4"))
			browserNum = LookupString("MSIE 4");
		else if (strstr(mpAgent, "ompatible"))
			browserNum = LookupString("Other");
		else if (strstr(mpAgent, "Mozilla/0"))
			browserNum = LookupString("Netscape 0");
		else if (strstr(mpAgent, "Mozilla/1"))
			browserNum = LookupString("Netscape 1");
		else if (strstr(mpAgent, "Mozilla/2"))
			browserNum = LookupString("Netscape 2");
		else if (strstr(mpAgent, "Mozilla/3"))
			browserNum = LookupString("Netscape 3");
		else if (strstr(mpAgent, "Mozilla/4"))
			browserNum = LookupString("Netscape 4");
		else if (strstr(mpAgent, "Microsoft Internet Explorer/4"))
			browserNum = LookupString("MSIE 4");
		else if (strstr(mpAgent, "aolbrowser"))
			browserNum = LookupString("AOL");
		else if (strstr(mpAgent, "lynx"))
			browserNum = LookupString("Lynx");
		else
			browserNum = LookupString("Other");
		AddBrowser(stringId, browserNum);
		stringId = browserNum;
	}

	pCount = LookupCount(stringId, partner, mpvAgentCounts);

	if (!pCount)
	{
		cerr << "Error in AnalyzeAgent" << endl;
		return;
	}

	// And increment.
	++(*pCount);
	return;
}

// These are partners that may signify referral by
// passing a query string for the page, rather
// than coming from the site.
// The first entry is the query name, the second
// is the partner name. (Since the name is normalized,
// the first should always be lower case.)

static char *s_referrer_query_translations[] =
{
	"infospace", "Infospace",
	NULL, NULL
};

// AnalyzePage
// Increments the count for a specific page (excluding query string, if any manages
// to creep onto there (it should be in the query slot)).
// If the page is a listing page (we know by testing whether or not it has
// '/category#' in it), increment the category count.
void clsLogReadData::AnalyzePage(int partner)
{
	int *pCount;
	int string_id;
	int categoryNum;
	char *categoryStr;
	char *pQueryString;
	char **ppReferTranslate;
	int i, j;

	NormalizeString(mpPage);
	// Redirect strings, which we do want to track
	if (!strstr(mpPage, "redirectenter"))
		mpPage = strtok(mpPage, "?");

	// Ignore graphics.
	if (strstr(mpPage, ".gif") || strstr(mpPage, ".jpg"))
		return;
#ifndef TEMP_HACK
	pQueryString = strtok(NULL, "\n");
	// Figure out if we have a rogue referrer.
	if (pQueryString && *pQueryString)
	{
		ppReferTranslate = s_referrer_query_translations;
		while (*ppReferTranslate)
		{
			if (!strcmp(pQueryString, *ppReferTranslate))
			{
				strcpy(mReferBuffer, *(ppReferTranslate + 1));
				mpRefer = mReferBuffer;
				break;
			}
			ppReferTranslate += 2;
		}
	}

	string_id = LookupString(mpPage);
#endif
	// Figure out if it's a listing page, and do our thing if it is.
	if ((categoryStr = strstr(mpPage, "/category")))
	{
		categoryNum = atoi(categoryStr + 9);
		if (categoryNum > 0)
		{
			if (categoryNum >= (mpvCategoryCounts + partner)->size())
			{
				for (j = 0; j < mMaxPartnerNum; ++j)
				{
					i = categoryNum - (mpvCategoryCounts + j)->size() + 1;
					while (i-- > 0)
						(mpvCategoryCounts + j)->push_back(0);
				}
			}
			++(mpvCategoryCounts[partner][categoryNum]);
		}
	}
#ifndef TEMP_HACK
	pCount = LookupCount(string_id, partner, mpvPageCounts);

	if (!pCount)
	{
		cerr << "Error in AnalyzePage" << endl;
		return;
	}

	// And increment.
	++mHits;
	++(*pCount);
#endif
	return;
}

// AnalyzeReferral
//
// Count the referrals.
void clsLogReadData::AnalyzeReferral(int partner)
{
	int *pCount;
	char *machineName;
	char *firstSlash;
	int stringId;
	int lastIp;

#ifdef TEMP_HACK
	return;
#endif
	// Don't count any referrals from us.
	if (!mpRefer)
		return;

	NormalizeString(mpRefer);
	if (strncmp(mpRefer, "http", 4))
		return;

	// These exclude our own machines, so we don't
	// track internal referrals.
	if (!strncmp(mpRefer, "http://206.79.255.", 18))
	{
		lastIp = atoi(mpRefer + 18);
		if (lastIp >= 81 && lastIp <= 88)
			return;
	}
	else if (!strncmp(mpRefer, "http://209.1.251.", 17))
	{
		lastIp = atoi(mpRefer + 17);
		if (lastIp >= 128 && lastIp <= 159)
			return;
	}
	else if ((machineName = strstr(mpRefer, "ebay.com")) ||
		(machineName = strstr(mpRefer, "ebay2.com")))
	{
		firstSlash = strchr(mpRefer + 7, '/');
		if (!firstSlash || (firstSlash > machineName))
			return;
	}

	mpRefer = strtok(mpRefer, "?");

	stringId = LookupString(mpRefer);
	pCount = LookupCount(stringId, partner, mpvReferCounts);

	if (!pCount)
	{
		cerr << "Error in AnalyzeReferral" << endl;
		return;
	}

	++(*pCount);
	return;
}

// AnalyzeQuery
// We mostly ignore the query, but if it's a redirect, or if it's
// got one of our special 'partner' strings, we switch it to
// be the referral.
void clsLogReadData::AnalyzeQuery(char *query_string, int partner)
{
	char **ppReferTranslate;

	if (strstr(query_string, "redirectenter"))
	{
		mpRefer = query_string;
		return;
	}

	// Figure out if we have a rogue referrer.
	if (query_string && *query_string)
	{
		ppReferTranslate = s_referrer_query_translations;
		while (*ppReferTranslate)
		{
			if (!strcmp(query_string, *ppReferTranslate))
			{
				strncpy(mReferBuffer, *(ppReferTranslate + 1), 254);
				mpRefer = mReferBuffer;
				break;
			}
			ppReferTranslate += 2;
		}
	}
	return;
}

// PartnerFromCookie
// Given a cookie string, figure out the partner id.
int clsLogReadData::PartnerFromCookie(char *cookie_val)
{
	char *pStr;
	int partner;

	if (!cookie_val || !*cookie_val)
		return 0;

	partner = 0;
	pStr = strstr(cookie_val, "p=");
	if (pStr && ((pStr == cookie_val) || isspace(*(pStr - 1))))
	{
		// Get past the p=
		partner = atoi(pStr + 2);
	}

	if (partner < 0 || partner >= mMaxPartnerNum)
		return 0;

	return partner;
}

// LookupString
// Given a string, return a unique number for that string.
// We use a translation table so that our numbers are
// sequential and increment by 1 (the returned number
// is increasing, but doesn't increment by 1).
int clsLogReadData::LookupString(const char *to_find)
{
	vector<int> *pWalkers;
	vector<int>::iterator j;
	int i;
	int val;

	if (!mpHash)
		mpHash = new clsTextHashPool(1 << 31);

	val = mpHash->LookupString(to_find);
	if (val <= mMaxIndexSeen)
	{
		j = lower_bound(mpvStringNumbers->begin(),
			mpvStringNumbers->end(), val);
		return j - mpvStringNumbers->begin();
	}

	mpvStringNumbers->push_back(val);

	pWalkers = mpvCountStorage;

	for (i = 0; i < (mMaxPartnerNum * 3); ++i)
	{
		pWalkers[i].push_back(0);
	}

	mMaxIndexSeen = val;
	++mMaxNumberSeen;

	return mMaxNumberSeen;
}

// LookupString
// Given a unique number, find the string again.
const char *clsLogReadData::LookupString(int to_find)
{
	if (to_find < 0 || to_find > mMaxNumberSeen)
		return NULL;

	return mpHash->LookupString((*mpvStringNumbers)[to_find]);
}

// LookupCount
// to_find is the string number
// in_partner is the partner number
// in_set is the set in which we want to find this
// count -- this is really just a sanity checking function
int *clsLogReadData::LookupCount(int to_find,
				int in_partner,
				vector<int> *in_set)
{
	if ((to_find < 0)  || (mMaxNumberSeen < to_find))
	{
		cerr << "Bad to_find " << to_find;
		return NULL;
	}

	if ((in_partner < 0) || (in_partner >= mMaxPartnerNum))
	{
		cerr << "Bad partner " << in_partner;
		return NULL;
	}

	return &(in_set[in_partner][to_find]);
}

// Yow. We don't like static data, but ugly as it is, we need it to
// do the sorts well from STL.
static clsLogReadData *s_Instance;
static vector<int> *s_Counts;
static vector<int> *s_Translations;

// These are all sort functions.
static int s_sort_by_string(const int a, const int b)
{
	return strcmp(s_Instance->LookupString(a), s_Instance->LookupString(b)) < 0;
}

static int s_sort_by_count(const int a, const int b)
{
	return (*s_Counts)[a] > (*s_Counts)[b];
}

static int s_sort_by_browser(const int a, const int b)
{
	if ((*s_Translations)[a] == (*s_Translations)[b])
	{
		if ((*s_Translations)[a] == -1)
			return 0;
		return strcmp(s_Instance->LookupString(a),
			s_Instance->LookupString(b)) < 0;
	}

	if ((*s_Translations)[a] == -1)
		return 1;
	if ((*s_Translations)[b] == -1)
		return 0;

	return strcmp(s_Instance->LookupString((*s_Translations)[a]),
		s_Instance->LookupString((*s_Translations)[b])) < 0;
}

// Output
// Store all the information we have so laboriously collected.
void clsLogReadData::Output()
{
#ifdef TEMP_HACK
	vector<int>::iterator j;
	int ctr;

	ctr = 0;
	for (j = mpvCategoryCounts->begin(); j != mpvCategoryCounts->end(); ++j, ++ctr)
	{
		if (!*j)
			continue;
	}

	return;
#endif
	clsDatabase *pDatabase;
	vector<int> *pVec;
	vector<int>::iterator i;
	vector<const char *> vPageNames;
	vector<int> vPagesWithCounts;

	int id;
	int partner;

	pDatabase = gApp->GetDatabase();

	for (partner = 0; partner < mMaxPartnerNum; ++partner)
	{
		// Add the browsers, one at a time.
		pVec = mpvAgentCounts + partner;
		for (i = pVec->begin(); i != pVec->end(); ++i)
		{
			if (!*i)
				continue;

			id = pDatabase->GetBrowserId((char *) LookupString((int) (i - pVec->begin())));
			pDatabase->AddBrowserCount(partner, mDayStart, id, *i);
		}

		// Add the referrers one at a time.
		pVec = mpvReferCounts + partner;
		for (i = pVec->begin(); i != pVec->end(); ++i)
		{
			if (*i < 5)
				continue;

			id = pDatabase->GetReferrerId((char *) LookupString((int) (i - pVec->begin())));
			pDatabase->AddReferrerCount(partner, mDayStart, id, *i);

		}

		// Gather the pages and add as a group.
		pVec = mpvPageCounts + partner;
		for (i = pVec->begin(); i != pVec->end(); ++i)
		{
			if (*i < 100)
				continue;

			vPagesWithCounts.push_back(*i);
			vPageNames.push_back((char *) LookupString((int) (i - pVec->begin())));
		}
		pDatabase->AddPartnerPageData(partner, mDayStart,
			&vPagesWithCounts, &vPageNames);

		vPagesWithCounts.erase(vPagesWithCounts.begin(), vPagesWithCounts.end());
		vPageNames.erase(vPageNames.begin(), vPageNames.end());
		
/*		if (mpTally)
			mpTally->SetPageViews(partner, mpvCategoryCounts + partner);
*/		
	}

	ClearData();
	return;
}

// GetCategoryViewCount
//
// Get the number of views in a category and partner.
//
int clsLogReadData::GetCategoryViewCount(int partner, int category)
{
	if (partner >= mMaxPartnerNum)
		return 0;

	if (category >= (mpvCategoryCounts + partner)->size())
		return 0;

	return (*(mpvCategoryCounts + partner))[category];
}

// AddBrowser
// Management function for the browser translation table --
// agent is the ugly string, browser is the one to replace
// agent. Updates the translation table only.
void clsLogReadData::AddBrowser(int agent, int browser)
{
	int i;
	int highestNumberSeen;

	i = mMaxNumberSeen - mpvBrowserTranslations->size();
	highestNumberSeen = mMaxNumberSeen;

	while (i >= 0)
	{
		mpvBrowserTranslations->push_back(-1);
		--i;
	}

	(*mpvBrowserTranslations)[browser] = browser;
	if (((*mpvBrowserTranslations)[agent] != browser) &&
		((*mpvBrowserTranslations)[agent] >= 0))
	{
		if ((*mpvBrowserTranslations)[agent] >= 0)
			cerr << "Overwriting browser translation -- duplicate agent." 
				<< endl << flush;
		(*mpvBrowserTranslations)[agent] = browser;
	}

	return;
}

// Given a newline seperated file of
// browserName	[tab]	agentName
// can speed processing by pre-loading browsers.
void clsLogReadData::LoadBrowsers()
{
	FILE *browserFile;
	char buffer[4096];
	char *agentName;
	char *browserName;
	char *pVia;
	int agentNum;
	int browserNum;
	int highestNumberSeen;
	int i;

	// We're not using a browser file at the moment.
	return;

	i = mMaxNumberSeen - mpvBrowserTranslations->size();
	highestNumberSeen = mMaxNumberSeen;
	// Make sure we start at the right place.
	while (i >= 0)
	{
		mpvBrowserTranslations->push_back(-1);
		--i;
	}

	browserFile = fopen("browsers.txt", "r");
	if (!browserFile)
	{
		cerr << "Could not open browsers file.\n";
		// Sartre says "No Exit".
		//exit(1);
		return;
	}

	while (fgets(buffer, 4095, browserFile))
	{
		browserName = strtok(buffer, "\t");
		agentName = strtok(NULL, "\n");
		if (!agentName)
			browserName = agentName;
		else
			while (*agentName == '\t')
				++agentName;

		pVia = strstr(agentName, " via ");
		if (pVia)
			*pVia = '\0';

		browserNum = LookupString(browserName);
		if (browserNum > highestNumberSeen)
		{
			mpvBrowserTranslations->push_back(browserNum);
			highestNumberSeen = browserNum;
		}

		agentNum = LookupString(agentName);
		if (agentNum > highestNumberSeen)
		{
			mpvBrowserTranslations->push_back(browserNum);
			highestNumberSeen = agentNum;
		} 
		else
		{
			if ((*mpvBrowserTranslations)[agentNum] != browserNum)
			{
				cerr << "Overwriting browser translation -- duplicate agent." 
					<< endl << flush;
				(*mpvBrowserTranslations)[agentNum] = browserNum;
			}
		}
	}

	fclose(browserFile);
	return;
}		

// Run
// The heart of the thing -- takes input from stdin,
// runs it through all the functions it needs to go through.
void clsLogReadData::Run(clsBDTallyData *pTally)
{
	char buffer[4096];
	char *cookie_val;
	char *query_string;
	char *pSwap;
	FILE *inFile;
	int i = 0;
	int partner;

	//Not using browser file at the moment.
	//LoadBrowsers();

	inFile = stdin;

	while (fgets(buffer, 4095, inFile))
	{
		++i;
		if (!(i % 100000))
			cerr << i << endl;
		if (!BreakupLine(buffer, &mpPage, &mpAgent, &mpRefer,
			&cookie_val, &query_string))
		{
			continue;
		}
		if (!cookie_val)
		{
			query_string = mpAgent;
			mpAgent = NULL;
		}

		partner = PartnerFromCookie(cookie_val);
		AnalyzePage(partner);
		AnalyzeQuery(query_string, partner);
#ifndef TEMP_HACK
		AnalyzeAgent(partner);
		AnalyzeReferral(partner);
#endif TEMP_HACK
	}

	cerr << "Done reading logs." << endl << flush;
	return;
}
