/*	$Id: clsAuditAccountingApp.cpp,v 1.2 1999/02/21 02:20:57 josh Exp $	*/
//
//	File:	clsAuditAccountingApp.cc
//
//	Class:	clsAuditAccountingApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsAuditAccountingApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"
#include "clsAccountDetail.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#define FLUSH_TO_END									\
	do													\
	{													\
		if (!fgets(buf, sizeof(buf), pFile))			\
		{												\
			done  = true;								\
			break;										\
		}												\
		recCount++;										\
		if (strncmp(buf, BeginRec, BeginRecLen) == 0)	\
			break;										\
	} while (1==1);					

static float RoundToCents(float it)
{
	float		itCents;
	int			itInt;

	itCents	= it * 100;
	itCents	= itCents + .5;
	itInt	= (int)itCents;
	it		= (float)itInt / 100;

	return it;
}

char *
split(register char *s, 
	  register const char *delim)
{
	register char	*spanp;
	register int	c, sc;
	char			*tok;
	static char		*last;

	if (s == NULL && (s = last) == NULL)
		return (NULL);

	/*
	 * Skip (span) leading delimiters (s += strspn(s, delim), sort of).
	 */
		/*cont:*/
	c = *s++;

	if (c == 0) {           /* no non-delimiter characters */
			last = NULL;
			return (NULL);
	}
	tok = s - 1;

	/*
	 * Scan token (scan for delimiters: s += strcspn(s, delim), sort of).
	 * Note that delim must have one NUL; we stop if we see that, too.
	 */
	for (;;) {
			/* c = *s++; ** pmo - move to bottom of loop */
			spanp = (char *)delim;
	do {
			if ((sc = *spanp++) == c) {
					if (c == 0)
							s = NULL;
					else
							s[-1] = 0;
					last = s;
					return (tok);
			}
	} while (sc != 0);
		c = *s++;       /* pmo - move to bottom of loop */
	}
/* NOTREACHED */
}

//
// Sort routine
//
static bool sort_account_detail_time(clsAccountDetail *pA, clsAccountDetail *pB)
{
	if (pA->mTime < pB->mTime)
		return true;

	return false;
}



clsAuditAccountingApp::clsAuditAccountingApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;

	return;
}


clsAuditAccountingApp::~clsAuditAccountingApp()
{
	return;
}

// Important Defines
#define TIME_SIZE		32
#define	MEMO_SIZE		80	
#define	ITEM_SIZE		12
#define	ARRAY_SIZE		1	

const char *BeginRec		= "BEGIN\t";
const int	BeginRecLen		= 6;
const char *EndRec			= "END\t";
const int	EndRecLen		= 4; 

void clsAuditAccountingApp::Run(int BatchId)
{
	// File stuff
	char			*pFileName = "accounts.txt";
	FILE			*pFile;
	char			buf[1024];
	char			buf2[1024];
	char			*s;

	// Counters and stuff
	bool			done;
	bool			badRecord;
	int				recCount;
	int				rejectCount	= 0;
	int				acceptCount	= 0;
	int				userCount	= 0;

	// Things about the current user
	char			*pUserName;
	int				userNameLen;
	int				theAWId;

	char			*pItemId;
	char			*pComma;

	// The real current user and things about them.
	clsUser							*pUser;
	clsAccount						*pAccount;
	AccountDetailVector				vAccountDetail;
	AccountDetailVector::iterator	vADi;

	int					userRecCount;
	int					userRecFoundCount;
	int					userRecMissingCount;
	int					userRejectCount;
	int					userAcceptCount;

	char				*pEndUserName;

	// These fields represent the individual accounting
	// detail records. We don't use array insert because
	// recovery is SUCH a pain;
	int						theUserId;
	time_t					theTime;
	const struct tm			*pTheTimeAsTM;
	char					cTheTime[32];
	AccountDetailTypeEnum	theType;
	double					theAmount;
	int						memoLen;
	char					theMemo[MEMO_SIZE + 1];
	char					theItem[ITEM_SIZE + 1];

	// Date conversion
	struct tm				*pTheTime;

	// Arrays of things
	int				id[ARRAY_SIZE];
	char			date[ARRAY_SIZE][32];
	int				action[ARRAY_SIZE];
	float			amount[ARRAY_SIZE];
	int				transactionId[ARRAY_SIZE];
	char			memo[ARRAY_SIZE][MEMO_SIZE + 1];
	int				migrationBatchId[ARRAY_SIZE];

	int				iItem;
	unsigned int	itemTransactionId[ARRAY_SIZE];
	char			item[ARRAY_SIZE][ITEM_SIZE +1];

	if (!mpMarketPlaces)
		mpMarketPlaces	= gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();


	// File shenanigins
	pFile	= fopen(pFileName, "r");

	if (!pFile)
	{
		fprintf(stderr,
				"Error %s opening %s\n",
				strerror(errno), 
				pFileName);
		return;
	}

	// Ok, here we gooooo
	done			= false;
	recCount		= 0;

	// Prime the pump
	if (!fgets(buf, sizeof(buf), pFile))
	{
		done  = true;
	}
	recCount++;

	// Ok, here we go
	do
	{
		// Here, we expect a user record to be in the buffer,
		// which tells us which user(s) the feedback is for.
		// Figure out who the user is...
		if (memcmp(buf, BeginRec, sizeof(BeginRec)) != 0)
		{
			fprintf(stderr, "Begin record missing in <%s>\n", buf);
			exit(1);
		}

		pUserName	= buf + BeginRecLen;
		if ((s = split(pUserName, "\t")) && *s)
		{
			pUserName	= s;
			userNameLen	= strlen(pUserName);
		}
		else
		{
			fprintf(stderr, "No User Name found in <%s>\n", buf);
			FLUSH_TO_END;
			continue;
		}

		if (strlen(pUserName) > EBAY_MAX_USERID_SIZE)
		{
			fprintf(stderr, "User Name <%s> too long\n",
					pUserName);

			FLUSH_TO_END;

			continue;
		}

		// Let's find the user
		pUser	= mpUsers->GetUser(pUserName);
		if (!pUser)
		{
			printf("User <%s> NOT found. Flushing\n",
				 	 pUserName);

			FLUSH_TO_END;
			continue;

		}

		theUserId	= pUser->GetId();
		printf("Begin processing User <%s>/<%s>\n",
				pUserName, pUser->GetUserId());


		// Ok, we're ready. Let's get the user's account information
		pAccount	= pUser->GetAccount();

		// And the detail...
		pAccount->GetAccountDetail(&vAccountDetail);

		// Sort it, and position the iterator
		sort(vAccountDetail.begin(), vAccountDetail.end(),
			 sort_account_detail_time);

		vADi	= vAccountDetail.begin();

		// Now, let's fetch, decode, and handle those records
		userRecCount		= 0;
		userRecFoundCount	= 0;
		userRecMissingCount	= 0;
		userRejectCount		= 0;
		userAcceptCount		= 0;
		iItem				= 0;
		do
		{
			// Fetch the next record. If we're at EOF,
			// then just break out.
			badRecord	= false;
			if (!fgets(buf, sizeof(buf), pFile))
			{
				done	= true;
				break;
			}
			recCount++;
			strcpy(buf2, buf);

			// See if it's a signal record
			if (strncmp(buf, EndRec, EndRecLen) == 0)
			{
				pEndUserName	= buf + EndRecLen;
				if ((s = split(pEndUserName, "\t")) && *s)
				{
					pEndUserName	= s;
				}
				else
				{
					fprintf(stderr, "No Ending User Name found in <%s>\n", buf);
					continue;
				}

				if (strcmp(pEndUserName, pUser->GetUserId()) != 0)
				{
					fprintf(stderr, "Begin/End User name mismatch! Begin <%s>, End <%s>\n",
							pUser->GetUserId(), pEndUserName);
				}


				// Print out interesting things
				printf(
				"User <%s>, AW Account %d: %d/%d/%d/%d/%d\n",
				pUser->GetUserId(), theAWId, userRecCount, userRecFoundCount,
				userRecMissingCount, userAcceptCount, userRejectCount
						);

				// Bump the user count
				userCount++;

				// Look for the next Begin
				if (!fgets(buf, sizeof(buf), pFile))
				{
					done	= true;
					break;
				}

				// If we find a begin without an end, then
				// something's up. It's ok, we can recover
				// though
				if (strncmp(buf, BeginRec, BeginRecLen) == 0)
				{
					break;
				}
				else
				{
					fprintf(stderr, "End without Begin!\n");
					exit(1);
				}
			}

			// Make sure we didn't miss something
			if (strncmp(buf, BeginRec, BeginRecLen) == 0)
			{
				fprintf(stderr, "Warning! Begin found without End!\n");
				exit(1);
			}

			//	We know this isn't a delimiting record, so let's
			//	count it.
			userRecCount++;

			
			// Now the time
			if ((s = split(buf, "\t")) && *s)
			{
				theTime		= atol(s);
			}
			else
			{
				fprintf(stderr, "Can\'t find time! in <%s>\n",
						  buf2);
				badRecord	= true;
			}

			// Now, the "type". AuctionWeb only had 3 types --
			// "New", "C" and "D". We'll start with that, and
			// hope we can do better.
			if (!badRecord &&
				((s = split(NULL, "\t")) && *s))
			{
				// If it's a "new" record, just ignore it
				if (strcmp(s, "NEW") == 0)
				{
					continue;
				}
				else if (strcmp(s, "C") == 0)
				{
					theType		= 	AccountDetailAWCredit;
				}
				else if (strcmp(s, "D") == 0)
				{
					theType		=	AccountDetailAWDebit;
				}
				else if (strcmp(s, "M") == 0)
				{
					theType		= AccountDetailAWMemo;
				}
				else
				{
					fprintf(stderr, "Bad Type in <%s>!\n",
							  buf2);
					badRecord	= true;
				}
			}
			else
			{
				fprintf(stderr, "Can\'t find type in <%s>!!!\n",
						  buf2);
				badRecord	= true;
			}


			// We assume the input records are sorted by time,
			// and we know the account records are.
			for (;
				 vADi != vAccountDetail.end();
				 vADi++)
			{
				// If we got it, we're gold!
				if ((*vADi)->mTime == theTime)
					break;

				if ((*vADi)->mTime > theTime)
					break;
			}

			//	Ok, if the times are equal, we found the 
			//	item, Just count it and be done!
			if ((*vADi)->mTime == theTime)
			{
				userRecFoundCount++;
				continue;
			}

			//	Otherwise, we must not have found it, and
			//	have to make a new one.
			userRecMissingCount++;
			pTheTimeAsTM	= localtime(&theTime);
			strftime(cTheTime, sizeof(cTheTime),
					 "%m/%d/%y %H:%M:%S",
					 pTheTimeAsTM);

			printf("%s %s missing %s\n",
				   pUser->GetUserId(),
				   cTheTime,
				   buf2);



			// Now, the amount
			if (theType != AccountDetailAWMemo)
			{
				if (!badRecord)
				{
					if ((s = split(NULL,"\t")) && *s)
					{
						theAmount	= atof(s);
					}
					else
					{
						fprintf(stderr, "Can\'t find amount in <%s>!!!\n",
								  buf2);
						badRecord	= true;
					}
				}
			}
			else
				theAmount	= 0;

			if (theAmount > 99999999.99 ||
				 theAmount < -99999999.99)
			{
				fprintf(stderr, "Amount %f too large in <%s>. Skipped!\n",
						  theAmount, buf);
				badRecord	= true;
			}

			// Finally...zee comment!
			if (!badRecord)
			{
				if ((s = split(NULL, "\t")) && *s)
				{
					memoLen	= strlen(s);
					if (*(s + memoLen - 1) == '\n')
              	    *(s + memoLen - 1) = '\0';				
					if (strlen(s) > MEMO_SIZE)
					{
						fprintf(stderr, "Memo <%s> too big in <%s>!\n", s, buf2);
						badRecord	= true;
					}
					else if (strlen(s) > 0)
					{
						strcpy(theMemo, s);
					}
					else
					{
						fprintf(stderr, "Zero Len Comment!\n");
						strcpy(s, " ");
					}
				}
				else
				{
					fprintf(stderr, "No Memo!\n");
					strcpy(theMemo, " ");
				}
			}
			// All done! If we don't have a "bad" record,
			// then we should advance our pointers
			if (badRecord)
			{
				rejectCount++;
				userRejectCount++;
				printf("Record Skipped!!!\n");
				continue;
			}
			else
			{
				acceptCount++;
				userAcceptCount++;
			}

			// Let's see if we can figure out anything else about
			// this record
			if (strncmp(theMemo, "Insertion fee item ", 19) == 0)
			{
				theType	= AccountDetailFeeInsertion;
				pItemId	= theMemo + 19;
				if (strlen(pItemId) <= ITEM_SIZE)
					strcpy(theItem, pItemId);
				else
					theItem[0]	= '\0';

				// No need for a memo.
				theMemo[0]		= '\0';
			}
			else if (strncmp(theMemo, "Item ", 5) == 0) 
			{
				pItemId	= theMemo + 5;
				pComma	= strchr(pItemId, ',');
				if (pComma != NULL)
				{
					if (((pComma - pItemId) <= ITEM_SIZE) &&
						strncmp(pComma, ", final value $", 15) == 0)
					{
						// SOMETIMEs the amount is -ve, which means this
						// is _really_ a listing fee credit!
						if (theAmount < 0)
						{
							theType	= AccountDetailCreditNoSale;
							theAmount	= -theAmount;
						}
						else
						{
							theType	= AccountDetailFeeFinalValue;
						}
						memset(theItem, 0x00, sizeof(theItem));
						memcpy(theItem, pItemId, pComma - pItemId);

						// Memo is only final value now
						sprintf(theMemo, "F%s", pComma + 3);
					}
				}
			}
			else if (strncmp(theMemo,
							 "courtesy credit ", 16) == 0)
			{
				theType	= AccountDetailCreditCourtesy;
			}
			else if (strncmp(theMemo,
							 "credit card payment -- thank you!",
							 33) == 0)
			{
				theType	= AccountDetailPaymentCC;
				theMemo[0]	= '\0';
			}
			else if (strncmp(theMemo,
							 "payment -- thank you!",
							 20) == 0)
			{
				theType	= AccountDetailPaymentCheck;
				theMemo[0]	= '\0';
			}
			else if (strncmp(theMemo,
							 "finance charge",
							 14) == 0)
			{
				theType	= AccountDetailFinanceCharge;
				theMemo[0]	= '\0';
			}

			// For some reason, some old records come in with -ve signs in the
			// amounts, some don't. Let's make sure it's consistent : All debits
			// are negative.
			if (theType == AccountDetailFeeInsertion		||
				theType == AccountDetailFeeBold				||
				theType == AccountDetailFeeFeatured			||
				theType == AccountDetailFeeCategoryFeatured ||
				theType == AccountDetailFeeFinalValue		||
				theType == AccountDetailFinanceCharge		||
				theType == AccountDetailAWDebit					)
			{
				if (theAmount > 0)
					theAmount	= -theAmount;
			}
			else
			{
				if (theAmount < 0)
					theAmount	= -theAmount;
			}

			// One more check
			 if (theAmount > 99999999.99 ||
				 theAmount < -99999999.99)
			 {
				fprintf(stderr, "Amount %f too large in <%s>. Skipped!\n",
						theAmount, buf);
				badRecord   = true;
					continue;
			 }

			// Ok, let's start filling in the array
			id[0]						=	pUser->GetId();

			pTheTime	= localtime(&theTime);
			sprintf(date[0], 
					"%02d-%02d-%02d %02d:%02d:%02d",
					pTheTime->tm_year + 1900,
					pTheTime->tm_mon + 1,
					pTheTime->tm_mday,
					pTheTime->tm_hour,
					pTheTime->tm_min,
					pTheTime->tm_sec);

			action[0]					=	theType;
			amount[0]					=	theAmount;
			transactionId[0]			=	0;
			strcpy(memo[0], theMemo);
			migrationBatchId[0]	=	BatchId;

			itemTransactionId[0] =
				gApp->GetDatabase()->XAddRawAccountDetail(1,
														  &id[0],
														  (char *)&date[0],
														  &action[0],
														  &amount[0],
														  (char *)&memo[0],
														  &transactionId[0],
														  &migrationBatchId[0]);


			// Now, construct an account detail record
			if (theType == AccountDetailFeeInsertion ||
				theType == AccountDetailFeeFinalValue)
			{
				strcpy(item[iItem], theItem);
				if (item[iItem][0] == '\0')
					strcpy(item[0], "Unknown");

				gApp->GetDatabase()->XAddAccountAWItemXref(iItem,
														  &itemTransactionId[0],
														  (char *)&item[0]);


			}


		} while (1==1);

		delete	pUser;
		pUser			= NULL;

		// Dump them out
		// Now we'll be looping around here...
		
	} while (!done);

	printf("Done! %d Users, %d Records, %d accepted, %d rejected\n",
			userCount, recCount, acceptCount, rejectCount);

	// Clean up
	fclose(pFile);

	return;
}

static clsAuditAccountingApp *pTestApp = NULL;

int main(int argc, char* argv[])
{
	int	batchId;

	batchId	= atoi(argv[1]);

	if (batchId == 0)
	{
		printf("Bad Batch id <%s>\n", argv[1]);
	}
	else
	{
		printf("**** Begin Batch %d ****\n", batchId);
	}

	if (!pTestApp)
	{
		pTestApp	= new clsAuditAccountingApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run(batchId);

	return 0;
}
