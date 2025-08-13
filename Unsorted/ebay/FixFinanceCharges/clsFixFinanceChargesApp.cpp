/*	$Id: clsFixFinanceChargesApp.cpp,v 1.2.390.1 1999/08/06 02:26:54 nsacco Exp $	*/
//
//	File:	clsFixFinanceChargesApp.cc
//
//	Class:	clsFixFinanceChargesApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 04/06/97 michael	- Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsFixFinanceCharges.h"
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


clsFixFinanceChargesApp::clsFixFinanceChargesApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;

	return;
}


clsFixFinanceChargesApp::~clsFixFinanceChargesApp()
{
	return;
}

// Important Defines
#define TIME_SIZE		32
#define	MEMO_SIZE		80	
#define	ITEM_SIZE		12
#define	ARRAY_SIZE		10000	

const char *BeginRec		= "BEGIN\t";
const int	BeginRecLen		= 6;
const char *EndRec			= "END\t";
const int	EndRecLen		= 4; 

void clsFixFinanceChargesApp::Run(int BatchId)
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

	// The real current user and things about them.
	clsUser				*pUser;
	clsAccount			*pAccount;
	int					userRecCount;
	int					userRecSkipCount;
	int					userRejectCount;

	char				*pEndUserName;
	int					theEndAWId;

	// These fields represent the individual accounting
	// detail records. We don't use array insert because
	// recovery is SUCH a pain;
	int						theUserId;
	time_t					theTime;
	AccountDetailTypeEnum	theType;
	double					theAmount;
	int						memoLen;
	char					theMemo[MEMO_SIZE + 1];

	// Date conversion
	struct tm				*pTheTime;

	// Limiting time
	struct tm				limitTimeTM;
	time_t					limitTime;

	// Arrays of things
	int				i;
	int				id[ARRAY_SIZE];
	char			date[ARRAY_SIZE][32];
	int				action[ARRAY_SIZE];
	float			amount[ARRAY_SIZE];
	unsigned int	transactionId[ARRAY_SIZE];
	char			memo[ARRAY_SIZE][MEMO_SIZE + 1];
	int				migrationBatchId[ARRAY_SIZE];

	if (!mpMarketPlaces)
		mpMarketPlaces	= gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();


	// Limit time
	memset(&limitTimeTM, 0x00, sizeof(limitTimeTM));
	limitTimeTM.tm_sec			= 00;
	limitTimeTM.tm_min			= 00;
	limitTimeTM.tm_hour			= 00;
	limitTimeTM.tm_mon			= 8;	// Month - 1!!!
	limitTimeTM.tm_mday			= 1;
	limitTimeTM.tm_year			= 97;
	limitTimeTM.tm_isdst		= 1;
	limitTime	= mktime(&limitTimeTM);


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
			printf("User <%s> not on Beta. Adding as ghost\n",
				 	 pUserName);

			// nsacco 08/05/99
			// new constructor
			int user_state = UserUnknown;
			pUser	= new clsUser(mpMarketPlace->GetId(),
								  0,
								  pUserName,
								  "",
								  UserGhost,
								  "",
								  "",
								  0, 
								  0,
								  0,
								  Country_None,
								  0,
								  0);

			mpUsers->AddUser(pUser);

			pUser	= mpUsers->GetUser(pUserName);

			if (!pUser)
			{
				fprintf(stderr, "User <%s> NOT there after Add!\n",
						pUserName);
				exit(1);
			}
		}

		theUserId	= pUser->GetId();
		printf("Begin processing User <%s>/<%s>\n",
				pUserName, pUser->GetUserId());


		// Now, for their AW account id
		if ((s = split(NULL, "\t")) && *s)
		{
			theAWId	= atoi(s);
		}
		else
		{
			fprintf(stderr, "No AW account id in <%s> for <%s>\n",
					buf, pUser->GetUserId());
			FLUSH_TO_END;
			continue;
		}

		// Ok, we're ready. Let's get the user's account information
		pAccount	= pUser->GetAccount();


		// Now, let's fetch, decode, and handle those records
		userRecCount		= 0;
		userRecSkipCount	= 0;
		userRejectCount		= 0;
		i					= 0;
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

				// Now, for their AW account id
				if ((s = split(NULL, "\t")) && *s)
				{
					theEndAWId	= atoi(s);
				}
				else
				{
					fprintf(stderr, "No Ending AW account id in <%s> for <%s>\n",
							buf, pUser->GetUserId());
					continue;
				}

				if (theEndAWId != theAWId)
				{
					fprintf(stderr, "Begin/End AW Account mismatch for %s! Begin %d, End %d\n",
							pUser->GetUserId(), theAWId, theEndAWId);
					exit(1);
				}


				// If the user didn't have an account, create one.
				if (pAccount->GetLastUpdate() == 0)
				{
					gApp->GetDatabase()->CreateAccount(pUser->GetId(),
														   0);
				}

				if (userRecCount > 0)
				{
					// Now, let's stuff those records
					gApp->GetDatabase()->AddRawAccountDetail(i,
															 &id[0],
															 // TODO
															 // need table indicator here
															 (char *)&date[0],
															 &action[0],
															 &amount[0],
															 (char *)&memo[0],
															 (int *)&transactionId[0],
															 &migrationBatchId[0]);

					// All done with the user. Rebalance the account.
					pAccount->Rebalance();

				}


				// Print out interesting things
				printf(
				"User <%s>, AW Account %d: %d/%d/%d\n",
				pUser->GetUserId(), theAWId, userRecCount, userRecSkipCount,
				userRejectCount
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

			if (theTime < limitTime)
				continue;


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
					continue;
				}
				else if (strcmp(s, "D") == 0)
				{
					theType		=	AccountDetailAWDebit;
				}
				else if (strcmp(s, "M") == 0)
				{
					continue;
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
			}


			// Let's see if we can figure out anything else about
			// this record
			if (strncmp(theMemo,
						"finance charge",
						 14) == 0)
			{
				theType	= AccountDetailFinanceCharge;
				theMemo[0]	= '\0';
				userRecCount++;
			}
			else
				continue;

			// For some reason, some old records come in with -ve signs in the
			// amounts, some don't. Let's make sure it's consistent : All debits
			// are negative.
			if (theType == AccountDetailFinanceCharge)
			{
				if (theAmount > 0)
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
			id[i]						=	pUser->GetId();

			pTheTime	= localtime(&theTime);
			sprintf(date[i], 
					"%02d-%02d-%02d %02d:%02d:%02d",
					pTheTime->tm_year + 1900,
					pTheTime->tm_mon + 1,
					pTheTime->tm_mday,
					pTheTime->tm_hour,
					pTheTime->tm_min,
					pTheTime->tm_sec);

			action[i]					=	theType;
			amount[i]					=	theAmount;
			mpDatabase->GetNextTransactionId(&transactionId[i]);
			strcpy(memo[i], theMemo);
			migrationBatchId[i]	=	BatchId;
			i++;
			if (i > ARRAY_SIZE)
			{
				fprintf(stderr, "User has tooo many records. Flushing!\n");
				FLUSH_TO_END;
				break;
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

static clsFixFinanceChargesApp *pTestApp = NULL;

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
		pTestApp	= new clsFixFinanceChargesApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run(batchId);

	return 0;
}
