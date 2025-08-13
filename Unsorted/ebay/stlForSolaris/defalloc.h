/*	$Id: defalloc.h,v 1.3 1998/12/17 04:13:33 josh Exp $	*/
/*
 *
 * Copyright (c) 1994
 * Hewlett-Packard Company
 *
 * Permission to use, copy, modify, distribute and sell this software
 * and its documentation for any purpose is hereby granted without fee,
 * provided that the above copyright notice appear in all copies and
 * that both that copyright notice and this permission notice appear
 * in supporting documentation.  Hewlett-Packard Company makes no
 * representations about the suitability of this software for any
 * purpose.  It is provided "as is" without express or implied warranty.
 *
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

#ifndef DEFALLOC_H
#define DEFALLOC_H

#include <new.h>
#include <stddef.h>
#include <stdlib.h>
#include <limits.h>
#include <iostream.h>
#include <algobase.h>

__BEGIN_STL_NAMESPACE

template <class T>
inline T* allocate(size_t size, T*) {
    set_new_handler(0);
    T* tmp = (T*)(::operator new(size));
    if (tmp == 0) {
	cerr << "out of memory" << endl; 
	exit(1);
    }
    return tmp;
}


template <class T>
inline void deallocate(T* buffer) {
    ::operator delete(buffer);
}

template <class T>
inline void deallocate(T* buffer, size_t) {
    ::operator delete(buffer);
}

template <class T>
class allocator {
public:
    typedef T value_type;
    typedef T* pointer;
    typedef const T* const_pointer;
    typedef T& reference;
    typedef const T& const_reference;
    typedef size_t size_type;
    typedef ptrdiff_t difference_type;
    static pointer allocate(size_type n) { 
	return STL_NAMESPACE::allocate(n, (pointer)0);
    }
    static void deallocate(pointer p) { STL_NAMESPACE::deallocate(p); }
    static void deallocate(pointer p, size_t s) { STL_NAMESPACE::deallocate(p,s); }
    static pointer address(reference x) { return (pointer)&x; }
    static const_pointer const_address(const_reference x) { 
	return (const_pointer)&x; 
    }
    static size_type init_page_size() { 
	return max(size_type(1), size_type(4096/sizeof(T))); 
    }
    static size_type max_size() { 
	return max(size_type(1), size_type(UINT_MAX/sizeof(T))); 
    }
};

class allocator<void> {
public:
    typedef void* pointer;
};

__END_STL_NAMESPACE

#endif
