/*
   $Id: clsAuthorize.cpp,v 1.2 1998/08/25 03:20:34 josh Exp $

   NAME
    clsAuthorize.cpp    -- 
 
   DESCRIPTION
   This module contains all of the includes and class methods to implement
   the clsAuthorize.  This class will perform the tasks necessary to
   automatically validate credit cards.
 
 
   NOTES
   Refer to hardcopy documentation provided by Wells Fargo and FDMS as the
   'source of truth' for field and message definition required to validate
   credit cards.

   AUTHOR
   Matthew Paul Houseman

   MODIFIED
   $Log: clsAuthorize.cpp,v $
   Revision 1.2  1998/08/25 03:20:34  josh
   E105 integration; includes E104_prod and E102_SECURE

   Revision 1.1.8.1  1998/07/08 01:20:46  wwen
   merged E102 with E100_SECURE and E102_PROD.

   Revision 1.1.6.1  1998/06/26 01:51:30  wwen
   merged the codes on secure server with E100.

   Revision 1.12.2.1  1998/06/10 18:16:27  sam
   Initial Checkin.


*/
#include "eBayKernel.h"
#if !defined ( _CLSAUTHORIZE_CPP_ )
#define _CLSAUTHORIZE_CPP_

/********************************Includes**********************************/

#include "clsAuthorize.h"

/********************************Constants************************************/

/********************************Typedefs*************************************/

/********************************Variables************************************/

/********************************Prototypes***********************************/

/********************************Begin Module*********************************/

/********************* clsAuthorize::clsAuthorize() **************************
Name

  clsAuthorize::clsAuthorize() -- Class constructor

Description
  This method is the class constructor which will open a socket and connect
  to the service on the FDMS host machine.

Exceptions
  xGetEnv       - Expected environment variable not found
  xatoi         - Failure on ASCII string to integer conversion
  xStartSockets - Failure on socket subsystem startup
  xConnect      - Failure on connect to FDMS host

Notes

******************************************************************************/

clsAuthorize::clsAuthorize ( )
{
    int                 iSts;                   // Return status
    char                *pszEnvString;          // Environment pointer

    /***************************************************
     * Get the address of the eBay<==>FDMS server.     *
     * The address should be in the form:  "127.0.0.1" *
     ***************************************************/
    pszEnvString = getenv ( "eBayFDMSAddress" );
    if ( pszEnvString == NULL )
    {
        throw xGetEnv ( );
    }
    strcpy ( pszAddressService, pszEnvString );

    /************************************************************
     * Get the port for the service on the eBay<==>FDMS server. *
     ************************************************************/
    pszEnvString = getenv ( "eBayFDMSPort" );
    if ( pszEnvString == NULL )
    {
        throw xGetEnv ( );
    }

    /****************************************************
     * If the eBayFDMSPort environment variable         *
     * can't be converted, atoi ( ) will return a zero. *
     ****************************************************/
    iPortService = atoi ( pszEnvString );
    if ( iPortService == 0 )
    {
        throw xatoi ( );
    }

    /***************************
     * Start the socket layer. *
     ***************************/
    EBAY_STARTSOCKETS_M ( iSts );
    if ( iSts != 0 )
    {
        throw xStartSockets ( );
    }

    /****************************************
     * Initialize and stuff the addrServer. *
     ****************************************/
    memset ( &addrServer, 0, sizeof ( addrServer ) );
    addrServer.sin_family      = AF_INET;
    addrServer.sin_addr.s_addr = inet_addr ( pszAddressService );
    addrServer.sin_port        = htons ( iPortService );

} /* clsAuthorize::clsAuthorize() */


/********************* clsAuthorize::~clsAuthorize() *************************
Name

  clsAuthorize::~clsAuthorize() -- Class destructor

Description
  This method is the class destructor which will close the sockets.

Exceptions
  xSocketClose  - Failure to close socket

Notes

******************************************************************************/

clsAuthorize::~clsAuthorize ( )
{
    int         iSts;

    if ( iSocketService != -1 )
    {
        EBAY_CLOSESOCKET_M ( iSocketService, iSts );
        if ( iSts == -1 )
        {
            throw xSocketClose ( );
        }
    }

} /* clsAuthorize::~clsAuthorize() */


/**************** clsAuthorize::DispatchAuthorizationRequest() **************
Name

  clsAuthorize::DispatchAuthorizationRequest() -- Dispatch the authorization
                                                  request message

Description
  This method will dispatch the authorization request message to FDMS.

Exceptions
    00          Approved
    01          Refer to card issuer
    02          Refer to card issuer's special condition
    03          Invalid merchant
    04          Pick up card
    05          Do not honor
    06          Error (Check acceptance only)
    07          Pick up card, special condition
    08          Honor with identification
    12          Invalid transaction
    13          Invalid amount
    14          Invalid card number (no such number)
    15          No such issuer
    19          Re-enter transaction
    30          Format error
    41          Pick up card (lost card)
    43          Pick up card (stolen card)
    51          Not sufficient funds
    52          No checking account
    53          No savings account
    54          Expired card
    55          Incorrect PIN
    57          Transaction not permitted to cardholder
    58          Transaction not permitted to terminal
    61          Exceeds withdrawl amount limit
    62          Restricted card
    65          Exceeds withdrawl count limit
    75          Allowable number of PIN tries exceeded
    78          Invalid/non-existent account specified (general)
    85          Not declined
    91          Switch or issuer system inoperative
    92          Unable to route transaction
    94          Duplicate transaction detected
    96          System error

Notes

******************************************************************************/

int clsAuthorize::DispatchAuthorizationRequest ( char *pszAccountNumberInput,
                                                 char *pszTransAmountInput,
                                                 char *pszSystemTraceInput,
                                                 char *pszCardExpDateInput,
                                                 char *pszBillingAddrInput,
                                                 bool  bIsBatch )
{
    char        pszBufferMessage [ 64 ];        // Communication buffer
    int         iNumberOfBytes;                 // Bytes on send()/recv()
    int         iBytesRead;
    int         iSts;

    /********************
     * Open the socket. *
     ********************/
    iSocketService = socket ( AF_INET, SOCK_STREAM, 0 );
    if ( iSocketService == -1 )
    {
        throw xSocketOpen ( );
    }

    /**************************
     * Connect to the server. *
     **************************/
    iSts = connect ( iSocketService,
                     ( struct sockaddr * ) &addrServer,
                     sizeof ( addrServer ) );
    if ( iSts == -1 )
    {
        EBAY_CLOSESOCKET_M ( iSocketService, iSts );
        throw xConnect ( );
    }

    /**************************
     * Send the message code. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, EBAY_AUTHORIZATION );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /****************************
     * Send the account number. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszAccountNumberInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /********************************
     * Send the transaction amount. *
     ********************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszTransAmountInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /**************************
     * Send the system trace. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszSystemTraceInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /******************************
     * Send the card expiry date. *
     ******************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszCardExpDateInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /*****************************
     * Send the billing address. *
     *****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszBillingAddrInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /****************************
     * Send the execution mode. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    if ( bIsBatch )
    {
        strcpy ( pszBufferMessage, EBAY_BATCH );
    }
    else
    {
        strcpy ( pszBufferMessage, EBAY_REALTIME );
    }
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /*************************************
     * Get the response from the server. *
     *************************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    iNumberOfBytes = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );

RAISE_EXCEPTION:

    EBAY_CLOSESOCKET_M ( iSocketService, iSts );
    if ( strcmp ( pszBufferMessage, "0" ) == 0 )
    {
        return ( 0 );
    }
    else if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
    {
        return ( -1 );
    }
    else
    {
        iSts = atoi ( pszBufferMessage );
        if ( iSts == 0 )
        {
            iSts = -1;
        }
    }
    return ( iSts );

} /* clsAuthorize::DispatchAuthorizationRequest() */


/******************* clsAuthorize::DispatchReversalRequest() *****************
Name

  clsAuthorize::DispatchReversalRequest() -- Dispatch the reversal request
                                             message

Description
  This method will dispatch the reversal request message to FDMS.

Exceptions

Notes

******************************************************************************/

int clsAuthorize::DispatchReversalRequest ( char *pszAccountNumberInput,
                                            char *pszTransAmountInput,
                                            char *pszSystemTraceInput,
                                            char *pszCardExpDateInput,
                                            char *pszBillingAddrInput,
                                            bool  bIsBatch )
{
    char        pszBufferMessage [ 64 ];        // Communication buffer
    int         iNumberOfBytes;                 // Bytes on send()/recv()
    int         iBytesRead;
    int         iSts;

    /**************************
     * Send the message code. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, EBAY_REVERSAL );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /****************************
     * Send the account number. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszAccountNumberInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /********************************
     * Send the transaction amount. *
     ********************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszTransAmountInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /**************************
     * Send the system trace. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszSystemTraceInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /******************************
     * Send the card expiry date. *
     ******************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszCardExpDateInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /*****************************
     * Send the billing address. *
     *****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, pszBillingAddrInput );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );
    if ( iNumberOfBytes != -1 )
    {
        iBytesRead = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );
        if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
        {
            goto RAISE_EXCEPTION;
        }
    }

    /****************************
     * Send the execution mode. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    if ( bIsBatch )
    {
        strcpy ( pszBufferMessage, EBAY_BATCH );
    }
    else
    {
        strcpy ( pszBufferMessage, EBAY_REALTIME );
    }
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /*************************************
     * Get the response from the server. *
     *************************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    iNumberOfBytes = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );

RAISE_EXCEPTION:

    EBAY_CLOSESOCKET_M ( iSocketService, iSts );
    if ( strcmp ( pszBufferMessage, "00" ) == 0 )
    {
        return ( 0 );
    }
    else if ( strcmp ( pszBufferMessage, "FAILURE" ) == 0 )
    {
        return ( -1 );
    }
    else
    {
        iSts = atoi ( pszBufferMessage );
        if ( iSts == 0 )
        {
            iSts = -1;
        }
    }
    return ( iSts );

} /* clsAuthorize::DispatchReversalRequest() */


/**************** clsAuthorize::DispatchFDMSStandardEchoTest() **************
Name

  clsAuthorize::DispatchFDMSStandardEchoTest() -- Dispatch the FDMS standard
                                                  echo test message 

Description
  This method will dispatch the FDMS Standard Echo Test message to FDMS.

Exceptions

Notes

******************************************************************************/

int clsAuthorize::DispatchFDMSStandardEchoTest ( bool bIsBatch )
{
    char        pszBufferMessage [ 64 ];        // Communication buffer
    int         iNumberOfBytes;                 // Bytes on send()/recv()

    /**************************
     * Send the message code. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, EBAY_FDMS_TEST );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /****************************
     * Send the execution mode. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    if ( bIsBatch )
    {
        strcpy ( pszBufferMessage, EBAY_BATCH );
    }
    else
    {
        strcpy ( pszBufferMessage, EBAY_REALTIME );
    }
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /*************************************
     * Get the response from the server. *
     *************************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    iNumberOfBytes = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );

    return ( 0 );

} /* clsAuthorize::DispatchFDMSStandardEchoTest() */

/**************** clsAuthorize::DispatchVISAStandardEchoTest() **************
Name

  clsAuthorize::DispatchVISAStandardEchoTest() -- Dispatch the VISA standard
                                                  echo test message 

Description
  This method will dispatch the VISA Standard Echo Test message to FDMS.

Exceptions

Notes

******************************************************************************/

int clsAuthorize::DispatchVISAStandardEchoTest ( bool bIsBatch )
{
    char        pszBufferMessage [ 64 ];        // Communication buffer
    int         iNumberOfBytes;                 // Bytes on send()/recv()

    /**************************
     * Send the message code. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, EBAY_VISA_TEST );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /****************************
     * Send the execution mode. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    if ( bIsBatch )
    {
        strcpy ( pszBufferMessage, EBAY_BATCH );
    }
    else
    {
        strcpy ( pszBufferMessage, EBAY_REALTIME );
    }
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /*************************************
     * Get the response from the server. *
     *************************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    iNumberOfBytes = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );

    return ( 0 );

} /* clsAuthorize::DispatchVISAStandardEchoTest() */

/******************* clsAuthorize::DispatchLogonMessageTest() *****************
Name

  clsAuthorize::DispatchLogonMessageTest() -- Dispatch the logon test message

Description
  This method will dispatch the login test message to FDMS.

Exceptions

Notes

******************************************************************************/

int clsAuthorize::DispatchLogonMessageTest ( bool bIsBatch )
{
    char        pszBufferMessage [ 64 ];        // Communication buffer
    int         iNumberOfBytes;                 // Bytes on send()/recv()

    /**************************
     * Send the message code. *
     **************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    strcpy ( pszBufferMessage, EBAY_LOGON_TEST );
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /****************************
     * Send the execution mode. *
     ****************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    if ( bIsBatch )
    {
        strcpy ( pszBufferMessage, EBAY_BATCH );
    }
    else
    {
        strcpy ( pszBufferMessage, EBAY_REALTIME );
    }
    iNumberOfBytes = send ( iSocketService,
                            ( char * ) pszBufferMessage,
                            strlen ( pszBufferMessage ) + 1,
                            0 );

    /*************************************
     * Get the response from the server. *
     *************************************/
    memset ( pszBufferMessage, 0, sizeof ( pszBufferMessage ) );
    iNumberOfBytes = recv ( iSocketService,
                            pszBufferMessage,
                            sizeof ( pszBufferMessage ),
                            0 );

    return ( 0 );

} /* clsAuthorize::DispatchLogonMessageTest() */

#endif /* #if !defined ( _CLSAUTHORIZE_CPP_ ) */
