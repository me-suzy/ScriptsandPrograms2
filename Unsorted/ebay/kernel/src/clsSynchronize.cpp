/* $Id: clsSynchronize.cpp,v 1.2 1999/04/17 20:22:44 wwen Exp $ */
//
// File: clsSynchronize
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: This a set of class for thread synchronization.
//				This is part of the Gallery project.
//

#include <windows.h>
#include "eBayKernel.h"

//
// clsSynchronizeable
//

clsSynchronizeable::clsSynchronizeable()
{
	mLockCount = -1;
	mOwnCount = 0;
	mOwnerThreadId = 0;
#if defined(USE_CRITICALSECTION)
	InitializeCriticalSection(&mSemaphore);
#else
	mEvent = CreateEvent(NULL, FALSE, FALSE, NULL);
#endif
}

clsSynchronizeable::~clsSynchronizeable()
{
#if defined(USE_CRITICALSECTION)
	DeleteCriticalSection(&mSemaphore);
#else
	CloseHandle(mEvent);
#endif
}

long clsSynchronizeable::Lock(long 
#if !defined(USE_CRITICALSECTION)
							  timeout
#endif
							  )
{
#if defined(USE_CRITICALSECTION)
	EnterCriticalSection(&mSemaphore);
	return 0;
#else
	DWORD threadId = GetCurrentThreadId();
	DWORD result = WAIT_OBJECT_0;

	if (InterlockedIncrement(&mLockCount) == 0)
	{
		mOwnerThreadId = threadId;
		mOwnCount = 1;
	}
	else
	{
		if (mOwnerThreadId == threadId)
		{
			++ mOwnCount;
		}
		else
		{
			result = WaitForSingleObject(mEvent, timeout);
			if (result != WAIT_TIMEOUT)	// can be WAIT_ABANDONED
			{
				mOwnerThreadId = threadId;
				mOwnCount = 1;
			}
		}
	}

	return result;
#endif
}

void clsSynchronizeable::Unlock()
{
#if defined(USE_CRITICALSECTION)
	LeaveCriticalSection(&mSemaphore);
#else
	if (--mOwnCount > 0)
	{
		InterlockedDecrement(&mLockCount);
	}
	else
	{
		mOwnerThreadId = 0;
		if (InterlockedDecrement(&mLockCount) >= 0)
		{
			SetEvent(mEvent);
		}
	}
#endif
}

