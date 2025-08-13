//	$Id: MSJDBG.h,v 1.2 1999/03/22 00:09:30 josh Exp $
/*----------------------------------------------------------------------
FILE        :   MSJDBG.h
FROM        :   "BugSlayer" Microsoft Systems Journal, Oct. 97
                John Robbins
HISTORY     :
    Feb. 98 column - Added TRACE macros.
----------------------------------------------------------------------*/

#ifndef _MSJDBG_H
#define _MSJDBG_H

// Force the include of the debug run-time header file and make sure the
//  allocation mapping functions are in effect.
#define _CRTDBG_MAP_ALLOC
#include <crtdbg.h>

#ifdef _DEBUG
/*//////////////////////////////////////////////////////////////////////
Debug Build Options
//////////////////////////////////////////////////////////////////////*/

////////////////////////////////////////////////////////////////////////
// I prefer one style of assert and the expression should, by default,
//  always be part of the assert.  Of course, it would have also been
//  nice if the CRT ASSERT also showed the result of GetLastError!
// The CRT assert causes a warning when compiled /W4, which should
//  have been fixed.  The warning is C4127: conditional expression is
//  constant, so I turn that warning off.
////////////////////////////////////////////////////////////////////////
// If you are using MFC and you want to keep using the MFC assert macro,
//  just define USE_MFC_ASSERT.
#   if defined (_AFX) && ! defined ( USE_MFC_ASSERT )
#       undef ASSERT
#       undef VERIFY
#   endif

#   ifndef ASSERT
#pragma warning (disable : 4127)
#       ifdef _ASSERTE
#           define ASSERT _ASSERTE
#       endif

#       ifdef _ASSERT
#           undef _ASSERT
#           define _ASSERT  ASSERT
#       endif
#   endif   // ifndef ASSERT

// Catch the standard C assert macro.
#   ifdef assert
#       undef  assert
#       define assert ASSERT
#   endif

// The debug run-time does not have a VERIFY macro so I stuck one in
//  so that it is always availible.
#   ifndef VERIFY
#       define  VERIFY(x)  ASSERT(x)
#   endif   // ifndef VERIFY

// To keep everything straight and uniform, I provide trace macros.
#   if ! defined (_AFX)
#       define TRACE0(fmt) /*lint -e717*/            \
                           _RPT0 ( _CRT_WARN , fmt ) \
                           /*lint +e717*/
#       define TRACE1(fmt,arg1) /*lint -e717*/                   \
                                _RPT1 ( _CRT_WARN , fmt , arg1 ) \
                                /*lint +e717*/
#       define TRACE2(fmt,arg1,arg2) /*lint -e717*/       \
                                     _RPT1 ( _CRT_WARN ,  \
                                             fmt       ,  \
                                             arg1      ,  \
                                             arg2       ) \
                                     /*lint +e717*/
#       define TRACE3(fmt,arg1,arg2,arg3) /*lint -e717*/       \
                                          _RPT1 ( _CRT_WARN ,  \
                                                  fmt       ,  \
                                                  arg1      ,  \
                                                  arg2      ,  \
                                                  arg3       ) \
                                          /*lint +e717*/
#       define TRACE4(fmt,arg1,arg2,arg3,arg4) /*lint -e717*/       \
                                               _RPT1 ( _CRT_WARN ,  \
                                                       fmt       ,  \
                                                       arg1      ,  \
                                                       arg2      ,  \
                                                       arg3      ,  \
                                                       arg4       ) \
                                               /*lint +e717*/
#   endif   // ifndef _AFX

#else       // _DEBUG is not defined
/*//////////////////////////////////////////////////////////////////////
Release Build Options
//////////////////////////////////////////////////////////////////////*/

#   if ! defined (_AFX)
#       define ASSERT(x)    ((void)0)
#       define VERIFY(x)    ((void)(x))
#       define TRACE0(fmt)
#       define TRACE1(fmt,arg1)
#       define TRACE2(fmt,arg1,arg2)
#       define TRACE3(fmt,arg1,arg2,arg3)
#       define TRACE4(fmt,arg1,arg2,arg3,arg4)
#   endif


#endif      // _DEBUG


#endif      // _MSJDBG_H


