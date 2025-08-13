/*	$Id: vector.h,v 1.3 1998/12/17 04:13:55 josh Exp $	*/
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
 *
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

#ifndef VECTOR_H
#define VECTOR_H

#include <stddef.h>
#include <algobase.h>
#include <alloc.h>

__BEGIN_STL_NAMESPACE
__BEGIN_STL_FULL_NAMESPACE
#  define vector __WORKAROUND_RENAME(vector)

template <class T, __DFL_TYPE_PARAM(Alloc,alloc) >
class vector {
    typedef vector<T,Alloc> self;
public:
    typedef T value_type;
    typedef value_type* pointer;
    typedef value_type* iterator;
    typedef const value_type* const_iterator;
    typedef value_type& reference;
    typedef const value_type& const_reference;
    typedef size_t size_type;
    typedef ptrdiff_t difference_type;
    typedef reverse_iterator<const_iterator, value_type, const_reference, 
                             difference_type>  const_reverse_iterator;
    typedef reverse_iterator<iterator, value_type, reference, difference_type>
        reverse_iterator;
protected:
    typedef simple_alloc<value_type, Alloc> data_allocator;
    iterator start;
    iterator finish;
    iterator end_of_storage;
    void insert_aux(iterator position, const T& x);
    void deallocate() {
      if (start) data_allocator::deallocate(start, end_of_storage - start);
    }
public:
    iterator begin() { return start; }
    const_iterator begin() const { return start; }
    iterator end() { return finish; }
    const_iterator end() const { return finish; }
    reverse_iterator rbegin() { return reverse_iterator(end()); }
    const_reverse_iterator rbegin() const { 
        return const_reverse_iterator(end()); 
    }
    reverse_iterator rend() { return reverse_iterator(begin()); }
    const_reverse_iterator rend() const { 
        return const_reverse_iterator(begin()); 
    }
    size_type size() const { return size_type(end() - begin()); }
    size_type max_size() const { return size_type(-1); }
    size_type capacity() const { return size_type(end_of_storage - start); }
    bool empty() const { return begin() == end(); }
    reference operator[](size_type n) { return *(begin() + n); }
    const_reference operator[](size_type n) const { return *(begin() + n); }
    vector() : start(0), finish(0), end_of_storage(0) {}
    explicit vector(size_type n, const T& value) {
	start = data_allocator::allocate(n);
	uninitialized_fill_n(start, n, value);
	finish = start + n;
	end_of_storage = finish;
    }
    explicit vector(size_type n) {
	start = data_allocator::allocate(n);
	uninitialized_fill_n(start, n, T());
	finish = start + n;
	end_of_storage = finish;
    }
    vector(const self& x) {
	start = data_allocator::allocate(x.end() - x.begin());
	finish = uninitialized_copy(x.begin(), x.end(), start);
	end_of_storage = finish;
    }
    vector(const_iterator first, const_iterator last) {
	size_type n = 0;
	distance(first, last, n);
	start = data_allocator::allocate(n);
	finish = uninitialized_copy(first, last, start);
	end_of_storage = finish;
    }
    ~vector() { 
	destroy(start, finish);
	deallocate();
    }
    self& operator=(const self& x);
    void reserve(size_type n) {
	if (capacity() < n) {
	    iterator tmp = data_allocator::allocate(n);
	    uninitialized_copy(begin(), end(), tmp);
	    destroy(start, finish);
	    deallocate();
	    finish = tmp + size();
	    start = tmp;
	    end_of_storage = begin() + n;
	}
    }
    reference front() { return *begin(); }
    const_reference front() const { return *begin(); }
    reference back() { return *(end() - 1); }
    const_reference back() const { return *(end() - 1); }
    void push_back(const T& x) {
	if (finish != end_of_storage) {
	    /* Borland bug */
	    construct(finish, x);
	    finish++;
	} else
	    insert_aux(end(), x);
    }
    void swap(self& x) {
	STL_NAMESPACE::swap(start, x.start);
	STL_NAMESPACE::swap(finish, x.finish);
	STL_NAMESPACE::swap(end_of_storage, x.end_of_storage);
    }
    iterator insert(iterator position, const T& x) {
	size_type n = position - begin();
	if (finish != end_of_storage && position == end()) {
	    /* Borland bug */
	    construct(finish, x);
	    finish++;
	} else
	    insert_aux(position, x);
	return begin() + n;
    }
    iterator insert(iterator position) { return insert(position, T()); }
    void insert (iterator position, const_iterator first, 
		 const_iterator last);
    void insert (iterator position, size_type n, const T& x);
    void pop_back() {
	/* Borland bug */
        --finish;
        destroy(finish);
    }
    void erase(iterator position) {
	if (position + 1 != end())
	    copy(position + 1, end(), position);
	--finish;
	destroy(finish);
    }
    void erase(iterator first, iterator last) {
	iterator i = copy(last, end(), first);
	destroy(i, finish);
	finish = finish - (last - first); 
    }
    void resize(size_type new_size, const T& x) {
      if (new_size < size()) 
        erase(begin() + new_size, end());
      else
        insert(end(), new_size - size(), x);
    }
    void resize(size_type new_size) { resize(new_size, T()); }
    void clear() { erase(begin(), end()); }
};

# ifdef HAVE_NESTED_TYPE_PARAM_BUG
#  define __iterator__       T*
#  define __const_iterator__ const T*
#  define __size_type__      size_t
# else
#  define __iterator__       iterator
#  define __const_iterator__ const_iterator
#  define __size_type__      size_t
# endif

template <class T, class Alloc>
vector<T, Alloc>& vector<T, Alloc>::operator=(const vector<T, Alloc>& x) {
    if (&x == this) return *this;
    if (x.size() > capacity()) {
	destroy(start, finish);
	deallocate();
	start = data_allocator::allocate(x.end() - x.begin());
	end_of_storage = uninitialized_copy(x.begin(), x.end(), start);
    } else if (size() >= x.size()) {
	iterator i = copy(x.begin(), x.end(), begin());
	destroy(i, finish);
    } else {
	copy(x.begin(), x.begin() + size(), begin());
	uninitialized_copy(x.begin() + size(), x.end(), begin() + size());
    }
    finish = begin() + x.size();
    return *this;
}

template <class T, class Alloc>
void vector<T, Alloc>::insert_aux(__iterator__ position, const T& x) {
    if (finish != end_of_storage) {
	construct(finish, *(finish - 1));
	copy_backward(position, finish - 1, finish);
	*position = x;
	++finish;
    } else {
        const size_type old_size = size();
	const size_type len = old_size != 0 ? 2 * old_size : 1;
	iterator tmp = data_allocator::allocate(len);
	uninitialized_copy(begin(), position, tmp);
	construct(tmp + (position - begin()), x);
	uninitialized_copy(position, end(), tmp + (position - begin()) + 1);
	destroy(begin(), end());
	deallocate();
	end_of_storage = tmp + len;
	finish = tmp + old_size + 1;
	start = tmp;
    }
}

template <class T, class Alloc>
void vector<T, Alloc>::insert(__iterator__ position, __size_type__ n, const T& x) {
    if (n == 0) return;
    if (end_of_storage - finish >= (difference_type)n) {
	if (end() - position > (difference_type)n) {
	    uninitialized_copy(end() - n, end(), end());
	    copy_backward(position, end() - n, end());
	    fill(position, position + n, x);
	} else {
	    uninitialized_copy(position, end(), position + n);
	    fill(position, end(), x);
	    uninitialized_fill_n(end(), n - (end() - position), x);
	}
	finish += n;
    } else {
        const size_type old_size = size();        
	const size_type len = old_size + max(old_size, n);
	iterator tmp = data_allocator::allocate(len);
	uninitialized_copy(begin(), position, tmp);
	uninitialized_fill_n(tmp + (position - begin()), n, x);
	uninitialized_copy(position, end(), tmp + (position - begin() + n));
	destroy(begin(), end());
	deallocate();
	end_of_storage = tmp + len;
	finish = tmp + old_size + n;
	start = tmp;
    }
}

template <class T, class Alloc>
void vector<T, Alloc>::insert(__iterator__ position, 
		       __const_iterator__ first,
		       __const_iterator__ last) {
    if (first == last) return;
    size_type n = 0;
    distance(first, last, n);
    if (end_of_storage - finish >= (difference_type)n) {
	if (end() - position > (difference_type)n) {
	    uninitialized_copy(end() - n, end(), end());
	    copy_backward(position, end() - n, end());
	    copy(first, last, position);
	} else {
	    uninitialized_copy(position, end(), position + n);
	    copy(first, first + (end() - position), position);
	    uninitialized_copy(first + (end() - position), last, end());
	}
	finish += n;
    } else {
        const size_type old_size = size();
	const size_type len = old_size + max(old_size, n);
	iterator tmp = data_allocator::allocate(len);
	uninitialized_copy(begin(), position, tmp);
	uninitialized_copy(first, last, tmp + (position - begin()));
	uninitialized_copy(position, end(), tmp + (position - begin() + n));
	destroy(begin(), end());
	deallocate();
	end_of_storage = tmp + len;
	finish = tmp + old_size + n;
	start = tmp;
    }
}

// do a cleanup
# undef  vector
# undef  __iterator__
# undef  __const_iterator__
# undef  __size_type__

// provide a uniform way to access full funclionality
#  define __vector__ __FULL_NAME(vector)
__END_STL_FULL_NAMESPACE

# if ! defined (HAVE_DEFAULT_TYPE_PARAM)
// provide a "default" vector adaptor
template <class T>
class vector : public __vector__<T,alloc>
{
    typedef vector<T> self;
public:
    typedef __vector__<T,alloc> super;
    __CONTAINER_SUPER_TYPEDEFS
    __IMPORT_SUPER_COPY_ASSIGNMENT(vector)
    vector() {}
    explicit vector(size_type n, const T& value) : super(n, value) { }
    explicit vector(size_type n) : super(n) { }
    vector(const_iterator first, const_iterator last) : super(first,last) { }
    ~vector() {}
};

#  if defined (HAVE_BASE_MATCH_BUG)
template <class T>
inline bool operator==(const vector<T>& x, const vector<T>& y) {
    typedef  __vector__<T,alloc> super;
    return operator == ((const super&)x,(const super&)y);
}

template <class T>
inline bool operator<(const vector<T>& x, const vector<T>& y) {
    typedef  __vector__<T,alloc> super;
    return operator < ((const super&)x,(const super&)y);
}
#  endif
# endif /* HAVE_DEFAULT_TEMPLATE_PARAM */

template <class T, class Alloc>
inline bool operator==(const __vector__<T, Alloc>& x, const __vector__<T, Alloc>& y) {
    return x.size() == y.size() && equal(x.begin(), x.end(), y.begin());
}

template <class T, class Alloc>
inline bool operator<(const __vector__<T, Alloc>& x, const __vector__<T, Alloc>& y) {
    return lexicographical_compare(x.begin(), x.end(), y.begin(), y.end());
}

// close std namespace
__END_STL_NAMESPACE

#endif
