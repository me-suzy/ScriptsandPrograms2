/*	$Id: stlconf.h,v 1.2 1998/12/17 04:13:51 josh Exp $	*/
/* stlconf.h.  Generated automatically by configure.  */
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

#ifndef __STLCONF_H
# define __STLCONF_H

# define __AUTO_CONFIGURED 1

//==========================================================
// Getting proper values of autoconf-style HAVE_ flags
// if you ran 'configure', __AUTO_CONFIGURED is set to 1 and
// specific compiler fetures will be used.
// Otherwise, the <stlcomp.h> header will be included for per-version
// features recognition.
//==========================================================
# if defined ( BYPASS_AUTOCONF_SETTINGS ) || ! defined (__AUTO_CONFIGURED)
// per-version compiler features recognition
#  include <stlcomp.h>
# else
// auto-configured section

// define that to disable these features
/* # undef __NO_EXCEPTIONS */
/* # undef __NO_NAMESPACES */

// select allocation method you like
# define __USE_MALLOC 1
// this one is not mandatory, just enabled
/* # undef __USE_DEFALLOC */

// define NO_USING_STD if don't want using STL namespace by default
// new-style-headers define that to get proper behaviour
/* # undef __NO_USING_STD */

// define __USE_ABBREVS if your linker has trouble with long 
// external symbols
/* # undef __USE_ABBREVS */

// unsigned 32-bit integer type
#  define __STL_UINT32_T unsigned int
#  define HAVE_BOOL_KEYWORD 1
/* #  undef HAVE_RESERVED_BOOL_KEYWORD */
/* #  undef HAVE_DEFAULT_TEMPLATE_PARAM */
#  define HAVE_DEFAULT_TYPE_PARAM 1
/* #  undef HAVE_STATIC_TEMPLATE_DATA */
#  define HAVE_RAND48 1
/* #  undef HAVE_LOOP_INLINE_PROBLEMS */
/* #  undef HAVE_NAMESPACES */
/* #  undef HAVE_TYPENAME */
#  define HAVE_EXPLICIT 1
/* #  undef HAVE_EXCEPTIONS */
/* #  undef HAVE_EXCEPTION_SPEC */
#  define HAVE_WEAK_ATTRIBUTE 0
#  define HAVE_BASE_MATCH_BUG 1
#  define HAVE_NESTED_TYPE_PARAM_BUG 1
/* #  undef HAVE_UNUSED_REQUIRED_BUG */
/* #  undef HAVE_UNINITIALIZABLE_PRIVATE */
/* #  undef HAVE_BASE_TYPEDEF_BUG */
#  define HAVE_BASE_TYPEDEF_OUTSIDE_BUG 1
/* #  undef HAVE_CONST_CONSTRUCTOR_BUG */
# endif /* AUTO_CONFIGURED */

//==========================================================

//==========================================================
// final workaround tuning based on given flags
//==========================================================

// some justification

# if !defined ( HAVE_STATIC_TEMPLATE_DATA )
#   define HAVE_STATIC_TEMPLATE_DATA 0
#  if !defined ( HAVE_WEAK_ATTRIBUTE )
#   define HAVE_WEAK_ATTRIBUTE 1
#  endif
# endif

# if defined (HAVE_BASE_TYPEDEF_BUG)
#  define  HAVE_BASE_TYPEDEF_OUTSIDE_BUG 1
#  define HAVE_BASE_TYPEDEF_OUTSIDE_BUG 1
# endif

# if ! defined ( HAVE_NAMESPACES )
#  define __NO_NAMESPACES 1
# endif 

# if ! defined ( HAVE_EXCEPTIONS )
#  define __NO_EXCEPTIONS 1
# endif 

# ifdef HAVE_RAND48
#  define __rand lrand48
# else
#  define __rand rand
# endif

// tuning of static template data members workaround
# if ( HAVE_STATIC_TEMPLATE_DATA < 1 )
// ignore __PUT directive in this case
#  if ( HAVE_WEAK_ATTRIBUTE > 0 )
#   define __DECLARE_INSTANCE(type,item,init) type item __attribute__ (( weak )) = init
#  else
#   ifdef __PUT_STATIC_DATA_MEMBERS_HERE
#    define __DECLARE_INSTANCE(type,item,init) type item = init
#   else
#    define __DECLARE_INSTANCE(type,item,init)
#   endif /* __PUT_STATIC_DATA_MEMBERS_HERE */
#  endif /* HAVE_WEAK_ATTRIBUTE */
# endif /* HAVE_STATIC_TEMPLATE_DATA */

// default parameters as template types derived from arguments ( not always supported )
#  if ! defined (HAVE_DEFAULT_TEMPLATE_PARAM)
#   define __DFL_TMPL_PARAM( classname, defval ) class classname
#   define __DFL_TMPL_ARG(classname) , classname
#  else
#   define HAVE_DEFAULT_TYPE_PARAM 1
#   define __DFL_TMPL_PARAM( classname, defval ) class classname = defval
#   define __DFL_TMPL_ARG(classname)  
#  endif

// default parameters as complete types
# if defined ( HAVE_DEFAULT_TYPE_PARAM )
#   define __DFL_TYPE_PARAM( classname, defval ) class classname = defval
#   define __DFL_TYPE_ARG(classname)
# else
#  define __DFL_TYPE_PARAM( classname, defval ) class classname
#  define __DFL_TYPE_ARG(classname) , classname
# endif

// namespace selection
# if defined (HAVE_NAMESPACES) && ! defined (__NO_NAMESPACES)

// change this if don't think that is standard enough ;)
#  define STL_NAMESPACE std
#  define __BEGIN_STL_NAMESPACE namespace STL_NAMESPACE {

#  ifdef __NO_USING_STD
#   define __USING_NAMESPACE
#  else
#   define __USING_NAMESPACE using namespace STL_NAMESPACE ;
#  endif
#  ifdef HAVE_DEFAULT_TYPE_PARAM
#    define STL_FULL_NAMESPACE STL_NAMESPACE
#    define __BEGIN_STL_FULL_NAMESPACE
#    define __END_STL_FULL_NAMESPACE
#  else
#    define STL_FULL_NAMESPACE sgi_full
#    define __BEGIN_STL_FULL_NAMESPACE namespace STL_FULL_NAMESPACE {
#    define __END_STL_FULL_NAMESPACE } ;
#  endif
#  define __END_STL_NAMESPACE }; __USING_NAMESPACE
# else /* HAVE_NAMESPACES */
#  define STL_NAMESPACE
#  define STL_FULL_NAMESPACE
#  define __BEGIN_STL_NAMESPACE
#  define __END_STL_NAMESPACE
#  define __BEGIN_STL_FULL_NAMESPACE
#  define __END_STL_FULL_NAMESPACE
# endif  /* HAVE_NAMESPACES */

// default parameters workaround tuning
#  if defined  ( HAVE_DEFAULT_TYPE_PARAM ) || defined ( HAVE_NAMESPACES )
#    define __WORKAROUND_RENAME(X) X
#  else
#    define __WORKAROUND_RENAME(X) __##X
#  endif

// workaround tuning
#  define __FULL_NAME(X) STL_FULL_NAMESPACE::__WORKAROUND_RENAME(X)

// advanced keywords usage
#  ifndef HAVE_TYPENAME
#   define typename
#  endif

#  ifndef HAVE_EXPLICIT
#   define explicit
#  endif

// throw specification ( used in inline constructors 
// to improve efficiency some compilers )
// param count is variable, parens used.
#  if defined ( __NO_EXCEPTIONS ) || ! defined ( HAVE_EXCEPTION_SPEC )  
#   define THROWS(x)
#  else
#   define THROWS(x) throw x
#  endif

#  if defined (HAVE_LOOP_INLINE_PROBLEMS)
#   define INLINE_LOOP
#  else
#   define INLINE_LOOP inline 
#  endif

#if defined ( HAVE_UNINITIALIZABLE_PRIVATE )
#  define __PRIVATE public
   // Extra access restrictions prevent us from really making some things
   // private.
#else
#  define __PRIVATE private
#endif

#  define __IMPORT_CONTAINER_TYPEDEFS(super)                            \
    typedef typename super::value_type value_type;                               \
    typedef typename super::iterator iterator;                                   \
    typedef typename super::const_iterator const_iterator;                       \
    typedef typename super::reference reference;                                 \
    typedef typename super::size_type size_type;                                 \
    typedef typename super::const_reference const_reference;                     \
    typedef typename super::difference_type difference_type;

#  define __IMPORT_REVERSE_ITERATORS(super)                                      \
    typedef typename super::const_reverse_iterator  const_reverse_iterator;      \
    typedef typename super::reverse_iterator reverse_iterator;

#define  __IMPORT_SUPER_COPY_ASSIGNMENT(__derived_name)         \
    __derived_name(const self& x) : super(x) {}                 \
    __derived_name(const super& x) : super(x) {}                \
    self& operator=(const self& x) {                            \
        super::operator=(x);                                    \
        return *this;                                           \
    }                                                           \
    self& operator=(const super& x) {                           \
        super::operator=(x);                                    \
        return *this;                                           \
    }

# if defined (HAVE_BASE_TYPEDEF_OUTSIDE_BUG) || defined (HAVE_NESTED_TYPE_PARAM_BUG)
#   define __CONTAINER_SUPER_TYPEDEFS __IMPORT_CONTAINER_TYPEDEFS(super) __IMPORT_REVERSE_ITERATORS(super)
# else
#   define __CONTAINER_SUPER_TYPEDEFS
# endif


//==========================================================

#endif /* __STLCONF_H */
