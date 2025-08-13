/*
   $Id: clsAuthorize.h,v 1.2 1998/08/25 03:20:01 josh Exp $

   NAME
    clsAuthorize.h      -- 
 
   DESCRIPTION
   This module contains all of the includes, constants, typedefs and class
   definitions to define the clsAuthorize.  This class will perform the
   tasks necessary to automatically validate credit cards.
 
 
   NOTES
   Refer to hardcopy documentation provided by Wells Fargo and FDMS as the
   'source of truth' for field and message definition required to validate
   credit cards.
     
   AUTHOR
   Matthew Paul Houseman

   MODIFIED
   $Log: clsAuthorize.h,v $
   Revision 1.2  1998/08/25 03:20:01  josh
   E105 integration; includes E104_prod and E102_SECURE

   Revision 1.1.8.1  1998/07/08 01:20:33  wwen
   merged E102 with E100_SECURE and E102_PROD.

   Revision 1.1.6.1  1998/06/26 01:49:46  wwen
   merged the codes on secure server with E100.

   Revision 1.9.2.1  1998/06/10 17:15:48  sam
   Initial Checkin for FDMS related CC AUthorizations


*/

#if !defined ( _CLSAUTHORIZE_H_ )
#define _CLSAUTHORIZE_H_

/******************************** Includes **********************************/

#include <iostream.h>
#if defined ( _MSC_VER )
#  include <winsock2.h>
#  include <stdio.h>
#  include <time.h>
#else
#  include <unistd.h>
#  include <stdio.h>
#  include <stdlib.h>
#  include <string.h>
#  include <sys/types.h>
#  include <sys/socket.h>
#  include <netinet/in.h>
#  include <arpa/inet.h>
#  include <netdb.h>
#endif

/******************************** Constants *********************************/

#define EBAY_AUTHORIZATION  "AUTHORIZATION"
#define EBAY_REVERSAL       "REVERSAL"
#define EBAY_FDMS_TEST      "FDMSTEST"
#define EBAY_VISA_TEST      "VISATEST"
#define EBAY_LOGON_TEST     "LOGONTEST"
#define EBAY_BATCH          "BATCH"
#define EBAY_REALTIME       "REALTIME"

/******************************** Variables *********************************/

/******************************** Macros ************************************/

#if defined ( _MSC_VER )

/*************************************
 * Start the WINSOCK V2.0 subsystem. *
 *************************************/
#  define EBAY_STARTSOCKETS_M(_ists)                                    \
{                                                                       \
    WSAData                     wsaData;                                \
                                                                        \
    (_ists) = WSAStartup ( MAKEWORD ( 2, 0 ), &wsaData );               \
}

/***************************
 * Close a WINSOCK socket. *
 ***************************/
#  define EBAY_CLOSESOCKET_M(_isocketfd,_ists)                          \
{                                                                       \
    (_ists) = closesocket ( (_isocketfd) );                             \
    if ( (_ists) == SOCKET_ERROR )                                      \
    {                                                                   \
        (_ists) = -1;                                                   \
    }                                                                   \
    (_isocketfd) = -1;                                                  \
}

/********************************
 * Sleep for number of seconds. *
 ********************************/
#define EBAY_SLEEP_SECONDS_M(_ulSeconds)    Sleep((_ulSeconds) * 1000L)

#else

/*************************************************
 * Socket subsystem is already started for UNIX. *
 *************************************************/
#  define EBAY_STARTSOCKETS_M(_ists)                                    \
{                                                                       \
    (_ists) = 0;                                                        \
}

/************************
 * Close a UNIX socket. *
 ************************/
#  define EBAY_CLOSESOCKET_M(_isocketfd,_ists)                          \
{                                                                       \
    (_ists) = close ( (_isocketfd) );                                   \
    (_isocketfd) = -1;                                                  \
}

/********************************
 * Sleep for number of seconds. *
 ********************************/
#define EBAY_SLEEP_SECONDS_M(_ulSeconds)    sleep((_ulSeconds))

#endif


/******************************** Typedefs **********************************/

/******************************** Class Definitions *************************/

class clsAuthorize
{
    public:

        /***********************************
         * Constructor/Destructor methods. *
         ***********************************/
        clsAuthorize  ( );
        ~clsAuthorize ( );

        /****************************
         * 'Set' attribute methods. *
         ****************************/

        /*********************
         * Dispatch methods. *
         *********************/
        int DispatchAuthorizationRequest ( char *pszAccountNumberInput,
                                           char *pszTransAmountInput,
                                           char *pszSystemTraceInput,
                                           char *pszCardExpDateInput,
                                           char *pszBillingAddrInput,
                                           bool bIsBatch );
        int DispatchReversalRequest      ( char *pszAccountNumberInput,
                                           char *pszTransAmountInput,
                                           char *pszSystemTraceInput,
                                           char *pszCardExpDateInput,
                                           char *pszBillingAddrInput,
                                            bool bIsBatch );
        int DispatchFDMSStandardEchoTest ( bool bIsBatch );
        int DispatchVISAStandardEchoTest ( bool bIsBatch );
        int DispatchLogonMessageTest     ( bool bIsBatch );

        /**********************
         * Exception classes. *
         **********************/
        class xGetEnv {};
        class xatoi {};
        class xStartSockets {};
        class xSocketOpen {};
        class xConnect {};
        class xSocketClose {};
        class xStopSockets {};
        class xBoundary {};
        class xRequired {};

    private:
        int                     iSocketService;
        int                     iPortService;
        char                    pszAddressService [ 32 ];
        struct sockaddr_in      addrServer;

};

#endif /* #if !defined ( _CLSAUTHORIZE_H_ ) */
