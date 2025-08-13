/*	$Id: clsMailControl.cpp,v 1.2 1999/04/17 20:22:43 wwen Exp $	*/
//
//	File:		clsMailControl.cc
//
// Class:	clsMailControl
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//
//				Represents a Marketplace
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 06/18/98 inna     - added CCardEmail
//				- 08/22/98 mila		- added methods for getting pics/ directory
//									  path, relative path, etc...
//

#ifdef _MSC_VER

#include "eBayKernel.h"
#include "clsStatistics.h"
#include "clsAnnouncements.h"
#include "clsBulletinBoards.h"
#include "clsAdWidget.h"
#include "clsAdRelated.h"
#include "ostream.h"
#include "clsPartners.h"
#include <stdio.h>
#include <strstrea.h>
#include <string.h>
#include "clsSynchronize.h"

#define LEAK_METHOD 1

// static MailPoolVector *MailMachineGlobalPools;
// our global
MailPoolVector *mpMailPools;

//
// Default Constructor
//
clsMailControl::clsMailControl(clsMarketPlace *pMarketPlace)
{
	mpMarketPlace	= pMarketPlace;

	// only care about this on CGI
#ifdef _MSC_VER
	// mail control
	mpMailPools = new MailPoolVector;
//	MailMachineGlobalPools = mpMailPools;

#ifndef LEAK_METHOD
	SetupMailPoolsSyncLock();
#endif
	SetupMailMachines();
//	WriteoutMailMachines();

#ifndef LEAK_METHOD
	SetupMailBidSyncLock();
#endif
	SetMailBidNoticesState(bidNoticesChinese, true);
	SetMailBidNoticesState(bidNoticesDutch, true);
	SetMailBidNoticesState(outBidNoticesChinese, true);

#endif
	return;
}

clsMailControl::~clsMailControl()
{
}

// all this only pertains to NT CGI machines
#ifdef _MSC_VER

void clsMailControl::SetupMailPoolsSyncLock()
{
	mpSyncMailPools = new clsSynchronizeable();
}

int clsMailControl::GetMailMachineCount(MailPoolTypeEnum poolType, bool obeyLock)
{
	MailPoolVector::iterator thePool;
	int value = -1;

#ifndef LEAK_METHOD
	if(obeyLock) {
		unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return value; //  NULL;
	}
#endif
	
	for(thePool = mpMailPools->begin(); thePool != mpMailPools->end(); thePool++) {
		if((**thePool).poolType == poolType) 
			value = (**thePool).machines->size();
	}
	
	// if we don't have this pool

#ifndef LEAK_METHOD
	if(obeyLock) 
		mpSyncMailPools->Unlock();
#endif

	return value;
}

bool clsMailControl::GetMailMachine(MailPoolTypeEnum poolType,
									int machineIndex,
									std::string& machineAddress, 
									bool obeyLock)
{
	MailPoolVector::iterator thePool;
	bool success = false;
	
#ifndef LEAK_METHOD
	if(obeyLock) {
		unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return false;
	}
#endif
	
	for(thePool = mpMailPools->begin(); thePool != mpMailPools->end(); thePool++) {
		if((**thePool).poolType == poolType) {
			// sanity
			if(machineIndex >= GetMailMachineCount(poolType, false)) {
				success = false;
				goto done;
			}
			
			if(((*(**thePool).machines)[machineIndex])->machine) {
				machineAddress = ((*(**thePool).machines)[machineIndex])->machine;
				success = true;
			}
		}
	}
	
done:
#ifndef LEAK_METHOD
	if(obeyLock) 
		mpSyncMailPools->Unlock();
#endif
	
	return success;
}

//
// ClearMailPools 
//
// CS lock can be used or not, depends on who is calling it
//
void clsMailControl::ClearMailPools(MailPoolTypeEnum whichPool, bool obeyLock, MailPoolVector *theMailPools)
{
	MailMachineVector::iterator theMachine;
	MailPoolVector::iterator thePool;
	
#ifdef LEAK_METHOD
	// no need to do any of this with the 'leak' method
	return;
#endif
	
	if(obeyLock) {
		unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return; //  NULL;
	}
	
	// use the global pool if we don't get another vector to fill
	if(theMailPools == NULL)
		theMailPools = mpMailPools;
	
	
	// reset the vectors
	thePool = theMailPools->begin();
	while(thePool != theMailPools->end()) {
		if((whichPool == all_pools) || ((**thePool).poolType == whichPool)) {
			theMachine = (**thePool).machines->begin();
			while(theMachine != (**thePool).machines->end()) {
				// delete the char array
				if((**theMachine).machine) {
					delete (**theMachine).machine;
					(**theMachine).machine = NULL;
				}
				(**thePool).machines->erase(theMachine);
				theMachine = (**thePool).machines->begin();
			}
			// empty this vector
			(**thePool).machines->clear();
			// and remove it
			theMailPools->erase(thePool);
			
			// if deleting only a specific pool then bail here
			if(whichPool != all_pools) 
				break;
		}
		if(whichPool == all_pools) 
			thePool = theMailPools->begin();
		else
			thePool++;
	}
	// clear vector
	if(whichPool == all_pools)
		theMailPools->clear();
	
	if(obeyLock) 
		mpSyncMailPools->Unlock();
}

// AddMailPool 
//
// adds a mail pool. if the pool exists then it is replaced.
//
static void AddMailPool(MailPoolTypeEnum mailPoolType, MailPool *aPool, 
	MailPoolVector *theMailPools)
{
	MailPoolVector::iterator thePool;
	bool match = false;
	
	// use the global pool if we don't get another vector to fill
	if(theMailPools == NULL)
		theMailPools = mpMailPools;

	// check to see if this pool exisits
	for(thePool = theMailPools->begin(); thePool != theMailPools->end(); thePool++) 
	{
		if((**thePool).poolType == mailPoolType) 
		{
			// if so, add this machine to the existing pool
			*thePool = aPool;
			match = true;
		}
	}
	
	// if there is no match, then first create a pool for this type
	if(!match) {
		// and add to the pool vector
		theMailPools->push_back(aPool);
	}
}


void clsMailControl::AddMailMachinesToPool(MailPoolTypeEnum mailPoolType, vector<char *>& vMachine, 
										   bool obeyLock)
{
	vector<char *>::iterator iMachine;
	MailMachine *bob;
	
#ifndef LEAK_METHOD
	if(obeyLock) {
		unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return; //  NULL;
	}
#endif
	
	// clear the pools
	ClearMailPools(mailPoolType, false, NULL);
		
#if 0
// if we're here, we need to copy all the contents of the vectors
// THIS SHOULD BE ELSEWHERE maybe in the routine that calls it
	MailPoolVector *tempMailPools = new MailPoolVector;
	tempMailPools->reserve(mpMailPools->size());

	MailPoolVector::iterator mpBegin = mpMailPools->begin();
	MailPoolVector::iterator mpEnd = mpMailPools->end();

	for(; mpBegin != mpEnd; ++mpBegin) 
	{
		MailPool* mailPool = *mpBegin;

		if (mailPool)
		{
			MailPool* newMailPool = new MailPool;

			newMailPool->poolType = mailPool->poolType;
			newMailPool->machines = new MailMachineVector;
			
			MailMachineVector::iterator mmBegin = mailPool->machines->begin();
			MailMachineVector::iterator mmEnd = mailPool->machines->end();

			for (; mmBegin != mmEnd; ++mmBegin)
			{
				MailMachine* mailMachine = *mmBegin;
				MailMachine* newMailMachine = new MailMachine;

				newMailMachine->machine = new char[64];
				newMailMachine->machine[0] = '\0';

				newMailMachine->weighting = mailMachine->weighting;
				strcpy(newMailMachine->machine, mailMachine->machine);

				newMailPool->machines->push_back(newMailMachine);
			}

			tempMailPools->push_back(newMailPool);
		}
	}

#endif

	MailPool* newMailPool = new MailPool;

	newMailPool->poolType = mailPoolType;
	newMailPool->machines = new MailMachineVector;
	

	for (iMachine = vMachine.begin(); iMachine != vMachine.end(); iMachine++) {
		{
			char seps[]   = ",";
			char *token;
			/* Establish string and get the first token: */
			token = strtok( *iMachine, seps );   
			
				bob = new MailMachine;
				bob->machine = new char[64];
				bob->machine[0] = 0;
				
				strcpy(bob->machine, token);
				/* While there are tokens in "string" */      
				/* Get next token: */      
				token = strtok( NULL, seps );  
				
				// if we have the weighting, use it. else assign '10'
				if(token) {
					bob->weighting = atoi(token);
				} else {
					bob->weighting = 10;
				}
				
#ifdef LEAK_METHOD
				newMailPool->machines->push_back(bob);
#else
				AddMailMachineToPool((MailPoolTypeEnum) mailPoolType, bob, false);			
#endif
		}
	}

#ifdef LEAK_METHOD
	AddMailPool(mailPoolType, newMailPool, mpMailPools);
#endif

#ifndef LEAK_METHOD
	if(obeyLock) 
		mpSyncMailPools->Unlock();
#endif
}

//
// AddMailMachineToPool 
//
// adds a new mail machine to a mail pool. if the pool does not exist it is created here.
//
void clsMailControl::AddMailMachineToPool(MailPoolTypeEnum mailPoolType, MailMachine *machine, 
	bool obeyLock, MailPoolVector *theMailPools)
{
	MailPoolVector::iterator thePool;
	MailPool *aPool;
	bool match = false;
	
#ifndef LEAK_METHOD
	if(obeyLock) {
		unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
		if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
			return; //  NULL;
	}
#endif

	// use the global pool if we don't get another vector to fill
	if(theMailPools == NULL)
		theMailPools = mpMailPools;

	// check to see if this pool exisits
	for(thePool = theMailPools->begin(); thePool != theMailPools->end(); thePool++) {
		if((**thePool).poolType == mailPoolType) {
			
			// if so, add this machine to the existing pool
			(**thePool).machines->push_back(machine);
			match = true;
		}
	}
	
	// if there is no match, then first create a pool for this type
	if(!match) {
		// create the new pool
		aPool = new MailPool;
		// a new machines vector
		(*aPool).machines = new MailMachineVector;
		// give it the machine 
		(*aPool).machines->push_back(machine);
		// give it a type (NOTE: we may want to check the pool type against our internal list)
		(*aPool).poolType = mailPoolType;
		
		// and add to the pool vector
		theMailPools->push_back(aPool);
	}

#ifndef LEAK_METHOD
	if(obeyLock) 
		mpSyncMailPools->Unlock();
#endif
}

//
// GetMailMachinesForType
//
MailMachineVector *clsMailControl::GetMailMachinesForType(MailPoolTypeEnum mailPoolType)
{
	MailPoolVector::iterator thePool;

#ifndef LEAK_METHOD
	unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return NULL; //  ;
#endif

	for(thePool = mpMailPools->begin(); thePool != mpMailPools->end(); thePool++) {
			// write out a line feed if not the first entry
		if((**thePool).poolType == mailPoolType) {
#ifndef LEAK_METHOD
			mpSyncMailPools->Unlock();
#endif
			return (**thePool).machines;
		}
	}

#ifndef LEAK_METHOD
	mpSyncMailPools->Unlock();
#endif

	return NULL;
}

void clsMailControl::SetupMailMachines()
{
	MailMachine *bob;
	FILE *stream;
	istrstream *theCharStream;
	char chars[10000], tempStr[2];
	int count;
//	MailPoolVector::iterator thePool;
//	MailMachineVector::iterator theMachine;
	MailPool *aPool;
	char token[64];
	bool match, done = false, failure = false;
	int cmp;

	const char * const poolToken = ">>";
	const char * const EODToken = "<<EOD>>";
	const char * const EOFToken = "<<EOF>>";

#ifndef LEAK_METHOD
	unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return; //  NULL;
#endif

	// first check for existence of mail file
	
	// i'd use fstream here, but // #$*&#$*&!!$fstream won't compile under our project. 
	// something to do with 'text' being redefined. i suspect the oci includes
	//	fstream instream;
	
	//	instream.open(MAIL_MACHINES, ios::nocreate, filebuf::sh_none   );
	stream = fopen(MAIL_MACHINES, "r");
	
	if(stream) {
		// read all the data into one string stream
		count = fread(chars, 1, sizeof(chars), stream);
		theCharStream = new istrstream(chars, count);
		
		// reset the vectors
		ClearMailPools(all_pools, false, NULL);

		// format of mail file:
		//  machine name [string]
		//  weighting [int]
		//  {repeat}
		while (theCharStream->eof() == 0 && !done) {
			match = false;
			
			// token parse
			*theCharStream >> token;
			
			// are the first two chars part of a pool type?
			cmp = strncmp(poolToken, token, 2);			
			if(cmp == 0) {
				aPool = new MailPool;
				aPool->machines = new MailMachineVector;
				tempStr[0] = token[2];
				tempStr[1] = '\0';

				aPool->poolType = atoi(tempStr);
				match = true;
			}

			// EOD - end of pool
			if(!match) {
				cmp = strncmp(EODToken, token, strlen(EODToken));			
				if(cmp == 0) {
					// push on the pool
					mpMailPools->push_back(aPool);
					match = true;
				}
			}

			// EOF - end of all data
			if(!match) {
				cmp = strncmp(EOFToken, token, strlen(EOFToken));			
				if(cmp == 0) {
					match = true;
					done = true;
					break;
				}
			}
			
			// add a MailMachine to the pool
			if(!match) {
				bob = new MailMachine;
				bob->machine = new char[64];
				bob->machine[0] = 0;
				
				strcpy(bob->machine, token);
				
				if(bob->machine[0] == 0) 
					// something is wrong
					goto failure;
				
				*theCharStream >> bob->weighting;
				{
					//				int q = theCharStream->gcount();
					int it = theCharStream->peek();
					printf("%d", it);
					// check the peek to see if our type fetch failed. if it did, just bail
					if(it == -1) {
						failure = true;
						goto failure;
					}
				}

				// push into vector
				aPool->machines->push_back(bob);
			}
		}
		delete theCharStream;
		
		fclose(stream);
	} else {
		// if we had no file or we otherswise failed, load up the source based mail machines (from clsMailControl)
failure:
		// reset the vectors
		ClearMailPools(all_pools, false, NULL);

		// now read in our static arrays of machines
		{
			int i, j;
			
			// general pool
			aPool = new MailPool;
			j = sizeof(gStatMailGenMachines) / sizeof(MailMachine);			
			aPool->machines = new MailMachineVector;
			for(i=0;i < j; i++) {
				bob = new MailMachine;
				bob->machine = new char[64];
				bob->machine[0] = 0;
				strcpy(bob->machine, gStatMailGenMachines[i].machine);
				bob->weighting = gStatMailGenMachines[i].weighting;
				aPool->machines->push_back((MailMachine *const) bob);	
			}
			aPool->poolType = pool_general;
			mpMailPools->push_back(aPool);

			// registration pool
			aPool = new MailPool;
			j = sizeof(gStatMailRegMachines) / sizeof(MailMachine);			
			aPool->machines = new MailMachineVector;
			for(i=0;i < j; i++) {
				bob = new MailMachine;
				bob->machine = new char[64];
				bob->machine[0] = 0;
				strcpy(bob->machine, gStatMailRegMachines[i].machine);
				bob->weighting = gStatMailRegMachines[i].weighting;
				aPool->machines->push_back((MailMachine *const) bob);	
			}
			aPool->poolType = pool_registration;
			mpMailPools->push_back(aPool);

			// help pool
			aPool = new MailPool;
			j = sizeof(gStatMailHelpMachines) / sizeof(MailMachine);			
			aPool->machines = new MailMachineVector;
			for(i=0;i < j; i++) {
				bob = new MailMachine;
				bob->machine = new char[64];
				bob->machine[0] = 0;
				strcpy(bob->machine, gStatMailHelpMachines[i].machine);
				bob->weighting = gStatMailHelpMachines[i].weighting;
				aPool->machines->push_back((MailMachine *const) bob);	
			}
			aPool->poolType = pool_help;
			mpMailPools->push_back(aPool);
		}
	}

#ifndef LEAK_METHOD
	mpSyncMailPools->Unlock();
#endif

	if(failure)
		WriteoutMailMachines();
}

void clsMailControl::WriteoutMailMachines()
{
	MailMachineVector::iterator theMachine;
	FILE *stream;
	ostrstream *theCharStream;
	char chars[10000];
	int count;
	MailPoolVector::iterator thePool;
	
#ifndef LEAK_METHOD
	unsigned long lockResult = mpSyncMailPools->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return; //  NULL;
#endif

	// first check for existence of mail file
	
	// i'd use fstream here, but // #$*&#$*&!!$fstream won't compile under our project. 
	// something to do with 'tex' being redefined. i suspect the oci includes
	//	fstream instream;
	
	//	instream.open(MAIL_MACHINES, ios::nocreate, filebuf::sh_none   );
	stream = fopen(MAIL_MACHINES, "w+");
	
	if(stream) {
		theCharStream = new ostrstream(chars, 10000);
		
		// format of mail file:
		//  machine name [string]
		//  weighting [int]
		//  {repeat}
		for(thePool = mpMailPools->begin(); thePool != mpMailPools->end(); thePool++) {
			// emit the type
			*theCharStream << ">>";
			*theCharStream << (**thePool).poolType;
			*theCharStream << "<<" << endl;

			// emit the machines in the pool and their weighting
			for(theMachine = (**thePool).machines->begin(); theMachine != (**thePool).machines->end(); theMachine++) {					
				*theCharStream << (**theMachine).machine << endl;
				*theCharStream << (**theMachine).weighting << endl;
			}
			*theCharStream << "<<EOD>>" << endl;
		}

		*theCharStream << "<<EOF>>" << endl;

		count = fwrite(chars, 1, theCharStream->pcount(), stream);

		delete theCharStream;
		
		fclose(stream);
	}

#ifndef LEAK_METHOD
	mpSyncMailPools->Unlock();
#endif
}

void clsMailControl::SetMailBidNoticesState(MailBidNoticeTypeEnum type, bool value)
{
#ifndef LEAK_METHOD
	unsigned long lockResult = mpSyncMailBid->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return; //  NULL;
#endif

	mpMailBidNoticeArray[type] = value;

#ifndef LEAK_METHOD
	mpSyncMailBid->Unlock();
#endif
}

bool clsMailControl::GetMailBidNoticesState(MailBidNoticeTypeEnum type)
{
	bool value;
	
#ifndef LEAK_METHOD
	unsigned long lockResult = mpSyncMailBid->Lock(1000*10); // Wait up to 10 seconds for a lock
	if (lockResult == WAIT_FAILED || lockResult == WAIT_TIMEOUT)
		return NULL; //  NULL;
#endif

	value = mpMailBidNoticeArray[type];

#ifndef LEAK_METHOD
	mpSyncMailBid->Unlock();
#endif

	return value;
}

void clsMailControl::SetupMailBidSyncLock()
{
	mpSyncMailBid = new clsSynchronizeable();
}

/*
void clsMailControl::SetBidNoticesChinese(bool value)
{
	doBidNoticesChinese = value;
}

void clsMailControl::SetBidNoticesDutch(bool value)
{
	doBidNoticesDutch = value;
}

void clsMailControl::SetOutBidNoticesChinese(bool value)
{
	doOutBidNoticesChinese = value;
}

bool clsMailControl::GetBidNoticesChinese()
{
	return doBidNoticesChinese;
}

bool clsMailControl::GetBidNoticesDutch()
{
	return doBidNoticesDutch;
}

bool clsMailControl::GetOutBidNoticesChinese()
{
	return doOutBidNoticesChinese;
}
*/

#endif // _MSC_VER


// #endif // _MSC_VER
