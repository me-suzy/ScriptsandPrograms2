/*	$Id: clsTextHashPool.cpp,v 1.2 1999/02/21 02:30:36 josh Exp $	*/
//
// Class Name:		clsTextHashPool
//
// Description:		A text hashing class, for fast storage and
//					lookup of strings (for sharing memory).
//
// Author:			Chad Musick
//
// N.B.:			I wrote this class in May of 1997, and I reserve
//					the right to use it in any of my personal work,
//					and grant eBay, Inc. an unrevokable and permanent
//					right to use it in any way they see fit, provided
//					it does not abridge my own rights to use it.

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "clsTextHashPool.h"

const int clsTextHashPool::mPrimeModValue = 15101;

// Constructor for text pool
clsTextHashPool::clsTextHashPool(size_t minSize) : mMinSize(minSize),
	mLength(0), mMaxLength(0), mCurrentIndex(TEXTHASH_POOL_BLOCK_SIZE),
	mpBuffer(NULL), mpCurrentBlock(NULL)
{
	mppLinkTable = new clsTextLink *[mPrimeModValue];
	memset(mppLinkTable, '\0', sizeof (clsTextLink *) * mPrimeModValue);
};

// Destructor
clsTextHashPool::~clsTextHashPool()
{
    clsTextLinkBlock *current = mpCurrentBlock;
    clsTextLinkBlock *previous = NULL;

    while (current) {
		delete previous;
		previous = current;
		current = previous->mpNext;
    }
	delete previous;

	delete [] mpBuffer;
	delete [] mppLinkTable;
	return;
}

// Lookup a string and insert it if necessary, returning a unique
// number for that string.
int clsTextHashPool::LookupString(const char *text)
{
	int nonModdedIndex;
	int moddedIndex;
	clsTextLink *current;
	
	nonModdedIndex = HashText(text);
	moddedIndex = nonModdedIndex % mPrimeModValue;
	current = mppLinkTable[moddedIndex];
	
	while (current)
	{
		if ((current->mPreModHashValue != nonModdedIndex) ||
			(strcmp(text, mpBuffer + current->mValue) != 0))
			current = current->mpNext;
		else
			return current->mValue;
	}

	current = GetTextLink();
	current->mValue = AddToBuffer(text);
	current->mPreModHashValue = nonModdedIndex;
	current->mpNext = mppLinkTable[moddedIndex];
	mppLinkTable[moddedIndex] = current;

	return current->mValue;
}

// Lookup a string by number and return the string, or
// NULL if the requested string is beyond the boundaries.
// No checks are done beyond make sure the number
// is reasonable, so it is possible to retrieve midstring
// if a bad number is passed.
const char *clsTextHashPool::LookupString(int number) const
{
	if ((size_t) number > mLength || number < 0)
		return NULL;

	return mpBuffer + number;
}

// Allocate a new link block if necessary, and return
// a clsTextLink pointer to store the information in.
clsTextLink *clsTextHashPool::GetTextLink()
{
	clsTextLink *newLink;
	clsTextLinkBlock *newBlock;

	// Allocate a new block if necessary, and make it current.
	if (mCurrentIndex >= TEXTHASH_POOL_BLOCK_SIZE)
	{
		newBlock = new clsTextLinkBlock;
		newBlock->mpNext = mpCurrentBlock;
		mpCurrentBlock = newBlock;
		mCurrentIndex = 0;
	}

	newLink = mpCurrentBlock->mLinks + mCurrentIndex;
	++mCurrentIndex;

	return newLink;
}

// Add a string to the buffer, returning its
// position (also its id)
int clsTextHashPool::AddToBuffer(const char *text)
{
    int retval;
    size_t length;

	retval = mLength;
	length = strlen(text) + 1;

	if ((mLength + length) > mMaxLength)
		GrowBuffer(length);

	memcpy(mpBuffer + mLength, text, length);
	mLength += length;

	return retval;
}

// Make the buffer bigger. We double in size
// every time we grow.
void clsTextHashPool::GrowBuffer(size_t length)
{
	char *newBuffer;

	length += mMaxLength * 2;

	if (length < mMinSize)
		length = mMinSize;

	newBuffer = new char [length];

	memcpy(newBuffer, mpBuffer, mLength);
	mMaxLength = length;

	delete [] mpBuffer;
	mpBuffer = newBuffer;

	return;
}

// This hash function is taken straight from the
// dragon book and tested effective there.
// We return a possibly huge number which should
// have mod applied with a prime number for
// optimal results.
int clsTextHashPool::HashText(const char *text)
{
	unsigned int h, g;

	h = 0;
	for ( ; *text != '\0'; text++)
	{
		h = (h << 4) + (*text);
		if ((g = h & 0xf0000000) != 0)
		{
			h ^= (g >> 24);
			h ^= g;
		}
	}

	return h;
}
