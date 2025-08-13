// $Id: clsDatabaseOracleTrustAndSafety.cpp,v 1.4 1999/04/17 20:22:42 wwen Exp $
//
//	File:	clsDatabaseOracleTrustAndSafety.cpp
//
//	Class:	clsDatabaseOracle
//
//	Author:	Josh Gordon (josh@ebay.com)
//
//	Function: Trust and safety related Oracle routines.for
//			things like Shillworm and Feedback Shillworm

#include "eBayKernel.h"
#define ORA_ITEM_ARRAYSIZE 100
// returns true if there was any problem, and closes the cursor.
bool clsDatabaseOracle::OracleErrorCheck(int rc,unsigned char *&pCDA)
{
	if ((rc < 0 || rc >= 4) && ((cda_def *)pCDA)->rc != 1403)	// something wrong
	{
		Check(rc);
		Close(&pCDA);
		SetStatement(NULL);
		return true;
	}
	return false;
}

// Some speedup tools for clsUserRelationships
// This is a generic function that takes an oracle statement as input which uses
// one vector of integers and a limit to return two vectors of integers. The SQL 
// statement must have "list_size" integer variables named :u00, :u01, and so on,
// as well as an integer variable named :limit. Calling examples follow.

// This is only because min() confuses stuff.
static int least(int m, int n)
{
	return (m <= n) ? m : n;
}

void clsDatabaseOracle::GetGenericPairsFromSingleVectorWithLimit(const char *SQL,
														const vector<int> &vInputs,
														vector<int> &vOutput1,
														vector<int> &vOutput2,	
														int limit,
														unsigned char*& pCursor)
{

	int i;
	const int list_size = 40;
	int inputs[list_size];

	OpenAndParse(&pCursor, SQL);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":u%02d", i);
		Bind(namebuf, inputs + i);
	}
	Bind(":limit", &limit);

	int output1[ORA_ITEM_ARRAYSIZE];
	Define(1, output1);

	int output2[ORA_ITEM_ARRAYSIZE];
	Define(2, output2);

	int input;
	for (input = 0; input < vInputs.size(); input += list_size)
	{
		// Put the auction ids into the auction array.
		int nInputs = least(vInputs.size() - input, list_size);
		memset(inputs, 0, sizeof inputs);
		copy(vInputs.begin() + input, vInputs.begin() + input + nInputs, inputs);

		Execute();

		int rowsFetched = 0;
		do 
		{
			cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
			int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

			if (OracleErrorCheck(rc, pCursor))
			{
				// oops, error, clear out return vector.
				vOutput1.clear();
				vOutput2.clear();
				return;
			}

			// Otherwise, suck the stuff in.
			int n = pCDACurrent->rpc - rowsFetched;
			rowsFetched += n;

			// and jam it into the vectors.
			copy(output1, output1 + n, back_inserter(vOutput1));
			copy(output2, output2 + n, back_inserter(vOutput2));
		} while (!CheckForNoRowsFound());
	}
	Close(&pCursor);
	SetStatement(NULL);
}

#define LIST_SIZE_ID_NAMES \
	"(:u00, :u01, :u02, :u03, :u04, :u05, :u06, :u07, :u08, :u09, :u10," \
		" :u10, :u11, :u12, :u13, :u14, :u15, :u16, :u17, :u18, :u19, :u20," \
		" :u20, :u21, :u22, :u23, :u24, :u25, :u26, :u27, :u28, :u29, :u30," \
		" :u30, :u31, :u32, :u33, :u34, :u35, :u36, :u37, :u38, :u39)"

void clsDatabaseOracle::GetAuctionIds(const vector<int>&vBidders, 
									  vector<int>&vCandidateBidders,
									  vector<int>&vCandidateAuctionIds, 
									  int limit)
{
	// Get all auctions listed by all of the bidders in vBidders. Return the 
	// pairs in the two arrays.

	static const char* SQL_GetAuctionIds =
		"select seller, id from ebay_items where "
		" sale_end > sysdate - :limit "
		" and seller in "
		LIST_SIZE_ID_NAMES;

	static const char* SQL_GetAuctionIdsEnded =
		"select seller, id from ebay_items_ended where "
		" sale_end > sysdate - :limit "
		" and seller in "
		LIST_SIZE_ID_NAMES;
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionIds,
		vBidders, vCandidateBidders, vCandidateAuctionIds, limit, mpCDAGetAuctionIds);
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionIdsEnded,
			vBidders, vCandidateBidders, vCandidateAuctionIds, limit, mpCDAGetAuctionIdsEnded);

}

void clsDatabaseOracle::GetAuctionsWon(const vector<int>& vBidders,
									   vector<int>& vItemNumbers,		// Item numbers
									   vector<int>& vReturnedBidders,	// Bidder ids
									   int limit)
{
	static const char* SQL_GetAuctionsWon =
		"select id, high_bidder from ebay_items "
		"where sale_end > sysdate - :limit "
		"and high_bidder in "
		LIST_SIZE_ID_NAMES;

	static const char* SQL_GetAuctionsWonEnded =
		"select id, high_bidder from ebay_items_ended "
		"where sale_end > sysdate - :limit "
		"and high_bidder in "
		LIST_SIZE_ID_NAMES;
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionsWon,
		vBidders, vItemNumbers, vReturnedBidders, limit, mpCDAGetAuctionsWon);
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionsWonEnded,
		vBidders, vItemNumbers, vReturnedBidders, limit, mpCDAGetAuctionsWonEnded);

}

void clsDatabaseOracle::GetAuctionsBidOn(const vector<int>&vBidderIds,
										 vector<int>&vAllBidders,
										 vector<int>&vAllAuctionsBidOn,
										 int limit)
{
	// This one returns repeats. Client does the counting.
	static const char* SQL_GetAuctionsBidOn =
		"select user_id, item_id from ebay_bids where "
		"created > sysdate - :limit and "
		"user_id in "
		LIST_SIZE_ID_NAMES;
	static const char* SQL_GetAuctionsBidOnEnded =
		"select user_id, item_id from ebay_bids_ended where "
		"created > sysdate - :limit and "
		"user_id in "
		LIST_SIZE_ID_NAMES;

	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionsBidOn,
		vBidderIds, vAllBidders, vAllAuctionsBidOn, limit, mpCDAGetAuctionsBidOn);
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionsBidOnEnded,
		vBidderIds, vAllBidders, vAllAuctionsBidOn, limit, mpCDAGetAuctionsBidOnEnded);

}

void clsDatabaseOracle::GetAuctionsWithRetractions(const vector<int>&vBidderIds,
												   vector<int>&vReturnedBidders,
												   vector<int>&vReturnedRetractions,
												   int limit)
{
	static const char* SQL_GetAuctionsWithRetractions =
		"select user_id, item_id from ebay_bids where "
		" created > sysdate - :limit and "
		" type != 1 and type != 2 and "
		" user_id in "
		LIST_SIZE_ID_NAMES;

	static const char* SQL_GetAuctionsWithRetractionsEnded =
		"select user_id, item_id from ebay_bids_ended where "
		" created > sysdate - :limit and "
		" type != 1 and type != 2 and "
		" user_id in "
		LIST_SIZE_ID_NAMES;
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionsWithRetractions,
		vBidderIds, vReturnedBidders, vReturnedRetractions, limit, mpCDAGetAuctionsWithRetractions);
	GetGenericPairsFromSingleVectorWithLimit(SQL_GetAuctionsWithRetractionsEnded,
		vBidderIds, vReturnedBidders, vReturnedRetractions, limit, mpCDAGetAuctionsWithRetractionsEnded);
}


void clsDatabaseOracle::GetSellersOfAuctions(const vector<int>&vAuctions,
											 hash_map<int, int, hash<int>, eqint>&mSellersAndAuctions,
											 int limit)
{
	static const char* SQL_GetSellersOfAuctions=
	"select seller, id from ebay_items "
		"where sale_end > sysdate - :limit "
		"and id in "
		LIST_SIZE_ID_NAMES;
	static const char* SQL_GetSellersOfAuctionsEnded=
	"select seller, id from ebay_items_ended "
		"where sale_end > sysdate - :limit "
		"and id in "
		LIST_SIZE_ID_NAMES;

	int i;
	const int list_size = 40;
	int auctions[list_size];

	OpenAndParse(&mpCDAGetSellersOfAuctions, SQL_GetSellersOfAuctions);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":u%02d", i);
		Bind(namebuf, auctions + i);
	}
	Bind(":limit", &limit);

	int sellers[ORA_ITEM_ARRAYSIZE];
	Define(1, sellers);

	int outauctions[ORA_ITEM_ARRAYSIZE];
	Define(2, outauctions);

	int auction;
	for (auction = 0; auction < vAuctions.size(); auction += list_size)
	{
		// Put the auction ids into the auction array.
		int nAuctions = least(vAuctions.size() - auction, list_size);
		memset(auctions, 0, sizeof auctions);
		copy(vAuctions.begin() + auction, vAuctions.begin() + auction + nAuctions, auctions);

		Execute();

		int rowsFetched = 0;
		do 
		{
			cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
			int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

			if (OracleErrorCheck(rc, mpCDAGetSellersOfAuctions))
			{
				// oops, error, clear out return vector.
				mSellersAndAuctions.erase(mSellersAndAuctions.begin(), mSellersAndAuctions.end());
				
				return;
			}

			// Otherwise, suck the stuff in.
			int n = pCDACurrent->rpc - rowsFetched;
			rowsFetched += n;

			// and jam it into the vector.
			for (i = 0; i < n; i++)
				mSellersAndAuctions[outauctions[i]] = sellers[i];

		} while (!CheckForNoRowsFound());
	}
	Close(&mpCDAGetSellersOfAuctions);
	SetStatement(NULL);
	OpenAndParse(&mpCDAGetSellersOfAuctionsEnded, SQL_GetSellersOfAuctionsEnded);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":u%02d", i);
		Bind(namebuf, auctions + i);
	}
	Bind(":limit", &limit);

	Define(1, sellers);

	Define(2, outauctions);

	for (auction = 0; auction < vAuctions.size(); auction += list_size)
	{
		// Put the auction ids into the auction array.
		int nAuctions = least(vAuctions.size() - auction, list_size);
		memset(auctions, 0, sizeof auctions);
		copy(vAuctions.begin() + auction, vAuctions.begin() + auction + nAuctions, auctions);

		Execute();

		int rowsFetched = 0;
		do 
		{
			cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
			int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

			if (OracleErrorCheck(rc, mpCDAGetSellersOfAuctionsEnded))
			{
				// oops, error, clear out return vector.
				mSellersAndAuctions.erase(mSellersAndAuctions.begin(), mSellersAndAuctions.end());
				
				return;
			}

			// Otherwise, suck the stuff in.
			int n = pCDACurrent->rpc - rowsFetched;
			rowsFetched += n;

			// and jam it into the vector.
			for (i = 0; i < n; i++)
				mSellersAndAuctions[outauctions[i]] = sellers[i];

		} while (!CheckForNoRowsFound());
	}
	Close(&mpCDAGetSellersOfAuctionsEnded);
	SetStatement(NULL);
}




void clsDatabaseOracle::GetOurAuctionsBidOnByUs(const vector<int>&vItems,
												const vector<int>&vBidderIds,
												vector<int>&vReturnedIds,
												int limit)
{


	// Given a list of auctions and a list of bidders, return those auctionids
	// which these bidders have ongebid.

	static const char* SQL_GetOurAuctionsBidOnByUs =
		"select item_id from ebay_bids where "
		" created >= sysdate - :limit"
		" and item_id in "
		"(:i00, :i01, :i02, :i03, :i04, :i05, :i06, :i07, :i08, :i09, :i10,"
		" :i10, :i11, :i12, :i13, :i14, :i15, :i16, :i17, :i18, :i19, :i20,"
		" :i20, :i21, :i22, :i23, :i24, :i25, :i26, :i27, :i28, :i29, :i30,"
		" :i30, :i31, :i32, :i33, :i34, :i35, :i36, :i37, :i38, :i39)"
		" and user_id in "
		LIST_SIZE_ID_NAMES;

	static const char* SQL_GetOurAuctionsBidOnByUsEnded =
		"select item_id from ebay_bids_ended where "
		" created >= sysdate - :limit"
		" and item_id in "
		"(:i00, :i01, :i02, :i03, :i04, :i05, :i06, :i07, :i08, :i09, :i10,"
		" :i10, :i11, :i12, :i13, :i14, :i15, :i16, :i17, :i18, :i19, :i20,"
		" :i20, :i21, :i22, :i23, :i24, :i25, :i26, :i27, :i28, :i29, :i30,"
		" :i30, :i31, :i32, :i33, :i34, :i35, :i36, :i37, :i38, :i39)"
		" and user_id in "
		LIST_SIZE_ID_NAMES;
	int i;
	const int list_size = 40;
	int input_item_ids[list_size];
	int bidders[list_size];

	OpenAndParse(&mpCDAGetOurAuctionsBidOnByUs, SQL_GetOurAuctionsBidOnByUs);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":i%02d", i);
		Bind(namebuf, input_item_ids + i);
		namebuf[1] = 'u';
		Bind(namebuf, bidders + i);
	}
	Bind(":limit", &limit);

	int output_item_ids[ORA_ITEM_ARRAYSIZE];
	Define(1, output_item_ids);

	// Now loop through the bidders.
	int bidder;
	for (bidder = 0; bidder < vBidderIds.size(); bidder += list_size)
	{
		int nBidders = least(vBidderIds.size() - bidder, list_size);
		memset(bidders, 0, sizeof bidders);
		copy(vBidderIds.begin() + bidder, vBidderIds.begin() + bidder + nBidders, bidders);
		
		// and the items. We put the items inside because we want 'em bunched per user.
		int input_item;
		for (input_item = 0; input_item < vItems.size(); input_item += list_size)
		{
			// Put the item ids into the item array.
			int nInputItems = least(vItems.size() - input_item, list_size);
			memset(input_item_ids, 0, sizeof input_item_ids);
			copy(vItems.begin() + input_item, vItems.begin() + input_item + nInputItems, input_item_ids);

			// OK. We have a block of bidders and a block of bidders. Get the relevant item ids.
			Execute();

			int rowsFetched = 0;
			do 
			{
				cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
				int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

				if (OracleErrorCheck(rc, mpCDAGetOurAuctionsBidOnByUs))
				{
					// oops, error, clear out return vector.
					vReturnedIds.clear();
					return;
				}

				// Otherwise, suck the stuff in.
				int n = pCDACurrent->rpc - rowsFetched;
				rowsFetched += n;

				// and jam it into the vector.
				copy(output_item_ids, output_item_ids + n, back_inserter(vReturnedIds));
			} while (!CheckForNoRowsFound());

		}
	}
	// OK. If the vector ain't empty, sort and squeeze it.
	if (!vReturnedIds.empty())
	{
		sort(vReturnedIds.begin(), vReturnedIds.end());
		vReturnedIds.erase(
			unique(vReturnedIds.begin(), vReturnedIds.end()),
			vReturnedIds.end());
	}

	Close(&mpCDAGetOurAuctionsBidOnByUs);
	SetStatement(NULL);
	OpenAndParse(&mpCDAGetOurAuctionsBidOnByUsEnded, SQL_GetOurAuctionsBidOnByUsEnded);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":i%02d", i);
		Bind(namebuf, input_item_ids + i);
		namebuf[1] = 'u';
		Bind(namebuf, bidders + i);
	}
	Bind(":limit", &limit);

	Define(1, output_item_ids);

	// Now loop through the bidders.
	for (bidder = 0; bidder < vBidderIds.size(); bidder += list_size)
	{
		int nBidders = least(vBidderIds.size() - bidder, list_size);
		memset(bidders, 0, sizeof bidders);
		copy(vBidderIds.begin() + bidder, vBidderIds.begin() + bidder + nBidders, bidders);
		
		// and the items. We put the items inside because we want 'em bunched per user.
		int input_item;
		for (input_item = 0; input_item < vItems.size(); input_item += list_size)
		{
			// Put the item ids into the item array.
			int nInputItems = least(vItems.size() - input_item, list_size);
			memset(input_item_ids, 0, sizeof input_item_ids);
			copy(vItems.begin() + input_item, vItems.begin() + input_item + nInputItems, input_item_ids);

			// OK. We have a block of bidders and a block of bidders. Get the relevant item ids.
			Execute();

			int rowsFetched = 0;
			do 
			{
				cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
				int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

				if (OracleErrorCheck(rc, mpCDAGetOurAuctionsBidOnByUsEnded))
				{
					// oops, error, clear out return vector.
					vReturnedIds.clear();
					return;
				}

				// Otherwise, suck the stuff in.
				int n = pCDACurrent->rpc - rowsFetched;
				rowsFetched += n;

				// and jam it into the vector.
				copy(output_item_ids, output_item_ids + n, back_inserter(vReturnedIds));
			} while (!CheckForNoRowsFound());

		}
	}
	// OK. If the vector ain't empty, sort and squeeze it.
	if (!vReturnedIds.empty())
	{
		sort(vReturnedIds.begin(), vReturnedIds.end());
		vReturnedIds.erase(
			unique(vReturnedIds.begin(), vReturnedIds.end()),
			vReturnedIds.end());
	}

	Close(&mpCDAGetOurAuctionsBidOnByUsEnded);
	SetStatement(NULL);
}


void clsDatabaseOracle::GetShillInformationForOurAuctions(
			const vector<int>&vInputs,		// item ids
			hash_map<int, int, hash<int>, eqint>& mapItemsToCounts,
			hash_map<int, time_t, hash<int>, eqint>& mapItemsToDurations,
			hash_map<int, float, hash<int>, eqint>& mapItemsToReserves)
{
	static const char *SQL_GetShillInformationForOurAuctions =
		"SELECT"
		"  bidcount,"
		"  TO_CHAR(sale_start, 'YYYY-MM-DD HH24:MI:SS'),"
		"  TO_CHAR(sale_end, 'YYYY-MM-DD HH24:MI:SS'),"
		"  reserve_price, "
		"  id "
		"FROM "
		" ebay_items "
		"WHERE " 
		"  id in "
		LIST_SIZE_ID_NAMES;
	static const char *SQL_GetShillInformationForOurAuctionsEnded =
		"SELECT"
		"  bidcount,"
		"  TO_CHAR(sale_start, 'YYYY-MM-DD HH24:MI:SS'),"
		"  TO_CHAR(sale_end, 'YYYY-MM-DD HH24:MI:SS'),"
		"  reserve_price, "
		"  id "
		"FROM "
		" ebay_items_ended "
		"WHERE " 
		"  id in "
		LIST_SIZE_ID_NAMES;
	int i;
	const int list_size = 40;
	int inputs[list_size];

	OpenAndParse(&mpCDAGetShillInformationForOurAuctions, SQL_GetShillInformationForOurAuctions);
	
	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":u%02d", i);
		Bind(namebuf, inputs + i);
	}

	int bidcounts[ORA_ITEM_ARRAYSIZE];
	Define(1, bidcounts);

	char sale_start[ORA_ITEM_ARRAYSIZE][32];
	Define(2, sale_start[0], sizeof(sale_start[0]));
	
	char sale_end[ORA_ITEM_ARRAYSIZE][32];
	Define(3, sale_end[0], sizeof(sale_end[0]));

	float reserves[ORA_ITEM_ARRAYSIZE];
	Define(4, reserves);

	int output_ids[ORA_ITEM_ARRAYSIZE];
	Define(5, output_ids);

	int input;
	for (input = 0; input < vInputs.size(); input += list_size)
	{
		// Put the auction ids into the auction array.
		int nInputs = least(vInputs.size() - input, list_size);
		memset(inputs, 0, sizeof inputs);
		copy(vInputs.begin() + input, vInputs.begin() + input + nInputs, inputs);

		Execute();

		int rowsFetched = 0;
		do 
		{
			cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
			int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

			if (OracleErrorCheck(rc, mpCDAGetShillInformationForOurAuctions))
			{
				// oops, error, clear out return vector.
				mapItemsToCounts.clear();
				mapItemsToReserves.clear();
				mapItemsToDurations.clear();
				return;
			}

			// Otherwise, suck the stuff in.
			int n = pCDACurrent->rpc - rowsFetched;
			rowsFetched += n;

			// and jam it into the maps.
			for (i = 0; i < n; i++)
			{
				int item = output_ids[i];
				mapItemsToCounts[item] = bidcounts[i];
				mapItemsToReserves[item] = reserves[i];

				time_t tstart, tend;
				ORACLE_DATEToTime(sale_start[i], &tstart);
				ORACLE_DATEToTime(sale_end[i], &tend);
				mapItemsToDurations[item] = tend - tstart;
			}

		} while (!CheckForNoRowsFound());
	}
	Close(&mpCDAGetShillInformationForOurAuctions);
	SetStatement(NULL);
	OpenAndParse(&mpCDAGetShillInformationForOurAuctionsEnded, 
		SQL_GetShillInformationForOurAuctionsEnded);
	
	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":u%02d", i);
		Bind(namebuf, inputs + i);
	}

	Define(1, bidcounts);

	Define(2, sale_start[0], sizeof(sale_start[0]));
	
	Define(3, sale_end[0], sizeof(sale_end[0]));

	Define(4, reserves);

	Define(5, output_ids);

	for (input = 0; input < vInputs.size(); input += list_size)
	{
		// Put the auction ids into the auction array.
		int nInputs = least(vInputs.size() - input, list_size);
		memset(inputs, 0, sizeof inputs);
		copy(vInputs.begin() + input, vInputs.begin() + input + nInputs, inputs);

		Execute();

		int rowsFetched = 0;
		do 
		{
			cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
			int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

			if (OracleErrorCheck(rc, mpCDAGetShillInformationForOurAuctionsEnded))
			{
				// oops, error, clear out return vector.
				mapItemsToCounts.clear();
				mapItemsToReserves.clear();
				mapItemsToDurations.clear();
				return;
			}

			// Otherwise, suck the stuff in.
			int n = pCDACurrent->rpc - rowsFetched;
			rowsFetched += n;

			// and jam it into the maps.
			for (i = 0; i < n; i++)
			{
				int item = output_ids[i];
				mapItemsToCounts[item] = bidcounts[i];
				mapItemsToReserves[item] = reserves[i];

				time_t tstart, tend;
				ORACLE_DATEToTime(sale_start[i], &tstart);
				ORACLE_DATEToTime(sale_end[i], &tend);
				mapItemsToDurations[item] = tend - tstart;
			}

		} while (!CheckForNoRowsFound());
	}
	Close(&mpCDAGetShillInformationForOurAuctionsEnded);
	SetStatement(NULL);
}
		
void clsDatabaseOracle::GetBidsFromTheseUsers(
			const vector<int>&vItems, 
			const vector<int>&vBidderIds,
			vector<int>&vBidderIdsOfBids,
			vector<int>&vOurBidItemNumbers,
			vector<int>&vOurBidTypes)
{
	
	static const char* SQL_GetBidsFromTheseUsers =
		"SELECT "
		" user_id,"
		" item_id,"
		" type "
		"FROM "
		" ebay_bids "
		"WHERE"
		" item_id in "
		"(:i00, :i01, :i02, :i03, :i04, :i05, :i06, :i07, :i08, :i09, :i10,"
		" :i10, :i11, :i12, :i13, :i14, :i15, :i16, :i17, :i18, :i19, :i20,"
		" :i20, :i21, :i22, :i23, :i24, :i25, :i26, :i27, :i28, :i29, :i30,"
		" :i30, :i31, :i32, :i33, :i34, :i35, :i36, :i37, :i38, :i39)"
		" and user_id in "
		LIST_SIZE_ID_NAMES;

	static const char* SQL_GetBidsFromTheseUsersEnded =
		"SELECT "
		" user_id,"
		" item_id,"
		" type "
		"FROM "
		" ebay_bids_ended "
		"WHERE"
		" item_id in "
		"(:i00, :i01, :i02, :i03, :i04, :i05, :i06, :i07, :i08, :i09, :i10,"
		" :i10, :i11, :i12, :i13, :i14, :i15, :i16, :i17, :i18, :i19, :i20,"
		" :i20, :i21, :i22, :i23, :i24, :i25, :i26, :i27, :i28, :i29, :i30,"
		" :i30, :i31, :i32, :i33, :i34, :i35, :i36, :i37, :i38, :i39)"
		" and user_id in "
		LIST_SIZE_ID_NAMES;

	
	int i;
	const int list_size = 40;
	int input_item_ids[list_size];
	int bidders[list_size];

	OpenAndParse(&mpCDAGetBidsFromTheseUsers, SQL_GetBidsFromTheseUsers);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":i%02d", i);
		Bind(namebuf, input_item_ids + i);
		namebuf[1] = 'u';
		Bind(namebuf, bidders + i);
	}
	
	int output_user_ids[ORA_ITEM_ARRAYSIZE];
	Define(1, output_user_ids);

	int output_item_ids[ORA_ITEM_ARRAYSIZE];
	Define(2, output_item_ids);

	int output_types[ORA_ITEM_ARRAYSIZE];
	Define(3, output_types);
	int bidder;
	for (bidder = 0; bidder < vBidderIds.size(); bidder += list_size)
	{
		int nBidders = least(vBidderIds.size() - bidder, list_size);
		memset(bidders, 0, sizeof bidders);
		copy(vBidderIds.begin() + bidder, vBidderIds.begin() + bidder + nBidders, bidders);
		
		// Now loop through the bidders.
		int input_item;
		for (input_item = 0; input_item < vItems.size(); input_item += list_size)
		{
			// Put the item ids into the item array.
			int nInputItems = least(vItems.size() - input_item, list_size);
			memset(input_item_ids, 0, sizeof input_item_ids);
			copy(vItems.begin() + input_item, vItems.begin() + input_item + nInputItems, input_item_ids);

			Execute();

			int rowsFetched = 0;
			do 
			{
				cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
				int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

				if (OracleErrorCheck(rc, mpCDAGetBidsFromTheseUsers))
				{
					// oops, error, clear out return vector.
					vBidderIdsOfBids.clear();
					vOurBidItemNumbers.clear();
					vOurBidTypes.clear();
					return;
				}

				// Otherwise, suck the stuff in.
				int n = pCDACurrent->rpc - rowsFetched;
				rowsFetched += n;

				// and jam it into the vector.
				copy(output_user_ids, output_user_ids + n, back_inserter(vBidderIdsOfBids));
				copy(output_item_ids, output_item_ids + n, back_inserter(vOurBidItemNumbers));
				copy(output_types, output_types + n, back_inserter(vOurBidTypes));
			} while (!CheckForNoRowsFound());

		}
	}

	Close(&mpCDAGetBidsFromTheseUsers);
	SetStatement(NULL);
	// now the ended
	OpenAndParse(&mpCDAGetBidsFromTheseUsersEnded, SQL_GetBidsFromTheseUsersEnded);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":i%02d", i);
		Bind(namebuf, input_item_ids + i);
		namebuf[1] = 'u';
		Bind(namebuf, bidders + i);
	}
	
	Define(1, output_user_ids);

	Define(2, output_item_ids);

	Define(3, output_types);
	for (bidder = 0; bidder < vBidderIds.size(); bidder += list_size)
	{
		int nBidders = least(vBidderIds.size() - bidder, list_size);
		memset(bidders, 0, sizeof bidders);
		copy(vBidderIds.begin() + bidder, vBidderIds.begin() + bidder + nBidders, bidders);
		
		// Now loop through the bidders.
		int input_item;
		for (input_item = 0; input_item < vItems.size(); input_item += list_size)
		{
			// Put the item ids into the item array.
			int nInputItems = least(vItems.size() - input_item, list_size);
			memset(input_item_ids, 0, sizeof input_item_ids);
			copy(vItems.begin() + input_item, vItems.begin() + input_item + nInputItems, input_item_ids);

			Execute();

			int rowsFetched = 0;
			do 
			{
				cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
				int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

				if (OracleErrorCheck(rc, mpCDAGetBidsFromTheseUsersEnded))
				{
					// oops, error, clear out return vector.
					vBidderIdsOfBids.clear();
					vOurBidItemNumbers.clear();
					vOurBidTypes.clear();
					return;
				}

				// Otherwise, suck the stuff in.
				int n = pCDACurrent->rpc - rowsFetched;
				rowsFetched += n;

				// and jam it into the vector.
				copy(output_user_ids, output_user_ids + n, back_inserter(vBidderIdsOfBids));
				copy(output_item_ids, output_item_ids + n, back_inserter(vOurBidItemNumbers));
				copy(output_types, output_types + n, back_inserter(vOurBidTypes));
			} while (!CheckForNoRowsFound());

		}
	}

	Close(&mpCDAGetBidsFromTheseUsersEnded);
	SetStatement(NULL);
}

#ifdef SLURPING_FEEDBACK
	
#include "trust.h"
void clsDatabaseOracle::GetABunchOfFeedback(int age, vector<clsFeedbackDetailPieces> &vPieces)
{
	static const char* SQL_GetABunchOfFeedback = 
		"SELECT "
		"	id, "
		"	commenting_id, "
		"	TO_CHAR(time, 'YYYY-MM-DD HH24:MI:SS'), "
		"   comment_type "
		"FROM "
		"	ebay_feedback_detail "
		"WHERE "
		"	time > sysdate - :age";


	OpenAndParse(&mpCDAOneShot, SQL_GetABunchOfFeedback);
	
	int ids[ORA_ITEM_ARRAYSIZE];
	Define(1, ids);

	int commenters[ORA_ITEM_ARRAYSIZE];
	Define(2, commenters);

	char times[ORA_ITEM_ARRAYSIZE][64];
	Define(3, times[0], sizeof times[0]);

	int types[ORA_ITEM_ARRAYSIZE];
	Define(4, types);

	Bind(":age", &age);

	Execute();
	
	struct cda_def *pCDACurrent = (struct cda_def *)mpCDACurrent;
	if (CheckForNoRowsFound())
	{
		ocan(pCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}
	int rowsFetched	= 0;

	do 
	{
		int rc = ofen(pCDACurrent,ORA_ITEM_ARRAYSIZE);
		if (OracleErrorCheck(rc, mpCDAOneShot))
		{
			// oops, error, clear out return vectors
			vPieces.clear();
			return;
		}

		int n = pCDACurrent->rpc - rowsFetched;
		rowsFetched += n;
		cerr << rowsFetched << " rows fetched" << endl << flush;

		// slurp
		for (int i = 0; i < n; i++)
		{
			time_t timex;
			ORACLE_DATEToTime(times[i], &timex);
			vPieces.push_back(clsFeedbackDetailPieces(ids[i], timex, commenters[i], types[i]));
		}
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);
}



void clsDatabaseOracle::GetFeedbackScores(const vector<int>&vIds,
										  hash_map<int, int, hash<int>, eqint>&mIdsAndScores)
{
	static const char* SQL_GetFeedbackScores =
		"select id, score "
		"from ebay_feedback "
		"where id in "
		LIST_SIZE_ID_NAMES;

	const int list_size = 40;
	int input_ids[list_size];
	int i;
	
	OpenAndParse(&mpCDAOneShot, SQL_GetFeedbackScores);

	for (i = 0; i < list_size; i++)
	{
		char namebuf[5];
		sprintf(namebuf, ":u%02d", i);
		Bind(namebuf, input_ids + i);
	}

	int output_ids[ORA_ITEM_ARRAYSIZE];
	Define(1, output_ids);

	int output_scores[ORA_ITEM_ARRAYSIZE];
	Define(2, output_scores);


	int id;
	for (id = 0; id < vIds.size(); id += list_size)
	{
		// Put the ids into the id array
		int nIds = least(vIds.size() - id, list_size);
		memset(input_ids, 0, sizeof input_ids);
		copy(vIds.begin() + id, vIds.begin() + id + nIds, input_ids);

		Execute();
		int rowsFetched = 0;
		do 
		{
			cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
			int rc = ofen(pCDACurrent, ORA_ITEM_ARRAYSIZE);

			if (OracleErrorCheck(rc, mpCDAOneShot))
			{
				// oops, error, clear out return vector.
				mIdsAndScores.erase(mIdsAndScores.begin(), mIdsAndScores.end());
				return;
			}

			// Otherwise, suck the stuff in.
			int n = pCDACurrent->rpc - rowsFetched;
			rowsFetched += n;

			// and jam it into the vector.
			for (i = 0; i < n; i++)
				mIdsAndScores[output_ids[i]] = output_scores[i];

		} while (!CheckForNoRowsFound());
	}
	Close(&mpCDAOneShot);
	SetStatement(NULL);
}



#endif
