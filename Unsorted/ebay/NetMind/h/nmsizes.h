/*MAN
MODULE 
     nmsizes.h
DESCRIPTION
     Variable and record size limits.
COPYRIGHT
     Copyright Netmind Services 1996-1997
CHANGE LOG
		 06 Nov 97 SR Changed MAX_HOST_FUTURE to 1 to add host_port
     17 Nov 97 AN Changed MAX_URL_FUTURE to 11 to add url_bytes
     04 Dec 97 AN Changed MAX_USER_FUTURE to 10; added MAX_POSTCODE
     16 Dec 97 AN Added MAX_TREE_NODES
     18 Dec 97 AN Changed MAX_REG_FUTURE to 4 to add reg_mnemonic
     05 Jan 98 SR Changed MAX_URL_FUTURE to 10 to add url_top_checksum
     29 Jan 98 AN Changed MAX_REG_FUTURE to 3 to add reg_priority
     08 Apr 98 AN Removed MAX_FILTER_EXPR
		 16 Apr 98 SR Reset MAX_HOST_FUTURE to 5
     04 May 98 AN Decreased MAX_USER_FUTURE to 6 and added MAX_USER_CUSTOM
     06 May 98 AN Decreased MAX_URL_FUTURE to 6
     18 May 98 SR Set MAX_SUBSYSTEMS to 5
     18 May 98 SR Set MAX_REG_FUTURE to 2 with the addition of reg_last_checksum
     03 Jul 98 SR Added url_last_content - changed MAX_URL_FUTURE to 4 
     13 Aug 98 SR Bug fix - Changing MAX_URL_FUTURE to 6 since stuff was removed
     26 Aug 98 AN Added MAX_REG_HISTORY
     10 Nov 98 SR Changed MAX_USER_FUTURE to 8 (from 3)
     18 Nov 98 SR Added MAX_USERNAME (128)
     01 Feb 99 TH Changed MAX_USER_FUTURE to 1 (from 2) to add
                  reg_num_notifications

*/

#ifndef _NM_SIZES_H_
#define _NM_SIZES_H_

/*
 * Database record field sizes
 */
#define MAX_PROGRAM          16
#define MAX_SYS_STATS        7
#define MAX_URL              1024
#define MAX_TITLE            128
#define MAX_KEYWORDS         128
#define MAX_EMAIL            64
#define MAX_PASSWORD         16
#define MAX_CLASS_NAME       48
#define MAX_SEG_MEDIA        3     /* same as PREF_MIME_TEXT_MAX */ 
#define MAX_SEG_FILE         128
#define MAX_SEG_CONTENT      2048
#define MAX_FILTER_PATTERN   512
#define MAX_ADDR             5		/* max IP addresses which can be cached */
#define MAX_HOSTNAME         256	/* host name in NmHost */
#define MAX_POSTCODE         12
#define MAX_USER_CUSTOM1     32
#define MAX_USER_CUSTOM2     16
#define MAX_TREE_NODES       256
#define MAX_REG_HISTORY      2
#define MAX_USERNAME				 MAX_EMAIL 

/*
 * Unused database fields reserved for future use
 * Adjust these so the total record length is 8-byte aligned
 */
#define MAX_SYS_FUTURE       7
#define MAX_URL_FUTURE       6
#define MAX_USER_FUTURE      8
#define MAX_REG_FUTURE       1
#define MAX_FILTER_FUTURE    5 
#define MAX_SEG_FUTURE       16
#define MAX_CLASS_FUTURE     6
#define MAX_TREE_FUTURE      12
#define MAX_HOST_FUTURE      5

/* 
 * Other limits
 */
#define MAX_ULONG            0xffffffff
#define MAX_DIGITS           11	   /* max. unsigned long digits */
#define MAX_BOOL             6	   /* "true" or "false" */
#define MAX_PATHSIZE         1024	 /* should use pathconf here */
#define MAX_TIME             26	   /* per 'ctime' man page */
#define MAX_DATE             64	   /* for standard email date */   
#define MAX_STRING           2048	 /* arbitrary max. string size */
#define MAX_SCHEME           32	   /* http, ftp, gopher, etc */
#define MAX_KEY              128   /* max. size of list key */
#define MAX_IP_ADDR          13    /* aaa.bbb.ccc.ddd */
#define MAX_HTTP_HEADER      40    /* max. number of HTTP header fields */
#define MAX_FETCH_RESULT     50    /* max. fetch header fields + above */
#define MAX_HTTP_HEADER_SIZE 1024  /* max. string size of HTTP reply header */
#define MAX_SUB_URL          25    /* max sub urls which can be monitored */
/* mk... 1012 */
#define MAX_URL_NOTE         400   /* max nimber of url_note characters */

#define MAX_HEARTBEAT        10
#define MAX_THREADS          1000
#define MAX_SUBSYSTEMS			 5

#ifdef LINUX
#define MAX_OPEN_FILES       256
#else
#define MAX_OPEN_FILES       1024
#endif

#endif

