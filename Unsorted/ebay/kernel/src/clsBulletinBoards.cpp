/*	$Id: clsBulletinBoards.cpp,v 1.3 1998/06/30 09:10:59 josh Exp $	*/
//
//	File:	clsBulletinBoards.cpp
//
//	Class:	clsBulletinBoards
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				The repository for all bulletin boards
//
//	Modifications:
//				- 05/07/97 michael	- Created
//
#include "eBayKernel.h"
#include "clsBulletinBoards.h"


//
//	CTOR
//
//	Ask the database for all the bulletin boards
//
clsBulletinBoards::clsBulletinBoards()
{
	BulletinBoardVector::iterator						i;
	hash_map<char*, int, hash<char*>, eqstr>::iterator	ii;

	// This is easy ;-)
	gApp->GetDatabase()->GetBulletinBoardControlEntries(&mvBulletinBoards);

	// How that the boards are "build", let's build the hash of 
	// board names, since that's how we usually get at them
	for (i = mvBulletinBoards.begin();
		 i != mvBulletinBoards.end();
		 i++)
	{
		// Try and look up the entry. Assuming we don't find  it,
		// then we'll add it. Since CTORS can't return errors, if
		// we find a duplicate, we'll just skip it. This is probably
		// not a good idea, but the table constraints should handle
		// it.
		ii	= mhBulletinBoardsByShortName.find((*i)->GetShortName());
		if  (ii == mhBulletinBoardsByShortName.end())
		{
			mhBulletinBoardsByShortName[(*i)->GetShortName()] = 
					(*i)->GetId();
		}
	}


	return;
}

//
// DTOR
//
//	Let's clean up!
//
clsBulletinBoards::~clsBulletinBoards()
{
	vector<clsBulletinBoard *>::iterator		i;

	for (i = mvBulletinBoards.begin();
		 i != mvBulletinBoards.end();
		 i++)
	{
		delete	(*i);
	}

	mvBulletinBoards.erase(mvBulletinBoards.begin(),
						   mvBulletinBoards.end());

	return;
}

//
// GetBulletinBoard
//
clsBulletinBoard *clsBulletinBoards::GetBulletinBoard(BulletinBoardId id)
{

	// See if it's in range
	if (id > mvBulletinBoards.size())
		return NULL;

	return mvBulletinBoards[id];
}

//
// GetBulletinBoard
//
clsBulletinBoard *clsBulletinBoards::GetBulletinBoard(char *pShortName)
{

	hash_map<char*, int, hash<char*>, eqstr>::iterator	ii;

	ii	= mhBulletinBoardsByShortName.find(pShortName);
	if  (ii == mhBulletinBoardsByShortName.end())
	{
		return	NULL;
	}

	return GetBulletinBoard((*ii).second - 1);
}

//
// GetBoardVector
//
BulletinBoardVector *clsBulletinBoards::GetBoardVector()
{
	return	&mvBulletinBoards;
}

//
// UpdateBulletinBoard
//
void clsBulletinBoards::UpdateBulletinBoard(clsBulletinBoard *pBoard)
{
	gApp->GetDatabase()->UpdateBulletinBoardControlEntry(pBoard);
}
