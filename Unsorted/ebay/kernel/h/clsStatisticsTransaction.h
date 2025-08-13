/*	$Id: clsStatisticsTransaction.h,v 1.2 1998/06/23 04:28:25 josh Exp $	*/
//
//	File:		clsStatisticsTransaction.h
//
// Class:	clsStatisticsTransaction
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Represents a collection of statistics
//
// Modifications:
//				- 10/07/97 wen	- Created
//
#ifndef CLSSTATISTICSXATION_INCLUDED
#define CLSSTATISTICSXATION_INCLUDED

#include "eBayTypes.h"
#include "vector.h"

class clsStatisticsTransaction
{
	public:
		clsStatisticsTransaction(){;}
		~clsStatisticsTransaction(){;}

		void Set(int id,
				 int StatsType,
				 char* pDescription,
				 char* pQuery)
		{
			memset(mDescription, 0, sizeof(mDescription));
			memset(mQuery, 0, sizeof(mQuery));

			mId = id;
			mStatsType = StatsType;
			strncpy(mDescription, pDescription, sizeof(mDescription)-1);
			strncpy(mQuery, pQuery, sizeof(mQuery));
		}

		int		GetId() {return mId;}
		int		GetStatisticsType() {return mStatsType;}
		char*	GetDesciption() {return mDescription;}
		char*	GetQuery() {return mQuery;}

	protected:
		int		mId;
		int		mStatsType;
		char	mDescription[50];
		char	mQuery[500];
};

// Convienent Typedefs
typedef vector<clsStatisticsTransaction *> StatsTransactionVector;

#endif // CLSSTATISTICSXATION_INCLUDED
