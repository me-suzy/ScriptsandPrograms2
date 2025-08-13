/*	$Id: clsSplitFeedback.cpp,v 1.3 1999/02/21 02:24:30 josh Exp $	*/
//
//	File:	clsSplitFeedback.cpp
//
//	Class:	clsSplitFeedback
//  c
//	Author:	inna markov
//
//	Function:
//
//		Loads New ebay_feedback_detail## tables from ebay_feedback_detail table
//
// Modifications:
//

#include "clsSplitFeedback.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "vector.h"

#include <stdio.h>
//#include <errno.h>
#include <time.h>


clsSplitFeedback::clsSplitFeedback(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers	= (clsUsers *)0;

	return;
}


clsSplitFeedback::~clsSplitFeedback()
{
	return;
};


void clsSplitFeedback::Run(int FromUser, int ToUser)
{
	// This is the vector of users taht have account_balances record 
	// and are in the range and have not been "split" into new tables yet
	vector<int>	vUsers;
	// And it's iterator
	vector<int>::iterator	i;

	//we need a place to hold user data
	clsUser		*pUser;

	// save current state temporarily
	UserStateEnum	currUserState;

	//this flag used to mark records taht already are in the new table
	//in case of reprocessing killed job
	bool	skipFlag;

	//keep count to know when to commit
	int count; 

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	// First, let's get the users in the Range from account balances
	mpDatabase->GetUserIdsUnsplit(FromUser,ToUser,&vUsers);


	// Now, we loop through users
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		// Feedback about the user in a vector
		FeedbackItemVector				pvItems;
		FeedbackItemVector::iterator	ii;

		FeedbackItemVector				pvSplitItems;
		FeedbackItemVector::iterator	iii;

		// regardless of this user having current details or NOT we should
		// set user in maintenace, even if no need to split prevent him from 
		// getting new ebay_feedback_detail records until his ebay_feedback
		// is updated with split indicator.

		pUser=mpUsers->GetUser(*i);
		currUserState = pUser->GetUserState();
		pUser->SetInMaintenance();

		printf("In Maintenance set for %d user.Old Status=%d.\n", (*i), currUserState);
		pUser->UpdateUser();


		//lets get all feedbacks for this user
		mpDatabase->GetFeedbackDetailToSplit((*i),0, &pvItems);
		//lets get a vector of fedbacks already in the split table,
		//this will only happen if job stopped in the middle;
		mpDatabase->GetFeedbackDetailToSplit((*i),1, &pvSplitItems);

		// Let's see if there's anything to do here
		if (pvItems.empty())
		{
			printf("No details for %d user. Nothing to Split.\n", (*i));
		}
		else 
		{
			count=0;

			for (ii = pvItems.begin();
				ii != pvItems.end();
				ii++)
			{
				skipFlag=false;

				// let's see if any records are already in the ebay_feddback_detailX?
				if (pvSplitItems.size() > 0)
				{
					// check if this pvItems node has a match in pvSplitItems
					for (iii = pvSplitItems.begin();
						iii != pvSplitItems.end() && (!skipFlag);
						iii++)
					{
						if ((*ii)->mTime == (*iii)->mTime 
							&& (*ii)->mCommentingId == (*iii)->mCommentingId)
						{
							// get rid of this Split Feedback node - not needed
							delete (*iii);
							pvSplitItems.erase(iii);
							skipFlag=true;
							break;
						}
					}
				}

				
				// Go ahead and write this to ebay_feedback_detailX table
				// this function has no commit; commit happens here or when you update 
				// ebay_feedback with a split flag
				if (!skipFlag)
				{
					mpDatabase->SplitFeedbackDetail((*ii));
					count++;
					if (count %20==0)
						mpDatabase->End();
				}

			}
			//lets free the memory used by the feeddback vector, 
			//only has stuff in it if part of else (not empty!)
			for (ii = pvItems.begin();
				ii != pvItems.end();
				ii++)
			{
				delete (*ii);
			}
		} //end of else (not empty vector to be split)

		//lets free the memory used by the feedback vector
		//just in case; if nothing there begin will = end
		for (iii = pvSplitItems.begin();
			iii != pvSplitItems.end();
			iii++)
		{
			delete (*iii);
		}
	
		// will also commit last detail records.
		mpDatabase->UpdateFeedbackSplitFlag((*i));

		printf("End Update for %d user.Commited.\n", (*i));

		pUser->SetUserState(currUserState);
		pUser->UpdateUser();
		printf("In Maintenance Unset for %d user.\n\n", (*i));
			
	}

	//debug
	printf("I am Done!\n");
	
	return;
}



static clsSplitFeedback *pTestApp = NULL;

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tSplitFeedaback [-s int] [-e int]");
}


int main(int argc, char* argv[])
{
	int		Index = 1;
	int FromUser;
	int ToUser;

	// we need this for Oracle to be able to connect
	#ifdef _MSC_VER
		g_tlsindex = 0;
	#endif

	while (--argc)
	{
		switch (argv[Index][1])
		{
			// Get starting user id
			case 's':
				FromUser = atoi(argv[++Index]);
				Index++;
				argc--;
			break;
					
			//Get End User
			case 'e':
				ToUser = atoi(argv[++Index]);
				Index++;
				argc--;
				break;

			default:
				InputError();
				return 0;
		}
	}

	// users must be in ortder smaller start, greater end
	if (FromUser > ToUser)
	{
		printf("Invalid user Range: Start User is greater than End User.\n");
		return 0;
	}
	// due to vector reserve problem lets limit the number ofvector cells
	// to reserve to 9,999,999
	if ((ToUser-FromUser)>9999999)
	{
		printf("Invalid user Range: End User - Start User must be <= 9,999,9999.\n");
		return 0;
	}

	if (!pTestApp)
	{
		pTestApp	= new clsSplitFeedback(0);
	}


	pTestApp->InitShell();
	pTestApp->Run(FromUser, ToUser);

	return 0;
}

