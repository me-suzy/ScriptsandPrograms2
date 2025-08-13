/*	$Id: clsDatabaseOracleGiftOccasions.cpp,v 1.2 1998/12/06 05:31:55 josh Exp $	*/
//
//	File:	clsDatabaseOracleGiftOccasions.cc
//
//	Class:	clsDatabaseOracleGiftOccasions
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 10/27/98 mila		- Created

#include "eBayKernel.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>

#include "clsGiftOccasion.h"

#define ORA_GIFT_OCCASION_ID_ARRAYSIZE 50

//
// GetActiveGiftOccasions 
//
static const char SQL_GetActiveGiftOccasions[] =
	"select	id,								\
			name,							\
			greeting,						\
			flags,							\
			header,							\
			footer							\
		from ebay_gift_occasions			\
		where marketplace = :marketplace	\
		order by id";

void clsDatabaseOracle::GetActiveGiftOccasions(MarketPlaceId marketplace,
							vector<clsGiftOccasion *> *pvOccasions)
{
	int		id[ORA_GIFT_OCCASION_ID_ARRAYSIZE];
	char	name[ORA_GIFT_OCCASION_ID_ARRAYSIZE][32];
	char	greeting[ORA_GIFT_OCCASION_ID_ARRAYSIZE][32];
	char	header[ORA_GIFT_OCCASION_ID_ARRAYSIZE][32];
	sb2		header_ind;
	char	footer[ORA_GIFT_OCCASION_ID_ARRAYSIZE][32];
	sb2		footer_ind;
	int		flags[ORA_GIFT_OCCASION_ID_ARRAYSIZE];
	int		i, n, rowsFetched, rc;

	clsGiftOccasion	*pOccasion;

	OpenAndParse(&mpCDAOneShot, SQL_GetActiveGiftOccasions);

	// Ok, let's do the binds
	Bind(":marketplace", (int *)&marketplace);

	// And the defines
	Define(1, id);
	Define(2, (char *)name, sizeof(name[0]));
	Define(3, (char *)greeting, sizeof(greeting[0]));
	Define(4, flags);
	Define(5, (char *)header, sizeof(header[0]), &header_ind);
	Define(6, (char *)footer, sizeof(footer[0]), &footer_ind);

	// Execute
	Execute();

	// Now, we do the standard array fetch thing.
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_GIFT_OCCASION_ID_ARRAYSIZE);

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
		// (always <= ORA_USER_ID_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if (flags[i] & GiftOccasionFlagActive)
			{
				pOccasion = new clsGiftOccasion(marketplace,
												id[i],
												name[i],
												greeting[i],
												header[i],
												footer[i],
												flags[i]);
				pvOccasions->push_back(pOccasion);
			}
		}
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
//	GetGiftOccasion
//
static char *SQL_GetGiftOccasionById =
	"select	id,								\
			name,							\
			greeting,						\
			flags,							\
			header,							\
			footer							\
		from ebay_gift_occasions			\
		where	marketplace = :marketplace	\
		and		id = :id";

bool clsDatabaseOracle::GetGiftOccasion(MarketPlaceId marketplace,
										int id,
										clsGiftOccasion *pOccasion)
{
	// Temporary slots for things to live in
	int		occasionId;
	char	name[255];
	char	greeting[255];
	int		flags;

	char	header[65];
	sb2		header_ind;
	char	*pHeader;

	char	footer[256];
	sb2		footer_ind;
	char	*pFooter;

	char	*pName;
	char	*pGreeting;

	OpenAndParse(&mpCDAGetSingleGiftOccasion, SQL_GetGiftOccasionById);

	// Bind the input variables
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &occasionId);
	Define(2, (char *)name, sizeof(name));
	Define(3, (char *)greeting, sizeof(greeting));
	Define(4, &flags);
	Define(5, (char *)header, sizeof(header), &header_ind);
	Define(6, (char *)footer, sizeof(footer), &footer_ind);

	// Fetch
	ExecuteAndFetch();

	// if no item found, then return
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetSingleGiftOccasion);
		SetStatement(NULL);
		return false;
	}

	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item
	pName		= new char[strlen(name) + 1];
	strcpy(pName, (char *)name);

	pGreeting	= new char[strlen(greeting) + 1];
	strcpy(pGreeting, (char *)greeting);

	if (header_ind == -1)
	{
		pHeader	= NULL;
	}
	else
	{
		pHeader	= new char[strlen(header) + 1];
		strcpy(pHeader, header);
	}
	
	if (footer_ind == -1)
	{
		pFooter	= NULL;
	}
	else
	{
		pFooter	= new char[strlen(footer) + 1];
		strcpy(pFooter, footer);
	}

	// Fill in the item
	pOccasion->Set(marketplace,
				   occasionId,
				   pName,
				   pGreeting,
				   pHeader,
				   pFooter,
				   flags);

	Close(&mpCDAGetSingleGiftOccasion);
	SetStatement(NULL);

	return true;
}

//
//	AddGiftOccasion
//
static const char*SQL_AddGiftOccasion =
	"insert into ebay_gift_occasions	\
	(	marketplace,					\
		id,								\
		name,							\
		greeting,						\
		flags,							\
		headerfile,						\
		footerfile						\
	)									\
	values								\
	(	:marketplace,					\
		:id,							\
		:name,							\
		:greeting,						\
		:flags,							\
		:headerfile,					\
		:footerfile						\
	)";

void clsDatabaseOracle::AddGiftOccasion(clsGiftOccasion *pOccasion)
{
	int			marketplace;
	int			id;
	char		name[32] = {0};
	char		greeting[32] = {0};
	int			flags;

	char		*pHeader;
	sb2			header_null;
	char		nullHeader	= '\0';

	char		*pFooter;
	sb2			footer_null;
	char		nullFooter	= '\0';

	// Extract info into our local variables
	// to prevent any casting confusion
	marketplace = (int)pOccasion->GetMarketPlaceId();
	id = pOccasion->GetId();

	pHeader = pOccasion->GetHeader();

	if (pHeader == NULL)
	{
		header_null = -1;
	}
	else
	{
		header_null = 0;
	}
	
	pFooter	= pOccasion->GetFooter();

	if (pFooter == NULL)
	{
		footer_null	= -1;
	}
	else
	{
		footer_null	= 0;
	}
	
	// Get the next item id
	if (id == 0)
		id	= GetNextGiftOccasionId();

	// We don't use this statement very often,
	// so the cursor's not persistant. Let's 
	// prepare the statement
	OpenAndParse(&mpCDAAddGiftOccasion, SQL_AddGiftOccasion);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":name", (char *)name);
	Bind(":greeting", (char *)greeting);
	Bind(":flags", &flags);

	if (pHeader)
		Bind(":header", pHeader);
	else
		Bind(":header", (char *)&nullHeader, &header_null);

	if (pFooter)
		Bind(":footer", pFooter);
	else
		Bind(":footer", (char *)&nullFooter, &footer_null);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAAddItem);
	SetStatement(NULL);

	return;
}

//
//	AddGiftOccasions
//
void clsDatabaseOracle::AddGiftOccasions(vector<clsGiftOccasion *> *pvOccasions)
{
	vector<clsGiftOccasion *>::const_iterator	i;

	for (i = pvOccasions->begin(); i != pvOccasions->end(); ++i)
	{
		AddGiftOccasion(*i);
	}
}

//
//	DeleteAllGiftOccasions
//
static char *SQL_DeleteAllGiftOccasions =
	"delete from ebay_gift_occasions where marketplace = :marketplace";

void clsDatabaseOracle::DeleteAllGiftOccasions(MarketPlaceId marketplace)
{
	OpenAndParse(&mpCDAOneShot, SQL_DeleteAllGiftOccasions);

	Bind(":marketplace", (int *)&marketplace);

	Execute();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	Commit();

	return;
}

//
//	DeleteGiftOccasion
//
static char *SQL_DeleteGiftOccasion =
	"delete from ebay_gift_occasions			\
		where	marketplace = :marketplace		\
		and		id = :id";

void clsDatabaseOracle::DeleteGiftOccasion(MarketPlaceId marketplace,
										   int id)
{
	OpenAndParse(&mpCDADeleteGiftOccasion, SQL_DeleteGiftOccasion);

	// Ok, let's do the bind
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	// Just do it!
	Execute();
	Commit();

	Close(&mpCDADeleteGiftOccasion);

	return;
}

//
// UpdateGiftOccasion
//
static const char *SQL_UpdateGiftOccasion =
	"update ebay_gift_occasions				\
		set	name = :name,					\
			greeting = :greeting,			\
			flags = :flags,					\
			header = :header,				\
			footer = :footer				\
		where marketplace = :marketplace	\
		and id = :id";

void clsDatabaseOracle::UpdateGiftOccasion(clsGiftOccasion *pOccasion)
{
	int			marketplace;
	int			id;
	char		name[32] = {0};
	char		greeting[32] = {0};
	int			flags;

	char		*pHeader;
	sb2			header_null;
	char		nullHeader	= '\0';
	
	char		*pFooter;
	sb2			footer_null;
	char		nullFooter	= '\0';

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplace = pOccasion->GetMarketPlaceId();
	id = pOccasion->GetId();

	pHeader	= pOccasion->GetHeader();
	if (pHeader == NULL)
	{
		pHeader	= (char  *)&nullHeader;
		header_null	= -1;
	}
	else
		header_null	= 0;

	pFooter	= pOccasion->GetFooter();
	if (pFooter == NULL)
	{
		pFooter	= (char  *)&nullFooter;
		footer_null	= -1;
	}
	else
		footer_null	= 0;

	OpenAndParse(&mpCDAOneShot, SQL_UpdateGiftOccasion);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":name", (char *)pOccasion->GetName());
	Bind(":greeting", (char *)pOccasion->GetGreeting());
	Bind(":flags", &flags);

	if (pHeader)
		Bind(":header", pHeader);
	else
		Bind(":header", pHeader, header_null);
	
	if (pFooter)
		Bind(":footer", pFooter);
	else
		Bind(":footer", pFooter, footer_null);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetNextOccasionId
//
// Retrieves the next availible occasion id.
//
static const char *SQL_GetNextGiftOccasionId =
	"select ebay_gift_occasions_sequence.nextval from dual";

int clsDatabaseOracle::GetNextGiftOccasionId()
{
	int			nextId;

	// Not used often, so we don't need a persistent
	// cursor
	OpenAndParse(&mpCDAGetNextGiftOccasionId, SQL_GetNextGiftOccasionId);
	Define(1, &nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAGetNextGiftOccasionId);
	SetStatement(NULL);

	return nextId;
}

//
// SetGiftOccasionFlags
//
static const char *SQL_SetGiftOccasionFlags = 
	"update ebay_gift_occasions					\
		set		flags = :flags					\
		where	marketplace = :marketplace		\
		and		id = :id";

void clsDatabaseOracle::SetGiftOccasionFlags(MarketPlaceId marketplace,
											 int id, 
											 int flags)
{
	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_SetGiftOccasionFlags);

	// Bind
	Bind(":flags", &flags);
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	// Do it!
	Execute();
	Commit();

	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// 
// GetGiftOccasionFlags
//
static const char *SQL_GetGiftOccasionFlags = 
	"select	flags							\
		from ebay_gift_occasions			\
		where	marketplace = :marketplace	\
		and		id = :id";

int clsDatabaseOracle::GetGiftOccasionFlags(MarketPlaceId marketplace,
											int id)
{
	int	flags = 0;  

	OpenAndParse(&mpCDAOneShot, SQL_GetGiftOccasionFlags);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	Define(1, &flags);

	// Do it
	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return flags;
}



