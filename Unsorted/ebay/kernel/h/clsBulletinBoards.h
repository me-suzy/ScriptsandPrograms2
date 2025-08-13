/*	$Id: clsBulletinBoards.h,v 1.2 1998/06/23 04:27:48 josh Exp $	*/
//
//	File:	clsBulletinBoards.h
//
//	Class:	clsBulletinBoards
//
//	Author:	michael (michael@ebay.com)
//
//	Function:
//
//		Represents the bulletin boards
//
// Modifications:
//				- 02/22/98 michael	- Created
//
#ifndef CLSBULLETINBOARDS_INCLUDED


#include "eBayTypes.h"
#include "clsBulletinBoard.h"
#include "vector.h"
#include "hash_map.h"

class clsBulletinBoards
{
	public:

		// 
		// CTOR, DTOR
		//
		// ** NOTE **
		//	Do we need something for partners here?
		// ** NOTE **
		//
		clsBulletinBoards();
		~clsBulletinBoards();

		//
		// Get a clsBulletinBoard entry
		//
		clsBulletinBoard *GetBulletinBoard(BulletinBoardId id);
		clsBulletinBoard *GetBulletinBoard(char *pShortName);

		//
		// Adds a new bulletin board
		//
		void AddBulletinBoard(clsBulletinBoard *pNewBoard);

		//
		// Updates a bulletin board's control information
		//
		void UpdateBulletinBoard(clsBulletinBoard *pBoard);

		//
		// Access to all the board objects
		//
		BulletinBoardVector	*GetBoardVector();

	private:

		// 
		// Load up the list of bulletin boards
		//
		GetBulletinBoards();


		//
		// This is a <list> of pointers to all bulletin boards. It's
		// VERY important that the position of the boards in the list
		// match the board's id, so mlBulletinBoards[n] is Board id n.
		//
		BulletinBoardVector	mvBulletinBoards;

		//
		// This is a hash of the Bulletin Board short names. It gives
		// us quick access  by name
		//
		hash_map<char*, int, hash<char*>, eqstr>	
							mhBulletinBoardsByShortName;

};

#define CLSBULLETINBOARDS_INCLUDED 1
#endif /* CLSBULLETINBOARDS_INCLUDED */
