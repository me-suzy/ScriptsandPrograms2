/*	$Id: clsLogReadData.h,v 1.2 1999/02/21 02:30:35 josh Exp $	*/
//
// File Name: clsLogReadData.h
//
// Purpose: This stores information as a server log file is read,
// so that this information can then be entered in the database
// and collated to something useful.
//
// Author: Chad Musick
// Dates:  10/20/97 - Created
//

#ifndef CLSLOGREADDATA_INCLUDE
#define CLSLOGREADDATA_INCLUDE

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#include "vector.h"

class clsTextHashPool;
class clsBDTallyData;

class clsLogReadData
{
public:

	clsLogReadData(int maxPartner, time_t dayStart);
	~clsLogReadData();

	// Makes no new strings. Repeat calls will overwrite!
	// true/false success/failure
	// Possible causes of failure: malformed line, non 200 result code.
	bool BreakupLine(char *theLine,
				     char **page_name,
					 char **agent_name, 
					 char **referrer_name, 
					 char **cookie_val,
					 char **query_string);

	void AnalyzeAgent(int partner);
	void AnalyzePage(int partner);
	void AnalyzeReferral(int partner);
	void AnalyzeQuery(char *query_string, int partner);
	int PartnerFromCookie(char *cookie_val); // Get a partner number from the cookie

	int LookupString(const char *to_find);
	const char *LookupString(int to_find);

	int *LookupCount(int to_find, int in_partner, vector<int> *in_set);

	void Output();
	void Run(clsBDTallyData *pTally = NULL);
	void ClearData();

	int GetCategoryViewCount(int partner, int category);

private:

	void LoadBrowsers();
	void AddBrowser(int agent, int browser);
	void NormalizeString(char *theString);

	time_t mDayStart; // What day are we running the logs for.

	clsTextHashPool *mpHash;

	// This is a vector of all the _actual_ string numbers
	// (as far as clsTextHashPool is concerned), in the indices
	// of the pseudo text numbers we give out.
	vector<int> *mpvStringNumbers;
	// A vector to translate agents to browsers
	vector<int> *mpvBrowserTranslations;

	// These actually are arrays of the vectors. The base allocation for all
	// of these is stored in *mpvCountStorage
	vector<int> *mpvAgentCounts;
	vector<int> *mpvPageCounts;
	vector<int> *mpvReferCounts;
	vector<int> *mpvCategoryCounts;
	vector<int> *mpvCountStorage;

	int mMaxPartnerNum;
	int mMaxNumberSeen;
	int mMaxIndexSeen;
	int mHits;

	// Sometimes the function before it may want to change these values,
	// such as in the case of referrers which get specified in the
	// page name as a query string. These are not allocated strings,
	// so don't delete them!
	char *mpAgent;
	char *mpRefer;
	char *mpPage;

	char mReferBuffer[512];
};

#endif /* CLSLOGREADDATA_INCLUDE */
