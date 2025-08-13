/*	$Id: hashtable.h,v 1.3 1998/12/17 04:13:38 josh Exp $	*/
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
 *
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


#ifndef SGI_STL_HASHTABLE_H
#define SGI_STL_HASHTABLE_H

// Hashtable class, used to implement the hashed associative containers
// hash_set, hash_map, hash_multiset, and hash_multimap.


#include <stdlib.h>
#include <stddef.h>
#include <algo.h>
#include <vector.h>

# if defined ( __USE_ABBREVS )
#  define __hashtable_iterator         _hT__It
#  define __hashtable_const_iterator   _hT__cIt
#  define __hashtable_node             _hT__N
#  define hashtable                    _h__T
# endif

__BEGIN_STL_NAMESPACE

template <class Key> struct hash { };

inline size_t __stl_hash_string(const char* s)
{
  unsigned long h = 0; 
  for ( ; *s; ++s)
    h = 5*h + *s;
  
  return size_t(h);
}

struct hash<char*>
{
  size_t operator()(const char* s) const { return __stl_hash_string(s); }
};

struct hash<const char*>
{
  size_t operator()(const char* s) const { return __stl_hash_string(s); }
};

struct hash<char> {
  size_t operator()(char x) const { return x; }
};
struct hash<unsigned char> {
  size_t operator()(unsigned char x) const { return x; }
};
struct hash<signed char> {
  size_t operator()(unsigned char x) const { return x; }
};
struct hash<short> {
  size_t operator()(short x) const { return x; }
};
struct hash<unsigned short> {
  size_t operator()(unsigned short x) const { return x; }
};
struct hash<int> {
  size_t operator()(int x) const { return x; }
};
struct hash<unsigned int> {
  size_t operator()(unsigned int x) const { return x; }
};
struct hash<long> {
  size_t operator()(long x) const { return x; }
};
struct hash<unsigned long> {
  size_t operator()(unsigned long x) const { return x; }
};

template <class Value>
struct __hashtable_node
{
  __hashtable_node* next;
  Value val;
};  

template <class Value, class Key, class HashFcn,
          class ExtractKey, class EqualKey, class Alloc>
class hashtable;

template <class Value, class Key, class HashFcn,
          class ExtractKey, class EqualKey, class Alloc>
struct __hashtable_iterator;

template <class Value, class Key, class HashFcn,
          class ExtractKey, class EqualKey, class Alloc>
struct __hashtable_const_iterator;

template <class Value, class Key, class HashFcn,
          class ExtractKey, class EqualKey, class Alloc>
struct __hashtable_iterator {
  typedef hashtable<Value, Key, HashFcn, ExtractKey, EqualKey, Alloc>
          hashtable;
  typedef __hashtable_iterator<Value, Key, HashFcn, 
                               ExtractKey, EqualKey, Alloc>
          iterator;
  typedef __hashtable_const_iterator<Value, Key, HashFcn, 
                                     ExtractKey, EqualKey, Alloc>
          const_iterator;
  typedef __hashtable_node<Value> node;
  typedef size_t size_type;
  typedef Value& reference;
  typedef const Value& const_reference;

  node* cur;
  hashtable* ht;

  __hashtable_iterator(node* n, hashtable* tab) : cur(n), ht(tab) {}
  __hashtable_iterator() {}
  reference operator*() const { return cur->val; }
  iterator& operator++();
  iterator operator++(int);
  bool operator==(const iterator& it) const { return cur == it.cur; }
  bool operator!=(const iterator& it) const { return cur != it.cur; }
};


template <class Value, class Key, class HashFcn,
          class ExtractKey, class EqualKey, class Alloc>
struct __hashtable_const_iterator {
  typedef hashtable<Value, Key, HashFcn, ExtractKey, EqualKey, Alloc>
          hashtable;
  typedef __hashtable_iterator<Value, Key, HashFcn, 
                               ExtractKey, EqualKey, Alloc>
          iterator;
  typedef __hashtable_const_iterator<Value, Key, HashFcn, 
                                     ExtractKey, EqualKey, Alloc>
          const_iterator;
  typedef __hashtable_node<Value> node;
  typedef size_t size_type;
  typedef Value& reference;
  typedef const Value& const_reference;

  const node* cur;
  const hashtable* ht;

  __hashtable_const_iterator(const node* n, const hashtable* tab)
    : cur(n), ht(tab) {}
  __hashtable_const_iterator() {}
  __hashtable_const_iterator(const iterator& it) : cur(it.cur), ht(it.ht) {}
  const_reference operator*() const { return cur->val; }
  const_iterator& operator++();
  const_iterator operator++(int);
  bool operator==(const const_iterator& it) const { return cur == it.cur; }
  bool operator!=(const const_iterator& it) const { return cur != it.cur; }
};

// Note: assumes long is at least 32 bits.
// fbp: try to avoid intances in every module
enum { __stl_num_primes = 28 };

#if ( HAVE_STATIC_TEMPLATE_DATA > 0 )
#  define __stl_prime_list __stl_prime<false>::list_
   template <bool dummy>
   struct __stl_prime {
   public:
       static const unsigned long list_[];
   };
   template <bool dummy>
   const unsigned long __stl_prime<dummy>::list_[] =
#  else
#  if ( HAVE_WEAK_ATTRIBUTE > 0 )
      extern const unsigned long __stl_prime_list[__stl_num_primes] __attribute__((weak)) =
#  else
      // give up
      static const unsigned long __stl_prime_list[__stl_num_primes] =
#  endif /* HAVE_WEAK_ATTRIBUTE */
#endif /* HAVE_STATIC_TEMPLATE_DATA */
{
  53,         97,         193,       389,       769,
  1543,       3079,       6151,      12289,     24593,
  49157,      98317,      196613,    393241,    786433,
  1572869,    3145739,    6291469,   12582917,  25165843,
  50331653,   100663319,  201326611, 402653189, 805306457, 
  1610612741, 3221225473, 4294967291
};

inline unsigned long __stl_next_prime(unsigned long n)
{
  const unsigned long* last = __stl_prime_list + __stl_num_primes;
  const unsigned long* pos = lower_bound((const unsigned long*)__stl_prime_list, last, n);
  return pos == last ? *(last - 1) : *pos;
}

template <class Value, class Key, class HashFcn,
          class ExtractKey, class EqualKey,
// Borland chokes on this, so __DFL_TMPL used instead of __DFL_TYPE
          __DFL_TMPL_PARAM(Alloc,alloc) >
class hashtable {
  typedef hashtable<Value, Key, HashFcn, ExtractKey, EqualKey, Alloc> self;
public:
  typedef Key key_type;
  typedef Value value_type;
  typedef HashFcn hasher;
  typedef EqualKey key_equal;

  typedef size_t            size_type;
  typedef ptrdiff_t         difference_type;
  typedef value_type*       pointer;
  typedef const value_type* const_pointer;
  typedef value_type&       reference;
  typedef const value_type& const_reference;

  hasher hash_funct() const { return hash; }
  key_equal key_eq() const { return equals; }

private:
  hasher hash;
  key_equal equals;
  ExtractKey get_key;

  typedef __hashtable_node<Value> node;
  typedef simple_alloc<node, Alloc> node_allocator;

  __vector__<node*,Alloc> buckets;
  size_type num_elements;

public:
  typedef __hashtable_iterator<Value, Key, HashFcn, ExtractKey, EqualKey, 
                               Alloc>
  iterator;

  typedef __hashtable_const_iterator<Value, Key, HashFcn, ExtractKey, EqualKey,
                                     Alloc>
  const_iterator;

  friend struct
  __hashtable_iterator<Value, Key, HashFcn, ExtractKey, EqualKey, Alloc>;
  friend struct
  __hashtable_const_iterator<Value, Key, HashFcn, ExtractKey, EqualKey, Alloc>;

public:
  hashtable(size_type n,
            const HashFcn&    hf,
            const EqualKey&   eql,
            const ExtractKey& ext)
    : hash(hf), equals(eql), get_key(ext), num_elements(0)
  {
    initialize_buckets(n);
  }

  hashtable(size_type n,
            const HashFcn&    hf,
            const EqualKey&   eql)
    : hash(hf), equals(eql), get_key(ExtractKey()), num_elements(0)
  {
    initialize_buckets(n);
  }

  hashtable(const self& ht)
    : hash(ht.hash), equals(ht.equals), get_key(ht.get_key)
  {
    copy_from(ht);
  }

  hashtable& operator= (const self& ht)
  {
    if (&ht != this) {
      hash = ht.hash;
      equals = ht.equals;
      get_key = ht.get_key;
      clear();
      buckets.erase(buckets.begin(), buckets.end());
      copy_from(ht);
    }
    return *this;
  }

  ~hashtable() { clear(); }

  size_type size() const { return num_elements; }
  size_type max_size() const { return size_type(-1); }
  bool empty() const { return size() == 0; }

  void swap(self& ht)
  {
    STL_NAMESPACE::swap(hash, ht.hash);
    STL_NAMESPACE::swap(equals, ht.equals);
    STL_NAMESPACE::swap(get_key, ht.get_key);
    STL_NAMESPACE::swap(buckets, ht.buckets);
    STL_NAMESPACE::swap(num_elements, ht.num_elements);
  }

  iterator begin()
  { 
    for (size_type n = 0; n < buckets.size(); ++n)
      if (buckets[n])
        return iterator(buckets[n], this);
    return end();
  }

  iterator end() { return iterator((node*)0, this); }

  const_iterator begin() const
  {
    for (size_type n = 0; n < buckets.size(); ++n)
      if (buckets[n])
        return const_iterator(buckets[n], this);
    return end();
  }

  const_iterator end() const { return const_iterator((node*)0, this); }

  friend bool operator== (const self&,const self);

public:

  size_type bucket_count() const { return buckets.size(); }

  size_type max_bucket_count() const
    { return __stl_prime_list[__stl_num_primes - 1]; } 

  size_type elems_in_bucket(size_type bucket) const
  {
    size_type result = 0;
    for (node* cur = buckets[bucket]; cur; cur = cur->next)
      result += 1;
    return result;
  }

  pair<iterator, bool> insert_unique(const value_type& obj)
  {
    resize(num_elements + 1);
    return insert_unique_noresize(obj);
  }

  iterator insert_equal(const value_type& obj)
  {
    resize(num_elements + 1);
    return insert_equal_noresize(obj);
  }

  pair<iterator, bool> insert_unique_noresize(const value_type& obj);
  iterator insert_equal_noresize(const value_type& obj);
 
  void insert_unique(const value_type* f, const value_type* l)
  {
    size_type n = l - f;
    resize(num_elements + n);
    for ( ; n > 0; --n)
      insert_unique_noresize(*f++);
  }

  void insert_equal(const value_type* f, const value_type* l)
  {
    size_type n = l - f;
    resize(num_elements + n);
    for ( ; n > 0; --n)
      insert_equal_noresize(*f++);
  }

 void insert_unique(const_iterator f, const_iterator l)
  {
    size_type n = 0;
    distance(f, l, n);
    resize(num_elements + n);
    for ( ; n > 0; --n)
      insert_unique_noresize(*f++);
  }

  void insert_equal(const_iterator f, const_iterator l)
  {
    size_type n = 0;
    distance(f, l, n);
    resize(num_elements + n);
    for ( ; n > 0; --n)
      insert_equal_noresize(*f++);
  }

  reference find_or_insert(const value_type& obj);

  iterator find(const key_type& key) 
  {
    size_type n = bkt_num_key(key);
    node* first;
    for ( first = buckets[n];
          first && !equals(get_key(first->val), key);
          first = first->next)
      {}
    return iterator(first, this);
  } 

  const_iterator find(const key_type& key) const
  {
    size_type n = bkt_num_key(key);
    const node* first;
    for ( first = buckets[n];
          first && !equals(get_key(first->val), key);
          first = first->next)
      {}
    return const_iterator(first, this);
  } 

  size_type count(const key_type& key) const
  {
    const size_type n = bkt_num_key(key);
    size_type result = 0;

    for (const node* cur = buckets[n]; cur; cur = cur->next)
      if (equals(get_key(cur->val), key))
        ++result;
    return result;
  }

  pair<iterator, iterator> equal_range(const key_type& key);
  pair<const_iterator, const_iterator> equal_range(const key_type& key) const;

  size_type erase(const key_type& key);
  void erase(const self::iterator& it);
  void erase(self::iterator first, self::iterator last);

  void erase(const self::const_iterator& it);
  void erase(self::const_iterator first, self::const_iterator last);

  void resize(size_type num_elements_hint);
  void clear();

private:
  size_type next_size(size_type n) const { return __stl_next_prime(n); }

  void initialize_buckets(size_type n)
  {
    const size_type n_buckets = next_size(n);
    buckets.reserve(n_buckets);
    buckets.insert(buckets.end(), n_buckets, (node*) 0);
    num_elements = 0;
  }

  size_type bkt_num_key(const key_type& key) const
  {
    return bkt_num_key(key, buckets.size());
  }

  size_type bkt_num(const value_type& obj) const
  {
    return bkt_num_key(get_key(obj));
  }

  size_type bkt_num_key(const key_type& key, size_t n) const
  {
    return hash(key) % n;
  }

  size_type bkt_num(const value_type& obj, size_t n) const
  {
    return bkt_num_key(get_key(obj), n);
  }

  node* new_node(const value_type& obj)
  {
    node* n = node_allocator::allocate();
    construct(&(n->val), obj);
    return n;
  }
  
  void delete_node(node* n)
  {
    destroy(&(n->val));
    node_allocator::deallocate(n);
  }
  
  void erase_bucket(const size_type n, node* first, node* last);
  void erase_bucket(const size_type n, node* last);

  void copy_from(const self& ht);

};

// fbp: these defines are for outline methods definitions.
// needed to definitions to be portable. Should not be used in method bodies.
# define __template__        template <class V, class K, class HF, class ExK, class EqK, class A>
# define __hashtable__       hashtable<V, K, HF, ExK, EqK, A>
# define __iterator__        __hashtable_iterator<V, K, HF, ExK, EqK, A>
# define __const_iterator__  __hashtable_const_iterator<V, K, HF, ExK, EqK, A>
// provide additional syntax suitable for gcc
# if defined ( HAVE_NESTED_TYPE_PARAM_BUG )
#  define __difference_type__ ptrdiff_t
#  define __size_type__       size_t
#  define __value_type__      V
#  define __key_type__        K
#  define __node__            __hashtable_node<V>
#  define __reference__       V&
# else
#  define __difference_type__  __hashtable__::difference_type
#  define __size_type__        __hashtable__::size_type
#  define __value_type__       __hashtable__::value_type
#  define __key_type__         __hashtable__::key_type
#  define __node__             __hashtable__::node
#  define __reference__        __hashtable__::reference
# endif

__template__
__iterator__
__iterator__::operator++(int)
{
  iterator tmp = *this;
  ++*this;
  return tmp;
}

__template__
inline __const_iterator__&
__const_iterator__::operator++()
{
  const node* old = cur;
  cur = cur->next;
  if (!cur) {
    size_type bucket = ht->bkt_num(old->val);
    while (!cur && ++bucket < ht->buckets.size())
      cur = ht->buckets[bucket];
  }
  return *this;
}

__template__
inline __const_iterator__
__const_iterator__::operator++(int)
{
  const_iterator tmp = *this;
  ++*this;
  return tmp;
}


__template__
inline forward_iterator_tag
iterator_category(const __iterator__&)
{
  return forward_iterator_tag();
}

__template__
inline V* 
value_type(const __iterator__&)
{
  return (V*) 0;
}

__template__
inline __difference_type__*
distance_type(const __iterator__&)
{
  return (__difference_type__*) 0;
}

__template__
inline forward_iterator_tag
iterator_category(const __const_iterator__&)
{
  return forward_iterator_tag();
}

__template__
inline V* 
value_type(const __const_iterator__&)
{
  return (V*) 0;
}

__template__
inline __difference_type__*
distance_type(const __const_iterator__&)
{
  return (__difference_type__*) 0;
}



template <class V, class K, class HF, class Ex, class Eq, class A, class A2>
bool operator==(const hashtable<V, K, HF, Ex, Eq, A>& ht1,
                const hashtable<V, K, HF, Ex, Eq, A2>& ht2)
{
  typedef typename hashtable<V, K, HF, Ex, Eq, A>::node node;
  if (ht1.buckets.size() != ht2.buckets.size())
    return false;
  for (int n = 0; n < ht1.buckets.size(); ++n) {
    node* cur1 = ht1.buckets[n];
    node* cur2 = ht2.buckets[n];
    for ( ; cur1 && cur2 && cur1->val == cur2->val;
          cur1 = cur1->next, cur2 = cur2->next)
      {}
    if (cur1 || cur2)
      return false;
  }
  return true;
}  


__template__
pair<__iterator__, bool> 
__hashtable__::insert_unique_noresize(const __value_type__& obj)
{
  const size_type n = bkt_num(obj);
  node* first = buckets[n];

  for (node* cur = first; cur; cur = cur->next) 
    if (equals(get_key(cur->val), get_key(obj)))
      return pair<iterator, bool>(iterator(cur, this), false);

  node* tmp = new_node(obj);
  tmp->next = first;
  buckets[n] = tmp;
  ++num_elements;
  return pair<iterator, bool>(iterator(tmp, this), true);
}

__template__
__iterator__ 
__hashtable__::insert_equal_noresize(const __value_type__& obj)
{
  const size_type n = bkt_num(obj);
  node* first = buckets[n];

  for (node* cur = first; cur; cur = cur->next) 
    if (equals(get_key(cur->val), get_key(obj))) {
      node* tmp = new_node(obj);
      tmp->next = cur->next;
      cur->next = tmp;
      ++num_elements;
      return iterator(tmp, this);
    }

  node* tmp = new_node(obj);
  tmp->next = first;
  buckets[n] = tmp;
  ++num_elements;
  return iterator(tmp, this);
}

__template__
__reference__ 
__hashtable__::find_or_insert(const __value_type__& obj)
{
  resize(num_elements + 1);

  size_type n = bkt_num(obj);
  node* first = buckets[n];

  for (node* cur = first; cur; cur = cur->next)
    if (equals(get_key(cur->val), get_key(obj)))
      return cur->val;

  node* tmp = new_node(obj);
  tmp->next = first;
  buckets[n] = tmp;
  ++num_elements;
  return tmp->val;
}

__template__
pair<__iterator__,
     __iterator__> 
__hashtable__::equal_range(const __key_type__& key)
{
  typedef pair<iterator, iterator> pii;
  const size_type n = bkt_num_key(key);

  for (node* first = buckets[n]; first; first = first->next) {
    if (equals(get_key(first->val), key)) {
      for (node* cur = first->next; cur; cur = cur->next)
        if (!equals(get_key(cur->val), key))
          return pii(iterator(first, this), iterator(cur, this));
      for (size_type m = n + 1; m < buckets.size(); ++m)
        if (buckets[m])
          return pii(iterator(first, this),
                     iterator(buckets[m], this));
      return pii(iterator(first, this), end());
    }
  }
  return pii(end(), end());
}

__template__
pair<__const_iterator__, 
     __const_iterator__> 
__hashtable__::equal_range(const __key_type__& key) const
{
  typedef pair<const_iterator, const_iterator> pii;
  const size_type n = bkt_num_key(key);

  for (const node* first = buckets[n] ; first; first = first->next) {
    if (equals(get_key(first->val), key)) {
      for (const node* cur = first->next; cur; cur = cur->next)
        if (!equals(get_key(cur->val), key))
          return pii(const_iterator(first, this),
                     const_iterator(cur, this));
      for (size_type m = n + 1; m < buckets.size(); ++m)
        if (buckets[m])
          return pii(const_iterator(first, this),
                     const_iterator(buckets[m], this));
      return pii(const_iterator(first, this), end());
    }
  }
  return pii(end(), end());
}

__template__
__size_type__ 
__hashtable__::erase(const __key_type__& key)
{
  const size_type n = bkt_num_key(key);
  node* first = buckets[n];
  size_type erased = 0;

  if (first) {
    node* cur = first;
    node* next = cur->next;
    while (next) {
      if (equals(get_key(next->val), key)) {
        cur->next = next->next;
        delete_node(next);
        next = cur->next;
        ++erased;
      }
      else {
        cur = next;
        next = cur->next;
      }
    }
    if (equals(get_key(first->val), key)) {
      buckets[n] = first->next;
      delete_node(first);
      ++erased;
    }
  }
  num_elements -= erased;
  return erased;
}

__template__
void 
__hashtable__::erase(const __iterator__& it)
{
  node* const p = it.cur;
  if (p) {
//  if (node* const p = it.cur) {
    const size_type n = bkt_num(p->val);
    node* cur = buckets[n];

    if (cur == p) {
      buckets[n] = cur->next;
      delete_node(cur);
      --num_elements;
    }
    else {
      node* next = cur->next;
      while (next) {
        if (next == p) {
          cur->next = next->next;
          delete_node(next);
          --num_elements;
          break;
        }
        else {
          cur = next;
          next = cur->next;
        }
      }
    }
  }
}

__template__
void 
__hashtable__::erase(__iterator__ first, 
                     __iterator__ last)
{
  size_type f_bucket = first.cur ? bkt_num(first.cur->val) : buckets.size();
  size_type l_bucket = last.cur ? bkt_num(last.cur->val) : buckets.size();

  if (first.cur == last.cur)
    return;
  else if (f_bucket == l_bucket)
    erase_bucket(f_bucket, first.cur, last.cur);
  else {
    erase_bucket(f_bucket, first.cur, 0);
    for (size_type n = f_bucket + 1; n < l_bucket; ++n)
      erase_bucket(n, 0);
    if (l_bucket != buckets.size())
      erase_bucket(l_bucket, last.cur);
  }
}

__template__
inline void
__hashtable__::erase(__const_iterator__ first, 
                     __const_iterator__ last)
{
  erase(hashtable::iterator(const_cast<hashtable::node*>(first.cur),
                            const_cast<hashtable*>(first.ht)),
        hashtable::iterator(const_cast<hashtable::node*>(last.cur),
                            const_cast<hashtable*>(last.ht)));
}

__template__
inline void
__hashtable__::erase(const __const_iterator__& it)
{
  erase(hashtable::iterator(const_cast<hashtable::node*>(it.cur),
                            const_cast<hashtable*>(it.ht)));
}

__template__
void 
__hashtable__::resize(__size_type__ num_elements_hint)
{
  const size_type old_n = buckets.size();
  if (num_elements_hint > old_n) {
    const size_type n = next_size(num_elements_hint);
    if (n > old_n) {
      __vector__<node*, A> tmp(n, (node*) 0);
      for (size_type bucket = 0; bucket < old_n; ++bucket) {
        node* first = buckets[bucket];
        while (first) {
          size_type new_bucket = bkt_num(first->val, n);
          buckets[bucket] = first->next;
          first->next = tmp[new_bucket];
          tmp[new_bucket] = first;
          first = buckets[bucket];          
        }
      }
      buckets = tmp;
    }
  }
}

__template__
void 
__hashtable__::erase_bucket(const __size_type__ n, 
                            __node__* first, __node__* last)
{
  node* cur = buckets[n];
  if (cur == first)
    erase_bucket(n, last);
  else {
    node* next;
    for (next = cur->next; next != first; cur = next, next = cur->next)
      ;
    while (next) {
      cur->next = next->next;
      delete_node(next);
      next = cur->next;
      --num_elements;
    }
  }
}

__template__
void 
__hashtable__::erase_bucket(const __size_type__ n, __node__* last)
{
  node* cur = buckets[n];
  while (cur != last) {
    node* next = cur->next;
    delete_node(cur);
    cur = next;
    buckets[n] = cur;
    --num_elements;
  }
}


__template__ 
void 
__hashtable__::clear()
{
  for (size_type i = 0; i < buckets.size(); ++i) {
    node* cur = buckets[i];
    while (cur != 0) {
      node* next = cur->next;
      delete_node(cur);
      cur = next;
    }
    buckets[i] = 0;
  }
  num_elements = 0;
}
    
__template__ 
void 
__hashtable__::copy_from(const __hashtable__& ht)
{
  buckets.reserve(ht.buckets.size());
  buckets.insert(buckets.end(), ht.buckets.size(), (node*) 0);
  for (size_type i = 0; i < ht.buckets.size(); ++i) {
    const node* cur = ht.buckets[i];
    if (cur) {
      node* copy = new_node(cur->val);
      buckets[i] = copy;

      for (node* next = cur->next; next; cur = next, next = cur->next) {
        copy->next = new_node(next->val);
        copy = copy->next;
      }

      copy->next = 0;
    }
  }
  num_elements = ht.num_elements;
}

__template__
__iterator__&
__iterator__::operator++()
{
  const node* old = cur;
  cur = cur->next;
  if (!cur) {
    size_type bucket = ht->bkt_num(old->val);
    while (!cur && ++bucket < ht->buckets.size())
      cur = ht->buckets[bucket];
  }
  return *this;
}


# undef __template__       
# undef __hashtable__      
# undef __iterator__       
# undef __const_iterator__ 
# undef __difference_type__ 
# undef __size_type__       
# undef __value_type__      
# undef __key_type__        
# undef __node__            

__END_STL_NAMESPACE

#endif /* SGI_STL_HASHTABLE_H */
