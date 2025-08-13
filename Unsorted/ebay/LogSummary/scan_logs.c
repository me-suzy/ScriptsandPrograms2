#include <string.h>
#include <stdio.h>
#include <ctype.h>
#include <stdlib.h>

#ifdef _MSC_VER
#define strcasecmp stricmp
#endif

/* Format of the log is --
 * host ident authuser [date] "request" status bytes "referrer"
 * "agent" "cookie"
 *
 * Quoted strings seperate tokens, the brackets around
 * date delineate a token, and in other cases they
 * are space seperated.
 *
 * Quotes are escaped in strings, so token seperation is safe there.
 * Oops. No, it's not, in the referrer. We need a space after the "
 * Missing values are replaced with the - character.
 *
 * Returns 1 on success, 0 on fail of parse.
 */

int scan_apache_log(const char *line,
                    char **url,
                    char **referrer,
                    char **agent,
                    int *status_code)
{
    /* No recursion, folks */
    static char buffer[4096];
    char *pPosition;
    char *pTmp;


    /* In case we return badly. */
    *url = *referrer = *agent = NULL;
    *status_code = 0;

    if (!line)
        return 0;

    strncpy(buffer, line, 4096);
    buffer[4095] = '\0';

    pPosition = &(buffer[0]);

    if (!*pPosition)
        return 0;

    /* Skip host */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip ident */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip authuser */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    if (*pPosition != '[')
        return 0;

    /* Skip date */
    pPosition = strchr(pPosition, ']');
    if (!pPosition || !*(pPosition + 1) ||
        !*(pPosition + 2))
        return 0;

    /* Skip the ] and space */
    pPosition += 2;

    /* Get the URL */
    if (*pPosition != '"')
        return 0;

    /* Skip the request (e.g. GET) */
	pTmp = strchr(pPosition + 1, '"'); /* Filter out bogus requests */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1) || pPosition > pTmp)
        return 0;
    ++pPosition;

    /* This is the beginning of the URL */
    *url = pPosition;

    /* Find the end of the request */
    pTmp = strchr(pPosition, '"');
    if (!pTmp || !*pTmp || !*(pTmp + 1) ||
        !*(pTmp + 2))
        return 0;
    *pTmp = '\0';
    /* Skip quote and space */
    pTmp += 2;

    /* Find the last space in request */
	if (pPosition = strrchr(pPosition, ' '))
	{
		/* End the URL string here. */
		*pPosition = '\0';
	}

    /* And get to the end of the request string. */
    pPosition = pTmp;

    /* Get status code */
    if (!isdigit(*pPosition))
        return 0;
    *status_code = atoi(pPosition);
    
    /* And skip status code */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;
    
    /* Skip byte count */
    if (!isdigit(*pPosition) && (*pPosition != '-'))
        return 0;
    pPosition = strchr(pPosition, ' ');

    /* Here we get into optional fields */
    if (!pPosition || !*(pPosition + 1))
        return 1;

    ++pPosition;

    /* Get the referrer */
    if (*pPosition != '"')
        return 0;

    ++pPosition;
    if (!*pPosition)
        return 0;

    *referrer = pPosition;
	pTmp = strstr(pPosition, "\" ");
	if (!pTmp)
		pTmp = strchr(pPosition, '"');
	pPosition = pTmp;
    if (!pPosition || !*pPosition)
        return 0;
    
    /* And 0 out the quote */
    *pPosition = '\0';
    ++pPosition;

    /* In case we don't have agent */
    if (!*(pPosition + 1))
        return 1;

    ++pPosition;

    /* Get the agent */
    /* Not having the quote here is an error */
    if (*pPosition != '"')
        return 0;

    ++pPosition;
    /* We had only a quote, which is an error */
    if (!*pPosition)
        return 0;

    *agent = pPosition;
    /* No end quote, which is an error */
    pPosition = strchr(pPosition, '"');
    if (!pPosition || !*pPosition)
        return 0;
    
    /* And 0 out the quote */
    *pPosition = '\0';

    /* And we're done. Cookie might be here, but we don't care. */
    return 1;
}

/* Format of the log is --
 * host user date time service computer name serverip
 * elapsedtime bytesreceived bytessent status ntstatus
 * operation url(-query) referrer[opt] agent[opt]
 * cookie[opt] query
 *
 * This log format is ugly because the fields are seperated
 * by commas, but commas are not necessarily escaped.
 * We try to do our best with this with the following rule:
 * check for '., ' as a field seperator (. is any character).
 *
 * If we come up with too many fields, we try to figure out
 * how to recombine them, but this is basically a horrid
 * mess of a log format.
 *
 * We use goto's to resolve the horrible check mechanism.
 * Eww.
 *
 * Because this is slower, we only use this checking after
 * we see the first free string.
 *
 * Returns 1 on success, 0 on fail of parse.
 */

int scan_iis_log(const char *line,
                 char **url,
                 char **referrer,
                 char **agent,
                 int *status_code)
{
    /* No recursion, folks */
    static char buffer[4096];
    static char url_buffer[4096];
    int extra_fields; /* Do we have referrer, agent, cookie */
    char *pPosition;
    int length;

    /* In case we return badly. */
    *url = *referrer = *agent = NULL;
    *status_code = 0;

    if (!line)
        return 0;

    strncpy(buffer, line, 4096);
    buffer[4095] = '\0';

    pPosition = &(buffer[0]);
    extra_fields = 1;

    if (!*pPosition)
        return 0;

    /* Skip host */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*pPosition || !(*pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip authuser */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip date */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip time */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip service */
    if (*pPosition != 'W')
        return 0; /* Make sure we only have W3SVC lines */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip computer name */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip server ip */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip elapsed time */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip bytes received */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip bytes sent */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Get the status */
    if (!isdigit(*pPosition))
        return 0;
    *status_code = atoi(pPosition);

    /* And skip status */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip NT code */
    if (!isdigit(*pPosition))
        return 0;
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Skip operation name */
    pPosition = strchr(pPosition, ' ');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Now it gets ugly. */

    /* This is the beginning of the URL */
    *url = pPosition;

ugly_log_label_1:
    pPosition = strchr(pPosition, ',');
    if (!pPosition || !*(pPosition + 1))
        return 0;
    ++pPosition;

    /* Either we had a space before the comma, or none after it. */
    if (isspace(*(pPosition - 2)) || *pPosition != ' ')
    {
        /* And we've got no referrer, agent, or cookie !*/
        extra_fields = 0;
        goto ugly_log_label_1;
    }

    /* We finally got a good comma, so null it. */
    *(pPosition - 1) = '\0';

    /* Pass the space */
    ++pPosition; 

    /* Oh boy. This is either the query _or_ the agent, depending on */
    /* which format it is. We assume it is the referrer until proven */
    /* wrong in that assumption */

    /* First, we set the agent, and then find the end of the field */

    *agent = pPosition;

ugly_log_label_2:
    if (extra_fields)
        pPosition = strchr(pPosition, ',');
    if (!extra_fields || !pPosition)
    {
        /* Hallelujah. We have nothing else, which means this */
        /* Format has no referrer, agent, or cookie and it    */
        /* is definitely query. */

        /* Form a url string which includes the query */

        /* Hey, no query. */
        if (**agent != '-')
        {
            length = strlen(*url);

            strncpy(url_buffer, *url, 4096);

            url_buffer[length] = '?';
            strncpy(url_buffer + length + 1, *agent, 4096 - length - 1);

            url_buffer[4095] = '\0';
            *url = &(url_buffer[0]);
        }

        *agent = NULL;

        return 1;
    }
    ++pPosition;

    if (!*pPosition)
    {
        *agent = NULL;
        return 0;
    }

    if (isspace(*(pPosition - 2)) || *pPosition != ' ')
    {
        /* Ooh. Joy for us. We found a false comma! This means */
        /* that the filter which does the extended logging is  */
        /* not running, which means that this is definitely    */
        /* query. */
        extra_fields = 0;
        goto ugly_log_label_2;
    }

    /* We finally got a good comma, so null it, and this is agent */
    *(pPosition - 1) = '\0';

    /* Pass the space */
    ++pPosition;

    /* This is the referrer. If we're here, we've got it, and we're in */
    /* for the long haul. This also means we're comma escaped. Huzzah. */
    *referrer = pPosition;
    
    pPosition = strchr(pPosition, ',');
    if (!pPosition || !*(pPosition + 1) || !*(pPosition + 2))
        return 0;

    *pPosition = '\0';

    /* Skip the (ex) comma and the space */
    pPosition += 2;

    /* This is the cookie. Skip it. */
    pPosition = strchr(pPosition, ',');
    if (!pPosition || !*(pPosition + 1) || !*(pPosition + 2))
        return 0;

    /* Skip the comma and the space */
    pPosition += 2;

    /* In that case, pPosition is now the query. Make the url and return */
    if (*pPosition != '-')
    {
        length = strlen(*url);

        strncpy(url_buffer, *url, 4096);

        url_buffer[length] = '?';
        strncpy(url_buffer + length + 1, pPosition, 4096 - length - 1);

        url_buffer[4095] = '\0';
        *url = &(url_buffer[0]);
    }

    return 1;
}

/* Format of the log is variable.
 * Fields of the log are specified in a control
 * line of #Fields: ..., which must come before
 * any log entries.
 *
 * We are interested in the following fields:
 * cs-uri or (cs-uri-stem and cs-uri-query)
 * cs(Referer) [sic]
 * cs(User-Agent)
 * sc-status
 *
 * uri's are of the type uri which is a spaceless string
 * the two cs types are of type string, which is a quote
 * delineated string (with "" being a quote escape)
 *
 */

int scan_extended_log(const char *line,
                 char **url,
                 char **referrer,
                 char **agent,
                 int *status_code)
{
    static int uri_position = -1;
    static int uri_stem_position = -1;
    static int uri_query_position = -1;
    static int referrer_position = -1;
    static int user_agent_position = -1;
    static int status_position = -1;
    static int total_num_required = 0;

    static char buffer[4096];
    static char url_buffer[4096];

    char *pPosition;
    char *pTmp;
    char *pQuery;
    int length;
    int at_position;
    int more_required;

    /* In case we return badly. */
    *url = *referrer = *agent = NULL;
    *status_code = 0;

    if (!line)
        return 0;

    strncpy(buffer, line, 4096);
    buffer[4095] = '\0';
    pQuery = NULL;

    pPosition = &(buffer[0]);

    /* Parse the fields directive line. */
    if (*pPosition == '#' && !strncmp(pPosition, "#Fields:", 8))
    {
        /* Reset. No sloppy seconds. */
        uri_position = uri_stem_position = uri_query_position =
            referrer_position = user_agent_position = status_position = -1;
        total_num_required = 0;

        /* Get past the directive */
        pPosition += strlen("#Fields:");

        /* What position we are reading */
        at_position = -1;

        while (*pPosition)
        {
            while (*pPosition && isspace(*pPosition))
                ++pPosition;

            if (!*pPosition)
                return 1;

            pTmp = pPosition;
            ++at_position;

            while (*pTmp && !isspace(*pTmp))
                ++pTmp;

            /* In case we end here, we don't want to be trying for */
            /* a position past the end of our string. */
            if (!*pTmp)
            {
                *pTmp = '\0';
                --pTmp;
            }
            else
                *pTmp = '\0';

            if (uri_position == -1 && 
                !strcasecmp(pPosition, "cs-uri"))
                uri_position = at_position;
            else if (uri_stem_position == -1 &&
                !strcasecmp(pPosition, "cs-uri-stem"))
                uri_stem_position = at_position;
            else if (uri_query_position == -1 &&
                !strcasecmp(pPosition, "cs-uri-query"))
                uri_query_position = at_position;
            else if (referrer_position == -1 &&
                !strcasecmp(pPosition, "cs(referer)")) /* [sic] */
                referrer_position = at_position;
            else if (user_agent_position == -1 &&
                !strcasecmp(pPosition, "cs(user-agent)"))
                user_agent_position = at_position;
            else if (status_position == -1 &&
                !strcasecmp(pPosition, "sc-status"))
                status_position = at_position;

            pPosition = pTmp + 1;
        }

        if (uri_position != -1)
        {
            ++total_num_required;

            /* Don't use stem and query if we can get it as */
            /* one string. */
            uri_stem_position = -1;
            uri_query_position = -1;
        }
        else
        {
            if (uri_stem_position != -1)
                ++total_num_required;
            if (uri_query_position != -1)
                ++total_num_required;
        }

        if (referrer_position != -1)
            ++total_num_required;

        if (user_agent_position != -1)
            ++total_num_required;

        if (status_position != -1)
            ++total_num_required;
        else
        {
            fprintf(stderr, "Fatal Error: Cannot parse a log file which "
                "has no status codes!\n");
            exit(1);
        }
            
        /* Done reading fields */
        return 1;
    }
    else if (*pPosition == '#' || isspace(*pPosition) || !*pPosition)
		return 1;

    /* This would cause all sorts of messes. */
    if (status_position == -1)
    {
        fprintf(stderr, "Fatal Error: Cannot parse a log file which "
            "has no status codes!\n");
        exit(1);
    }

    /* This is an entry, then. */

    /* Reset position */
    at_position = -1;

    more_required = total_num_required;

    /* Quit looping once we have our info! */
    while (more_required)
    {
        ++at_position;

        while (*pPosition && isspace(*pPosition))
            ++pPosition;

        if (!*pPosition)
            return 0;

        /* We're reading a string. */
        if (*pPosition == '"')
        {
            ++pPosition;
            pTmp = pPosition;

            while (*pTmp)
            {
                if (*pTmp == '"')
                {
                    /* Escaped */
                    if ((pTmp + 1) && *(pTmp + 1) != '"')
                    {
                        /* Move it on over to unescape the quote. */
                        memmove(pTmp, pTmp + 1, strlen(pTmp) + 1);

                        /* And skip this quote, so that """ parses. */
                        ++pTmp;

                        if (!*pTmp)
                            return 0; /* Malformed. */
                    }
                    else /* Not escaped */
                        break;
                }
                ++pTmp;
            }
            
            /* Malformed string. */
            if (!*pTmp)
                return 0;

            *pTmp = '\0';

            if (at_position == referrer_position)
            {
                *referrer = pPosition;
                --more_required;
            }
            else if (at_position == user_agent_position)
            {
                --more_required;
                *agent = pPosition;
            }

            pPosition = pTmp + 1;
            continue; /* Continue the outer loop. */
        }

        pTmp = pPosition;
        while (*pTmp && !isspace(*pTmp))
            ++pTmp;

        if (at_position == uri_position)
        {
            --more_required;
            *pTmp = '\0';
            *url = pPosition;
        }
        else if (at_position == uri_stem_position)
        {
            --more_required;
            *pTmp = '\0';
            *url = pPosition;
        }
        else if (at_position == uri_query_position)        
        {
            --more_required;
            *pTmp = '\0';
            pQuery = pPosition;
        }
        else if (at_position == status_position)
        {
            if (!isdigit(*pPosition))
                return 0; /* Malformed */

            --more_required;
            *status_code = atoi(pPosition);
        }

        pPosition = pTmp + 1;
    }

    if (pQuery && *pQuery != '-' && *url)
    {
        length = strlen(*url);

        strncpy(url_buffer, *url, 4096);

        url_buffer[length] = '?';
        strncpy(url_buffer + length + 1, pQuery, 4096 - length - 1);

        url_buffer[4095] = '\0';
        *url = &(url_buffer[0]);
    }

    return 1;
}
