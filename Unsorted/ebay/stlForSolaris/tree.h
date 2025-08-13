/*	$Id: tree.h,v 1.3 1998/12/17 04:13:53 josh Exp $	*/
/*
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


#ifndef TREE_H
#define TREE_H

/*

Red-black tree class, designed for use in implementing STL
associative containers (set, multiset, map, and multimap). The
insertion and deletion algorithms are based on those in Cormen,
Leiserson, and Rivest, Introduction to Algorithms (MIT Press, 1990),
except that

(1) the header cell is maintained with links not only to the root
but also to the leftmost node of the tree, to enable constant time
begin(), and to the rightmost node of the tree, to enable linear time
performance when used with the generic set algorithms (set_union,
etc.);

(2) when a node being deleted has two children its successor node is
relinked into its place, rather than copied, so that the only
iterators invalidated are those referring to the deleted node.

*/

#include <stddef.h>
#include <algobase.h>
#include <iterator.h>
#include <alloc.h>

# if defined ( __USE_ABBREVS )
// ugliness is intentional - to reduce conflicts possibility
#  define __rb_tree_node_base       _rbT__NB
#  define __rb_tree_node            _rbT__N
#  define __rb_tree_base_iterator   _rbTB__It
#  define __rb_tree_iterator        _rbT__It
#  define __rb_tree_const_iterator  _rbT__cIt
# endif

__BEGIN_STL_NAMESPACE

typedef bool __rb_tree_color_type;
const __rb_tree_color_type __rb_tree_red = false;
const __rb_tree_color_type __rb_tree_black = true;

struct __rb_tree_node_base
{
  typedef __rb_tree_color_type color_type;
  typedef __rb_tree_node_base* base_ptr;

  color_type color; 
  base_ptr parent;
  base_ptr left;
  base_ptr right;

  static base_ptr minimum(base_ptr x)
  {
    while (x->left != 0) x = x->left;
    return x;
  }

  static base_ptr maximum(base_ptr x)
  {
    while (x->right != 0) x = x->right;
    return x;
  }
};

template <class Value>
struct __rb_tree_node : public __rb_tree_node_base
{
  typedef __rb_tree_node<Value>* link_type;
  Value value_field;
};


struct __rb_tree_base_iterator
{
  typedef __rb_tree_node_base::base_ptr base_ptr;
  typedef ptrdiff_t distance_type;
  base_ptr node;

  void increment()
  {
    if (node->right != 0) {
      node = node->right;
      while (node->left != 0)
        node = node->left;
    }
    else {
      base_ptr y = node->parent;
      while (node == y->right) {
        node = y;
        y = y->parent;
      }
      if (node->right != y)
        node = y;
    }
  }

  void decrement()
  {
    if (node->color == __rb_tree_red &&
        node->parent->parent == node)
      node = node->right;
    else if (node->left != 0) {
      base_ptr y = node->left;
      while (y->right != 0)
        y = y->right;
      node = y;
    }
    else {
      base_ptr y = node->parent;
      while (node == y->left) {
        node = y;
        y = y->parent;
      }
      node = y;
    }
  }
};

template <class Value>
struct __rb_tree_iterator : public __rb_tree_base_iterator
{
  typedef Value& reference;
  typedef const Value& const_reference;
  typedef __rb_tree_node<Value>* link_type;
private:
  typedef __rb_tree_iterator<Value> self;
public:
  __rb_tree_iterator() {}
  __rb_tree_iterator(link_type x) { node = x; }

  reference operator*() const { return link_type(node)->value_field; }

  self& operator++() { increment(); return *this; }
  self operator++(int) {
    self tmp = *this;
    increment();
    return tmp;
  }
    
  self& operator--() { decrement(); return *this; }
  self operator--(int) {
    self tmp = *this;
    decrement();
    return tmp;
  }
};

template <class Value>
struct __rb_tree_const_iterator : public __rb_tree_base_iterator
{
  typedef Value& reference;
  typedef const Value& const_reference;
  typedef __rb_tree_node<Value>* link_type;
private:
  typedef __rb_tree_const_iterator<Value> self;
public:
  __rb_tree_const_iterator() {}
  __rb_tree_const_iterator(link_type x) { node = x; }
  __rb_tree_const_iterator(const __rb_tree_iterator<Value>& it) {
    node = it.node;
  }  

  const_reference operator*() const { return link_type(node)->value_field; }

  self& operator++() { increment(); return *this; }
  self operator++(int) {
    self tmp = *this;
    increment();
    return tmp;
  }
    
  self& operator--() { decrement(); return *this; }
  self operator--(int) {
    self tmp = *this;
    decrement();
    return tmp;
  }
};


inline bool operator==(const __rb_tree_base_iterator& x,
                       const __rb_tree_base_iterator& y) {
  return x.node == y.node;
}

inline bool operator!=(const __rb_tree_base_iterator& x,
                       const __rb_tree_base_iterator& y) {
  return x.node != y.node;
}

inline bidirectional_iterator_tag 
iterator_category(const __rb_tree_base_iterator&) {
  return bidirectional_iterator_tag();
}

inline __rb_tree_base_iterator::distance_type*
distance_type(const __rb_tree_base_iterator&) {
  return (__rb_tree_base_iterator::distance_type*) 0;
}

template <class Value>
inline Value* value_type(const __rb_tree_iterator<Value>&) {
  return (Value*) 0;
}

template <class Value>
inline Value* value_type(const __rb_tree_const_iterator<Value>&) {
  return (Value*) 0;
}

inline void 
__rb_tree_rotate_left(__rb_tree_node_base* x, __rb_tree_node_base*& root)
{
  __rb_tree_node_base* y = x->right;
  x->right = y->left;
  if (y->left != 0)
    y->left->parent = x;
  y->parent = x->parent;

  if (x == root)
    root = y;
  else if (x == x->parent->left)
    x->parent->left = y;
  else
    x->parent->right = y;
  y->left = x;
  x->parent = y;
}

inline void 
__rb_tree_rotate_right(__rb_tree_node_base* x, __rb_tree_node_base*& root)
{
  __rb_tree_node_base* y = x->left;
  x->left = y->right;
  if (y->right != 0)
    y->right->parent = x;
  y->parent = x->parent;

  if (x == root)
    root = y;
  else if (x == x->parent->right)
    x->parent->right = y;
  else
    x->parent->left = y;
  y->right = x;
  x->parent = y;
}

inline void 
__rb_tree_rebalance(__rb_tree_node_base* x, __rb_tree_node_base*& root)
{
  x->color = __rb_tree_red;
  while (x != root && x->parent->color == __rb_tree_red) {
    if (x->parent == x->parent->parent->left) {
      __rb_tree_node_base* y = x->parent->parent->right;
      if (y && y->color == __rb_tree_red) {
        x->parent->color = __rb_tree_black;
        y->color = __rb_tree_black;
        x->parent->parent->color = __rb_tree_red;
        x = x->parent->parent;
      }
      else {
        if (x == x->parent->right) {
          x = x->parent;
          __rb_tree_rotate_left(x, root);
        }
        x->parent->color = __rb_tree_black;
        x->parent->parent->color = __rb_tree_red;
        __rb_tree_rotate_right(x->parent->parent, root);
      }
    }
    else {
      __rb_tree_node_base* y = x->parent->parent->left;
      if (y && y->color == __rb_tree_red) {
        x->parent->color = __rb_tree_black;
        y->color = __rb_tree_black;
        x->parent->parent->color = __rb_tree_red;
        x = x->parent->parent;
      }
      else {
        if (x == x->parent->left) {
          x = x->parent;
          __rb_tree_rotate_right(x, root);
        }
        x->parent->color = __rb_tree_black;
        x->parent->parent->color = __rb_tree_red;
        __rb_tree_rotate_left(x->parent->parent, root);
      }
    }
  }
  root->color = __rb_tree_black;
}

inline __rb_tree_node_base*
__rb_tree_rebalance_for_erase(__rb_tree_node_base* z,
                              __rb_tree_node_base*& root,
                              __rb_tree_node_base*& leftmost,
                              __rb_tree_node_base*& rightmost)
{
  __rb_tree_node_base* y = z;
  __rb_tree_node_base* x = 0;
  __rb_tree_node_base* x_parent = 0;
  if (y->left == 0)             // z has at most one non-null child. y == z.
    x = y->right;               // x might be null.
  else
    if (y->right == 0)          // z has exactly one non-null child.  y == z.
      x = y->left;              // x is not null.
    else {                      // z has two non-null children.  Set y to
      y = y->right;             //   z's successor.  x might be null.
      while (y->left != 0)
        y = y->left;
      x = y->right;
    }
  if (y != z) {                 // relink y in place of z.  y is z's successor
    z->left->parent = y; 
    y->left = z->left;
    if (y != z->right) {
      x_parent = y->parent;
      if (x) x->parent = y->parent;
      y->parent->left = x;      // y must be a left child
      y->right = z->right;
      z->right->parent = y;
    }
    else
      x_parent = y;  
    if (root == z)
      root = y;
    else if (z->parent->left == z)
      z->parent->left = y;
    else 
      z->parent->right = y;
    y->parent = z->parent;
    STL_NAMESPACE::swap(y->color, z->color);
    y = z;
    // y now points to node to be actually deleted
  }
  else {                        // y == z
    x_parent = y->parent;
    if (x) x->parent = y->parent;   
    if (root == z)
      root = x;
    else 
      if (z->parent->left == z)
        z->parent->left = x;
      else
        z->parent->right = x;
    if (leftmost == z) 
      if (z->right == 0)        // z->left must be null also
        leftmost = z->parent;
    // makes leftmost == header if z == root
      else
        leftmost = __rb_tree_node_base::minimum(x);
    if (rightmost == z)  
      if (z->left == 0)         // z->right must be null also
        rightmost = z->parent;  
    // makes rightmost == header if z == root
      else                      // x == z->left
        rightmost = __rb_tree_node_base::maximum(x);
  }
  if (y->color != __rb_tree_red) { 
    while (x != root && (x == 0 || x->color == __rb_tree_black))
      if (x == x_parent->left) {
        __rb_tree_node_base* w = x_parent->right;
        if (w->color == __rb_tree_red) {
          w->color = __rb_tree_black;
          x_parent->color = __rb_tree_red;
          __rb_tree_rotate_left(x_parent, root);
          w = x_parent->right;
        }
        if ((w->left == 0 || w->left->color == __rb_tree_black) &&
            (w->right == 0 || w->right->color == __rb_tree_black)) {
          w->color = __rb_tree_red;
          x = x_parent;
          x_parent = x_parent->parent;
        } else {
          if (w->right == 0 || w->right->color == __rb_tree_black) {
            if (w->left) w->left->color = __rb_tree_black;
            w->color = __rb_tree_red;
            __rb_tree_rotate_right(w, root);
            w = x_parent->right;
          }
          w->color = x_parent->color;
          x_parent->color = __rb_tree_black;
          if (w->right) w->right->color = __rb_tree_black;
          __rb_tree_rotate_left(x_parent, root);
          break;
        }
      } else {                  // same as above, with right <-> left.
        __rb_tree_node_base* w = x_parent->left;
        if (w->color == __rb_tree_red) {
          w->color = __rb_tree_black;
          x_parent->color = __rb_tree_red;
          __rb_tree_rotate_right(x_parent, root);
          w = x_parent->left;
        }
        if ((w->right == 0 || w->right->color == __rb_tree_black) &&
            (w->left == 0 || w->left->color == __rb_tree_black)) {
          w->color = __rb_tree_red;
          x = x_parent;
          x_parent = x_parent->parent;
        } else {
          if (w->left == 0 || w->left->color == __rb_tree_black) {
            if (w->right) w->right->color = __rb_tree_black;
            w->color = __rb_tree_red;
            __rb_tree_rotate_left(w, root);
            w = x_parent->left;
          }
          w->color = x_parent->color;
          x_parent->color = __rb_tree_black;
          if (w->left) w->left->color = __rb_tree_black;
          __rb_tree_rotate_right(x_parent, root);
          break;
        }
      }
    if (x) x->color = __rb_tree_black;
  }
  return y;
}

template <class Key, class Value, class KeyOfValue, class Compare,
          __DFL_TYPE_PARAM(Alloc,alloc) >
class rb_tree {
    typedef rb_tree<Key,Value,KeyOfValue,Compare,Alloc> self;
protected:
    typedef void* void_pointer;
    typedef __rb_tree_node_base* base_ptr;
    typedef __rb_tree_node<Value> rb_tree_node;
    typedef simple_alloc<rb_tree_node, Alloc> rb_tree_node_allocator;
    typedef __rb_tree_color_type color_type;
public:
    typedef Key key_type;
    typedef Value value_type;
    typedef value_type* pointer;
    typedef value_type& reference;
    typedef const value_type& const_reference;
    typedef rb_tree_node* link_type;
    typedef size_t size_type;
    typedef ptrdiff_t difference_type;
    typedef __rb_tree_iterator<value_type> iterator;
    typedef __rb_tree_const_iterator<value_type> const_iterator;

    typedef reverse_bidirectional_iterator<iterator, value_type, reference,
                                           difference_type>
        reverse_iterator; 
    typedef reverse_bidirectional_iterator<const_iterator, value_type,
                                           const_reference, difference_type>
	const_reverse_iterator;
protected:
    link_type get_node() { return rb_tree_node_allocator::allocate(); }
    void put_node(link_type p) { rb_tree_node_allocator::deallocate(p); }
protected:
    link_type header;  
    Compare key_compare;

    link_type& root() const { return (link_type&) header->parent; }
    link_type& leftmost() const { return (link_type&) header->left; }
    link_type& rightmost() const { return (link_type&) header->right; }
    size_type node_count; // keeps track of size of tree

    static link_type& left(link_type x) { return (link_type&)(x->left); }
    static link_type& right(link_type x) { return (link_type&)(x->right); }
    static link_type& parent(link_type x) { return (link_type&)(x->parent); }
    static reference value(link_type x) { return x->value_field; }
    static const Key& key(link_type x) { return KeyOfValue()(value(x)); }
    static color_type& color(link_type x) { return (color_type&)(x->color); }

    static link_type& left(base_ptr x) { return (link_type&)(x->left); }
    static link_type& right(base_ptr x) { return (link_type&)(x->right); }
    static link_type& parent(base_ptr x) { return (link_type&)(x->parent); }
    static reference value(base_ptr x) { return ((link_type)x)->value_field; }
    static const Key& key(base_ptr x) { return KeyOfValue()(value(link_type(x)));} 
    static color_type& color(base_ptr x) { return (color_type&)(link_type(x)->color); }

    static link_type minimum(link_type x) { 
        return (link_type)  __rb_tree_node_base::minimum(x);
    }
    static link_type maximum(link_type x) {
        return (link_type) __rb_tree_node_base::maximum(x);
    }

private:
    iterator __insert(base_ptr x, base_ptr y, const value_type& v);
    link_type __copy(link_type x, link_type p);
    void __erase(link_type x);
    void init() {
        header = get_node();
        color(header) = __rb_tree_red; // used to distinguish header from 
                                       // root, in iterator.operator++
        root() = 0;
        leftmost() = header;
        rightmost() = header;
    }
public:
                                // allocation/deallocation
    rb_tree(const Compare& comp = Compare())
      : key_compare(comp), node_count(0)  { init(); }

    rb_tree(const self& x) 
      : key_compare(x.key_compare), node_count(0)  { 
        header = get_node();
        color(header) = __rb_tree_red;
        root() = __copy(x.root(), header);
        if (root() == 0) {
            leftmost() = header;
            rightmost() = header;
        } else {
	    leftmost() = minimum(root());
            rightmost() = maximum(root());
        }
        node_count = x.node_count;
    }
    ~rb_tree() {
        clear();
        put_node(header);
    }
    self& operator=(const self& x);
    
public:    
                                // accessors:
    Compare key_comp() const { return key_compare; }
    iterator begin() { return leftmost(); }
    const_iterator begin() const { return leftmost(); }
    iterator end() { return header; }
    const_iterator end() const { return header; }
    reverse_iterator rbegin() { return reverse_iterator(end()); }
    const_reverse_iterator rbegin() const { 
        return const_reverse_iterator(end()); 
    }
    reverse_iterator rend() { return reverse_iterator(begin()); }
    const_reverse_iterator rend() const { 
        return const_reverse_iterator(begin());
    } 
    bool empty() const { return node_count == 0; }
    size_type size() const { return node_count; }
    size_type max_size() const { return size_type(-1); }

    void swap(self& t) {
        STL_NAMESPACE::swap(header, t.header);
        STL_NAMESPACE::swap(node_count, t.node_count);
        STL_NAMESPACE::swap(key_compare, t.key_compare);
    }
    
public:
                                // insert/erase
    pair<iterator,bool> insert_unique(const value_type& x);
    iterator insert_equal(const value_type& x);

    iterator insert_unique(iterator position, const value_type& x);
    iterator insert_equal(iterator position, const value_type& x);

    void insert_unique(const_iterator first, const_iterator last);
    void insert_unique(const value_type* first, const value_type* last);
    void insert_equal(const_iterator first, const_iterator last);
    void insert_equal(const value_type* first, const value_type* last);


    void erase(iterator position);
    size_type erase(const key_type& x);
    void erase(iterator first, iterator last);
    void erase(const key_type* first, const key_type* last);
    void clear() {
      if (node_count != 0) {
        __erase(root());
        leftmost() = header;
        root() = 0;
        rightmost() = header;
        node_count = 0;
      }
    }      

public:
                                // set operations:
    iterator find(const key_type& x);
    const_iterator find(const key_type& x) const;
    size_type count(const key_type& x) const;
    iterator lower_bound(const key_type& x);
    const_iterator lower_bound(const key_type& x) const;
    iterator upper_bound(const key_type& x);
    const_iterator upper_bound(const key_type& x) const;
    pair<iterator,iterator> equal_range(const key_type& x);
    pair<const_iterator, const_iterator> equal_range(const key_type& x) const;
public:
                                // Debugging.
  bool __rb_verify() const;
};

# define __template__ template <class Key, class Value, class KeyOfValue, \
                      class Compare, class Alloc>
# define __rb_tree__ rb_tree<Key, Value, KeyOfValue, Compare, Alloc>

// fbp: these defines are for outline methods definitions.
// needed to definitions to be portable. Should not be used in method bodies.
# if defined  ( HAVE_NESTED_TYPE_PARAM_BUG )
#  define __iterator__        __rb_tree_iterator<Value>
#  define __const_iterator__  __rb_tree_const_iterator<Value>
#  define __size_type__       size_t
#  define __link_type__       __rb_tree_node<Value>*
#  define __base_ptr__        __rb_tree_node_base*
#  define __value_type__      Value
#  define __key_type__        Key
# else
#  define __iterator__        __rb_tree__::iterator
#  define __const_iterator__  __rb_tree__::const_iterator
#  define __link_type__       __rb_tree__::link_type
#  define __size_type__       __rb_tree__::size_type
#  define __base_ptr__        __rb_tree__::base_ptr
#  define __value_type__      __rb_tree__::value_type
#  define __key_type__        __rb_tree__::key_type
# endif

__template__
inline bool operator==(const __rb_tree__& x, 
                       const __rb_tree__& y) {
    return x.size() == y.size() && equal(x.begin(), x.end(), y.begin());
}

__template__
inline bool operator<(const __rb_tree__& x, 
                      const __rb_tree__& y) {
    return lexicographical_compare(x.begin(), x.end(), y.begin(), y.end());
}

__template__
__rb_tree__& 
__rb_tree__::operator=(const __rb_tree__& x) {
    if (this != &x) {
        // can't be done as in list because Key may be a constant type
        clear();
        root() = __copy(x.root(), header);
        if (root() == 0) {
            leftmost() = header;
            rightmost() = header;
        } else {
	    leftmost() = minimum(root());
            rightmost() = maximum(root());
        }
        node_count = x.node_count;
    }
    return *this;
}

__template__
__iterator__
__rb_tree__::__insert(__base_ptr__ x_, __base_ptr__ y_, const __value_type__& v) {
    link_type x = (link_type) x_;
    link_type y = (link_type) y_;
//    ++node_count;
    link_type z = get_node();
    construct(&(value(z)), v);
    if (y == header || x != 0 || key_compare(KeyOfValue()(v), key(y))) {
        left(y) = z;  // also makes leftmost() = z when y == header
        if (y == header) {
            root() = z;
            rightmost() = z;
        } else if (y == leftmost())
            leftmost() = z;   // maintain leftmost() pointing to minimum node
    } else {
        right(y) = z;
        if (y == rightmost())
            rightmost() = z;   // maintain rightmost() pointing to maximum node
    }
    parent(z) = y;
    left(z) = 0;
    right(z) = 0;
    __rb_tree_rebalance(z, header->parent);
    ++node_count;
    return iterator(z);
}

__template__
__iterator__
__rb_tree__::insert_equal(const __value_type__& v)
{
    link_type y = header;
    link_type x = root();
    while (x != 0) {
        y = x;
        x = key_compare(KeyOfValue()(v), key(x)) ? left(x) : right(x);
    }
    return __insert(x, y, v);
}


__template__
pair<__iterator__, bool>
__rb_tree__::insert_unique(const __value_type__& v)
{
    link_type y = header;
    link_type x = root();
    bool comp = true;
    while (x != 0) {
        y = x;
        comp = key_compare(KeyOfValue()(v), key(x));
        x = comp ? left(x) : right(x);
    }
    iterator j = iterator(y);   
    if (comp)
        if (j == begin())     
            return pair<iterator,bool>(__insert(x, y, v), true);
        else
            --j;
    if (key_compare(key(j.node), KeyOfValue()(v)))
        return pair<iterator,bool>(__insert(x, y, v), true);
    return pair<iterator,bool>(j, false);
}


__template__
__iterator__ 
__rb_tree__::insert_unique(__iterator__ position,
                           const __value_type__& v) 
{
    if (position.node == header->left) // begin()
        if (size() > 0 && key_compare(KeyOfValue()(v), key(position.node)))
            return __insert(position.node, position.node, v);
            // first argument just needs to be non-null 
        else
            return insert_unique(v).first;
    else if (position.node == header) // end()
        if (key_compare(key(rightmost()), KeyOfValue()(v)))
            return __insert(0, rightmost(), v);
        else
            return insert_unique(v).first;
    else {
        iterator before = position;
        --before;
        if (key_compare(key(before.node), KeyOfValue()(v))
            && key_compare(KeyOfValue()(v), key(position.node)))
            if (right(before.node) == 0)
                return __insert(0, before.node, v); 
            else
                return __insert(position.node, position.node, v);
                // first argument just needs to be non-null 
        else
            return insert_unique(v).first;
    }
}

__template__
__iterator__ 
__rb_tree__::insert_equal(__iterator__ position,
                          const __value_type__& v) {
    if (position.node == header->left) // begin()
        if (size() > 0 && key_compare(KeyOfValue()(v), key(position.node)))
            return __insert(position.node, position.node, v);
            // first argument just needs to be non-null 
        else
            return insert_equal(v);
    else if (position.node == header) // end()
        if (!key_compare(KeyOfValue()(v), key(rightmost())))
            return __insert(0, rightmost(), v);
        else
            return insert_equal(v);
    else {
        iterator before = position;
        --before;
        if (!key_compare(KeyOfValue()(v), key(before.node))
            && !key_compare(key(position.node), KeyOfValue()(v)))
            if (right(before.node) == 0)
                return __insert(0, before.node, v); 
            else
                return __insert(position.node, position.node, v);
                // first argument just needs to be non-null 
        else
            return insert_equal(v);
    }
}

__template__
void
__rb_tree__::insert_equal(const __value_type__* first, const __value_type__* last) {
    while (first != last) insert_equal(*first++);
}

__template__
void
__rb_tree__::insert_equal(__const_iterator__ first,
                        __const_iterator__ last) {
    while (first != last) insert_equal(*first++);
}

__template__
void 
__rb_tree__::insert_unique(const __value_type__* first, const __value_type__* last) {
    while (first != last) insert_unique(*first++);
}

__template__
void 
__rb_tree__::insert_unique(__const_iterator__ first,
                           __const_iterator__ last) {
    while (first != last) insert_unique(*first++);
}
         
__template__
inline void
__rb_tree__::erase(__iterator__ position) {
  link_type y = (link_type) __rb_tree_rebalance_for_erase(position.node,
                                                          header->parent,
                                                          header->left,
                                                          header->right);
  destroy(&(value(y)));
  put_node(y);
  --node_count;
}

__template__
__size_type__
__rb_tree__::erase(const __key_type__& x) {
    pair<iterator,iterator> p = equal_range(x);
    size_type n = 0;
    distance(p.first, p.second, n);
    erase(p.first, p.second);
    return n;
}

__template__
__link_type__
__rb_tree__::__copy(__link_type__ x, __link_type__ p) {
   // structural copy
   link_type r = x;
   while (x != 0) {
      link_type y = get_node();
      if (r == x) r = y;  // save for return value
      construct(&(value(y)), value(x));
      left(p) = y;
      parent(y) = p;
      color(y) = color(x);
      right(y) = __copy(right(x), y);
      p = y;
      x = left(x);
   }
   left(p) = 0;
   return r;
}

__template__
void __rb_tree__::__erase(__link_type__ x) {
    // erase without rebalancing
    while (x != 0) {
       __erase(right(x));
       link_type y = left(x);
       destroy(&(value(x)));
       put_node(x);
       x = y;
    }
}

__template__
void __rb_tree__::erase(__iterator__ first, 
                        __iterator__ last) {
    if (first == begin() && last == end())
        clear();
    else
         while (first != last) erase(first++);
}

__template__
void __rb_tree__::erase(const Key* first, 
                      const Key* last) {
    while (first != last) erase(*first++);
}

__template__
__iterator__
__rb_tree__::find(const __key_type__& k) {
   link_type y = header; /* Last node which is not less than k. */
   link_type x = root(); /* Current node. */

   while (x != 0) 
     if (!key_compare(key(x), k))
       y = x, x = left(x);
   else
       x = right(x);

   iterator j = iterator(y);   
   return (j == end() || key_compare(k, key(j.node))) ? end() : j;
}

__template__
__const_iterator__
__rb_tree__::find(const __key_type__& k) const {
   link_type y = header; /* Last node which is not less than k. */
   link_type x = root(); /* Current node. */

   while (x != 0) {
     if (!key_compare(key(x), k))
       y = x, x = left(x);
   else
       x = right(x);
   }
   const_iterator j = const_iterator(y);   
   return (j == end() || key_compare(k, key(j.node))) ? end() : j;
}

__template__
__size_type__
__rb_tree__::count(const __key_type__& k) const {
    pair<const_iterator, const_iterator> p = equal_range(k);
    size_type n = 0;
    distance(p.first, p.second, n);
    return n;
}

__template__
__iterator__
__rb_tree__::lower_bound(const __key_type__& k) {
   link_type y = header; /* Last node which is not less than k. */
   link_type x = root(); /* Current node. */

   while (x != 0) 
     if (!key_compare(key(x), k))
       y = x, x = left(x);
     else
       x = right(x);

   return iterator(y);
}

__template__
__const_iterator__
__rb_tree__::lower_bound(const __key_type__& k) const {
   link_type y = header; /* Last node which is not less than k. */
   link_type x = root(); /* Current node. */

   while (x != 0) 
     if (!key_compare(key(x), k))
       y = x, x = left(x);
     else
       x = right(x);

   return const_iterator(y);
}

__template__
__iterator__
__rb_tree__::upper_bound(const __key_type__& k) {
  link_type y = header; /* Last node which is greater than k. */
  link_type x = root(); /* Current node. */

   while (x != 0) 
     if (key_compare(k, key(x)))
       y = x, x = left(x);
     else
       x = right(x);

   return iterator(y);
}

__template__
__const_iterator__
__rb_tree__::upper_bound(const __key_type__& k) const {
  link_type y = header; /* Last node which is greater than k. */
  link_type x = root(); /* Current node. */

   while (x != 0) 
     if (key_compare(k, key(x)))
       y = x, x = left(x);
     else
       x = right(x);

   return const_iterator(y);
}

__template__
inline pair<__iterator__,__iterator__>
__rb_tree__::equal_range(const __key_type__& k) {
    return pair<iterator, iterator>(lower_bound(k), upper_bound(k));
}

__template__
inline pair<__const_iterator__,__const_iterator__>
__rb_tree__::equal_range(const __key_type__& k) const {
    return pair<const_iterator,const_iterator>(lower_bound(k), upper_bound(k));
}

inline int __black_count(__rb_tree_node_base* node, __rb_tree_node_base* root)
{
  if (node == 0)
    return 0;
  else {
    int bc = node->color == __rb_tree_black ? 1 : 0;
    if (node == root)
      return bc;
    else
      return bc + __black_count(node->parent, root);
  }
}

__template__
bool 
rb_tree<Key, Value, KeyOfValue, Compare, Alloc>::__rb_verify() const
{
  int len = __black_count(leftmost(), root());
  for (const_iterator it = begin(); it != end(); ++it) {
    link_type x = (link_type) it.node;
    link_type L = left(x);
    link_type R = right(x);

    if (x->color == __rb_tree_red)
      if ((L && L->color == __rb_tree_red) ||
          (R && R->color == __rb_tree_red))
        return false;

    if (L && key_compare(key(x), key(L)))
      return false;
    if (R && key_compare(key(R), key(x)))
      return false;

    if (!L && !R && __black_count(x, root()) != len)
      return false;
  }

  if (leftmost() != __rb_tree_node_base::minimum(root()))
    return false;
  if (rightmost() != __rb_tree_node_base::maximum(root()))
    return false;

  return true;
}

# undef __rb_tree__ 
# undef __template__
# undef __iterator__        
# undef __const_iterator__  
# undef __size_type__  
# undef __link_type__  
# undef __base_ptr__        
# undef __value_type__
# undef __key_type__  

__END_STL_NAMESPACE

#endif

