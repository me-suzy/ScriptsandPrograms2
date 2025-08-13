/*	$Id: stlcomp.h,v 1.3 1998/12/17 04:13:50 josh Exp $	*/
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

#ifndef __STLCOMP_H
# define __STLCOMP_H

//==========================================================
// Features selection

// Uncomment that to disable exception handling
// #  define __NO_EXCEPTIONS 1

// Uncomment that to disable std namespace usage
// #  define __NO_NAMESPACES 1

// Comment that not to include defalloc.h as well
#  define __USE_DEFALLOC   1

// Uncomment this to use malloc-based allocator as default
// #  define __USE_MALLOC 1

// Uncomment this to disable using std by default
// #  define __NO_USING_STD 1

// define __USE_ABBREVS if your linker has trouble with long 
// external symbols
// # define  __USE_ABBREVS 1

// Mostly correct guess
#  define __STL_UINT32_T unsigned long

//==========================================================

//==========================================================
// default values of autoconf-style HAVE_ flags
//==========================================================

// the values choosen here as defaults try to give
// maximum functionality on the most conservative settings

// Uncomment this if your compiler supports "bool"
// #  define  HAVE_BOOL_KEYWORD 1

// Uncomment this if your compiler has "bool" keyword reserved
// #  define  HAVE_RESERVED_BOOL_KEYWORD

// Comment this if your compiler doesn't support that
#  define HAVE_DEFAULT_TEMPLATE_PARAM 1
// Uncomment this if your compiler support only complete types as
// default parameters
// #  define HAVE_DEFAULT_TYPE_PARAM 1

// Comment this if your compiler lacks static data 
// members template declarations 
// Uncomment next line if your compiler supports __attribute__((weak))
#  define HAVE_STATIC_TEMPLATE_DATA 1
// #  define HAVE_WEAK_ATTRIBUTE 1

// Uncomment this if your C library has lrand48() function
// #  define HAVE_RAND48 1
// Uncomment this if your compiler can't inline while(), for()
// #  define HAVE_LOOP_INLINE_PROBLEMS 1

// Uncomment this if your compiler supports namespaces 
// #  define HAVE_NAMESPACES

// Uncomment this if your compiler supports typename
// #  define HAVE_TYPENAME

// Uncomment this if your compiler supports explicit constructors
// #  define HAVE_EXPLICIT

// Uncomment this if your compiler supports exceptions
// #  define HAVE_EXCEPTIONS

// Uncomment this if your compiler supports exception specifications
// with reduced overhead ( e.g. inlines them, not vice versa)
// #  define HAVE_EXCEPTION_SPEC

// All these settings don't affect performance/functionality
// Comment them if your compiler has no problems.
#  define HAVE_BASE_MATCH_BUG          1
#  define HAVE_NESTED_TYPE_PARAM_BUG   1
#  define HAVE_UNUSED_REQUIRED_BUG     1
#  define HAVE_UNINITIALIZABLE_PRIVATE  1
#  define HAVE_BASE_TYPEDEF_OUTSIDE_BUG 1
#  define HAVE_CONST_CONSTRUCTOR_BUG    1
// if your compiler have serious problems with typedefs, try this one
// #  define HAVE_BASE_TYPEDEF_BUG          1
//==========================================================

//==========================================================
// per-version compiler features recognition
//==========================================================

// reporting of incompatibility
#  define __GIVE_UP_WITH_STL(message) void give_up() \
   { upgrade_the_compiler_to_use_STL;}

#  if defined(__sgi) && ( defined(_BOOL) || !((_MIPS_ISA < 2) || defined (_ABIO32)))
#   define  HAVE_BOOL_KEYWORD
#  endif

// AIX xlC, is there more specific define ?
#if defined(_AIX)
#  define HAVE_RESERVED_BOOL_KEYWORD 1
#  undef  HAVE_DEFAULT_TEMPLATE_PARAM
#  undef  HAVE_DEFAULT_TYPE_PARAM
#  undef  HAVE_NAMESPACES
#  undef  HAVE_UNINITIALIZABLE_PRIVATE
#  define HAVE_UNINITIALIZABLE_PRIVATE 1
#endif

// Microsoft Visual C++ 4.0, 4.1, 4.2
# if defined(_MSC_VER)
#  undef  HAVE_BOOL_KEYWORD
#  undef  HAVE_DEFAULT_TEMPLATE_PARAM
#  undef  HAVE_UNINITIALIZABLE_PRIVATE
#  if ( _MSC_VER>=1000 )
#   define HAVE_NAMESPACES     1
#   define HAVE_EXCEPTIONS     1
#   if ( _MSC_VER<=1010 )
// "bool" is reserved in MSVC 4.1 while <yvals.h> absent, so :
#   define HAVE_RESERVED_BOOL_KEYWORD 1
#   else
#   define HAVE_YVALS_H 1
#   endif
#  endif
# endif

// Borland C++ ( 5.x )
# if defined ( __BORLANDC__ )
#  undef  HAVE_UNINITIALIZABLE_PRIVATE
#  undef  HAVE_DEFAULT_TEMPLATE_PARAM
#  if ( __BORLANDC__ < 0x500 )
#   undef  HAVE_BOOL_KEYWORD
#   undef  HAVE_NAMESPACES
#   undef  HAVE_DEFAULT_TEMPLATE_PARAM
#   undef  HAVE_NESTED_TYPE_PARAM_BUG
#   undef  HAVE_BASE_MATCH_BUG
#   define HAVE_NESTED_TYPE_PARAM_BUG 1
#   define HAVE_BASE_MATCH_BUG        1
#  else
#   define HAVE_BOOL_KEYWORD 1
#   define HAVE_DEFAULT_TYPE_PARAM 1
#   define HAVE_NAMESPACES 1
#   define HAVE_EXPLICIT   1
#   define HAVE_TYPENAME   1
#   define HAVE_EXCEPTIONS 1
#  endif
#  undef  HAVE_LOOP_INLINE_PROBLEMS
#  define HAVE_LOOP_INLINE_PROBLEMS 1
// empty exception spec make things worse in BC, so:
#  undef HAVE_EXCEPTION_SPEC
# endif

# if defined(__SUNPRO_CC)
#  if ( __SUNPRO_CC <= 0x420 )
   // SUNPro C++ 4.1 and above
#   undef  HAVE_BOOL_KEYWORD
#   undef  HAVE_DEFAULT_TEMPLATE_PARAM
#   undef  HAVE_NAMESPACES
#   define HAVE_EXCEPTIONS     1
#   undef  HAVE_EXCEPTION_SPEC
#   define HAVE_EXCEPTION_SPEC 1
#   undef  HAVE_UNINITIALIZABLE_PRIVATE
#   define HAVE_UNINITIALIZABLE_PRIVATE 1
   // SUNPro C++ prior to 4.1
#   if ( __SUNPRO_CC < 0x410 )
   // hard times ;(
#   define HAVE_NESTED_TYPE_PARAM_BUG   1
#   define HAVE_BASE_MATCH_BUG          1
#   define HAVE_BASE_TYPEDEF_BUG        1
#     if ( __SUNPRO_CC < 0x401 )
        __GIVE_UP_WITH_STL(SUNPRO_401)
#     endif
#   endif
#  endif
# endif

// g++ 2.7.x and above 
# if defined (__GNUC__ )
#  undef   HAVE_UNINITIALIZABLE_PRIVATE
#  define  HAVE_BOOL_KEYWORD 1
// cygnus have a lot of version, let's assume the best.
// no specific definitions known except this one
#  if defined (__CYGWIN32__)
#   define __CYGNUS_GCC__
#  endif

#  if ! ( __GNUC__ > 2 || __GNUC_MINOR__ > 7 || defined (__CYGNUS_GCC__) )
// Will it work with 2.6 ? I doubt it.
#   if ( __GNUC_MINOR__ < 6 )
    __GIVE_UP_WITH_STL(GCC_272);
#   endif
#   undef  HAVE_NAMESPACES
#   undef  HAVE_DEFAULT_TEMPLATE_PARAM
#   define HAVE_DEFAULT_TYPE_PARAM 1
#   undef  HAVE_STATIC_TEMPLATE_DATA
#   define HAVE_NESTED_TYPE_PARAM_BUG   1
#   undef  HAVE_STATIC_TEMPLATE_DATA
#   define HAVE_BASE_MATCH_BUG       1
//  unused operators are required (forward)
#   undef  HAVE_EXPLICIT
#   define HAVE_EXPLICIT 1
#   undef  HAVE_UNINITIALIZABLE_PRIVATE
#   define HAVE_UNINITIALIZABLE_PRIVATE 1
#   undef  HAVE_CONST_CONSTRUCTOR_BUG 

// default for gcc-2.7.2 is no exceptions, let's follow it
#  endif /* __GNUC__ > 2 */

// cygnus gcc may be as advanced as that
#  if defined ( __CYGNUS_GCC__ )
#   undef  HAVE_DEFAULT_TEMPLATE_PARAM
#   define HAVE_DEFAULT_TEMPLATE_PARAM 1
#   undef  HAVE_STATIC_TEMPLATE_DATA
#   define HAVE_STATIC_TEMPLATE_DATA   1
#   undef  HAVE_NAMESPACES
#   define HAVE_EXPLICIT   1
#   define HAVE_TYPENAME   1
#  endif 

// static template data members workaround strategy for gcc tries
// to use weak symbols.
// if you don't want to use that, #define HAVE_WEAK_ATTRIBUTE=0 ( you'll
// have to put "#define __PUT_STATIC_DATA_MEMBERS_HERE" line in one of your
// compilation unit ( or CFLAGS for it ) _before_ including any STL header ).
#  if !(defined (HAVE_STATIC_TEMPLATE_DATA) || defined (HAVE_WEAK_ATTRIBUTE ))
// systems using GNU ld or format that supports weak symbols
// may use "weak" attribute
// Linux & Solaris ( x86 & SPARC ) are being auto-recognized here
#   if defined(HAVE_GNU_LD) || defined(__ELF__) || \
    (( defined (__SVR4) || defined ( __svr4__ )) && \
     ( defined (sun) || defined ( __sun__ )))
#    define HAVE_WEAK_ATTRIBUTE 1
#   endif
#  endif /* HAVE_WEAK_ATTRIBUTE */

# endif /* __GNUC__ */

# undef __GIVE_UP_WITH_STL

#endif
