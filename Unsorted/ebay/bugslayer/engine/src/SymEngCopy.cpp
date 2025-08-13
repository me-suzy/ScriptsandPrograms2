/*----------------------------------------------------------------------
FILE        :   SymEngCopy.cpp
DISCUSSION  :
    The SymEngCopy program copys IMAGEHLP.DLL and MSPDB50.DLL into the
computer's %SYSTEMROOT%\System32 directory.  If IMAGEHLP.DLL is
currently loaded into memory, SymEngCopy will copy the file such that
the new version will get properly copied after a system reboot.
SymEngCopy does not do the actual system reboot.
    SymEngCopy requires that IMAGEHLP.DLL and MSPDB50.DLL be in the same
directory as itself.
DEVELOPER   :   John Robbins (john@jprobbins.com)
                [Under contract with eBay]
DATE        :   10/06/98.

HISTORY     :
DATE        :   03/14/99
    Updated the program to allow copying any specified symbol engine
    versions through an INI file.  This allows future updating to
    occur with new versions of IMAGEHLP.DLL.  For example, the
    NT5 Beta 3 version does not use MSPDB50.DLL but is hard linked to
    MSDBI.DLL.  It will change in future releases of NT as well.

    The input INI:

    [SymEngCopy]        ; The section name.
    NumFiles=2          ; Zero based number of files for copying.
    File0=<full path 1> ; The complete path and filename to copy.
    File1=<full path 2>

    All files will be copied to the %SYSTEMROOT%\System32 directory.
    If a file is in use, output will indicate that you need to reboot
    the system in order to complete the copy.
----------------------------------------------------------------------*/

// The usual include suspects.
#include <stdio.h>
#include <windows.h>
#include <tchar.h>

/*//////////////////////////////////////////////////////////////////////
                         Constants and Defines
//////////////////////////////////////////////////////////////////////*/
// The name of the INI file.
const LPCTSTR k_INI_FILE = _T ( "SYMENGCOPY.INI" ) ;
// The Section name.
const LPCTSTR k_SECT_NAME = _T ( "SymEngCopy" ) ;
// The file count key.
const LPCTSTR k_FILECOUNT_KEY = _T ( "NumFiles" ) ;
// The format string for individual file keys.
const LPCTSTR k_FILEFMT = _T ( "File%d" ) ;

// The extension slapped on files that will need to hang around until
// the reboot.
const LPCTSTR k_MOVEONREBOOTEXT = _T ( ".MoveOnReboot" ) ;

// szExisting - An existing file to copy to the %SYSTEMROOT%\System32
//              directory.
BOOL CopyOrMoveFileToSystem32Dir ( LPCTSTR szExisting )
{

    // Make sure the input file really exists.
    HANDLE hFile = CreateFile ( szExisting              ,
                                GENERIC_READ            ,
                                FILE_SHARE_READ         ,
                                NULL                    ,
                                OPEN_EXISTING           ,
                                FILE_ATTRIBUTE_NORMAL   ,
                                NULL                     ) ;
    if ( INVALID_HANDLE_VALUE == hFile )
    {
        _tprintf ( _T ( "'%s' does not exist!\n" ) , szExisting ) ;
        return ( FALSE ) ;
    }
    CloseHandle ( hFile ) ;

    // Get the individual pieces of the filename.
    TCHAR szDrive [ 5 ] ;
    TCHAR szDir[ MAX_PATH ] ;
    TCHAR szFile[ MAX_PATH ] ;
    TCHAR szExt[ MAX_PATH ] ;

    _tsplitpath ( szExisting , szDrive , szDir , szFile , szExt ) ;

    // Check that the input parameter was good.
    if ( ( _T ( '\0' ) == szDrive[ 0 ] ) ||
         ( _T ( '\0' ) == szFile[ 0 ]  ) ||
         ( _T ( '\0' ) == szExt[ 0 ]   )   )
    {
        _tprintf ( _T ( "Invalid input to CopyOrMoveFileToSystem32Dir "
                   "'%s'\n" ) , szExisting ) ;
        return ( FALSE ) ;
    }

    // Build up the system root filename.
    TCHAR szSystemDir[ MAX_PATH ] ;

    if ( 0 == GetSystemDirectory ( szSystemDir ,
                                   sizeof(szSystemDir)/sizeof(TCHAR) ) )
    {
        _tprintf ( _T ( "Unable to get system directory in "
                   "CopyOrMoveFileToSystem32Dir\n" ) ) ;
        return ( FALSE ) ;
    }

    // Slap the backslash on the system directory if needed.
    if ( _T ( '\\' ) != szSystemDir[ _tcslen ( szSystemDir ) - 1 ] )
    {
        _tcscat ( szSystemDir , _T ( "\\" ) ) ;
    }

    // Build up the final destination file no matter if the actual
    // copy requires a reboot.  This is the final filename.
    TCHAR szFinalName [ MAX_PATH ] ;

    _tcscpy ( szFinalName , szSystemDir ) ;
    _tcscat ( szFinalName , szFile ) ;
    _tcscat ( szFinalName , szExt ) ;

    // Step zero is to double check that the final file does not have
    // read-only as the file attribute.  If it does, then I will reset
    // them here.
    DWORD dwAttribs = GetFileAttributes ( szFinalName ) ;
    if ( (-1 != dwAttribs ) && ( FILE_ATTRIBUTE_READONLY & dwAttribs ) )
    {
        dwAttribs &= ~FILE_ATTRIBUTE_READONLY ;
        SetFileAttributes ( szFinalName , dwAttribs ) ;
    }

    // The first step is to just go ahead and copy the file.  If the
    // destination file is not in use, the copy will work just fine.
    if ( FALSE == CopyFile ( szExisting , szFinalName , FALSE ) )
    {
        // The file is probably in use so its time to do the gyrations
        // to move on reboot.

        // The temporary filename that will be around until the system
        // is rebooted.
        TCHAR szTempName[ MAX_PATH ] ;
        _tcscpy ( szTempName , szFinalName ) ;
        // Stick on the unique filename.
        _tcscat ( szTempName , k_MOVEONREBOOTEXT ) ;

        // Just in case the temp file is there, make sure it is not set
        // to read only.
        dwAttribs = GetFileAttributes ( szTempName ) ;
        if ( (-1 != dwAttribs                      ) &&
             ( FILE_ATTRIBUTE_READONLY & dwAttribs )   )
        {
            dwAttribs &= ~FILE_ATTRIBUTE_READONLY ;
            SetFileAttributes ( szTempName , dwAttribs ) ;
        }

        // Copy the input file to the temp file so that I can do the
        // move on reboot after this.  The temp file needs to be on the
        // same drive as %SYSTEMROOT%\System32 so I copy it there.  If
        // this copy fails, there is not much I can do.
        if ( FALSE == CopyFile ( szExisting , szTempName , FALSE ) )
        {
            _tprintf ( _T ( "CopyOrMoveFileToSystem32Dir is unable to "
                       "create the temporary file!!\n" ) ) ;
            return ( FALSE ) ;
        }

        // Wipe out the destination file.
        if ( FALSE == MoveFileEx ( szFinalName , 
                                   NULL        , 
                                   MOVEFILE_DELAY_UNTIL_REBOOT ) )
        {
            _tprintf ( _T ( "Unable to mark %s for deletion!\n" ) ,
                      szFinalName ) ;
            return ( FALSE ) ;
        }
        // Now tell the OS to move the temp file to the real file when
        // it reboots.
        if ( FALSE == MoveFileEx ( szTempName                   ,
                                   szFinalName                  ,
                                   MOVEFILE_DELAY_UNTIL_REBOOT   ) )
        {
            _tprintf ( _T ( "Serious error!  Unable to move '%s' to "
                       "'%s' on reboot!\n" ) ,
                       szTempName , szFinalName ) ;
            return ( FALSE ) ;
        }
        // Hey!  It worked!!
        _tprintf ( _T ( "'%s' will be moved to '%s' once the system is "
                   "rebooted.\n" ) , szTempName , szFinalName ) ;
    }
    else
    {
        // Copying worked.  The file is completely updated.
        _tprintf ( _T ( "%s successfully copied to %s\n" ) ,
                   szExisting                              ,
                   szFinalName                              ) ;
    }

    return ( TRUE ) ;
}

// Ye Ol' Main.
void main ( void )
{

    // Get the INI file that must be in the same directory as this
    // program.
    TCHAR szINI[ MAX_PATH ] ;

    if ( 0 == GetModuleFileName ( NULL  ,
                                  szINI ,
                                  sizeof ( szINI ) / sizeof ( TCHAR ) ))
    {
        _tprintf ( _T ( "Unable to get the module's filename!\n" ) ) ;
        return ;
    }
    TCHAR * pSlash = _tcsrchr ( szINI , _T ( '\\' ) ) ;
    if ( NULL != pSlash )
    {
        pSlash++ ;
    }
    else
    {
        pSlash = szINI ;
    }
    _tcscpy ( pSlash , k_INI_FILE ) ;

    // Get the number of files to read out of the INI file.
    DWORD dwCount = GetPrivateProfileInt ( k_SECT_NAME     ,
                                           k_FILECOUNT_KEY ,
                                           0               ,
                                           szINI            ) ;

    if ( 0 == dwCount )
    {
        _tprintf ( _T ( "Missing INI file!  See the source code "
                   "documentation\n" ) ) ;
        return ;
    }

    // Loop away.
    TCHAR szCurrVal[ 50 ] ;
    TCHAR szCurrFile[ MAX_PATH ] ;
    for ( DWORD i = 0 ; i < dwCount ; i++ )
    {
        wsprintf ( szCurrVal , k_FILEFMT , i ) ;
        GetPrivateProfileString ( k_SECT_NAME ,
                                  szCurrVal   ,
                                  _T ( "\0" ) ,
                                  szCurrFile  ,
                                  sizeof(szCurrFile)/sizeof(TCHAR) ,
                                  szINI        ) ;
        if ( _T ( '\0' ) == szCurrFile[ 0 ] )
        {
            _tprintf ( "Ill formed entry at %s\n" , szCurrVal ) ;
            return ;
        }
        if ( FALSE == CopyOrMoveFileToSystem32Dir ( szCurrFile ) )
        {
            return ;
        }
    }
}
