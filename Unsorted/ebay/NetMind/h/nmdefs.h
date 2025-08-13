/*MAN
MODULE 
     nmdefs.h
DESCRIPTION
     Constant definitions.
COPYRIGHT
     Copyright Netmind Services 1996-1997
*/

#ifndef _NM_DEFS_H_
#define _NM_DEFS_H_
#include "nmexport.h"

#define MINDER_VERSION             "3.0 B2"
#define MINDER_VERSION_NUMBER      0x00030000
#define MINDER_USER_AGENT          "Mozilla/2.0 (compatible; NetMind-Minder/3.0 B1)"
#define MINDER_WEB_SERVER          "Netmind-Responder/3.0 B1"
#define MINDER_NAME                "NetMind Minder"

#define WIN_CLIENT_VERSION         "1.0 B1"
#define WIN_CLIENT_VERSION_NUMBER   0x00010000
#define WIN_CLIENT_USER_AGENT      "NetMind-Sync/1.0"

#define NETMIND_HOME_PAGE          "http://www.netmind.com"
#define RESPONDER_PATH             "/responder"
#define PROXY_PATH                 "/proxy"
#define EXTERNAL_BIN_PATH          "/ext-bin"
#define REDIRECT_PATH              "/go"

#define MAX_REDIRECT_DATA          6

#define SECONDS_PER_MINUTE         60
#define MINUTES_PER_HOUR           60
#define SECONDS_PER_HOUR           (MINUTES_PER_HOUR * SECONDS_PER_MINUTE)
#define HOURS_PER_DAY              24
#define SECONDS_PER_DAY            (HOURS_PER_DAY * SECONDS_PER_HOUR)

#define INITIAL_TIME               0
#define INITIAL_SERIAL             0

#define DEFAULT_TTL            		 3600
#define DEFAULT_THROTTLE					 1            		 /* 1 second */
#define DEFAULT_TIMEOUT            60
#define DEFAULT_RETRIES            2
#define DEFAULT_HEARTBEAT          4
#define DEFAULT_CONFIG_FILE        "netmind.cfg"
#define DEFAULT_LOG_OUTPUT         "minder.log"
#define DEFAULT_LOG_LEVEL          2
#define DEFAULT_TEMPLATES          "templates"
#define DEFAULT_ERRORS             "errors"
#define DEFAULT_WEB_DIR            "web"
#define DEFAULT_WEB_INDEX          "index.html"
#define DEFAULT_WEB_SINDEX         "index.shtml"
#define DEFAULT_WEB_ACL            ".acl"

#define DEFAULT_FETCH_DYING        5
#define DEFAULT_FETCH_DEAD         20
#define DEFAULT_FETCH_MAX_REDIRECTS 5
#define DEFAULT_FETCH_MAX_SUBLEVELS 2
#define DEFAULT_FETCH_CLIENT_PULL_PAUSE 10
#define DEFAULT_FETCH_CACHE_PERIOD 2

#define DEFAULT_MAIL_DEAD          5
#define DEFAULT_MAIL_CACHE_PERIOD  2
#define DEFAULT_HOST_DEAD          5
#define DEFAULT_HOST_DEAD_PERIOD   1

#define DEFAULT_SEGMENT            0
#define DEFAULT_THREADS            8

#define MIN_SLEEP_PERIOD           5

#define SYS_MINDER_ID              0
#define SYS_RESPONDER_ID           20
#define SYS_NORMAL                 0

#define REG_SERIAL_OFFSET          10000000

#define MIME_DIR                   "mime"
#define NON_MIME_DIR               "non_mime"
#define PLAIN_TEXT_EXT             ".txt"
#define ENRICHED_TEXT_EXT          ".enr"
#define HTML_TEXT_EXT              ".html"
#define PERL_EXT                   ".pl"
#define JAVA_EXT                   ".class"

/*
 * Host caching policy bits:
 * |    7   |    6   |    5   |    4   |    3   |    2   |   1    |    0   |
 * |        |        |        |        |                 |        |use ttl |
 */
#define HOST_USE_TTL 0x0001

/*
 * USER Preference bits:
 * |   15   |   14   |   13   |   12   |   11   |   10   |    9   |    8   | 
 * |Group   |Not used|Not used|Not used|Pager   |Username|MIME text type   |
 * |        |        |        |        |created |created |                 |
 *
 * |    7   |    6   |    5   |    4   |    3   |    2   |   1    |    0   |
 * |        |Change  |Move    |Dead    |Include contents |Include |MIME    |
 * |        |notice  |notice  |notice  |                 |title   |email   |
 */

#define PREF_GROUP                 0x8000
#define PREF_PAGEREMAIL_CREATED    0x0800
#define PREF_USERNAME_CREATED      0x0400
#define PREF_MIME_TEXT             0x0300
#define PREF_MIME_TEXT_POS         8
#define PREF_MIME_TEXT_PLAIN       0
#define PREF_MIME_TEXT_ENRICHED    1
#define PREF_MIME_TEXT_HTML        2
#define PREF_MIME_TEXT_MAX         3

/*
 * REG Preference bits:
 * |   15   |   14   |   13   |   12   |   11   |   10   |    9   |    8   | 
 * |Frame   |Mind-it |Upgrade |Mind-it |Extranet|Diff    |Pager   |Email   |
 * |        |Central |        |button  |        |notice  |Enabled |Enabled |
 * |        |Changed |        |        |        |        |        |        |
 *
 *
 * |    7   |    6   |    5   |    4   |    3   |    2   |   1    |    0   |
 * |Not used|Change  |Move    |Dead    |Include contents |Include |MIME    |
 * |        |notice  |notice  |notice  |                 |title   |email   |
 */

#define PREF_FRAMES                0x8000    
#define PREF_CHANGED               0x4000
#define PREF_UPGRADE               0x2000    /* filter upgrade */
#define PREF_MINDIT                0x1000    /* Mind-it button registration */
#define PREF_EXTRANET              0x0800    /* reverse proxy registration */
#define PREF_NOTICE_DIFF           0x0400
#define PREF_PAGER_ENABLED         0x0200
#define PREF_EMAIL_ENABLED         0x0100

/* Defines for both REG and USER Preference bits (0 - 7): */

#define PREF_NOTICE_CHANGE         0x0040
#define PREF_NOTICE_MOVE           0x0020
#define PREF_NOTICE_DEAD           0x0010
#define PREF_DOC_CONTENTS          0x000C
#define PREF_DOC_CONTENTS_POS      2
#define PREF_DOC_CONTENTS_NONE     0
#define PREF_DOC_CONTENTS_ATTACH   1
#define PREF_DOC_CONTENTS_INLINE   2
#define PREF_DOC_CONTENTS_MAX      3
#define PREF_DOC_TITLE             0x0002
#define PREF_MIME                  0x0001

/* Virtual bits act as flags or name space for Preference bits */

#define PREF_USER_PREFERENCES      0x00040000
#define PREF_NEW_EMAIL             0x00020000
#define PREF_UPDATE_PERIOD         0x00010000

/* Aggregated Preference bits */

#define DEFAULT_NOTICE_PREF \
  PREF_NOTICE_DEAD | PREF_NOTICE_MOVE | PREF_NOTICE_CHANGE | PREF_DOC_TITLE

#define USER_PREF \
  PREF_USER_PREFERENCES | PREF_NEW_EMAIL | PREF_UPDATE_PERIOD | \
	PREF_GROUP | PREF_PAGEREMAIL_CREATED | PREF_USERNAME_CREATED | \
	PREF_MIME_TEXT | \
	DEFAULT_NOTICE_PREF | PREF_DOC_CONTENTS | PREF_MIME
	
#define REG_PREF \
  PREF_UPDATE_PERIOD | PREF_FRAMES | PREF_CHANGED | PREF_UPGRADE | \
	PREF_MINDIT | PREF_EXTRANET | PREF_NOTICE_DIFF | \
	PREF_PAGER_ENABLED | PREF_EMAIL_ENABLED | \
	DEFAULT_NOTICE_PREF | PREF_DOC_CONTENTS | PREF_MIME

#define DEFAULT_PERIOD             1

#define PREF_GET(pref, mask, pos)      (((pref) & (mask)) >> (pos))
#define PREF_SET(pref, mask, pos, val) \
  (pref) &= ~(mask); \
  if(val != 0) { \
    (pref) |= ((mask) & ((val) << (pos))); \
  }

/* serial number masks */
#define NM_SERIAL_TRANSIENT   0x80000000

/* synchronization status flags */
#define SYNCH_IN_PROGRESS   0x0001
#define SYNCH_MSIE_ORDER    0x0002

/*user group*/
#define USER_GROUP_PREF_MASK          0x0000FFFF
#define USER_GROUP_MODE_MASK          0x00F00000
#define USER_GROUP_SHARE_MODE         0x00100000
#define USER_GROUP_LOCK_DOWN_MODE     0x00200000
#define USER_GROUP_UPDATE_PERIOD_MASK 0x000F0000
#define USER_GROUP_PERSONAL_VIEW      0x04
#define USER_GROUP_SHARE_MODE_TAG     "SHARE_MODE"
#define USER_GROUP_LOCK_DOWN_MODE_TAG "LOCK_DOWN_MODE"

#define GROUP_PREF_DONT_CARE_MASK            0xFF000000  
#define GROUP_PAGE_CHANGE_PREF_DONT_CARE     0x01000000
#define GROUP_PAGE_MOVE_PREF_DONT_CARE       0x02000000
#define GROUP_PAGE_DEAD_PREF_DONT_CARE       0x04000000
#define GROUP_EMAIL_ENABLED_PREF_DONT_CARE   0x08000000
#define GROUP_PAGER_ENABLED_PREF_DONT_CARE   0x10000000
#define GROUP_DOC_CONTENTS_PREF_DONT_CARE    0x20000000
#define GROUP_DOC_DIFF_PREF_DONT_CARE        0x40000000


/* URL flags */
#define URL_AUTHENTICATE    0x0001
#define URL_DATA_REQUIRED   0x1000
/*
 * common macros--here just for convenience
 */
#define NEW(type)           (type *)calloc(1, sizeof(type))

#define SEG_DELIVERED(seg) \
  ((seg)->seg_delivered[0] + (seg)->seg_delivered[1] + (seg)->seg_delivered[2])

#ifdef ENFORCE_WITH_STACK_TRACE

#ifdef __cplusplus
extern "C" {
#endif
NMEXPORT void nm_log_stack_trace(int, const char *);
#ifdef __cplusplus
}
#endif

#define ENFORCE(x) if(!(x)) { nm_log_stack_trace(__LINE__, #x); return FALSE; }
#define ENFORCE1(x, a) if(!(x)) { a, nm_log_stack_trace(__LINE__, #x); return FALSE; }
#else
#define ENFORCE(x) {if(!(x)) return FALSE;}
#define ENFORCE1(x, a) if(!(x)) { a; return FALSE; }
#endif

#endif /* _NM_DEFS_H_ */
