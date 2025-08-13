/*	$Id: clsTextHashPool.h,v 1.2 1999/02/21 02:30:37 josh Exp $	*/
//
// Class Name:		clsTextHashPool
//
// Description:		A text hashing class, for fast storage and
//					lookup of strings (for sharing memory).
//					Allocates in large blocks to reduce fragmentation.
//
// Author:			Chad Musick
//
// N.B.:			I wrote this class in May of 1997, and I reserve
//					the right to use it in any of my personal work,
//					and grant eBay, Inc. an unrevokable and permanent
//					right to use it in any way they see fit, provided
//					it does not abridge my own rights to use it.
//

#ifndef CLSTEXTHASHPOOL_INCLUDE
#define CLSTEXTHASHPOOL_INCLUDE

#include <stdio.h>
#include "vector.h"

// This is the number of clsTextLink that we
// allocate at one time.
#define TEXTHASH_POOL_BLOCK_SIZE 512

struct clsTextLink
{
    int mValue;
	int mPreModHashValue; 
	clsTextLink *mpNext;
};

struct clsTextLinkBlock
{
    clsTextLink mLinks[TEXTHASH_POOL_BLOCK_SIZE];
    clsTextLinkBlock *mpNext;
};

class clsTextHashPool
{
public:

	clsTextHashPool(size_t minSize);
	~clsTextHashPool();

	int			LookupString(const char *pText);
	const char *LookupString(int number) const;

	static const int mPrimeModValue; // The size of our hash table.

private:

	int					HashText(const char *text);
	void				GrowBuffer(size_t length);
	int					AddToBuffer(const char *text);
	clsTextLink			*GetTextLink();

	size_t				mMinSize;
	size_t				mLength;
	size_t				mMaxLength;

	int					mCurrentIndex;
	char				*mpBuffer;
	clsTextLink			**mppLinkTable;
	clsTextLinkBlock	*mpCurrentBlock;
};

#endif /*CLSTEXTHASHPOOL_INCLUDE*/
