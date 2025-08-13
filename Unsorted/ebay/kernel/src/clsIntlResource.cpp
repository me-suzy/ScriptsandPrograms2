/*	$Id: clsIntlResource.cpp,v 1.1.4.1 1999/08/05 19:01:10 nsacco Exp $ */
//
//	File:	clsIntlResource.cpp
//
//	Class:	clsIntlResource
//
//	Author:	Robin Kennedy (rkennedy@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 08/02/90 Robin Kennedy - Created
//
//

#include <stdio.h>
#include <malloc.h>
#include <string.h>

#include "eBayKernel.h"
#include "platform.h"

#define DEFAULT_INTLBUFFER_SIZE		0x0400
#define CURRENTRES gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSites()->GetCurrentSite()->GetSiteResource()

// IMPORTANT, NOTE WELL!
// Global resource container, i.e. One container group per dll, not per thread!!!!
// Any code writing to the container group must be thread safe!!!
static clsIntlResContainer * ResContainerRoot = NULL;

//--clsIntlStringList-------------------------------------------------------------------------------
//
//
//--------------------------------------------------------------------------------------------------

clsIntlStringList::clsIntlStringList()
{
	mStringBlockSize	= 0;		// Total size of the allocated string block in BYTES.
	mpStringBlock		= NULL;		// Pointer to the start of the string block
	mStringCount		= 0;		// Total count of the number of strings in the list.
	mpOffsetList		= NULL;		// Index array to each string in the string block.
}
//--------------------------------------------------------------------------------------------------

clsIntlStringList::~clsIntlStringList()
{
	// IMPORTANT
	// The memory for mpStringBlock will be released with mpOffsetList.
	// So don't free mpStringBlock.
	if (mpOffsetList)
		free(mpOffsetList);

	mStringBlockSize	= 0;	
	mpStringBlock		= NULL;	
	mStringCount		= 0;	
	mpOffsetList		= NULL;	
}
//--------------------------------------------------------------------------------------------------

unsigned long clsIntlStringList::Count()	// Returns the string count.
{
	return (mStringCount);
}
//--------------------------------------------------------------------------------------------------

IntlString clsIntlStringList::Add(IntlString s)	// Add a string to the end of the list.
{
	if (!s)
		return (false);

	// First we grow the data block, so calc the new size.
	mStringCount++;
	unsigned long Index = mStringBlockSize / sizeof(IntlChar);
	mStringBlockSize += StrByteCount(s); // Includes the terminator.
	unsigned long NewSize = (sizeof(unsigned long *) * mStringCount) + mStringBlockSize;

	// Grow the block
	char * Temp = (char *)realloc(mpOffsetList, NewSize);
	if (!Temp)
	{
		// Can't grow it, so restore the original values.
		mStringCount--;
		mStringBlockSize = Index * sizeof(IntlChar);
		return (false);
	}
	mpOffsetList = (unsigned long *)Temp;
	Temp += sizeof(unsigned long *) * mStringCount;
	
	// make space for the new index value in the index list.

	memmove(Temp, Temp - sizeof(unsigned long *), mStringBlockSize);
	mpStringBlock = (IntlString)Temp;
	// Now we can add the string and index.
	mpOffsetList[mStringCount - 1] = Index;
	IntlString t = mpStringBlock + Index;
	IntlString Ret = t;
	
	while (*s)
		*(t++) = *(s++);
	*t = 0;

	return (Ret);
}
//--------------------------------------------------------------------------------------------------

IntlString clsIntlStringList::Get(unsigned long Index)	// Returns a pointer to the indexed string.
{
	if (Index >= mStringCount)
		return (NULL);
	return (mpStringBlock + mpOffsetList[Index]);
}
//--------------------------------------------------------------------------------------------------

long clsIntlStringList::Size(unsigned long Index)	// Returns the size of the string in characters. In 
{													// the case of MBCS, the number of BYTES are returned.
	if (Index >= mStringCount)
		return (-1);

	long Size = 0;
	IntlString  s = mpStringBlock + mpOffsetList[Index];
	while (*(s++))
		Size++;
	return (Size);
}
//--------------------------------------------------------------------------------------------------

unsigned long clsIntlStringList::StrByteCount(IntlString s)
{
	unsigned long Count = 0;

	if (s)
	{
		while (*(s++))
			Count++;
		Count++;
		Count *= sizeof(IntlChar);
	}

	return (Count);
}
//--------------------------------------------------------------------------------------------------

//--clsIntlStringBuffer-----------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------
clsIntlStringBuffer::clsIntlStringBuffer()
{
	mpBuffer = (IntlString)malloc(sizeof(IntlChar) * DEFAULT_INTLBUFFER_SIZE);
	if (!mpBuffer)
	{
		// Just in case we can't get any memory
		mBufferSize = 0;
		mBytesUsed	= 0;
		return;
	}
	
	mBufferSize = DEFAULT_INTLBUFFER_SIZE;
	*mpBuffer	= 0;
	mBytesUsed	= 1;
}
//--------------------------------------------------------------------------------------------------

clsIntlStringBuffer::~clsIntlStringBuffer()
{
	if (mpBuffer)
		free(mpBuffer);
}
//--------------------------------------------------------------------------------------------------

void clsIntlStringBuffer::Clear()
{
	// Note: We do not reset the buffer size, memory allocation has a performance hit, so if we
	// had to grow the buffer we will probobly want it this big again!

	if (!mpBuffer)
		return;

	*mpBuffer	= 0;
	mBytesUsed	= sizeof(IntlChar);
	mNextChar	= 0;
}
//--------------------------------------------------------------------------------------------------

void clsIntlStringBuffer::Add(IntlChar c)
{
	Add(&c, 1);
}

// RDK_NOTE: Come back and optimize this method for performance.
void clsIntlStringBuffer::Add(IntlString s, int len)
{
	while (len-- && *s) // Note: If len is -1, we are going to end on a zero terminator.
	{
		// Can the buffer take a character and a zero terminator?
		if ((mBufferSize - mBytesUsed) < (2 * sizeof(IntlChar)))
		{
			if (!GrowBuffer())
				return;
		}
		mpBuffer[mNextChar++] = *(s++);
		mBytesUsed += sizeof(IntlChar);
	}

	// At this point we know that the buffer can take a zero terminator because we took it into account
	// in the buffer expantion. Note: We don't inc 'NextChar', as this zero is the write possition for
	// the next call to Add().
	mpBuffer[mNextChar] = 0;
}
//--------------------------------------------------------------------------------------------------

bool clsIntlStringBuffer::GrowBuffer()
{
	IntlString Temp = (IntlString)realloc(mpBuffer, mBufferSize + DEFAULT_INTLBUFFER_SIZE);
	if (!Temp)
	{
		// Can't grow the buffer, so clean up and get out.
		// Make sure that the buffer is zero terminated.
		if (mpBuffer)
		{
			mpBuffer[mBytesUsed-1] = 0;
			return (false);
		}
	}
	mpBuffer = Temp;
	mBufferSize += DEFAULT_INTLBUFFER_SIZE;
	return (true);
}

//--clsIntlResource---------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------

clsIntlResource::clsIntlResource(short Id)
{
	clsIntlResContainer * rc = NULL;

	mpToStringList	= NULL;
	mpFormatBuffer	= NULL;

	// Do we have a resource container root?
	if (ResContainerRoot)
	{
		// We are going to write to the container group, so get a lock now, as we have a root
		// we must also have a lock handler
		GET_IR_LOCK;
		// Do we have the requested ID in the group
		rc = ResContainerRoot;
		while (rc)
		{
			if (rc->mResId == Id)
				break;
			rc = rc->mpNext;
		}
	}
	else
		// First time in, so set up a Lock handler.
		CREATE_IR_LOCK; // Returns with access for this thread.
	
	if (rc)
	{
		rc->mUserCount++;
		mpResContainer = rc;
		FREE_IR_LOCK;
		return;
	}

	
	rc = new clsIntlResContainer;
	rc->mResId			= Id;
	rc->mpResName[0]	= 0;
	rc->mUserCount		= 1;
	rc->mVersion		= 0;
	rc->mRevision		= 0;
	rc->mpNext			= NULL;
	rc->mpPrevious		= NULL;	
	rc->mpStringList	= LoadResource(Id);

	// Now link the new container in to the group
	if (!ResContainerRoot)
		ResContainerRoot = rc;
	else
	{
		clsIntlResContainer * now = ResContainerRoot->mpNext;
		while (now->mpNext)
			now = now->mpNext;
		now->mpNext = rc;
		rc->mpPrevious = now;
	}

	mpResContainer = rc;
	FREE_IR_LOCK;
}

clsIntlResource::~clsIntlResource()
{
	if (mpToStringList)
		delete mpToStringList;
	if (mpFormatBuffer)
		delete mpFormatBuffer;
	
	if (mpResContainer)
	{
		GET_IR_LOCK;
		mpResContainer->mUserCount--;
		if (!mpResContainer->mUserCount)
		{
			// Not in use so kill it
			if (mpResContainer->mpStringList)
				delete mpResContainer->mpStringList;
			// Unlink this object
			if (mpResContainer->mpNext)
				mpResContainer->mpNext->mpPrevious = mpResContainer->mpPrevious;
			if (mpResContainer->mpPrevious)
				mpResContainer->mpPrevious->mpNext = mpResContainer->mpNext;
			
			if (mpResContainer == ResContainerRoot)
				ResContainerRoot = mpResContainer->mpNext;
			
			delete mpResContainer;
			FREE_IR_LOCK;

			if (!ResContainerRoot)
				KILL_IR_LOCK;
		}
	}
}

clsIntlStringList * clsIntlResource::LoadResource(short Id)
{
	return (NULL);
}

IntlString clsIntlResource::Get_Formatted_String(long Id, IntlString UsString, va_list vl)
{
	va_end(vl);

	IntlString s;
	IntlString p;

	if (Id == -1 || !mpResContainer || !mpResContainer->mpStringList)
		s = UsString;
	else
	{
		s = mpResContainer->mpStringList->Get(Id);
		if (!s)
			s = UsString;
	}

	if (!mpFormatBuffer)
		mpFormatBuffer = new clsIntlStringBuffer;

	mpFormatBuffer->Clear();

	p = s;
	while (*p)
	{
		if (*p == '%' && *(p+1) == '{')
		{
			*p = 0;
			mpFormatBuffer->Add(s);
			p += 2;
			s = p;
			while (*s && *s != '}')
				s++;
			if (*s)
				s++;
			// s now points to the next char following the variable marker.
			int Index = IntlStrToInt(p);
			mpFormatBuffer->Add(mpToStringList->Get(mpToStringList->Count() - Index));
			p = s;
		}
		else
			p++;
	}

	if (p != s)
		mpFormatBuffer->Add(s);

	delete mpToStringList;
	mpToStringList = NULL;

	return (mpFormatBuffer->mpBuffer);
}

int clsIntlResource::IntlStrToInt(IntlString s)
{
	int Value = 0;

	while (*s >= '0' && *s <= '9')
	{
		Value *= 10;
		Value += *s - '0';
		s++;
	}

	return (Value);
}

IntlString clsIntlResource::Get_Res_String(long Id, IntlString UsString)
{
	IntlString s;
	
	if (!mpResContainer || !mpResContainer->mpStringList)
		s = UsString;
	else
	{
		s = mpResContainer->mpStringList->Get(Id);
		if (!s)
			s = UsString;
	}

	return (s);
}

//
// Static members
//

IntlString clsIntlResource::ToString(const char * s)
{
	return (clsIntlResource::ToString((IntlString)s));	
}

IntlString clsIntlResource::ToString(IntlString s)
{
	clsIntlResource * Res = CURRENTRES;

	if (!Res->mpToStringList)
		Res->mpToStringList = new clsIntlStringList;

	return (Res->mpToStringList->Add(s));
}


IntlString clsIntlResource::ToString(int i)
{
	IntlChar Buffer[20];
	IntlString p;
	clsIntlResource * Res = CURRENTRES;

	p = &Buffer[19];
	*p = 0;
	

	if (!Res->mpToStringList)
		Res->mpToStringList = new clsIntlStringList;

	while (i)
	{
		p--;
		int Mod = i % 10;
		*p = '0' + Mod;
		i /= 10;
	}
	
	if (!*p)
		*(p--) = '0';
	return(Res->mpToStringList->Add(p));
}

IntlString clsIntlResource::GetResString(long Id, IntlString UsString)
{
	clsIntlResource * Res = CURRENTRES;

	if (Id == -1)
		return (UsString);
	return(Res->Get_Res_String(Id, UsString));
}

IntlString clsIntlResource::GetFResString(long Id, IntlString UsString, ...)
{
	clsIntlResource * Res = CURRENTRES;
	va_list vl;

	va_start(vl, UsString);
	return (Res->Get_Formatted_String(Id, UsString, vl));
}