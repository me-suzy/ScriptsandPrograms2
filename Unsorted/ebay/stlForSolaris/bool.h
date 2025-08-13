/*	$Id: bool.h,v 1.2 1998/12/17 04:13:31 josh Exp $	*/
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
 * Copyright (c) 1997
 * Silicon Graphics
 *
 * Permission to use, copy, modify, distribute and sell this software
 * and its documentation for any purpose is hereby granted without fee,
 * provided that the above copyright notice appear in all copies and
 * that both that copyright notice and this permission notice appear
 * in supporting documentation.  Silicon Graphics makes no
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


#ifndef HPSTL_BOOL_H
# define HPSTL_BOOL_H

// include compiler settings
# include <stlconf.h>

# if defined(HAVE_YVALS_H)
#  include <yvals.h>
# else
#  if ! defined(HAVE_BOOL_KEYWORD)
#   if defined (HAVE_RESERVED_BOOL_KEYWORD)
#    define bool int
#    define true 1
#    define false 0
#   else
     typedef int bool;
#    define true 1
#    define false 0
#   endif
#  endif /* HAVE_BOOL_KEYWORD */
# endif

#endif /* HPSTL_BOOL_H */
