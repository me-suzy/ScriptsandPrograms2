/*
 * File:   pig.h
 * 
 * PigBase header file
 *
 * "Plain old Indices by Gosh!"
 * 
 * Author:  Alan Noble
 * Created: 2 Nov 1996
 *
 */

#ifndef __PIG_H__
#define __PIG_H__

#include <stdio.h>            /* for FILE */
#include <stdarg.h>           /* for va_list */
#include <time.h>             /* for time_t */

#include "nmport.h"
#include "nmport.h"
#include "nmsynch.h"

#ifdef WIN32
#include <windows.h>
typedef HANDLE PigHandle;

#elif defined(SOLARIS) || defined(LINUX)
typedef int PigHandle;

#endif

/* SR - for DLL support */
#if defined(WIN32)

#if defined (BUILD_DLL)
#define NMEXPORT __declspec(dllexport)

#elif defined (BUILD_STATIC)
#define NMEXPORT

#else
#define NMEXPORT __declspec(dllimport)
#endif  // BUILD_DLL

#else /* for Solaris */

#define NMEXPORT

#endif  /* if defined WIN32 */

#ifdef __cplusplus
extern "C" {
#endif

/*
 * Constants 
 */
#define PIG_FILE_NAMELEN   256
#define PIG_PATH_NAMELEN   1024

#define PIG_UNIQUE         0x0001

#define PIG_DELETED        -1
#define PIG_CORRUPT        -2

/* mode flags */
#define PIG_LOCK           0x00000001
#define PIG_THREAD_LOCK    0x00000002
#define PIG_GENERATE       0x00000004
#define PIG_SHARE          0x00000008

#define PIG_STD2           0x00000010
#define PIG_RAM            0x00000020
#define PIG_FMMAP          0x00000100

#ifdef WIN32
#define PIG_UNIX           0x00000000
#define PIG_MMAP           0x00000000
#elif defined(SOLARIS) || defined(LINUX)
#define PIG_UNIX           0x00000040
#define PIG_MMAP           0x00000080
#endif

#define PIG_DEFER_INDEX    0x00000200
#define PIG_FAST_INDEX     0x00000400
#define PIG_FULL_INDEX     0x00000800
#define PIG_NO_SCHEMA      0x00001000
#define PIG_READ_ONLY      0x00002000
#define PIG_VERBOSE        0x00004000
#define PIG_COUNT          0x00008000
#define PIG_VAR            0x00010000
#define PIG_UPDATE         0x00020000

#define PIG_SHARED         PIG_LOCK | PIG_GENERATE | PIG_SHARE
#define PIG_PRIVATE        PIG_LOCK | PIG_GENERATE

/*
 * dump flags
 */
#define PIG_DUMP_DATA      0x0001
#define PIG_DUMP_OFFSET    0x0002
#define PIG_DUMP_AUDIT     0x0004
#define PIG_DUMP_TIME      0x0008
#define PIG_DUMP_LINK      0x0010
#define PIG_DUMP_DELETED   0x0020
#define PIG_DUMP_HEADER    0x0040

#define PIG_DUMP_ALL       PIG_DUMP_HEADER | PIG_DUMP_DATA | PIG_DUMP_OFFSET |\
                           PIG_DUMP_AUDIT  | PIG_DUMP_TIME | PIG_DUMP_LINK | PIG_DUMP_DELETED
#define PIG_DUMP_ALL_BUT_TIME PIG_DUMP_HEADER | PIG_DUMP_DATA | PIG_DUMP_OFFSET |\
                           PIG_DUMP_AUDIT  | PIG_DUMP_LINK | PIG_DUMP_DELETED
#define PIG_DUMP_ONE       0x1000

/*
 * update flags
 */
#define PIG_DEFAULT_UPDATE 0x0000
#define PIG_INDEX_UPDATE   0x0001
#define PIG_VERSION_UPDATE 0x0002
#define PIG_ROW_UPDATE     0x0100

#define PIG_SCHEMA         "schema"
#define PIG_HIDDEN_FIELDS  11    /* keep in synch with pig_hidden_fld */

typedef enum {
  PIG_OK,

  PIG_DATA_ALREADY_EXISTS,
  PIG_CANNOT_OPEN_DATA,
  PIG_CANNOT_SEEK_DATA,
  PIG_CANNOT_FLUSH_DATA,
  PIG_CANNOT_READ_DATA,
  PIG_CANNOT_WRITE_DATA,
  PIG_CANNOT_CLOSE_DATA,

  PIG_INDEX_ALREADY_EXISTS,
  PIG_CANNOT_OPEN_INDEX,
  PIG_CANNOT_SEEK_INDEX,
  PIG_CANNOT_FLUSH_INDEX,
  PIG_CANNOT_READ_INDEX,
  PIG_CANNOT_WRITE_INDEX,
  PIG_CANNOT_CLOSE_INDEX,
  PIG_EMPTY_INDEX,

  PIG_CANNOT_OPEN_LOCK,
  PIG_CANNOT_SEEK_LOCK,
  PIG_CANNOT_READ_LOCK,
  PIG_CANNOT_WRITE_LOCK,
  PIG_CANNOT_CLOSE_LOCK,
  PIG_CANNOT_CREATE_LOCK,
  PIG_CANNOT_INIT_LOCK,
  PIG_CANNOT_RESET_LOCK,
  PIG_CANNOT_DESTROY_LOCK,
  PIG_INVALID_LOCK,
  PIG_CREATED_LOCK,
  PIG_RESET_LOCK,

  PIG_CANNOT_OPEN_LOG,
  PIG_CANNOT_CLOSE_LOG,

  PIG_CANNOT_READ_SCHEMA,

  PIG_INVALID_SIZE,
  PIG_INVALID_INDEX_ID,
  PIG_INVALID_INDEX_TYPE,
  PIG_INVALID_SELECTOR,
  PIG_INVALID_ACTION,
  PIG_INVALID_UPDATE,
  PIG_INVALID_INDEX,
  PIG_INVALID_LINK,
  PIG_CORRUPT_INDEX,
  PIG_DATA_SIZE_MISMATCH,

  PIG_CANNOT_LOCK,
  PIG_CANNOT_UNLOCK,

  PIG_NONEXISTENT_RECORD,
  PIG_DELETED_RECORD,
  PIG_OVERFLOWED,
  PIG_UNSELECTED_RECORD,
  PIG_CANNOT_UPDATE_FIXED_FIELD,
  PIG_DUPLICATE_SERIAL,

  PIG_INVALID_SERIAL_KEY,
  PIG_MAXIMUM_KEY_EXCEEDED,
  PIG_READ_ONLY_MODE,
  PIG_VERSION_MISMATCH,
  PIG_OUT_OF_MEMORY,
  PIG_INVALID_OPERATION,
  PIG_INVALID_OFFSET,
  PIG_INVALID_MODE,
  PIG_INVALID_DATA,

  PIG_USER_TERMINATED,

  PIG_INCOMPATIBLE_SCHEMA_FIELD,
  PIG_INCOMPATIBLE_SCHEMA_INDEX,
  PIG_INVALID_FIELD_TYPE,
  PIG_INVALID_RECORD_SIZE,

  PIG_LICENSE_INVALID,
  PIG_LICENSE_PERIOD_EXPIRED,
  PIG_LICENSE_SIZE_EXCEEDED,

  PIG_HEAP_ALLOC_ERROR,
  PIG_HEAP_FREE_ERROR,

  PIG_MAX_ERRORS

} PigError;

typedef enum {
  PIG_CHAR,
  PIG_SHORT,
  PIG_LONG,
  PIG_BLOB,
  PIG_VLONG
} PigType;

typedef enum {
  PIG_SERIAL_INDEX,
  PIG_NUMERIC_INDEX,
  PIG_STRING_INDEX,
  PIG_STRING_POINTER_INDEX,
  PIG_BLOB_INDEX
} PigIndexType;

typedef enum {
  PIG_FIRST,
  PIG_NEXT,
  PIG_EQUAL,
  PIG_EQUIV

} PigFinder;

typedef enum {
  PIG_END = 0,
  PIG_SET1,
  PIG_SET2,
  PIG_SET4,
  PIG_SETS,
  PIG_SETN,
  PIG_ADD
} PigAction;

/*
 * Data structures
 */

/* 16 bytes */
typedef struct {
  PigIndexType type;
  ushort id;
  ushort flags;
  ulong pos;
  ulong size; /* cached number of records in index file or blob block size */
} PigIndex;

typedef PigIndex PigKey;

/* PigBase audit information per-record */

/* 8 bytes */
typedef struct {
  ulong  date;      /* update date */
  ushort uid;       /* OS-defined user ID */
  ushort cid;       /* application-defined caller ID */
} PigAudit;

/* 16 bytes */
typedef struct {
  ulong rec_size;
  ulong n_key;
  ulong n_idx;
  ulong n_rec;
} PigDataDesc;

/* Blob support */
/* persistent blob */
typedef struct {
  ulong  size;
  ulong  offset;
} PigPBlob;

typedef PigPBlob blob;

/* transient blob */
typedef struct {
  ulong  size;
  char * data;
} PigTBlob;

typedef struct {
  ulong   size;         /* size (bits) */
  ulong   wild;         /* beginning of wilderness */
  ulong   last;         /* last block allocated */
  ulong   unused;
} PigHeapDesc;

typedef struct {
  uchar * map;          /* bitmap */
  ulong   size;         /* bitmap size (bits) */
  ulong   wild;         /* beginning of wilderness */
  ulong   last;         /* last block allocated */
  ulong   allocations;  /* number of allocations */
  ulong   sweep_ratio;  /* sweep ratio */
} PigHeap;

typedef struct {
  PigHandle     hnd;    /* handle of blob map file */
  char *        addr;   /* memory-mapped address of blob map file */
  ulong         size;   /* blob map file size */
  PigHeapDesc * desc;   /* memory-mapped heap description */
  PigHeap       heap;   /* heap */
} PigBlobMap;

/* 
 * Relation structure
 */
typedef struct {
  char major;
  char minor;
  char unused[6];
} PigVersion;  

typedef struct {
  ulong start;
  ulong period;
  ulong size;
  ulong crc;
} PigLicense;

typedef struct {
  PigFinder which;
  ulong key;
  ulong first;
  ulong match;
} PigFindState;

struct pigshared;
struct pigrelation;
struct pigmeta;

typedef Boolean (*PigMapper)(void * rec, struct pigmeta * meta, void * data);
typedef void    (*PigPrinter)(void * rec, struct pigmeta * meta);
typedef Boolean (*PigEvaluator)(void * rec, void * data);
typedef Boolean (*PigIterator)(struct pigrelation * rel, void * data);

typedef struct {
  PigHandle hnd;
#if defined(SOLARIS) || defined(LINUX)
  NmMutex mutex;
  NmMutex *mutexp;
#endif
} PigLock;

typedef struct {
  ushort  uid;
  ulong   mode;
  ulong   max_key;
  ulong   max_blocks;
  ulong   blob_sweep_ratio;
  ulong   cache_size;
  char *  lock_dir;
  ulong   lock_timeout;
	ulong   lock_tries;
	ulong   lock_wait;
	ulong   lock_timeout_tries;
//  ulong   (*hash)(char * string);
  void    (*pause)(ulong milliseconds);
  void    (*print)(void * io, const char * format, ...);
  void *  print_io;
  Boolean (*terminated)();
  time_t  (*time)(time_t *);
} PigDefault;

typedef struct pigshared {
  /* identity */
  char name[PIG_FILE_NAMELEN];
  char dir[PIG_PATH_NAMELEN];

	/* data header */
  PigVersion version;
  PigDataDesc desc;
  PigLicense license;

  /* parameters */
  ulong mode;
  ulong audit_size;
  ulong row_size;
  ulong cache_row_size;
  PigKey * key;
  PigBlobMap * blob_map;

  /* log I/O */
  char * log_path;
  FILE * log_fp;
  void * print_io;

  /* data I/O */
  char * dat_path;
  PigHandle dat_hnd;
  ulong dat_size;
  char * dat_addr;
  char * dat_cache;							/* cached data, including meta data */
#ifdef LINUX
  NmMutex dat_lock;
#endif

  /* index I/O */
  char ** idx_path;
  PigHandle * idx_hnd;
  char ** idx_addr;
  ulong * idx_size;

	ulong *  idx_cache_sizes;
  ulong ** idx_cache;				/* cached used for last links or in-ram indexes */

#ifdef LINUX
  NmMutex idx_lock;
#endif

  /* concurrency control */
  char * lock_path;
  PigLock lock;
  Boolean locked;

  /* I/O routines */
  Boolean (*sinit)(struct pigshared *, Boolean);
  Boolean (*sdone)(struct pigshared *);
  Boolean (*init)(struct pigrelation *, Boolean);
  Boolean (*done)(struct pigrelation *);

  Boolean (*begin_read)(struct pigrelation *, ulong where);
  Boolean (*end_read)(struct pigrelation *, ulong where);
  Boolean (*begin_update)(struct pigrelation *, ulong where);
  Boolean (*end_update)(struct pigrelation *, ulong where);

  Boolean (*create_data)(struct pigrelation *);
  Boolean (*open_data)(struct pigrelation *);
  Boolean (*close_data)(struct pigrelation *);
  Boolean (*read_data)(struct pigrelation *, ulong, void *, ulong);
  Boolean (*write_data)(struct pigrelation *, ulong, void *, ulong);
  Boolean (*append_data)(struct pigrelation *, void *, ulong);
  long (*data_size)(struct pigrelation *);

  Boolean (*create_index)(struct pigrelation *, ushort);
  Boolean (*open_index)(struct pigrelation *, ushort);
  Boolean (*close_index)(struct pigrelation *, ushort);
  Boolean (*read_index)(struct pigrelation *, ushort, ulong where, void *, ulong);
  Boolean (*write_index)(struct pigrelation *, ushort, ulong where, void *, ulong);
  Boolean (*append_index)(struct pigrelation *, ushort, void *, ulong);
  long (*index_size)(struct pigrelation *, ushort);

  /* schema */
  struct pigrelschema * schema;

  /* defaults */
  PigDefault * def;
  
  /* error number */
  PigError serror;
} PigShared;

typedef struct pigrelation {
  /* per-thread I/O data */
  void * print_io;
  Boolean update;
  FILE * dat_fp;								/* std only */
  Boolean dat_read;							/* std only */
  FILE ** idx_fp;								/* std only */
  Boolean * idx_read;						/* std only */
#ifdef WIN32
  PigLock lock;                 /* win32 only */
#endif

  /* per-thread transient data */
	ushort uid;										/* OS-specified user ID */
	ushort cid;										/* application-specified caller ID */
  char * rec;										/* current record */
  PigAudit * audit;							/* audit info for current record */
  long * link;									/* links for current record (one per index) */
  PigFindState * find;					/* find state */
  PigFindState save_find;       /* saved find state */
  char * tmp_rec;								/* temporary record */
  long * tmp_link;							/* temporary links */
  PigError error;								/* error number */

  /* shared data */
  PigShared * shr;
} PigRelation;

/*
 * meta data when mapping
 */
typedef struct pigmeta {
  PigRelation * rel;
  PigAudit * audit;
  long * link;
} PigMeta;

typedef struct {
  ulong current;
  ulong begin;
  ulong end;
} PigIteratorData;

/*
 * Schema structures
 */
typedef struct  {
  char * name;									/* name */
  PigType type;									/* type */
  ulong pos;										/* record position in bytes */
  ulong dim;										/* dimension */
	ulong size;										/* size in bytes = sizeof(type) * dim */
  char * rec;                   /* container record (optional) */
  PigKey * idx; 								/* index if field is indexed (optional) */
  PigRelation * rel;            /* relation (optional) */
  char * foreign;               /* foreign relation name (optional) */
  PigRelation * foreign_rel;    /* foreign relation (optional) */
  void * value;                 /* computed value (optional) */
} PigField;

typedef struct pigrelschema {
  char * name;
  ulong rec_size;								/* record size (excluding meta data) */
  ulong n_fld;									/* number of fields */
  PigField * fld;								/* fields */
  ulong n_key;                  /* number of keys */
  PigKey * key;                 /* keys */
  ulong n_idx;                  /* number of indices */
} PigRelSchema;

typedef struct {
  PigRelation * new_rel;        /* new relation */
  PigRelSchema * old_sch;       /* old schema */
  PigRelSchema * new_sch;       /* new schema */
  long * trans;                 /* translation from old schema to new */
	char * evolve_cache;					/* cache minimizing writes during evolution */
	ulong cache_count;						/* cache count */
} PigEvolveData;

/*
 * Functions 
 */

/* creating relations */
NMEXPORT Boolean pig_create(PigRelation * rel, const char * dir, const char * name, ulong mode, ulong rec_size, ulong n_idx, PigKey idx[]);
/* opening, closing and information about relations */
NMEXPORT Boolean pig_open(PigRelation * rel, const char * dir, const char * name, ulong mode);
NMEXPORT Boolean pig_close(PigRelation * rel);
NMEXPORT ulong pig_entries(PigRelation * rel);
NMEXPORT ulong pig_records(PigRelation * rel);
NMEXPORT const char * pig_name(PigRelation * rel);
NMEXPORT ulong pig_hash1(const char * string);
NMEXPORT ulong pig_hash2(char * string);
NMEXPORT Boolean pig_set_default(const char * name, void * value);
NMEXPORT void * pig_get_default(const char * name);

/* adding records */
NMEXPORT ushort  pig_set_caller(PigRelation * rel, ushort id);
NMEXPORT Boolean pig_add(PigRelation * rel, void * rec);
NMEXPORT Boolean pig_new_serial(PigRelation * rel, ulong * serial);
NMEXPORT Boolean pig_first_serial(PigRelation * rel, ulong * serial);
NMEXPORT Boolean pig_last_serial(PigRelation * rel, ulong * serial);
NMEXPORT Boolean pig_add_unique(PigRelation * rel, ushort id, void * rec);

/* finding records */
NMEXPORT Boolean pig_find(PigRelation * rel, ushort id, PigFinder which, void * rec, long * tie);
NMEXPORT Boolean pig_find_equal(PigRelation * rel, ushort id, void * rec, long * tie);
NMEXPORT Boolean pig_find_equiv(PigRelation * rel, ushort id, void * rec);
NMEXPORT Boolean pig_set_finder(PigRelation * rel, PigFinder which);
NMEXPORT Boolean pig_find_next(PigRelation * rel, ushort id, void * rec);
NMEXPORT Boolean pig_find_always(PigRelation * rel, ushort id, void * rec);
NMEXPORT Boolean pig_find_always2(PigRelation * rel, ushort id, void * rec, ...);
NMEXPORT Boolean pig_find_serial(PigRelation * rel, long serial, void * rec, long * tie);
NMEXPORT Boolean pig_find_offset(PigRelation * rel, ulong offset, void * rec);
NMEXPORT Boolean pig_count(PigRelation * rel, ushort id, void * key, ulong * count);
NMEXPORT ulong   pig_count_number(PigRelation * rel, ushort id, ulong key);
NMEXPORT Boolean pig_map(PigRelation * rel, PigMapper mapper, void * data);
NMEXPORT void    pig_save_find(PigRelation * rel);
NMEXPORT void    pig_restore_find(PigRelation * rel);
NMEXPORT void    pig_set_find(PigRelation * rel, ulong offset);

/* updating records */
NMEXPORT Boolean pig_update(PigRelation * rel, void * rec, ulong flags);
NMEXPORT Boolean pig_update_fields(PigRelation * rel, ushort id, void * rec, va_list args);
NMEXPORT Boolean pig_update_tie(PigRelation * rel, long tie);

/* removing records */
NMEXPORT Boolean pig_remove(PigRelation * rel, void * rec);

/* error handling */
NMEXPORT const PigError pig_error(PigRelation * rel);
NMEXPORT const char * pig_strerror(PigError error);

/* indexing */
NMEXPORT Boolean pig_reindex(PigRelation * rel, ulong *size);
NMEXPORT Boolean pig_index(PigRelation * rel, ushort id,
 PigIterator iter, void * idata, PigEvaluator eval, void * edata, ulong * cnt);
NMEXPORT Boolean pig_std_iterate(PigRelation * rel, PigIteratorData * data);
NMEXPORT Boolean pig_mmap_iterate(PigRelation * rel, PigIteratorData * data);

/* diagnostic */
NMEXPORT Boolean pig_print_header(PigRelation * rel);
NMEXPORT Boolean pig_repair(const char * dir, const char * name);
NMEXPORT Boolean pig_upgrade_data(const char * dir, const char * name, ulong n_key);
NMEXPORT Boolean pig_analyze_index(PigRelation * rel, ushort id);
NMEXPORT PigError pig_reset_lock(const char * dir, const char * name);

/* schema evolution */
NMEXPORT Boolean pig_evolve(PigRelation * rel, PigRelSchema * old_sch, PigRelSchema * new_sch);
NMEXPORT Boolean pig_read_schema(const char * path, const char * name, PigRelSchema * schema);
NMEXPORT Boolean pig_read_schemas(const char * path, ulong * n_schemas, PigRelSchema * schemas[]);
NMEXPORT Boolean pig_parse_schema(const char * str, PigRelSchema * schema);
NMEXPORT PigField * pig_schema_field(PigRelSchema * schema, const char * name);
NMEXPORT PigField * pig_schema_nfield(PigRelSchema * schema, const char * name, size_t len);
NMEXPORT PigField * pig_schema_key(PigRelSchema * schema, ushort id);
NMEXPORT void pig_free_schema(PigRelSchema * schema);
NMEXPORT char * pig_format_field(PigField * fld, char * str);

/* encoding/decoding */
NMEXPORT Boolean pig_encode(PigRelation * rel, void * rec, char * str);
NMEXPORT Boolean pig_decode(PigRelation * rel, void * rec, char * str);
NMEXPORT ulong   pig_encode_length(PigRelation * rel, void * rec);
NMEXPORT void    pig_quote(char * src, char * dest, char ** next);
NMEXPORT void    pig_unquote(char * src, char * dest, char ** next);
NMEXPORT ulong   pig_quote_length(char * src);
NMEXPORT ulong   pig_unquote_length(char * src);
NMEXPORT char *  pig_strtok(char * str, const char * charset, char ** last);

/* debug */
NMEXPORT Boolean pig_dump_index(PigRelation * rel, ushort id);
NMEXPORT Boolean pig_dump_data(PigRelation * rel, PigPrinter print, ulong flags);
NMEXPORT Boolean pig_dump_one(PigRelation * rel, PigPrinter print_record, ulong serial);

/* initializing shared data */
NMEXPORT Boolean pig_shared_init(PigShared * shr, const char *, const char *, ulong, ulong, ulong, PigKey[], Boolean);
NMEXPORT Boolean pig_shared_open(PigShared * shr);
NMEXPORT Boolean pig_shared_create(PigShared * shr, ulong rec_size, ulong n_key, PigKey key[]);
NMEXPORT Boolean pig_shared_done(PigShared * shr);

/* license */
NMEXPORT Boolean pig_check_license(PigRelation * rel);

/* query */
NMEXPORT Boolean pig_query(const char * dir, const char * query, char ** result);

/* blob */
NMEXPORT Boolean pig_blob_alloc(PigRelation * rel, ushort id, PigTBlob * tb, ulong size, void * data);
NMEXPORT Boolean pig_blob_alloc_string(PigRelation * rel, ushort id, PigTBlob * tb, char * data);
NMEXPORT Boolean pig_blob_realloc(PigRelation * rel, ushort id, PigTBlob * tb, ulong size, void * data);
NMEXPORT Boolean pig_blob_free(PigRelation * rel, ushort id, PigTBlob * tb);

NMEXPORT Boolean pig_blob_create(PigRelation * rel, ushort id, PigTBlob * tb, PigPBlob * pb);
NMEXPORT Boolean pig_blob_read(PigRelation * rel, ushort id, PigTBlob * tb, PigPBlob * pb);
NMEXPORT Boolean pig_blob_update(PigRelation * rel, ushort id, PigTBlob * tb, PigPBlob * pb);
NMEXPORT Boolean pig_blob_destroy(PigRelation * rel, ushort id, PigPBlob * pb);

#define pig_blob_size(blob) ((blob)->size)
#define pig_blob_data(blob) ((blob)->data)
#define pig_blob_valid(blob) (((blob)->offset > 0) || ((blob)->size == 0))
#define pig_blob_init(blob, ss, dd) (blob)->size = (ss), (blob)->data = (dd)
#define pig_blob_invalidate(blob, ss) (blob)->size = (ss), (blob)->offset = 0

/* memory management */
NMEXPORT Boolean pig_ram_heap_init(ulong size);
NMEXPORT void pig_ram_heap_done();

/* other */
NMEXPORT ulong pig_crc(const uchar * string, ulong len);
NMEXPORT void pig_msleep(ulong milliseconds);
NMEXPORT void pig_sleep(ulong seconds);
NMEXPORT void pig_print(void * io, const char * format, ...);
NMEXPORT void pig_key_init(PigKey * key, PigIndexType type, ushort id,
                           ushort flags, ulong pos, ulong size);
NMEXPORT ulong pig_hash_key(PigRelation * rel, ushort id, void * rec);

#ifdef __cplusplus
}
#endif

#endif /* __PIG_H__ */



