/*MAN
MODULE 
     nmtypes.h
DESCRIPTION
     Common types used by Netmind clients and servers alike.
 
     Types commented "INIT" have initialization functions
     ("constructors") defined in "nmtypes.c".
     Types commented "XDR" are externalizable and have 
     XDR routines are defined in "nmxdr.c".
 
     NOTE: Database type restrictions:
       (1) serial numbers must be the first element in the structure
       (2) names, if defined, must be the second element in the structure
     These restrictions simplify vector access.    
COPYRIGHT
     Copyright Netmind Services 1996-1997
UPDATES
     13 Aug 1997  AN  Added data to NmError
     19 Sep 1997  AN  Added UNKNOWN_EXIT; removed responder tags
     25 Sep 1997  SR  Added URL ip address caching to the database record
     04 Oct 1997  SR  Added support for the host relation NmHost 
     17 Oct 1997  SR  Created the NmLicense type 
     18 Dec 1997  AN  Added reg_mnemonic
     19 Dec 1997  AN  Added MNEMONIC_TAG
     28 Jan 1998  AN  Added host_fail_time and reg_priority
     30 Jan 1998  AN  Added user_synch_status
     03 Apr 1998  AN  Added reg_post_data
     06 Apr 1998  AN  Added reg_credentials and filter_pattern
     08 Apr 1998  AN  Merged filter_expr int filter_pattern
     15 Apr 1998  AN  Renamed reg_mnemonic to reg_desc and made a blob
     04 May 1998  AN  Added user_custom
     06 Apr 1998  AN  Added reg_cookie
     08 Apr 1998  AN  Added url_flags
     09 Jun 1998  AD  Added CAPTURE_FILTER
     19 Jun 1998  AN  Reduced user_email in size and added user_group
     23 Jun 1998  SR  Added NM_URLMAPPING for reverse proxy support 
     24 Jun 1998  AN  Added user_type
     02 Jul 1998  AN  Added user_level
     03 Jul 1998  SR  Added url_last_content for diff support
     08 Jul 1998  SR  Added GROUP_TAG in NmTag
     09 Jul 1998  AN  Added user_tree and url_note
     10 Nov 1998  SR  Changed user_email to user_name and also user_email is 
											now a blob
     12 Nov 1998  RT  Added Tags for MESSAGE_CHARSET, DOC_VERB, MESSAGE_BODY,
                      and MESSAGE_ENCODING.
     23 Nov 1998  RT  remove references to regexp state information
*/

#ifndef _NM_TYPES_H_
#define _NM_TYPES_H_

#include <sys/types.h>
#include <stdio.h>
#ifdef WIN32_GUI
#include <windows.h>          /* for HTREEITEM */ 
#include <commctrl.h>         /* for HTREEITEM */ 
#endif

#include "nmport.h"
#include "pig.h"
#include "nmsizes.h"
#include "nmexport.h"

#ifdef __cplusplus
extern "C" {
#endif

/* typedefs */

/* enums */
typedef enum {
  SYS_FETCH_STATS,		/* number of fetch attempts */
  SYS_MISS_STATS,		  /* % misses */
  SYS_MAIL_STATS,		  /* number of mail attempts */
  SYS_BOUNCE_STATS,		/* % bounces */
  SYS_D_BOUNCE_STATS, /* % direct mail bounces */
  SYS_R_BOUNCE_STATS,	/* % routed mail bounces */
  SYS_UNUSED_STATS,		/* % routed mail bounces */
  SYS_MAX_STATS
} NmSysStat;

typedef enum {
  NORMAL_EXIT,
  ERROR_EXIT,
  CRASH_EXIT,
  UNKNOWN_EXIT
} NmExitStatus;

/*
 * Each data structure has an NmType "type code."
 * Apart from uniquely identifying the type of the structure,
 * type codes improve the type safety of operations involving
 * the corresponding structures, in particular, vector and
 * externalization functions.
 * 
 * Add a new NmType whenever you add a new data structure
 * that needs to be externalized, made persistent, or 
 * used within an NmVector.
 */
typedef enum {
  /* native types */
  NM_NULL            = 0,
  NM_OCTET           = 1,
  NM_CHAR            = 2,
  NM_WCHAR           = 3,
  NM_SHORT           = 4,
  NM_LONG            = 5,
  NM_LONG_LONG       = 6,
  NM_STRING          = 7,
  NM_WSTRING         = 8,
  NM_FLOAT           = 9,
  
  /* structured types */
  NM_VECTOR          = 101,
  NM_NAMED_VALUE     = 102,
  NM_NUMBERED_VALUE  = 103,
  NM_VIEW            = 105,
  NM_NUMBER          = 106,

  /* database types */
  NM_SYS             = 201,
  NM_URL             = 202,
  NM_USER            = 203,
  NM_REG             = 204,
  NM_SEG             = 205,
  NM_FILTER          = 206,
  NM_CLASS           = 207,
  NM_TREE            = 208,
  NM_HOST            = 209,

	/* other types */
  NM_URLMAPPING      = 210
} NmType;

/*
 * Hypertext Transport Protocol status codes and classes
 *
 * 1xx: Informational - Request received, continuing process
 * 2xx: Success - The action was successfully received, understood, and accepted
 * 3xx: Redirection - Further action must be taken in order to complete the request
 * 4xx: Client Error - The request contains bad syntax or cannot be fulfilled
 * 5xx: Server Error - The server failed to fulfill an apparently valid request
 */

typedef enum {
  HTTP_INFO         = 1,
  HTTP_SUCCESS      = 2,
  HTTP_REDIRECT     = 3,
  HTTP_CLIENT_ERROR = 4,
  HTTP_SERVER_ERROR = 5,
  NM_WARNING        = 10,
  NM_USER_ERROR     = 11,
  NM_FATAL_ERROR    = 12
} NmErrorClass;

typedef enum {
  HTTP_CONTINUE                      = 100,
  HTTP_SWITCHING_PROTOCOLS           = 101,

  HTTP_OK                            = 200,
  HTTP_CREATED                       = 201,
  HTTP_ACCEPTED                      = 202,
  HTTP_NON_AUTHORITATIVE_INFORMATION = 203,
  HTTP_NO_CONTENT                    = 204,
  HTTP_RESET_CONTENT                 = 205,
  HTTP_PARTIAL_CONTENT               = 206,

  HTTP_MULTIPLE_CHOICES              = 300,
  HTTP_MOVED_PERMANENTLY             = 301,
  HTTP_MOVED_TEMPORARILY             = 302,
  HTTP_SEE_OTHER                     = 303,
  HTTP_NOT_MODIFIED                  = 304,

  HTTP_BAD_REQUEST                   = 400,
  HTTP_UNAUTHORIZED                  = 401,
  HTTP_PAYMENT_REQUIRED              = 402,
  HTTP_FORBIDDEN                     = 403,
  HTTP_NOT_FOUND                     = 404,
  HTTP_METHOD_NOT_ALLOWED            = 405,
  HTTP_NOT_ACCEPTABLE                = 406,
  HTTP_PROXY_AUTHENTICATION_REQUIRED = 407,
  HTTP_REQUEST_TIME_OUT              = 408,
  HTTP_CONFLICT                      = 409,
  HTTP_GONE                          = 410,
  HTTP_LENGTH_REQUIRED               = 411,
  HTTP_PRECONDITION_FAILED           = 412,
  HTTP_REQUEST_ENTITY_TOO_LARGE      = 413,
  HTTP_REQUEST_URI_TOO_LARGE         = 414,
  HTTP_UNSUPPORTED_MEDIA_TYPE        = 415,

  HTTP_INTERNAL_SERVER_ERROR         = 500,
  HTTP_NOT_IMPLEMENTED               = 501,
  HTTP_BAD_GATEWAY                   = 502,
  HTTP_SERVICE_UNAVAILABLE           = 503,
  HTTP_GATEWAY_TIME_OUT              = 504,
  HTTP_VERSION_NOT_SUPPORTED         = 505,

	/* NTLM Client or Server side errors */
	NTLM_NOT_OWNER										 = 480,		/* caller does not own creds */
	NTLM_INVALID_TOKEN								 = 481,		/* invalid token supplied */
	NTLM_LOGON_DENIED									 = 482,		/* logon attempt failed */
	NTLM_NO_CREDENTIALS								 = 483,		/* credentials not recognized */
	NTLM_CONTEXT_EXPIRED							 = 484,		/* context expired */
	NTLM_SECURITY_PKG_NOT_FOUND				 = 485,		/* ntlm security not supported */

  /* URL-minder client status codes--our extensions */
  FETCH_NO_CONTENT                   = 490,
  FETCH_TIMED_OUT                    = 491,
  FETCH_UNSUPPORTED_PROTOCOL         = 492,
  FETCH_LOCAL_ERROR                  = 493,
  FETCH_UNKNOWN_HOST                 = 494,
  FETCH_CONNECTION_REFUSED           = 495,
  FETCH_UNEXPECTED_RESPONSE          = 496,
  FETCH_CONNECTION_ERROR             = 497,
  FETCH_EXCESSIVE_REDIRECTION        = 498,
  FETCH_FATAL_ERROR                  = 499,

	/* NTLM Server side errors */
	NTLM_NO_IMPERSONATION							 = 590,		/* no impersonation allowed */
} NmHttpStatus;

/*
 * replacement tags
 */
typedef enum {
  /* general */
  PROGRAM_TAG,
  VERSION_TAG,
  ADMIN_TAG,
  USER_TAG,
  NEW_USER_TAG,
  PASSWORD_TAG,
  EMAIL_TAG,
  NEW_EMAIL_TAG,
	USERNAME_TAG,
  URL_TAG,
  BASE_URL_TAG,
  NEW_URL_TAG,
  REG_TAG,
  SEGMENT_TAG,
  FILTER_TAG,
  CLASS_TAG,
  TREE_TAG,
  REGS_PER_URL_TAG,
  REGS_PER_USER_TAG,
  NUMBER_TAG,
  STRING_TAG,
  DATE_TAG,
  TIME_TAG,
  HOST_TAG,
  ERR_TAG,
  ERROR_TAG,

  /* document */
  DOC_TITLE_TAG,
  DOC_TYPE_TAG,
  DOC_ENCODING_TAG,
  DOC_DISPOSITION_TAG,
  DOC_NAME_TAG,
  DOC_CONTENTS_TAG,
  DOC_FREQUENCY_TAG,
  DOC_VERB_TAG,

  /* mail message */
  MESSAGE_CHARSET_TAG,
  MESSAGE_BODY_TAG,
  MESSAGE_ENCODING_TAG,
  
  /* minder only */
  SPONSOR_TAG,
  MNEMONIC_TAG,
  CHANGE_TAG,
	CUSTOM1_TAG,
  CUSTOM2_TAG,
  DIFF_CONTENT_TAG,
  URL_NOTE_TAG, /*mk... 1012 */
  LOCK_STATE_TAG,
  RESERVED2_TAG,
  RESERVED3_TAG,

	MAX_TAG
} NmTag;

/*
 * Caller IDs are used to identify the caller 
 * in database operations.  The CID is stored in
 * the database audit record along with the user ID (UID)
 * and the current time, each time a database record 
 * is updated.  This enables us to identify WHY a record
 * changed, not just when it changed and by whom.
 */
typedef enum {
  CID_NONE                                = 0,

  CID_MINDER_FETCH_OK                     = 1001,
  CID_MINDER_FETCH_ERROR                  = 1002,
  CID_MINDER_EMAIL_INVALID                = 1003,
  CID_MINDER_IMPRESSION_DELIVERED         = 1004,
  CID_MINDER_REG_INVALID                  = 1005,
  CID_MINDER_MAIL_CHANGE                  = 1006,
  CID_MINDER_USER_DEAD                    = 1007,
  CID_MINDER_MAIL_HOST_DEAD               = 1008,

  CID_RESPONDER_REGISTER                  = 2001,
  CID_RESPONDER_CANCEL                    = 2002,
  CID_RESPONDER_SET_PREF                  = 2003,
  CID_RESPONDER_SET_EMAIL                 = 2003,
  CID_RESPONDER_UPLOAD                    = 2005,
  CID_RESPONDER_DOWNLOAD                  = 2006,
  CID_RESPONDER_MAIL_CHANGE               = 2007,
  CID_RESPONDER_CANCEL_ALL                = 2008,
  CID_RESPONDER_USER_UPDATED              = 2009,
  CID_RESPONDER_ADD                       = 2010,
  CID_RESPONDER_EDIT                      = 2011,

  CID_DBEDIT                              = 3001
} NmCid;

/* error type */
typedef struct {
  char * major;
  ulong  minor;
  char * data;
} NmError;

typedef enum {
  TD_UNUSED,
	TD_RESUMED,
  TD_RUNNING,
  TD_FINISHED,
  TD_DATABASE_READ,
  TD_DATABASE_WRITE,
  TD_NETWORK_FETCH,
  TD_NETWORK_MAIL,
  TD_OTHER
} NmThreadState;

typedef enum {
  NO_FILTER,
  REGEX_FILTER,
  KEYWORD_FILTER,
  TEXT_FILTER,
  NUMBER_FILTER,
  BOUND_FILTER,
  SURROUND_FILTER,
  LINK_FILTER,
  CAPTURE_FILTER,
  EXTERNAL_FILTER
} NmFilter;

/* collection types */

/*
 * The NmNamedValue and NmNumberedValue 'value' is interpreted 
 * according to the 'type' field.
 * These essentially implements a generic type, much like a 
 * CORBA 'Any' type.
 */

/* INIT */
typedef struct {
  char * name;
  NmType type;
  void * value;
} NmNamedValue;

/* INIT, XDR */
typedef struct {
  ulong number;
  NmType type;
  void * value;
} NmNumberedValue;

typedef struct {
  NmType type;
  union {
    NmInt32 integer;
    NmDouble real;
  } value;
  ulong format;
  char * begin;
  char * end;
} NmNumber;

/* database types */
typedef struct {
  ulong    sys_id;          
  char     sys_program[MAX_PROGRAM];    
  ulong    sys_pid;         
  ulong    sys_started;
  ulong    sys_updated;
  ulong    sys_serial;
  ulong    sys_flags;       
  ulong    sys_stats[MAX_SYS_STATS];
  ulong    sys_future[MAX_SYS_FUTURE];
} DbSys;

/* INIT, XDR */
typedef struct {
  ulong    url_serial;     
  char     url_loc[MAX_URL]; 
  char     url_title[MAX_TITLE]; 
  char     url_keywords[MAX_KEYWORDS]; 
  ulong    url_created;
  ulong    url_checked;
  ulong    url_modified;
  ulong    url_detected;
  ulong    url_checksum;   
  ulong    url_length;
  NmUInt16   url_hits;
  NmUInt16   url_misses;
  NmUInt16   url_changes;
  NmUInt16   url_priority;
  ulong    url_bytes;
  ulong    url_last_checksum;
	ulong		 url_top_checksum;
  ulong    url_flags;
  ulong    url_owner;
  blob     url_note;
  ulong    url_future[MAX_URL_FUTURE];
} DbUrl;

/* INIT, XDR */
typedef struct {
  ulong    user_serial;   
  char     user_name[MAX_EMAIL];
  ulong    user_group;
  char     user_password[MAX_PASSWORD];
  NmUInt16   user_pref;      
  NmUInt16   user_period; 
  ulong    user_group_pref;
  ulong    user_created;   
  ulong    user_updated;
  ulong    user_host;
  NmUInt16   user_status;
  NmUInt16   user_fails;
  ulong    user_synch_status;
  uchar    user_sex;
  uchar    user_age;
  uchar    user_type;
  uchar    user_level;
  ulong    user_zip;
  ulong    user_os;
  ulong    user_browser;
  ulong    user_emailer;
  uchar    user_postcode[MAX_POSTCODE]; 
  blob     user_tree;
	ulong		 unused2[3] ;
  blob     user_email;
  char     user_custom1[MAX_USER_CUSTOM1];
  char     user_custom2[MAX_USER_CUSTOM2];
  ulong    user_future[MAX_USER_FUTURE]; 
} DbUser;

/* INIT, XDR */
typedef struct {
  ulong    reg_serial; 
  ulong    url_serial; 
  ulong    user_serial;
  NmUInt16   reg_pref;      
  NmUInt16   reg_period;
  ulong    reg_created;
  ulong    reg_notified;   
  ulong    reg_checksum;
  ulong    reg_segment;
  ulong    reg_filter;
  NmUInt16   reg_priority;
  NmUInt16   reg_source;
  blob     reg_desc;
  blob     reg_post_data;
  blob     reg_cookie;
  blob     reg_credentials;
  blob     reg_content;
  blob     reg_get_data;
  blob     reg_title;
  ulong    reg_last_checksum[MAX_REG_HISTORY];
  ulong    reg_num_notifications;
  ulong    reg_future[MAX_REG_FUTURE];   
} DbReg;

/* INIT, XDR */
typedef struct {
  ulong    filter_serial; 
  char     filter_pattern[MAX_FILTER_PATTERN];   
  ulong    filter_type;
  ulong    filter_flags;
  ulong    filter_future[MAX_FILTER_FUTURE];   
} DbFilter;

/* INIT */
typedef struct {
  ulong    seg_serial; 
  ulong    seg_sponsor;		/* sponsor serial number */
  uchar    seg_global;		/* TRUE for global slot, FALSE for segment */
  uchar    seg_unused1[3];
  ulong    seg_created;		/* segment creation date */
  ulong    seg_notified;	/* date of last delivery */
  ulong    seg_purchased;	/* impressions purchased */
	ulong    seg_delivered[MAX_SEG_MEDIA];
  ulong    seg_unused2;
  char     seg_file[MAX_SEG_FILE]; /* sponsor content file */
  char     seg_content[MAX_SEG_MEDIA][MAX_SEG_CONTENT]; /* sponsor content */
  ulong    seg_future[MAX_SEG_FUTURE];   
} DbSeg;

/* INIT, XDR */
typedef struct {
  ulong    class_serial; 
  char     class_name[MAX_CLASS_NAME];
  ulong    class_created;
  ulong    class_future[MAX_CLASS_FUTURE];   
} DbClass;

typedef struct {
  ulong data;
  uchar type;
  uchar flags;
  uchar next;
  uchar child;
} DbTreeNode;

/* INIT, XDR */
typedef struct {
  ulong      tree_serial;
  ulong      tree_user;       
  ulong      tree_length;
  ulong      tree_unused;
  DbTreeNode tree_nodes[MAX_TREE_NODES];
  ulong      tree_future[MAX_TREE_FUTURE];   
} DbTree;

/* INIT, XDR */
typedef struct {
  ulong    host_serial;
  char     host_name[MAX_HOSTNAME];
  ulong    host_cached;
  ulong    host_period;
  NmUInt16   host_pref ;
  NmUInt16   host_fails;
  NmUInt16   host_error;
  NmUInt16   host_num_cached;
  ulong    host_ip_addr[MAX_ADDR];
  ulong    host_ttl[MAX_ADDR] ;
  NmUInt16   host_port;
  NmUInt16   host_cache_policy ;
  ulong    host_fail_time;
	ulong    host_unused1 [MAX_HOST_FUTURE] ;
} DbHost;

/* Outbound click tracking relation */
typedef struct {
  ulong click_serial ;
  ulong click_key ;
  ulong click_time ;  
  ulong click_ip ;
  ushort click_type ;
  ushort unused[3] ;
} DbClick;

typedef DbHost NmHost;

typedef struct {
  ulong start ;						/* start time - database creation time */
  ulong period ;					/* number of days after start time */ 
  ulong users ;						/* max number of users */
  ulong urls ;						/* max number of urls */
  ulong ip_addr ;					/* host on which minder will run */
  ulong unused ;
} NmLicense ;

/* initialization functions */
NMEXPORT void dbsys_init(DbSys * sys, NmUInt32 pid, NmUInt32 started);
NMEXPORT void dburl_init(DbUrl * url, const char * loc, const char * title, 
	const char * keywords, NmUInt32 checked, NmUInt32 modified, 
	NmUInt32 detected, NmUInt32 checksum, NmUInt32 top_checksum, NmUInt32 length, 
	NmUInt16 hits);
NMEXPORT void dbuser_init(DbUser * user, const char * email, NmUInt16 pref, NmUInt16 period);
NMEXPORT void dbreg_init(DbReg * reg, NmUInt32 url_serial, NmUInt32 user_serial,
	NmUInt16 pref, NmUInt16 period, NmUInt32 notified, 
	NmUInt32 checksum, NmUInt32 segment, NmUInt32 filter);
NMEXPORT void dbseg_init(DbSeg * seg, NmUInt32 sponsor, NmBool global, NmUInt32 purchased);
NMEXPORT void dbfilter_init(DbFilter * filter, NmUInt32 type, const char * pattern, NmUInt32 flags);
NMEXPORT void dbclass_init(DbClass * clas, const char * name);
NMEXPORT void dbclass_ninit(DbClass * clas, const char * name, NmUInt32 len);
NMEXPORT void dbhost_init(DbHost * host, const char * name) ;
NMEXPORT void dbtree_init(DbTree * tree, NmUInt32 user, NmType type, NmUInt32 id);
NMEXPORT void nv_init(NmNamedValue * nv, const char * name, NmType type, void * value);
NMEXPORT void namedvalue_init(NmNamedValue * nv, const char * name, NmType type, void * value);
NMEXPORT void numberedvalue_init(NmNumberedValue * nv, NmUInt32 number, NmType type, void * value);
NMEXPORT NmUInt32 nm_size(NmType type);

/* convertors */
NMEXPORT NmFilter str2filter(const char * str);
NMEXPORT char * filter2str(NmFilter filter);

/* destructors */
NMEXPORT void namedvalue_done(NmNamedValue * nv);
NMEXPORT void numberedvalue_done(NmNumberedValue * nv);

/* tree node routines */
NMEXPORT DbTreeNode * tree_item(DbTree * tree, NmUInt32 index); 
NMEXPORT DbTreeNode * tree_find(DbTree * tree, NmType type, NmUInt32 data);
NMEXPORT DbTreeNode * tree_new(DbTree * tree, NmType type, NmUInt32 data);
NMEXPORT DbTreeNode * tree_add(DbTree * tree, NmType type, NmUInt32 data, 
                               DbTreeNode * parent);
NMEXPORT DbTreeNode * tree_find_folder(DbTree * tree, DbTreeNode * node,
                                       NmUInt32 path[]);
NMEXPORT DbTreeNode * tree_add_folder(DbTree * tree, DbTreeNode * node,
                                      NmUInt32 path[]);
NMEXPORT void tree_compress(DbTree * tree);
NMEXPORT void tree_print(DbTree * tree, DbTreeNode * node,
                         NmInt32 indent, FILE * output);
NMEXPORT NmBool tree_merge(DbTree * dest,DbTreeNode * dest_node, DbTree * src,DbTreeNode * src_node, uchar new_node_mask) ;
NMEXPORT DbTreeNode * tree_add_node(DbTree * tree,NmType type, NmUInt32 data,
                                    DbTreeNode * parent,NmBool as_child) ;
NMEXPORT DbTreeNode *  tree_find_last_next_node(DbTree * tree);
NMEXPORT NmBool tree_is_flat(DbTree * tree) ;
#define tree_length(tree)      ((tree)->tree_length)
#define tree_index(tree, node) ((uchar)(node - (tree)->tree_nodes))

/* client types--should be moved elsewhere */

typedef struct {
  NmUInt32    view_serial;
  NmUInt32    view_data;
  NmUInt32    view_user;       
  uchar    view_type;
  uchar    view_unused;
  NmUInt16   view_flags;
  NmUInt32    view_parent;
  NmUInt32    view_next;
  NmUInt32    view_child;  /* first child */
  NmUInt32    view_last;   /* last child */
} NmBookmarkView;

void bookmarkview_init(NmBookmarkView *, NmUInt32, NmUInt32, NmType, NmUInt16);

#ifdef __cplusplus
}
#endif

#endif /* _NM_TYPES_H_ */
