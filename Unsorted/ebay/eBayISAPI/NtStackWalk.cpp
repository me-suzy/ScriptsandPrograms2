/*	$Id: NtStackWalk.cpp,v 1.4 1999/03/22 00:09:31 josh Exp $	*/
//
// This routine prints a stack (call frame) trace
// of the current routine back to the start of
// the program (limited to 100 frames).  It works
// for both Intel and Alpha.
//
// If the image contains debug info (or has a .pdb),
// routine and offset are printed.
//
// Note that if it is used in an exception handler,
// the exception structure contains the necessary
// information to setup the starting point.  Some of
// the code in the architecture-specific section could
// be simplified to get the exception pointer data
// instead of using the current context.
//
// Please send any comments or corrections to
//
//      CW Hobbs
//      Software Partner Engineering - Palo Alto
//      CW.Hobbs@digital.com
//

/***********************************************************************
Updates : 10/08/98 John Robbins (john@jprobbins.com)

1.  Added the CONSOLE_TEST define to allow testing this code with the
    SW_TEST.CPP file.
2.  Add the source and line lookup.  Please note that the symbol engine
    stuff might look a little weird.  In order to ensure that this code
    compiles, links, and works with any one of the 600 different
    versions of IMAGEHLP.DLL, IMAGEHLP.H, and IMAGEHLP.LIB out there, I
    use the CSymbolEngine class it dynamically see if the source and
    line functions are there instead of hard linking.  Since that class
    takes care of the proper initialization, the actual IMAGEHLP.DLL
    symbol functions are passed to the stack walk function.  Since both
    stack walking and the symbol look up consistently use the same value
    for the instance ID it all works.
***********************************************************************/
/* Modified by Josh to add the system path and the eBayISAPI module path to the
search path. */

#ifdef CONSOLE_TEST
#include <windows.h>
#else
#include <afx.h>
#endif

//#include <imagehlp.h>       // link with imagehlp.lib as well...
#include "SymbolEngine.h"
#include <stdio.h>
#include <winerror.h>

#ifndef CONSOLE_TEST
#include "clsEventLog.h"
#endif  // CONSOLE_TEST

#define  sizeof_Name        128
#define  sizeof_CONTEXT     sizeof(CONTEXT)+96
#define  sizeof_STACKFRAME  sizeof(STACKFRAME)+16
#define  sizeof_symbol      sizeof(IMAGEHLP_SYMBOL)+sizeof_Name

#if defined ( _M_ALPHA )
void RtlCaptureContext ( CONTEXT * cxt ) ;
#endif

const int BUFFERSIZE=4096 ;

// typedef struct {DWORD d[8];} foob;
void add_to_buffer ( char * buffer , const char * p )
{
    if ( p == NULL )
        buffer[ 0 ] = '\0' ;
    else
    {
        if ( strlen ( p ) + strlen ( buffer ) + 2 < BUFFERSIZE )
            strcat ( buffer , p ) ;
    }
}

char StackTraceBuffer[BUFFERSIZE];

void dump_buffer ( char * buffer )
{
#ifdef CONSOLE_TEST
    printf ( buffer ) ;
#else
    clsEventLog().LogInformationEvent(buffer) ;
	strcpy(StackTraceBuffer, buffer);
#endif
}

extern "C" void NtStackTrace (char * message)
{
    HANDLE            hProc , hThread ;
    CONTEXT *         cxt ;
    IMAGEHLP_SYMBOL * sym ;
    IMAGEHLP_LINE     stSymLine ;
    DWORD             dwLineDisp ;
    STACKFRAME  *     frm ;
    DWORD             machType , symDisp , lastErr , filepathlen ;
    BOOL              stat ;
    int               i ;
    char              filepath[ MAX_PATH ] , * lastdir , * pPath ;

    // The symbol engine wrapper.
    CSymbolEngine     cSym ;

    char obuf[ BUFFERSIZE ] ;
    char tbuf[ MAX_PATH ] ;

    add_to_buffer ( obuf , NULL ) ;
    add_to_buffer ( obuf , message ) ;
    add_to_buffer ( obuf , ":  \n" ) ;
    // Initialize the IMAGEHLP package to decode addresses to symbols
    //
    //    Note: need to link /debug /debugtype:coff to get symbols into .EXE/.DLL files

    // Get image filename of the main executable
    filepathlen = GetModuleFileName ( NULL , filepath , sizeof ( filepath ) ) ;
    if ( filepathlen == 0 )
        add_to_buffer ( obuf , "NtStackTrace: Failed to get pathname for program\n" ) ;

    // Strip the filename, leaving the path to the executable

    lastdir = strrchr ( filepath , '/' ) ;
    if ( lastdir == NULL )
        lastdir = strrchr ( filepath , '\\' ) ;
    if ( lastdir != NULL )
        lastdir[ 0 ] = '\0' ;

	HMODULE dllHandle = GetModuleHandle("eBayISAPI.dll"); 
    char dllPath[ MAX_PATH ];
	filepathlen = GetModuleFileName(dllHandle, dllPath, sizeof dllPath);
	if (filepathlen == 0)
		add_to_buffer(obuf, "NtStackTrace: Failed to get pathname for DLL\n");
    lastdir = strrchr (dllPath , '/' ) ;
    if ( lastdir == NULL )
        lastdir = strrchr(dllPath, '\\' ) ;
    if ( lastdir != NULL )
        lastdir[ 0 ] = '\0' ;

    pPath = filepath ;
    if ( strlen ( filepath ) == 0 )
        pPath = NULL ;

	// OK. The path should consist of systemroot;filepath;dllpath
	char fullpath[MAX_PATH * 3 + 3];
	
	// What's the system root?
	char *systemRoot = getenv("SystemRoot");
	if (systemRoot == NULL)
		systemRoot = "c:\\winnt";
	sprintf(fullpath, "%s\\system32;%s;%s",
		systemRoot, filepath, dllPath);
	

	hProc = GetCurrentProcess () ;
    hThread = GetCurrentThread () ;

    // Set the symbol options so defered loading is off and line loading
    //  is on.
    SymSetOptions (  SymGetOptions ( ) | SYMOPT_LOAD_LINES ) ;

    // Initialize the symbol table routines, supplying a pointer to the path
    if ( !cSym.SymInitialize ( hProc , fullpath , TRUE ) )
        add_to_buffer ( obuf , "NtStackTrace: failed to initialize symbols\n" ) ;

    // Check if the in memory IMAGEHLP.DLL supports source and line look
    //  up.  If it does not, then add that to the output.
    if ( FALSE == cSym.CanDoSourceLines ( ) )
    {
        add_to_buffer ( obuf ,
                        "In memory IMAGEHLP.DLL does not support "
                        "source and line lookup\n" ) ;
    }



    // Allocate and initialize frame and symbol structures

    frm = ( STACKFRAME * ) malloc ( sizeof_STACKFRAME ) ;
    memset ( frm , 0 , sizeof ( STACKFRAME ) ) ;

    sym = ( IMAGEHLP_SYMBOL * ) malloc ( sizeof_symbol ) ;
    memset ( sym , 0 , sizeof_symbol ) ;
    sym->SizeOfStruct = sizeof ( IMAGEHLP_SYMBOL ) ;
    sym->MaxNameLength = sizeof_Name - 1 ;

    // Initialize the source and line lookup structure.
    memset ( &stSymLine , NULL , sizeof ( IMAGEHLP_LINE ) ) ;
    stSymLine.SizeOfStruct = sizeof ( IMAGEHLP_LINE ) ;


    // Initialize the starting point based on the architecture of the current machine

#if defined ( _M_IX86 )

    machType = IMAGE_FILE_MACHINE_I386 ;

    // The CONTEXT structure is not used on x86 systems

    cxt = NULL ;

    //  Initialize the STACKFRAME to describe the current routine

    frm->AddrPC.Mode = AddrModeFlat ;
    frm->AddrStack.Mode = AddrModeFlat ;
    frm->AddrFrame.Mode = AddrModeFlat ;

    // If we were called from an exception handler, the exception
    // structure would contain an embedded CONTEXT structure.  We
    // could initialize the following addresses from the CONTEXT
    // registers passed to us.

    // For this example, use _asm to fetch the processor register values

    _asm mov  i , esp                   // Stack pointer  (CONTEXT .Esp field)
    frm->AddrStack.Offset = i ;

    _asm mov  i , ebp                   // Frame pointer  (CONTEXT .Ebp field)
    frm->AddrFrame.Offset = i ;

    // We'd like to fetch the current instruction pointer, but the x86 IP
    // register is a bit special.  Use roughly the current offset instead
    // of a dynamic fetch (use offset because address should be past the prologue).

    //  _asm mov  i, ip     // ip is a special register, this is illegal
    //  frm->AddrPC.Offset       = i;

    frm->AddrPC.Offset = ( ( DWORD ) & NtStackTrace ) + 0x08c ;


#elif defined ( _M_ALPHA )

    machType = IMAGE_FILE_MACHINE_ALPHA ;

    cxt = malloc ( sizeof_CONTEXT ) ;
    memset ( cxt , 0 , sizeof_CONTEXT ) ;

    // Fetch the current context for the NtStackTrace procedure itself)

    RtlCaptureContext ( cxt ) ;

#else
#error( "unknown target machine type - not Alpha or X86" );
#endif


    //  The top stack frame is the call to this routine itself -
    // probably not of much interest, so grab the info outside
    // of the main loop.  Note that if we got the initial starting
    // point from the exception frame, the top frame might be of interest.

    if ( !StackWalk ( machType , hProc , hThread , frm , cxt ,
             NULL , SymFunctionTableAccess , SymGetModuleBase , NULL ) )
    {
        add_to_buffer ( obuf , "NtStackTrace: Failed to walk current stack call\n" ) ;
    }

    add_to_buffer ( obuf , "[NT Stack Trace]:\n" ) ;

    // Include the address/symbol info for the stack trace routine itself

    if ( !cSym.SymGetSymFromAddr ( frm->AddrPC.Offset , &symDisp , sym ) )
    {
        sprintf ( tbuf , "0x%08x " ,
            frm->AddrPC.Offset ) ;
    }
    else
    {
        sprintf ( tbuf , "0x%08x: %s+%d" ,
            frm->AddrPC.Offset , sym->Name , symDisp ) ;
    }

    add_to_buffer ( obuf , tbuf ) ;

    // See if the source and line information is there.
    if ( cSym.SymGetLineFromAddr ( frm->AddrPC.Offset   ,
                                   &dwLineDisp          ,
                                   &stSymLine            ) )
    {
        // Cool, found it.
        if ( 0 == dwLineDisp )
        {
            sprintf ( tbuf                 ,
                      ", %s, line %d"      ,
                      stSymLine.FileName   ,
                      stSymLine.LineNumber  ) ;
        }
        else
        {
            sprintf ( tbuf                  ,
                      ", %s, line %d (+%d)" ,
                      stSymLine.FileName    ,
                      stSymLine.LineNumber  ,
                      dwLineDisp             ) ;
        }
    }
    else
    {
        sprintf ( tbuf , ", no source" ) ;
    }

    add_to_buffer ( obuf , tbuf ) ;
    add_to_buffer ( obuf , "\n" ) ;


    // Loop through the rest of the call stack, limit trace to 100
    // routines to make sure that we don't loop or flood the user
    // with too much info in case of very deep stack or infinite recursion...

    for ( i = 0 ; i < 100 ; i++ )
    {

        // Call the routine to trace to the next frame

        stat = StackWalk ( machType , hProc , hThread , frm , cxt ,
             NULL , SymFunctionTableAccess , SymGetModuleBase , NULL ) ;
        if ( !stat )
        {
            lastErr = GetLastError () ;
            if ( lastErr == ERROR_NOACCESS | lastErr == ERROR_INVALID_ADDRESS )
                add_to_buffer ( obuf , "[done]\n" ) ; // Normal end-of-stack code
            else
            {
                sprintf ( tbuf , ";<stack walk terminated with error %d>\n" ,
                    lastErr ) ;
                add_to_buffer ( obuf , tbuf ) ;
            }
            break ;
        }

        // Ignore frames with PC = 0, these seem to be an end-of-stack guard frame on Intel

        if ( frm->AddrPC.Offset != 0 )
        {

            // Decode the closest routine symbol name

            if ( cSym.SymGetSymFromAddr ( frm->AddrPC.Offset , &symDisp , sym ) )
            {
                sprintf ( tbuf , "0x%08x: %s+%d" ,
                    frm->AddrPC.Offset , sym->Name , symDisp ) ;
                add_to_buffer ( obuf , tbuf ) ;
            }
            else
            {
                lastErr = GetLastError () ;
                if ( lastErr == ERROR_INVALID_ADDRESS ) // Seems normal for last frame on Intel
                    sprintf ( tbuf , "0x%08x: " ,
                        frm->AddrPC.Offset ) ;
                else
                    sprintf ( tbuf , "0x%08x: <error %d>" ,
                        frm->AddrPC.Offset , lastErr ) ;
                add_to_buffer ( obuf , tbuf ) ;
            }
        }
        // See if the source and line information is there.
        if ( cSym.SymGetLineFromAddr ( frm->AddrPC.Offset   ,
                                       &dwLineDisp          ,
                                       &stSymLine            ) )
        {
            // Cool, found it.
            if ( 0 == dwLineDisp )
            {
                sprintf ( tbuf                 ,
                          ", %s, line %d"      ,
                          stSymLine.FileName   ,
                          stSymLine.LineNumber  ) ;
            }
            else
            {
                sprintf ( tbuf                  ,
                          ", %s, line %d (+%d)" ,
                          stSymLine.FileName    ,
                          stSymLine.LineNumber  ,
                          dwLineDisp             ) ;
            }
        }
        else
        {
            sprintf ( tbuf , ", no source" ) ;
        }

                add_to_buffer ( obuf , tbuf ) ;
        add_to_buffer ( obuf , "\n" ) ;
    }

    if ( i >= 100 )
        add_to_buffer ( obuf , "...<traceback terminated after 100 routines>" ) ;

    if ( !cSym.SymCleanup ( ) )
        add_to_buffer ( obuf , "NtStackTrace: failed to cleanup symbols" ) ;

    free ( cxt ) ;  // If on Intel, freeing the NULL CONTEXT is a no-op...
    free ( frm ) ;
    free ( sym ) ;

    dump_buffer ( obuf ) ;
}

