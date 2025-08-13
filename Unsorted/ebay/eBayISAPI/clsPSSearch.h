/*	$Id: clsPSSearch.h,v 1.2 1999/05/19 02:34:14 josh Exp $	*/
//
//	File:	clsPSSearch.h
//
//	Class:	clsPSSearch
//
//	Author:	Wen Wen
//
//	Function:
//		This class is to hold the information for a personal shopper search
//							
// Modifications:
//				- 2/9/99	Wen - Created
//
#ifndef CLSPSSEARCH_INCLUDE
#define CLSPSSEARCH_INCLUDE

#include "vector.h"

// Some convienent macros
#define PSSTRING_VARIABLE(name)				\
protected:									\
	char	*mp##name;						\
public:										\
	const char	*Get##name();				\
	void	Set##name(const char *pNew);	


class clsPSSearch
{
public:
	clsPSSearch()
	{
		mpEmail = NULL;
		mpPassword = NULL;
		mpQuery = NULL;
		mpSearchDesc = NULL;
		mpMinPrice = NULL;
		mpMaxPrice = NULL;
		mpEmailFrequency = NULL;
		mpEmailDuration = NULL;
		mpReg = NULL;
		mpStartingDate = NULL;

		mEmpty[0] = 0;
	}

	clsPSSearch(const char* pEmail,
				const char* pPassword,
				const char* pQuery,
				const char* pSearchDesc,
				const char* pMinPrice,
				const char* pMaxPrice,
				const char* pEmailFrequecy,
				const char* pEmailDuration,
				const char* pReg);
	~clsPSSearch();

	// create the search URL based on the information
	const char* GetURLForSearch();
	void  SetURLForSearch(const char* pURL);
	bool  GetValue(const char* pURL, const char* pName, char* pValue);

protected:
	PSSTRING_VARIABLE(Email);
	PSSTRING_VARIABLE(Password);
	PSSTRING_VARIABLE(Query);
	PSSTRING_VARIABLE(SearchDesc);
	PSSTRING_VARIABLE(MinPrice);
	PSSTRING_VARIABLE(MaxPrice);
	PSSTRING_VARIABLE(EmailFrequency);
	PSSTRING_VARIABLE(EmailDuration);
	PSSTRING_VARIABLE(Reg);
	PSSTRING_VARIABLE(StartingDate);

	char mURL[500];
	char mEmpty[1];
};

typedef vector<clsPSSearch *>	PSSearchVector;

#endif // CLSPSSEARCH_INCLUDE
