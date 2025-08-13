//	$Id: CriticalSection.h,v 1.2 1999/03/22 00:09:30 josh Exp $
/*----------------------------------------------------------------------
  John Robbins - Microsoft Systems Journal Bugslayer Column - Feb '98
----------------------------------------------------------------------*/
/*----------------------------------------------------------------------
    A simple critical section wrapper that makes it easy to use a
critical section.  Just declare a single CCriticalSection as part of
your class data and whenever you need to user it, use the
CUseCriticalSection class.  Now, no matter how you exit the function,
the critical section will automatically be "leaved."
----------------------------------------------------------------------*/

#ifndef _CRITICALSECTION_H
#define _CRITICALSECTION_H

/*//////////////////////////////////////////////////////////////////////
                       The CCriticalSection Class
//////////////////////////////////////////////////////////////////////*/
class CUseCriticalSection ;

class CCriticalSection
{
public      :

    CCriticalSection ( void )
    {
        InitializeCriticalSection ( &m_CritSec ) ;
    }
    ~CCriticalSection ( )
    {
        DeleteCriticalSection ( &m_CritSec ) ;
    }

    friend CUseCriticalSection ;
private     :
    CRITICAL_SECTION m_CritSec ;
} ;

/*//////////////////////////////////////////////////////////////////////
                     The CUseCriticalSection Class
//////////////////////////////////////////////////////////////////////*/
//lint -e1704
class CUseCriticalSection
{
public      :
    CUseCriticalSection ( const CCriticalSection & cs )
    {
        m_cs = &cs ;
        EnterCriticalSection ( ( LPCRITICAL_SECTION)&(m_cs->m_CritSec));
    }

    ~CUseCriticalSection ( )
    {
        LeaveCriticalSection ( (LPCRITICAL_SECTION)&(m_cs->m_CritSec) );
        m_cs = NULL ;
    }

private     :
    CUseCriticalSection ( void )
    {
        m_cs = NULL ;
    }
    const CCriticalSection * m_cs ;
} ;
//lint +e1704

#endif      // _CRITICALSECTION_H

////////////////////////////////////////////////////////////////////////
//
// $Log: CriticalSection.h,v $
// Revision 1.2  1999/03/22 00:09:30  josh
// E113 integration
//
// Revision 1.1.2.1.2.1  1999/03/08 04:56:36  josh
// Adding Id lines
//
// Revision 1.1.2.1  1999/03/08 02:14:16  josh
// Bugslayer stuff
//
// 
// 1     12/07/97 11:51a John
// 
// 5     11/09/97 5:18p John
// 
// 4     11/06/97 11:40p John
// 
// 3     10/15/97 11:59p John
// 
// 2     9/17/97 2:03a John
// 
// 1     9/16/97 12:23a John
//
// 2     2/20/97 11:38p John
// - Cleaned up LINT warnings.
//
// 1     2/04/97 12:46a John
// The initial version of the PUtility library.
//
// 1     1/28/97 1:19a John
// The initial version the symbol engine library.
//
////////////////////////////////////////////////////////////////////////


