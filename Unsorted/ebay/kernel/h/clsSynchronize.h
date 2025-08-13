/* $Id: clsSynchronize.h,v 1.3 1999/04/18 01:59:11 wwen Exp $ */
//
// File: clsSynchronize
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: This a set of class for thread synchronization.
//				This is part of the Gallery project.
//

#ifndef clsSynchronize_h
#define clsSynchronize_h

#ifdef _MSC_VER

#include <windows.h>

class clsSynchronize;
class clsSynchronizeable;

// On windows there are many ways to synchronize processes. The main way
// is through event objects which work cross process and potentially 
// accross a network. Critical sections is another way, but they only
// work within a single process, however they are much faster.
// Define USE_CRITICALSECTION if you want to use critical sections
// instead of event objects for synchronization.
#define USE_CRITICALSECTION

class clsSynchronizeable
{
public:
	clsSynchronizeable();
	~clsSynchronizeable();

	friend clsSynchronize;

	long Lock(long dwTimeout = INFINITE);
	void Unlock();

private:
	long mLockCount;
	long mOwnCount;
	DWORD mOwnerThreadId;
#if defined(USE_CRITICALSECTION)
	CRITICAL_SECTION mSemaphore;
#else
	HANDLE mEvent;
#endif

	clsSynchronizeable(const clsSynchronizeable& );
	clsSynchronizeable& operator=(const clsSynchronizeable& );
		// no copying
};

class clsSynchronize
{
public:
	explicit clsSynchronize(clsSynchronizeable& cs, unsigned long timeout = INFINITE) :
	  mclsSynchronizeable(cs)
		{ mclsSynchronizeable.Lock(timeout); }

	~clsSynchronize() { mclsSynchronizeable.Unlock(); }


private:
	clsSynchronizeable& mclsSynchronizeable;

	clsSynchronize(const clsSynchronize& );
	clsSynchronize& operator=(const clsSynchronize& );
		// no copying
};

#endif // _MSC_VER

#endif // clsSynchronize_h
