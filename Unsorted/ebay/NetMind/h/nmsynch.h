/*MAN
FILE
     nmsynch.h
DESCRIPTION
     Portable synchronization primitives
*/

#ifndef _NM_SYNCH_H_
#define _NM_SYNCH_H_

#include "nmport.h"

#ifdef WIN32

#include <windows.h>
#include <process.h>

typedef HANDLE NmMutex;
typedef HANDLE NmHandle;
typedef HANDLE NmSemaphore;

#define nm_sigwait(set, sig) ((sig) = sigwait(&(set)))

#elif defined(POSIX)

#include <pthread.h>

typedef pthread_mutex_t NmMutex;
typedef int NmHandle;
typedef struct NmSemaphore {
  pthread_cond_t cond;
  pthread_mutex_t mutex;
} *NmSemaphore;

#define nm_sigwait(set, sig) sigwait(&(set), &(sig))
#define nm_sigsetmask(mask, set) pthread_sigmask((mask), &(set), NULL)

#elif defined(SOLARIS)

#include <synch.h>
#include <sys/types.h>
#include <sys/mman.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>

typedef mutex_t NmMutex;
typedef int NmHandle;
typedef sema_t *NmSemaphore;

#define nm_sigwait(set, sig) ((sig) = sigwait(&(set)))
#define nm_sigsetmask(mask, set) thr_sigsetmask((mask), &(set), NULL)

#endif

#include "nmexport.h"

#ifndef BOOLEAN_DEFINED
#define BOOLEAN_DEFINED
typedef int Boolean;
#endif
#ifndef TRUE
#define TRUE               1
#endif
#ifndef FALSE
#define FALSE              0
#endif

#ifdef __cplusplus
extern "C" {
#endif

typedef enum {
  NM_INTRA_PROCESS,
  NM_INTER_PROCESS
} NmSynchType;

/* Semaphore management.  Semaphores communicate between threads
   in a single process, not between processes. */
NMEXPORT Boolean nm_semaphore_init(NmSemaphore *self);
NMEXPORT Boolean nm_semaphore_post(NmSemaphore self);
NMEXPORT Boolean nm_semaphore_wait(NmSemaphore self);
NMEXPORT Boolean nm_semaphore_destroy(NmSemaphore self);

/* mutual exclusion routines */
NMEXPORT Boolean nm_mutex_init(NmMutex *mutex, NmSynchType type);
NMEXPORT void nm_mutex_done(NmMutex *mutex);
NMEXPORT Boolean nm_mutex_lock(NmMutex *mutex);
NMEXPORT Boolean nm_mutex_unlock(NmMutex *mutex);

#ifdef __cplusplus
}
#endif

#endif /* _NM_THREAD_H_ */
