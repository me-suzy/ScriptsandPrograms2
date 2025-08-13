/*	$Id: scan_logs.h,v 1.2 1999/02/21 02:23:28 josh Exp $	*/
#ifndef SCAN_LOGS_INCLUDE
#define SCAN_LOGS_INCLUDE

/* Make a typedef for the scan_functions */
typedef int (*scan_function)(const char *,
                             char **,
                             char **,
                             char **,
                             int *);

int scan_apache_log(const char *line,
                    char **url,
                    char **referrer,
                    char **agent,
                    int *status_code);

int scan_iis_log(const char *line,
                    char **url,
                    char **referrer,
                    char **agent,
                    int *status_code);

int scan_extended_log(const char *line,
                    char **url,
                    char **referrer,
                    char **agent,
                    int *status_code);

#endif /* SCAN_LOGS_INCLUDE */
