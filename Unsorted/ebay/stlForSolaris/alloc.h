/*	$Id: alloc.h,v 1.3 1998/12/17 04:13:30 josh Exp $	*/
/*
 * Copyright (c) 1996
 * Silicon Graphics Computer Systems, Inc.
 *
 * Permission to use, copy, modify, distribute and sell this software
 * and its documentation for any purpose is hereby granted without fee,
 * provided that the above copyright notice appear in all copies and
 * that both that copyright notice and this permission notice appear
 * in supporting documentation.  Silicon Graphics makes no
 * representations about the suitability of this software for any
 * purpose.  It is provided "as is" without express or implied warranty.
 */

/*
 *
 * Copyright (c) 1997
 * Moscow Center for SPARC Technology
 *
 * Permission to use, copy, modify, distribute and sell this software
 * and its documentation for any purpose is hereby granted without fee,
 * provided that the above copyright notice appear in all copies and
 * that both that copyright notice and this permission notice appear
 * in supporting documentation.  Moscow Center for SPARC Technology makes no
 * representations about the suitability of this software for any
 * purpose.  It is provided "as is" without express or implied warranty.
 *
 */

#ifndef __ALLOC_H
#define __ALLOC_H

// This implements some standard node allocators.  These are
// NOT the same as the allocators in the C++ draft standard or in
// in the original STL.  They do not encapsulate different pointer
// types; indeed we assume that there is only one pointer type.
// The allocation primitives are intended to allocate individual objects,
// not larger arenas as with the original STL allocators.

#if 0
#   include <new>
#   define __THROW_BAD_ALLOC throw bad_alloc
#elif !defined(__THROW_BAD_ALLOC)
#   include <iostream.h>
#   define __THROW_BAD_ALLOC cerr << "out of memory" << endl; exit(1)
#endif

#ifndef __ALLOC
#   define __ALLOC alloc
#endif
#ifdef _WIN32THREADS
#   include <windows.h>
//  This must precede bool.h
#endif

#include <stddef.h>
#include <stdlib.h>
#include <string.h>
#include <bool.h>

#ifndef __RESTRICT
#  define __RESTRICT
#endif
#if defined(_SGI_SOURCE) && !defined(_NOTHREADS) && !defined(_PTHREADS)
#  define _SGITHREADS
   // This is a simple thread-safe implementation.
   // It uses spin locks and is oblivious the threads implementation.
   // Slowdown in single-threaded and low contention environments should
   // be acceptable.  Performance with multiple allocation-intensive threads
   // is not great, but sometimes tolerable.
#endif

#if !defined(_PTHREADS) && !defined(_NOTHREADS) \
 && !defined(_SGITHREADS) && !defined(_WIN32THREADS)
#   define _NOTHREADS
#endif

# ifdef _PTHREADS
    // POSIX Threads
    // This is dubious, since this is likely to be a high contention
    // lock.  The Posix standard appears to require an implemention
    // that makes convoy effects likely.  Performance may not be
    // adequate.
#   include <pthread.h>
    pthread_mutex_t __node_allocator_lock = PTHREAD_MUTEX_INITIALIZER;
#   define __NODE_ALLOCATOR_LOCK \
	if (threads) pthread_mutex_lock(&__node_allocator_lock)
#   define __NODE_ALLOCATOR_UNLOCK \
	if (threads) pthread_mutex_unlock(&__node_allocator_lock)
#   define __NODE_ALLOCATOR_THREADS true
#   define __VOLATILE volatile  // Needed at -O3 on SGI
# endif
# ifdef _WIN32THREADS
    // This one needs to be global so that a single constructor
    // call suffices for initialization.
    CRITICAL_SECTION __node_allocator_lock;
    bool __node_allocator_lock_initialized;
#   define __NODE_ALLOCATOR_LOCK \
	EnterCriticalSection(&__node_allocator_lock)
#   define __NODE_ALLOCATOR_UNLOCK \
	LeaveCriticalSection(&__node_allocator_lock)
#   define __NODE_ALLOCATOR_THREADS true
#   define __VOLATILE volatile  // may not be needed
# endif /* WIN32THREADS */
# ifdef _SGITHREADS
    // This should work without threads, with sproc threads, or with
    // pthreads.  It is suboptimal in all cases.
    // It is unlikely to even compile on nonSGI machines.
#   include <malloc.h>
#   define __NODE_ALLOCATOR_LOCK if (threads && __us_rsthread_malloc) \
		{ __lock(&__node_allocator_lock); }
#   define __NODE_ALLOCATOR_UNLOCK if (threads && __us_rsthread_malloc) \
		{ __unlock(&__node_allocator_lock); }
#   define __NODE_ALLOCATOR_THREADS true
#   define __VOLATILE volatile  // Needed at -O3 on SGI
# endif
# ifdef _NOTHREADS
//  Thread-unsafe
#   define __NODE_ALLOCATOR_LOCK
#   define __NODE_ALLOCATOR_UNLOCK
#   define __NODE_ALLOCATOR_THREADS false
#   define __VOLATILE
# endif

# if defined ( __USE_DEFALLOC )
# include <defalloc.h>
// no way to make the allocator<T> be default alloc type, just let use it.
# endif

# if defined ( __USE_ABBREVS )
// ugliness is intentional - to reduce conflicts probability
#  define __malloc_alloc_template   MaL__A_T
#  define __default_alloc_template  DfL__A_T
# endif

__BEGIN_STL_NAMESPACE

// That is an adaptor for working with any alloc provided below
template<class T, class alloc>
class simple_alloc {

public:
    static T *allocate(size_t n)
		{ return 0 == n? 0 : (T*) alloc::allocate(n * sizeof (T)); }
    static T *allocate(void)
		{ return (T*) alloc::allocate(sizeof (T)); }
    static void deallocate(T *p, size_t n)
		{ if (0 != n) alloc::deallocate(p, n * sizeof (T)); }
    static void deallocate(T *p)
		{ alloc::deallocate(p, sizeof (T)); }
};

// Malloc-based allocator.  Typically slower than default alloc below.
// Typically thread-safe and more storage efficient.

template <int instance_no>
class __malloc_alloc_template {

private:

static void *oom_malloc(size_t);

static void *oom_realloc(void *, size_t);

static void (* oom_handler)();

public:

static void * allocate(size_t n)
{
    void *result = malloc(n);
    if (0 == result) result = oom_malloc(n);
    return result;
}

static void deallocate(void *p, size_t /* n */)
{
    free(p);
}

static void * reallocate(void *p, size_t /* old_sz */, size_t new_sz)
{
    void * result = realloc(p, new_sz);
    if (0 == result) result = oom_realloc(p, new_sz);
    return result;
}

static void (* set_malloc_handler(void (*f)()))()
{
    void (* old)() = oom_handler;
    oom_handler = f;
    return(old);
}

};

// malloc_alloc out-of-memory handling
# if ( HAVE_STATIC_TEMPLATE_DATA > 0 )
template <int instance_no>
void (* __malloc_alloc_template<instance_no>::oom_handler)() =0 ;
#  else
__DECLARE_INSTANCE(void , (* __malloc_alloc_template<0>::oom_handler)(),0);
# endif /* ( HAVE_STATIC_TEMPLATE_DATA > 0 ) */

template <int instance_no>
void * __malloc_alloc_template<instance_no>::oom_malloc(size_t n)
{
    void (* my_malloc_handler)();
    void *result;

    for (;;) {
	my_malloc_handler = oom_handler;
	if (0 == my_malloc_handler) { __THROW_BAD_ALLOC; }
	(*my_malloc_handler)();
	result = malloc(n);
	if (result) return(result);
    }
}

template <int instance_no>
void * __malloc_alloc_template<instance_no>::oom_realloc(void *p, size_t n)
{
    void (* my_malloc_handler)();
    void *result;

    for (;;) {
	my_malloc_handler = oom_handler;
	if (0 == my_malloc_handler) { __THROW_BAD_ALLOC; }
	(*my_malloc_handler)();
	result = realloc(p, n);
	if (result) return(result);
    }
}

typedef __malloc_alloc_template<0> malloc_alloc;

# ifdef __USE_MALLOC
typedef malloc_alloc alloc;
typedef malloc_alloc single_client_alloc;

# else

// Default node allocator.
// With a reasonable compiler, this should be roughly as fast as the
// original STL class-specific allocators, but with less fragmentation.
// Default_alloc_template parameters are experimental and MAY
// DISAPPEAR in the future.  Clients should just use alloc for now.
//
// Important implementation properties:
// 1. If the client request an object of size > __MAX_BYTES, the resulting
//    object will be obtained directly from malloc.
// 2. In all other cases, we allocate an object of size exactly
//    ROUND_UP(requested_size).  Thus the client has enough size
//    information that we can return the object to the proper free list
//    without permanently losing part of the object.
//

// The first template parameter specifies whether more than one thread
// may use this allocator.  It is safe to allocate an object from
// one instance of a default_alloc and deallocate it with another
// one.  This effectively transfers its ownership to the second one.
// This may have undesirable effects on reference locality.
// The second parameter is unreferenced and serves only to allow the
// creation of multiple default_alloc instances.
// Node that containers built on different allocator instances have
// different types, limiting the utility of this approach.
#   if defined ( __SUNPRO_CC ) || defined ( _AIX )
// breaks if we make these template class members:
  enum {__ALIGN = 8};
  enum {__MAX_BYTES = 128};
  enum {__NFREELISTS = __MAX_BYTES/__ALIGN};
#endif

template <bool threads, int instance_no>
class __default_alloc_template {

__PRIVATE:
  // Really we should use static const int x = N
  // instead of enum { x = N }, but few compilers accept the former.
#   if ! (defined ( __SUNPRO_CC ) || defined ( _AIX ))
    enum {__ALIGN = 8};
    enum {__MAX_BYTES = 128};
    enum {__NFREELISTS = __MAX_BYTES/__ALIGN};
# endif
private:
  static size_t ROUND_UP(size_t bytes) {
	return (((bytes) + __ALIGN-1) & ~(__ALIGN - 1));
  }
__PRIVATE:
  union obj {
        union obj * free_list_link;
        char client_data[1];    /* The client sees this.        */
  };
private:
#   if defined ( __SUNPRO_CC ) || defined ( _AIX )
    static obj * __VOLATILE free_list[]; 
	// Specifying a size results in duplicate def for 4.1
# else
    static obj * __VOLATILE free_list[__NFREELISTS]; 
# endif
  static  size_t FREELIST_INDEX(size_t bytes) {
	return (((bytes) + __ALIGN-1)/__ALIGN - 1);
  }

  // Returns an object of size n, and optionally adds to size n free list.
  static void *refill(size_t n);
  // Allocates a chunk for nobjs of size size.  nobjs may be reduced
  // if it is inconvenient to allocate the requested number.
  static char *chunk_alloc(size_t size, int &nobjs);

  // Chunk allocation state.
  static char *start_free;
  static char *end_free;
  static size_t heap_size;

# ifdef _SGITHREADS
    static volatile unsigned long __node_allocator_lock;
    static void __lock(volatile unsigned long *); 
    static inline void __unlock(volatile unsigned long *);
# endif

# ifdef _PTHREADS
    static pthread_mutex_t __node_allocator_lock;
# endif

    class lock {
	public:
	    lock() { __NODE_ALLOCATOR_LOCK; }
	    ~lock() { __NODE_ALLOCATOR_UNLOCK; }
    };
    friend class lock;

public:

# ifdef _WIN32THREADS
    __default_alloc_template() {
	if (!__node_allocator_lock_initialized) {
	    InitializeCriticalSection(&__node_allocator_lock);
	    __node_allocator_lock_initialized = true;
	}
    }
# endif

  /* n must be > 0	*/
  static void * allocate(size_t n)
  {
    obj * __VOLATILE * my_free_list;
    obj * __RESTRICT result;

    if (n > __MAX_BYTES) {
	return(malloc_alloc::allocate(n));
    }
    my_free_list = free_list + FREELIST_INDEX(n);
    // Acquire the lock here with a constructor call.
    // This ensures that it is released in exit or during stack
    // unwinding.
     	/*REFERENCED*/
        lock lock_instance;
    result = *my_free_list;
    if (result == 0) {
	void *r = refill(ROUND_UP(n));
	return r;
    }
    *my_free_list = result -> free_list_link;
    return (result);
  };

  /* p may not be 0 */
  static void deallocate(void *p, size_t n)
  {
    obj *q = (obj *)p;
    obj * __VOLATILE * my_free_list;

    if (n > __MAX_BYTES) {
	malloc_alloc::deallocate(p, n);
	return;
    }
    my_free_list = free_list + FREELIST_INDEX(n);
    // acquire lock
        /*REFERENCED*/
        lock lock_instance;
    q -> free_list_link = *my_free_list;
    *my_free_list = q;
    // lock is released here
  }

  static void * reallocate(void *p, size_t old_sz, size_t new_sz);

} ;

typedef __default_alloc_template<__NODE_ALLOCATOR_THREADS, 0> alloc;
typedef __default_alloc_template<false, 0> single_client_alloc;
typedef __default_alloc_template<true, 0>  multithreaded_alloc;

/* We allocate memory in large chunks in order to avoid fragmenting	*/
/* the malloc heap too much.						*/
/* We assume that size is properly aligned.				*/
/* We hold the allocation lock.						*/
template <bool threads, int instance_no>
char *__default_alloc_template<threads, instance_no>
::chunk_alloc(size_t size, int &nobjs)
{
    char * result;
    size_t total_bytes = size * nobjs;
    size_t bytes_left = end_free - start_free;

    if (bytes_left >= total_bytes) {
	result = start_free;
	start_free += total_bytes;
	return(result);
    } else if (bytes_left >= size) {
	nobjs = bytes_left/size;
	total_bytes = size * nobjs;
	result = start_free;
	start_free += total_bytes;
	return(result);
    } else {
	size_t bytes_to_get = 2 * total_bytes + ROUND_UP(heap_size >> 4);
	// Try to make use of the left-over piece.
	if (bytes_left > 0) {
	    obj * __VOLATILE * my_free_list =
			free_list + FREELIST_INDEX(bytes_left);

            ((obj *)start_free) -> free_list_link = *my_free_list;
            *my_free_list = (obj *)start_free;
	}
	start_free = (char *)malloc(bytes_to_get);
	if (0 == start_free) {
	    int i;
	    obj * __VOLATILE * my_free_list, *p;
	    // Try to make do with what we have.  That can't
	    // hurt.  We do not try smaller requests, since that tends
	    // to result in disaster on multi-process machines.
	    for (i = size; i <= __MAX_BYTES; i += __ALIGN) {
		my_free_list = free_list + FREELIST_INDEX(i);
		p = *my_free_list;
		if (0 != p) {
		    *my_free_list = p -> free_list_link;
		    start_free = (char *)p;
		    end_free = start_free + i;
		    return(chunk_alloc(size, nobjs));
		    // Any leftover piece will eventually make it to the
		    // right free list.
		}
	    }
	    start_free = (char *)malloc_alloc::allocate(bytes_to_get);
	    // This should either throw an
	    // exception or remedy the situation.  Thus we assume it
	    // succeeded.
	}
	heap_size += bytes_to_get;
	end_free = start_free + bytes_to_get;
	return(chunk_alloc(size, nobjs));
    }
}


/* Returns an object of size n, and optionally adds to size n free list.*/
/* We assume that n is properly aligned.				*/
/* We hold the allocation lock.						*/
template <bool threads, int instance_no>
void *__default_alloc_template<threads, instance_no>
::refill(size_t n)
{
    int nobjs = 20;
    char * chunk = chunk_alloc(n, nobjs);
    obj * __VOLATILE * my_free_list;
    obj * result;
    obj * current_obj, * next_obj;
    int i;

    if (1 == nobjs) return(chunk);
    my_free_list = free_list + FREELIST_INDEX(n);

    /* Build free list in chunk */
      result = (obj *)chunk;
      *my_free_list = next_obj = (obj *)(chunk + n);
      for (i = 1; ; i++) {
	current_obj = next_obj;
	next_obj = (obj *)((char *)next_obj + n);
	if (nobjs - 1 == i) {
	    current_obj -> free_list_link = 0;
	    break;
	} else {
	    current_obj -> free_list_link = next_obj;
	}
      }
    return(result);
}

template <bool threads, int instance_no>
void *__default_alloc_template<threads, instance_no>
::reallocate(void *p, size_t old_sz, size_t new_sz)
{
    void * result;
    size_t copy_sz;

    if (old_sz > __MAX_BYTES && new_sz > __MAX_BYTES) {
	return(realloc(p, new_sz));
    }
    if (ROUND_UP(old_sz) == ROUND_UP(new_sz)) return(p);
    result = allocate(new_sz);
    copy_sz = new_sz > old_sz? old_sz : new_sz;
    memcpy(result, p, copy_sz);
    deallocate(p, old_sz);
    return(result);
}


#ifdef _SGITHREADS
#include <mutex.h>
#include <time.h>
// Somewhat generic lock implementations.  We need only test-and-set
// and some way to sleep.  These should work with both SGI pthreads
// and sproc threads.  They may be useful on other systems.
template <bool threads, int instance_no>
volatile unsigned long __default_alloc_template<threads, instance_no>
::__node_allocator_lock = 0;

#if __mips < 3 || !(defined (_ABIN32) || defined(_ABI64)) || defined(__GNUC__)
#   define __test_and_set(l,v) test_and_set(l,v)
#endif

template <bool threads, int instance_no>
void __default_alloc_template<threads, instance_no>
::__lock(volatile unsigned long *lock)
{
    const unsigned low_spin_max = 30;  // spin cycles if we suspect uniprocessor
    const unsigned high_spin_max = 1000; // spin cycles for multiprocessor
    static unsigned spin_max = low_spin_max;
    unsigned my_spin_max;
    static unsigned last_spins = 0;
    unsigned my_last_spins;
    static struct timespec ts = {0, 1000};
    unsigned junk;
#   define __ALLOC_PAUSE junk *= junk; junk *= junk; junk *= junk; junk *= junk
    int i;

    if (!__test_and_set((unsigned long *)lock, 1)) {
	return;
    }
    my_spin_max = spin_max;
    my_last_spins = last_spins;
    for (i = 0; i < my_spin_max; i++) {
	if (i < my_last_spins/2 || *lock) {
	    __ALLOC_PAUSE;
	    continue;
        }
	if (!__test_and_set((unsigned long *)lock, 1)) {
	    // got it!
	    // Spinning worked.  Thus we're probably not being scheduled
	    // against the other process with which we were contending.
	    // Thus it makes sense to spin longer the next time.
	    last_spins = i;
	    spin_max = high_spin_max;
	    return;
	}
    }
    // We are probably being scheduled against the other process.  Sleep.
    spin_max = low_spin_max;
    for (;;) {
	if (!__test_and_set((unsigned long *)lock, 1)) {
	    return;
	}
	nanosleep(&ts, 0);
    }
}

template <bool threads, int instance_no>
inline void __default_alloc_template<threads, instance_no>
::__unlock(volatile unsigned long *lock)
{
#   if defined(__GNUC__) && __mips >= 3
	asm("sync");
	*lock = 0;
#   elif __mips >= 3 && (defined (_ABIN32) || defined(_ABI64))
	__lock_release(lock);
#   else 
        *lock = 0;
	// This is not sufficient on many multiprocessors, since
	// writes to protected variables and the lock may be reordered.
#   endif
}
# endif

# if ( HAVE_STATIC_TEMPLATE_DATA > 0 )

#ifdef _PTHREADS
    template <bool threads, int instance_no>
    pthread_mutex_t
    __default_alloc_template<threads, instance_no>::__node_allocator_lock
    	= PTHREAD_MUTEX_INITIALIZER;
#endif

template <bool threads, int instance_no>
char *__default_alloc_template<threads, instance_no>
::start_free = 0;

template <bool threads, int instance_no>
char *__default_alloc_template<threads, instance_no>
::end_free = 0;

template <bool threads, int instance_no>
size_t __default_alloc_template<threads, instance_no>
::heap_size = 0;

template <bool threads, int instance_no>
__default_alloc_template<threads, instance_no>::obj * __VOLATILE
__default_alloc_template<threads, instance_no> ::free_list[
#   if defined ( __SUNPRO_CC ) || defined ( _AIX )
    __NFREELISTS
#   else
    __default_alloc_template<threads, instance_no>::__NFREELISTS
#   endif
] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, };
// The 16 zeros are necessary to make version 4.1 of the SunPro
// compiler happy.  Otherwise it appears to allocate too little
// space for the array.

#   ifdef _WIN32THREADS
  // Create one to get critical section initialized.
  alloc __node_allocator_dummy_instance;
#   endif
# else /* ( HAVE_STATIC_TEMPLATE_DATA > 0 ) */
__DECLARE_INSTANCE(char *, single_client_alloc::start_free,0);
__DECLARE_INSTANCE(char *, single_client_alloc::end_free,0);
__DECLARE_INSTANCE(size_t, single_client_alloc::heap_size,0);
__DECLARE_INSTANCE(single_client_alloc::obj * __VOLATILE,
                   single_client_alloc::free_list[single_client_alloc::__NFREELISTS],
                   {0});
__DECLARE_INSTANCE(char *, multithreaded_alloc::start_free,0);
__DECLARE_INSTANCE(char *, multithreaded_alloc::end_free,0);
__DECLARE_INSTANCE(size_t, multithreaded_alloc::heap_size,0);
__DECLARE_INSTANCE(multithreaded_alloc::obj * __VOLATILE,
                   multithreaded_alloc::free_list[multithreaded_alloc::__NFREELISTS],
                   {0});

#   ifdef _WIN32THREADS
    // Create one to get critical section initialized.
    __DECLARE_INSTANCE(alloc, __node_allocator_dummy_instance, alloc());
#   endif
#   ifdef _PTHREADS
     __DECLARE_INSTANCE(pthread_mutex_t,
     multithreaded_alloc::__node_allocator_lock,
     PTHREAD_MUTEX_INITIALIZER);
#   endif
#  endif /* HAVE_STATIC_TEMPLATE_DATA */

#endif /* ! __USE_MALLOC */

__END_STL_NAMESPACE

#endif /* __NODE_ALLOC_H */
