/*	$Id: clsDatabaseOracleBDCategoryInfo.cpp,v 1.5 1998/10/16 01:05:54 josh Exp $	*/
//
// File Name:		clsDatabaseOracleBDCategoryInfo.cpp
//
// Description:		The oracle calls to store and retrieve information
//					used in the business development project.
//
#include "eBayKernel.h"

#define ORACLE_BDFETCH_ARRAY_SIZE 500

static const char *SQL_CreateEmptyPartnerCountRecord =
 "insert into ebay_partner_referral_counts				\
	(hitcount, forday, id, confirmed_registrations,		\
	all_registrations, total_confirmed_registrations)	\
	values												\
	(0, TO_DATE(:forday, 'YYYY-MM-DD HH24:MI:SS'),		\
	:id, 0, 0, 0)";

void clsDatabaseOracle::CreateEmptyPartnerCountRecord(int id,
													  const char *pTime)
{
	int rc;
	// Open and parse to create. use the one shot cursor
	// since we shouldn't have to do this very often, and
	// there's no need to let the cursor hang around.
	OpenAndParse(&mpCDAOneShot, SQL_CreateEmptyPartnerCountRecord);

	// Bind our variables.
	Bind(":id", &id);
	Bind(":forday", pTime);

	// Now, execute. We don't do Execute, because we might cause
	// a constraint violation here if someone slipped past us.
	rc = oexec((struct cda_def *)mpCDACurrent);

	if (((struct cda_def *)mpCDACurrent)->rc != 1)
	{
		// No violation. Check, and then commit.
		Check(rc);
		Commit();
		// And clean up.
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}
	else
	{
		// If we get here, we caused a unique violation
		// Let's clean up, shall we?
		// It was already there -- no need to dwell on it.
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}
}

static const char *SQL_IncrementPartnerCount =
	"update ebay_partner_referral_counts		\
	set hitcount = hitcount + 1 where			\
	id = :id and forday =						\
	TO_DATE(:forday, 'YYYY-MM-DD HH24:MI:SS')";

void clsDatabaseOracle::IncrementPartnerCount(int id)
{
	time_t theTime;
	struct tm *tp;
	char forday[32];

	// Convert the time to the current day.
	// Get the time now.
	theTime = time(NULL);
	// Make a struct of it.
	tp = localtime(&theTime);

	// Set the hour, minute and second to zero, thus giving us midnight.
	tp->tm_sec = 0;
	tp->tm_min = 0;
	tp->tm_hour = 0;

	// And make it an oracle time.
	TM_STRUCTToORACLE_DATE(tp, forday);

	// Open and parse to increment.
	OpenAndParse(&mpCDAIncrementPartnerCount, SQL_IncrementPartnerCount);

	// Bind our variables.
	Bind(":id", &id);
	Bind(":forday", forday);

	Execute();

	// If we didn't update anything, we need to create.
	if (!CheckForNoRowsUpdated())
	{
		// Good, we updated, so clean up.
		Commit();
		Close(&mpCDAIncrementPartnerCount);
		SetStatement(NULL);
	}
	else
	{
		// First, clean up from before.
		Commit();
		Close(&mpCDAIncrementPartnerCount);
		SetStatement(NULL);

		// Create a record.
		CreateEmptyPartnerCountRecord(id, forday);
		// And recurse.
		IncrementPartnerCount(id);
	}
}

static const char *SQL_GetUserCountsConfirmed =
 "select count(*), partner_id from ebay_user_info ui,				\
	ebay_users u													\
	where u.id = ui.id and u.user_state = 1 and						\
	ui.creation >= TO_DATE(:forWhen, 'YYYY-MM-DD HH24:MI:SS')		\
	and ui.creation < TO_DATE(:forWhenEnd, 'YYYY-MM-DD HH24:MI:SS')	\
	group by partner_id";

static const char *SQL_GetUserCountsAll =
 "select count(*), partner_id from ebay_user_info ui				\
	where ui.creation >= TO_DATE(:forWhen, 'YYYY-MM-DD HH24:MI:SS')	\
	and ui.creation < TO_DATE(:forWhenEnd, 'YYYY-MM-DD HH24:MI:SS')	\
	group by partner_id";

static const char *SQL_GetTotalUserCountsConfirmed =
 "select SUM(cs), ps from											\
 (select count(*) cs, NVL(partner_id, 0) ps from ebay_user_info ui,	\
	ebay_users u													\
	where u.id = ui.id and u.user_state = 1 and						\
	ui.creation < TO_DATE(:forWhenEnd, 'YYYY-MM-DD HH24:MI:SS')		\
	group by partner_id) group by ps";

static const char *SQL_SetConfirmedUserCount =
 "update ebay_partner_referral_counts						\
	set confirmed_registrations = :confirmed_registrations	\
	where id = :id and forday =								\
	TO_DATE(:when, 'YYYY-MM-DD HH24:MI:SS')";

static const char *SQL_SetAllUserCount =
 "update ebay_partner_referral_counts						\
	set all_registrations = :all_registrations				\
	where id = :id and forday =								\
	TO_DATE(:when, 'YYYY-MM-DD HH24:MI:SS')";

static const char *SQL_SetTotalConfirmedRegistrations =
 "update ebay_partner_referral_counts						\
	set total_confirmed_registrations =						\
	:total_confirmed_registrations							\
	where id = :id and forday =								\
	TO_DATE(:when, 'YYYY-MM-DD HH24:MI:SS')";

void clsDatabaseOracle::CountPartnerRegistrations(time_t forWhen)
{
	struct tm *tp;
	char when[32];
	char whenEnd[32];
	vector<int> vCounts;
	vector<int>::iterator j;
	int count;
	int id;
	int i;

	// First, get the times.
	tp = localtime(&forWhen);
	// Set the hour, minute and second to zero, thus giving us midnight.
	tp->tm_sec = 0;
	tp->tm_min = 0;
	tp->tm_hour = 0;

	// And make it an oracle time.
	TM_STRUCTToORACLE_DATE(tp, when);

	// Now, convert back to time_t
	forWhen = mktime(tp);
	// Add at least a day, remembering daylight savings.
	forWhen += 86501;
	// Convert back to tm.
	tp = localtime(&forWhen);
	// Drop the other stuff again.
	tp->tm_sec = 0;
	tp->tm_min = 0;
	tp->tm_hour = 0;

	// And make it an oracle time.
	TM_STRUCTToORACLE_DATE(tp, whenEnd);

	// Do the confirmed users first.
	OpenAndParse(&mpCDAOneShot, SQL_GetUserCountsConfirmed);

	// Bind the dates.
	Bind(":forWhen", when);
	Bind(":forWhenEnd", whenEnd);

	// Define the outputs.
	Define(1, &count);
	Define(2, &id);

	Execute();

	do
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		// Get enough elements in the vector, if we need.
		if (id >= vCounts.size())
		{
			i = id - vCounts.size() + 1;
			while (i--)
				vCounts.push_back(0);
		}

		vCounts[id] = count;
	} while (1 == 1);

	// Close our cursor and such.
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (!vCounts.empty())
	{
		for (j = vCounts.begin(); j != vCounts.end(); ++j)
		{
			// Don't update where it's 0.
			if (!*j)
				continue;

			// Set the count.
			count = *j;
			// And the id;
			id = j - vCounts.begin();

			// Now update the confirmed users.
			OpenAndParse(&mpCDAOneShot, SQL_SetConfirmedUserCount);

			Bind(":when", when);
			Bind(":confirmed_registrations", &count);
			Bind(":id", &id);

			Execute();

			if (CheckForNoRowsUpdated())
			{
				// We didn't update, so we need to create.
				Close(&mpCDAOneShot);
				SetStatement(NULL);
				CreateEmptyPartnerCountRecord(id, when);
				// And try again.
				OpenAndParse(&mpCDAOneShot, SQL_SetConfirmedUserCount);

				Bind(":when", when);
				Bind(":confirmed_registrations", &count);
				Bind(":id", &id);
				Execute();
				Commit();
			}
			else
				Commit();

			Close(&mpCDAOneShot);
			SetStatement(NULL);
		}
	}

	vCounts.clear();

	// Do the 'ever confirmed' users next.
	OpenAndParse(&mpCDAOneShot, SQL_GetTotalUserCountsConfirmed);

	// Bind the dates.
	Bind(":forWhenEnd", whenEnd);

	// Define the outputs.
	Define(1, &count);
	Define(2, &id);

	Execute();

	do
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		// Get enough elements in the vector, if we need.
		if (id >= vCounts.size())
		{
			i = id - vCounts.size() + 1;
			while (i--)
				vCounts.push_back(0);
		}

		vCounts[id] = count;
	} while (1 == 1);

	// Close our cursor and such.
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (!vCounts.empty())
	{
		for (j = vCounts.begin(); j != vCounts.end(); ++j)
		{
			// Don't update where it's 0.
			if (!*j)
				continue;

			// Set the count.
			count = *j;
			// And the id;
			id = j - vCounts.begin();

			// Now update the confirmed users.
			OpenAndParse(&mpCDAOneShot, SQL_SetTotalConfirmedRegistrations);

			Bind(":when", when);
			Bind(":total_confirmed_registrations", &count);
			Bind(":id", &id);

			Execute();

			if (CheckForNoRowsUpdated())
			{
				// We didn't update, so we need to create.
				Close(&mpCDAOneShot);
				SetStatement(NULL);
				CreateEmptyPartnerCountRecord(id, when);
				// And try again.
				OpenAndParse(&mpCDAOneShot, SQL_SetTotalConfirmedRegistrations);

				Bind(":when", when);
				Bind(":total_confirmed_registrations", &count);
				Bind(":id", &id);
				Execute();
				Commit();
			}
			else
				Commit();

			Close(&mpCDAOneShot);
			SetStatement(NULL);
		}
	}

	vCounts.clear();

	// Lastly do the 'all for the day' users.
	OpenAndParse(&mpCDAOneShot, SQL_GetUserCountsAll);

	// Bind the dates.
	Bind(":forWhen", when);
	Bind(":forWhenEnd", whenEnd);

	// Define the outputs.
	Define(1, &count);
	Define(2, &id);

	Execute();

	do
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		// Get enough elements in the vector, if we need.
		if (id >= vCounts.size())
		{
			i = id - vCounts.size() + 1;
			while (i--)
				vCounts.push_back(0);
		}

		vCounts[id] = count;
	} while (1 == 1);

	// Close our cursor and such.
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (!vCounts.empty())
	{
		for (j = vCounts.begin(); j != vCounts.end(); ++j)
		{
			// Don't update where it's 0.
			if (!*j)
				continue;

			// Set the count.
			count = *j;
			// And the id;
			id = j - vCounts.begin();

			// Now update the confirmed users.
			OpenAndParse(&mpCDAOneShot, SQL_SetAllUserCount);

			Bind(":when", when);
			Bind(":all_registrations", &count);
			Bind(":id", &id);

			Execute();

			if (CheckForNoRowsUpdated())
			{
				// We didn't update, so we need to create.
				Close(&mpCDAOneShot);
				SetStatement(NULL);
				CreateEmptyPartnerCountRecord(id, when);
				// And try again.
				OpenAndParse(&mpCDAOneShot, SQL_SetAllUserCount);

				Bind(":when", when);
				Bind(":all_registrations", &count);
				Bind(":id", &id);
				Execute();
				Commit();
			}
			else
				Commit();

			Close(&mpCDAOneShot);
			SetStatement(NULL);
		}
	}
	vCounts.clear();
}

static const char *SQL_GetOnePartnerData =
 "select hitcount, confirmed_registrations, all_registrations,	\
	total_confirmed_registrations from							\
	ebay_partner_referral_counts								\
	where id = :id and forday =									\
	TO_DATE(:forday, 'YYYY-MM-DD HH24:MI:SS')";

void clsDatabaseOracle::GetOnePartnerData(int id,
										  time_t forWhen,
										  int *hitcount,
										  int *new_users,
										  int *new_users_total,
										  int *new_users_ever)
{
	struct tm *tp;
	char when[32];

	tp = localtime(&forWhen);
	tp->tm_hour = 0;
	tp->tm_min = 0;
	tp->tm_sec = 0;

	TM_STRUCTToORACLE_DATE(tp, when);

	OpenAndParse(&mpCDAOneShot, SQL_GetOnePartnerData);

	Bind(":id", &id);
	Bind(":forday", when);

	Define(1, hitcount);
	Define(2, new_users);
	Define(3, new_users_total);
	Define(4, new_users_ever);

	ExecuteAndFetch();
	if (CheckForNoRowsFound())
	{
		*new_users_total = *hitcount = *new_users = *new_users_ever = 0;
	}
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_GetPartnerIds =
	"select id, partner_name from ebay_agg_partner_browser_list	\
	where entry_type = 0 order by id";

void clsDatabaseOracle::GetPartnerIds(vector<const char *> *pvPartners)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		names[ORACLE_BDFETCH_ARRAY_SIZE][256];
	int			partnerId[ORACLE_BDFETCH_ARRAY_SIZE];
	int			onIndex;

	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetPartnerIds);

	// Define
	Define(1, (int *) partnerId);
	Define(2, (char *) names, sizeof(names[0]));

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	onIndex = -1;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent, ORACLE_BDFETCH_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BDFETCH_ARRAY_SIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{
			if (onIndex < partnerId[i])
			{
				do
				{
					pvPartners->push_back((const char *) NULL);
					++onIndex;
				} while (onIndex < partnerId[i]);
			}
			(*pvPartners)[partnerId[i]] = (const char *) strdup(names[i]);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}
	
static const char *SQL_AddPartnerData =
  "insert into ebay_agg_partner_data	\
	(partner_id,						\
	 page_views,						\
	 covers_day,						\
	 new_user_registrations,			\
	 new_user_registrations_ever)		\
	values								\
	(:partner_id,						\
	 :page_views,						\
	  TO_DATE(:covers_day,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
	 :new_user_registrations,			\
	 :new_user_registrations_ever)";

void clsDatabaseOracle::AddPartnerData(int id,
									   int views,
									   time_t coversDay,
									   int newUserRegistrations,
									   int newUserRegistrationsEver)
{
	struct tm			*pTheTime;
	char				when[32];

	// Convert
	pTheTime = localtime(&coversDay);
	TM_STRUCTToORACLE_DATE(pTheTime, when);

	OpenAndParse(&mpCDAOneShot,
				 SQL_AddPartnerData);


	// Bind
	Bind(":partner_id", &id);
	Bind(":page_views", &views);
	Bind(":covers_day", (char *) when);
	Bind(":new_user_registrations", &newUserRegistrations);
	Bind(":new_user_registrations_ever", &newUserRegistrationsEver);

	// Do it
	Execute();

	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

