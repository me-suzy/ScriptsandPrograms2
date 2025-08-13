#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdlib.h>

#include "scan_logs.h"

#ifdef _MSC_VER
#define strcasecmp stricmp
#endif

typedef struct
{
    char long_name[512];
    char url_to_match[512];
    unsigned int url_length;
    int refer_count;
    int fail_count;
    int bounce_count; /* How many times we redirected */
    int exact_match;
    int printing_lines;
} partner_struct;

typedef enum
{
	UnknownSystem,
	Win95System,
	Win31System,
	WinNTSystem,
	MacSystem
} SystemEnum;

typedef enum
{
    UnknownBrowser, /* If we can't get agent from the log. */
    WebTVBrowser,
    MSIE1Browser,
	MSIE1OnWin95Browser,
	MSIE1OnWinNTBrowser,
	MSIE1OnWin31Browser,
	MSIE1OnMacBrowser,
    MSIE2Browser,
	MSIE2OnWin95Browser,
	MSIE2OnWinNTBrowser,
	MSIE2OnWin31Browser,
	MSIE2OnMacBrowser,
    MSIE3Browser,
	MSIE3OnWin95Browser,
	MSIE3OnWinNTBrowser,
	MSIE3OnWin31Browser,
	MSIE3OnMacBrowser,
    MSIE4Browser,
	MSIE4OnWin95Browser,
	MSIE4OnWinNTBrowser,
	MSIE4OnWin31Browser,
	MSIE4OnMacBrowser,
    Netscape0Browser,
	Netscape0OnWin95Browser,
	Netscape0OnWinNTBrowser,
	Netscape0OnWin31Browser,
	Netscape0OnMacBrowser,
    Netscape0IntBrowser,
	Netscape0OnWin95IntBrowser,
	Netscape0OnWinNTIntBrowser,
	Netscape0OnWin31IntBrowser,
	Netscape0OnMacIntBrowser,
    Netscape1Browser,
	Netscape1OnWin95Browser,
	Netscape1OnWinNTBrowser,
	Netscape1OnWin31Browser,
	Netscape1OnMacBrowser,
    Netscape1IntBrowser,
	Netscape1OnWin95IntBrowser,
	Netscape1OnWinNTIntBrowser,
	Netscape1OnWin31IntBrowser,
	Netscape1OnMacIntBrowser,
    Netscape2Browser,
	Netscape2OnWin95Browser,
	Netscape2OnWinNTBrowser,
	Netscape2OnWin31Browser,
	Netscape2OnMacBrowser,
    Netscape2IntBrowser,
	Netscape2OnWin95IntBrowser,
	Netscape2OnWinNTIntBrowser,
	Netscape2OnWin31IntBrowser,
	Netscape2OnMacIntBrowser,
    Netscape2GoldBrowser,
	Netscape2OnWin95GoldBrowser,
	Netscape2OnWinNTGoldBrowser,
	Netscape2OnWin31GoldBrowser,
	Netscape2OnMacGoldBrowser,
    Netscape3Browser,
	Netscape3OnWin95Browser,
	Netscape3OnWinNTBrowser,
	Netscape3OnWin31Browser,
	Netscape3OnMacBrowser,
    Netscape3IntBrowser,
	Netscape3OnWin95IntBrowser,
	Netscape3OnWinNTIntBrowser,
	Netscape3OnWin31IntBrowser,
	Netscape3OnMacIntBrowser,
    Netscape3GoldBrowser,
	Netscape3OnWin95GoldBrowser,
	Netscape3OnWinNTGoldBrowser,
	Netscape3OnWin31GoldBrowser,
	Netscape3OnMacGoldBrowser,
    Netscape4Browser,
	Netscape4OnWin95Browser,
	Netscape4OnWinNTBrowser,
	Netscape4OnWin31Browser,
	Netscape4OnMacBrowser,
    Netscape4IntBrowser,
	Netscape4OnWin95IntBrowser,
	Netscape4OnWinNTIntBrowser,
	Netscape4OnWin31IntBrowser,
	Netscape4OnMacIntBrowser,
    AolBrowser,
    LynxBrowser,
    OtherBrowser
} BrowserEnum;

typedef struct
{
    char name[64];
    int total_count;
    int fail_count;
} browser_struct;

static browser_struct BrowserArray[] =
{
    { "No Agent Given",								0, 0 },
    { "Web TV",										0, 0 },
    { "Microsoft Internet Explorer 1 (Other)",		0, 0 },
	{ "Microsoft Internet Explorer 1 (Windows 95)",	0, 0 },
	{ "Microsoft Internet Explorer 1 (Windows NT)", 0, 0 },
	{ "Microsoft Internet Explorer 1 (Windows 3.1)",0, 0 },
	{ "Microsoft Internet Explorer 1 (Macintosh)",	0, 0 },
    { "Microsoft Internet Explorer 2 (Other)",		0, 0 },
	{ "Microsoft Internet Explorer 2 (Windows 95)",	0, 0 },
	{ "Microsoft Internet Explorer 2 (Windows NT)", 0, 0 },
	{ "Microsoft Internet Explorer 2 (Windows 3.1)",0, 0 },
	{ "Microsoft Internet Explorer 2 (Macintosh)",	0, 0 },
    { "Microsoft Internet Explorer 3 (Other)",		0, 0 },
	{ "Microsoft Internet Explorer 3 (Windows 95)",	0, 0 },
	{ "Microsoft Internet Explorer 3 (Windows NT)", 0, 0 },
	{ "Microsoft Internet Explorer 3 (Windows 3.1)",0, 0 },
	{ "Microsoft Internet Explorer 3 (Macintosh)",	0, 0 },
    { "Microsoft Internet Explorer 4 (Other)",		0, 0 },
	{ "Microsoft Internet Explorer 4 (Windows 95)",	0, 0 },
	{ "Microsoft Internet Explorer 4 (Windows NT)", 0, 0 },
	{ "Microsoft Internet Explorer 4 (Windows 3.1)",0, 0 },
	{ "Microsoft Internet Explorer 4 (Macintosh)",	0, 0 },
    { "Netscape Navigator 0 (Beta) (Other)",		0, 0 },
    { "Netscape Navigator 0 (Beta) (Windows 95)",	0, 0 },
    { "Netscape Navigator 0 (Beta) (Windows NT)",	0, 0 },
    { "Netscape Navigator 0 (Beta) (Windows 3.1)",	0, 0 },
    { "Netscape Navigator 0 (Beta) (Macintosh)",	0, 0 },
    { "Netscape Navigator 0 (Int.) (Other)",		0, 0 },
    { "Netscape Navigator 0 (Int.) (Windows 95)",	0, 0 },
    { "Netscape Navigator 0 (Int.) (Windows NT)",	0, 0 },
    { "Netscape Navigator 0 (Int.) (Windows 3.1)",	0, 0 },
    { "Netscape Navigator 0 (Int.) (Macintosh)",	0, 0 },
    { "Netscape Navigator 1 (Other)",				0, 0 },
	{ "Netscape Navigator 1 (Windows 95)",			0, 0 },
	{ "Netscape Navigator 1 (Windows NT)",			0, 0 },
	{ "Netscape Navigator 1 (Windows 3.1)",			0, 0 },
	{ "Netscape Navigator 1 (Macintosh)",			0, 0 },
    { "Netscape Navigator 1 (Int.) (Other)",		0, 0 },
    { "Netscape Navigator 1 (Int.) (Windows 95)",	0, 0 },
    { "Netscape Navigator 1 (Int.) (Windows NT)",	0, 0 },
    { "Netscape Navigator 1 (Int.) (Windows 3.1)",	0, 0 },
    { "Netscape Navigator 1 (Int.) (Macintosh)",	0, 0 },
    { "Netscape Navigator 2 (Other)",				0, 0 },
	{ "Netscape Navigator 2 (Windows 95)",			0, 0 },
	{ "Netscape Navigator 2 (Windows NT)",			0, 0 },
	{ "Netscape Navigator 2 (Windows 3.1)",			0, 0 },
	{ "Netscape Navigator 2 (Macintosh)",			0, 0 },
    { "Netscape Navigator 2 (Int.) (Other)",		0, 0 },
    { "Netscape Navigator 2 (Int.) (Windows 95)",	0, 0 },
    { "Netscape Navigator 2 (Int.) (Windows NT)",	0, 0 },
    { "Netscape Navigator 2 (Int.) (Windows 3.1)",	0, 0 },
    { "Netscape Navigator 2 (Int.) (Macintosh)",	0, 0 },
    { "Netscape Navigator 2 (Gold) (Other)",		0, 0 },
	{ "Netscape Navigator 2 (Gold) (Windows 95)",	0, 0 },
	{ "Netscape Navigator 2 (Gold) (Windows NT)",	0, 0 },
	{ "Netscape Navigator 2 (Gold) (Windows 3.1)",	0, 0 },
	{ "Netscape Navigator 2 (Gold) (Macintosh)",	0, 0 },
    { "Netscape Navigator 3 (Other)",				0, 0 },
	{ "Netscape Navigator 3 (Windows 95)",			0, 0 },
	{ "Netscape Navigator 3 (Windows NT)",			0, 0 },
	{ "Netscape Navigator 3 (Windows 3.1)",			0, 0 },
	{ "Netscape Navigator 3 (Macintosh)",			0, 0 },
    { "Netscape Navigator 3 (Int.) (Other)",		0, 0 },
    { "Netscape Navigator 3 (Int.) (Windows 95)",	0, 0 },
    { "Netscape Navigator 3 (Int.) (Windows NT)",	0, 0 },
    { "Netscape Navigator 3 (Int.) (Windows 3.1)",	0, 0 },
    { "Netscape Navigator 3 (Int.) (Macintosh)",	0, 0 },
    { "Netscape Navigator 3 (Gold)",				0, 0 },
	{ "Netscape Navigator 3 (Gold) (Windows 95)",	0, 0 },
	{ "Netscape Navigator 3 (Gold) (Windows NT)",	0, 0 },
	{ "Netscape Navigator 3 (Gold) (Windows 3.1)",	0, 0 },
	{ "Netscape Navigator 3 (Gold) (Macintosh)",	0, 0 },
    { "Netscape 4 (Other)",							0, 0 },
	{ "Netscape 4 (Windows 95)",					0, 0 },
	{ "Netscape 4 (Windows NT)",					0, 0 },
	{ "Netscape 4 (Windows 3.1)",					0, 0 },
	{ "Netscape 4 (Macintosh)",						0, 0 },
    { "Netscape 4 (Int.) (Other)",					0, 0 },
    { "Netscape 4 (Int.) (Windows 95)",				0, 0 },
    { "Netscape 4 (Int.) (Windows NT)",				0, 0 },
    { "Netscape 4 (Int.) (Windows 3.1)",			0, 0 },
    { "Netscape 4 (Int.) (Macintosh)",				0, 0 },
    { "Aol Browser",								0, 0 },
    { "Lynx Text Browser",							0, 0 },
    { "Other Browser",								0, 0 }
};

static int BrowserArray_size = sizeof (BrowserArray) / 
    sizeof (browser_struct);
static partner_struct **sg_partners = NULL;
static partner_struct **sg_query_partners = NULL;
static partner_struct *sg_no_partner = NULL;
static int sg_partners_size = 0;
static int sg_query_partners_size = 0;
static int s_chop_referrer = 0; /* Whether we are head matching only */
static scan_function scan_func = NULL;

static char **sg_valid_types = NULL;
static int sg_valid_types_size = 0;

static char *sg_main_page = NULL;
static char *sg_partner_block = NULL;
static char *sg_agent_block = NULL;
static int sg_main_page_num_segs = 0;
static int sg_partner_block_num_segs = 0;
static int sg_agent_block_num_segs = 0;

/* Options */
static const char *sg_server_name = NULL;
static const char *sg_date_string = NULL;
static int sg_errors_on = 0;
static int sg_bad_type_on = 0;
static int sg_bounce_server = 0;
static int sg_pretty = 0;

/* Output files */
static FILE *sg_dump_file = NULL;
static FILE *sg_error_file = NULL;
static FILE *sg_bad_type_file = NULL;
static FILE *sg_bounce_file = NULL;
static FILE *sg_other_agent_file = NULL;

/* Comparison functions for referral and string. Used to find */
/* our referrer */
static int find_referral_compare(const void *v1, const void *v2)
{
	const char *ref = *((const char **) v1);
	const partner_struct *partner = *((const partner_struct **) v2);
    char *cp;
    char c;
    int ret;
    unsigned int length;

    cp = (char *) ref;

    if (s_chop_referrer && !partner->exact_match)
    {
        length = partner->url_length;
        if (strlen(cp) > length)
        {
            c = cp[length];
            cp[length] = '\0';
        }
        else
            c = 0;
    }
    else
        c = 0;

    ret = strcasecmp(ref, partner->url_to_match);
    if (c)
        cp[length] = c;

    return ret;
}

/* Sort function for partner structs. */
static int sort_referral_compare(const void *v1, const void *v2)
{
	const partner_struct *p1 = *((const partner_struct **) v1);
	const partner_struct *p2 = *((const partner_struct **) v2);

    return strcasecmp(p1->url_to_match, p2->url_to_match);
}

/* Sort function for partner structs for summing. */
static int sort_referral_compare_for_sum(const void *v1, const void *v2)
{
	const partner_struct *p1 = *((const partner_struct **) v1);
	const partner_struct *p2 = *((const partner_struct **) v2);

	return strcmp(p1->long_name, p2->long_name);
}

static int find_type_compare(const void *v1, const void *v2)
{
	return strcasecmp(*((const char **) v1), *((const char **) v2));
}

/* Find a particular browser. */
browser_struct *find_browser_struct(const char *name)
{
	int i;

	for (i = 0; i < BrowserArray_size; ++i)
	{
		if (!strcmp(BrowserArray[i].name, name))
			return BrowserArray + i;
	}

	return NULL;
}

/* Find the structure for a referral. */
/* Also, tell us if the referrer is a valid partner, as it may */
/* affect how we handle it. */
/* we may alter the referrer, but it will only be */
/* to compare strings, and then restore it. */
partner_struct *find_referral_struct(const char *url,
                                     char *referral,
                                     partner_struct **valid_referral)
{
    char *pPart;
    partner_struct **ppRet, **ppVRef;

    /* From the url is the preferred method. */
    pPart = NULL;
    ppRet = NULL;
    *valid_referral = NULL;

    if (url && *url)
        pPart = strrchr(url, '?');

    /* If it had a question mark, find the matching partner */
    if (pPart && sg_query_partners)
    {
        ++pPart;
        s_chop_referrer = 0;
        ppRet = bsearch(&pPart, sg_query_partners,
            sg_query_partners_size, sizeof (partner_struct *),
            find_referral_compare);
    }

    if (!referral || !*referral)
        return (ppRet ?  *ppRet : sg_no_partner);

    /* Find the matching referrer string. */
    s_chop_referrer = 1;
    if (sg_partners)
    {
        ppVRef = bsearch(&referral, sg_partners,
            sg_partners_size, sizeof (partner_struct *),
            find_referral_compare);
    }

    *valid_referral = (ppVRef ? *ppVRef : NULL);

    return (ppRet ? *ppRet : sg_no_partner);
}

/* Determine if this type of file is counted in our tallies. */
/* For example, we don't want to give referrals to pictures, do we? */
int tallied_type(char *url)
{
    char buffer[16];
    char *pPart;
    char *pTmp;
    char *pQuestion;
    char c;

    if (!url || !*url || !sg_valid_types)
        return 0;

    pQuestion = strchr(url, '?');
    if (pQuestion)
    {
        c = *pQuestion;
        *pQuestion = '\0';
    }
    else
        c = '\0';

    /* Find our theoretical end */
    pPart = strrchr(url, '.');
    pTmp = strrchr(url, '/');

    if (!pPart && !pTmp)
    {
        if (c)
            *pQuestion = c;
        return 0;
    }

    /* If we have a slash as the last character, that's */
    /* the type. Otherwise, our type goes from the dot. */
    if (pTmp > pPart && !*(pTmp + 1) || !pPart)
        pPart = pTmp;

    strncpy(buffer, pPart, 16);
    buffer[15] = '\0';

    if (c)
        *pQuestion = c;

	pPart = &(buffer[0]);

    /* Find out if it exists. */
    if (bsearch(&pPart, sg_valid_types,
        sg_valid_types_size, sizeof (char *),
        find_type_compare) != NULL)
        return 1;

    return 0;
}

/* Load the control file */
/* We have these valid line types: */
/* Set Name name -- sets the current partner long name */
/* Add Referrer ref -- adds a referrer to current partner */
/* Add Exact ref -- Adds an exact referrer to current partner */
/* Add Query query -- adds a query to current partner */
/* Add Valid Type type -- adds a valid file extension. */
/* Dump On -- turns on dumping for diagnostic purposes in referrers *
/*            listed after this and until Dump Off */
/* Dump Off -- turns off dumping */
/* Errors On -- turn on reporting of unparseable lines */
/* Errors Off -- turn off reporting of unparseable lines */
/* Invalid On -- turn on reporting of invalid file types */
/* Invalid Off -- turn off reporting of invalid file types */

void load_control_file(FILE *pFile)
{
    char buffer[4096];
    char long_name[512];
    partner_struct **ppPartners;
    partner_struct *pPartnerArray;
    char *cp;
    char c1, c2;
    unsigned int length;
    int i = 0;

    int num_types = 0;
    int num_queries = 0;
    int num_refs = 0;

    int dump_on = 0;

    /* Run through it once to get counts for the allocation. */
    rewind(pFile);
    while (fgets(buffer, 4096, pFile) != NULL)
    {
        length = strlen(buffer);
        if (buffer[length - 1] == '\n')
            buffer[--length] = '\0';

        cp = &(buffer[0]);

        if (!strncmp(buffer, "Add Referrer", strlen("Add Referrer")))
            ++num_refs;
        else if (!strncmp(buffer, "Add Query", strlen("Add Query")))
            ++num_queries;
        else if (!strncmp(buffer, "Add Valid Type",
            strlen("Add Valid Type")))
            ++num_types;
        else if (!strncmp(buffer, "Add Exact", strlen("Add Exact")))
            ++num_refs;
    }

    /* Allocate from results. */
    pPartnerArray = (partner_struct *)
        calloc(num_queries + num_refs, sizeof (partner_struct));

    sg_partners_size = num_refs;
    sg_query_partners_size = num_queries;
    sg_valid_types_size = num_types;

    /* Allocate enough partners for all. */
    if (!pPartnerArray)
    {
        fprintf(stderr, "Fatal Error: Cannot allocate memory for "
            "referral counting.\n");
        exit(1);
    }

    /* How many partners we have. */
    i = num_queries + num_refs;

    /* Make for the referral partners. */
    ppPartners = (partner_struct **)
        calloc(num_refs, sizeof (partner_struct *));

    if (!ppPartners)
    {
        fprintf(stderr, "Fatal Error: Cannot allocate memory for "
            "referral counting.\n");
        exit(1);
    }

    /* Fill the array */
    while (num_refs--)
    {
        --i;
        ppPartners[num_refs] = pPartnerArray + i;
    }

    /* And assign it. */
    sg_partners = ppPartners;

    /* Make for the query partners. */
    ppPartners = (partner_struct **)
        calloc(num_queries, sizeof (partner_struct *));

    if (!ppPartners)
    {
        fprintf(stderr, "Fatal Error: Cannot allocate memory for "
            "referral counting.\n");
        exit(1);
    }

    /* Fill the array */
    while (num_queries--)
    {
        --i;
        ppPartners[num_queries] = pPartnerArray + i;
    }

    /* And assign it. */
    sg_query_partners = ppPartners;

    /* Make enough space to hold all valid types. */
    cp = (char *) calloc(16 * num_types, sizeof (char));

    /* Now make the valid types array. */
    sg_valid_types = (char **) calloc(num_types,
        sizeof (char *));

    if (!sg_valid_types)
    {
        fprintf(stderr, "Fatal Error: Cannot allocate memory for "
            "referral counting.\n");
        exit(1);
    }

    /* Fill the array */
    while (num_types--)
    {
        sg_valid_types[num_types] = cp + (num_types * 16);
    }

    /* Reset our name and counts */
    long_name[0] = '\0';
    num_queries = num_refs = num_types = 0;

    /* And read the file again for the real thing. */
    rewind(pFile);
    while (fgets(buffer, 4096, pFile) != NULL)
    {
        /* Chop trailing whitespace */
        length = strlen(buffer);
        cp = buffer + length - 1;
        while (length && isspace(*cp))
        {
            --length;
			--cp;
        }
        buffer[length] = '\0';

        cp = &(buffer[0]);

        /* Referrer strings. */
        if (!strncmp(buffer, "Add Referrer", strlen("Add Referrer")))
        {
            /* Skip the instruction. */
            cp = buffer + strlen("Add Referrer");
            while (isspace(*cp))
                ++cp;

            strcpy(sg_partners[num_refs]->long_name, long_name);
            strncpy(sg_partners[num_refs]->url_to_match, cp, 512);
            sg_partners[num_refs]->url_to_match[511] = '\0';
            sg_partners[num_refs]->url_length = 
                strlen(sg_partners[num_refs]->url_to_match);
            sg_partners[num_refs]->printing_lines = dump_on;

            ++num_refs;
            continue;
        }

        /* Add exact referrer */
        if (!strncmp(buffer, "Add Exact", strlen("Add Exact")))
        {
            /* Skip the instruction. */
            cp = buffer + strlen("Add Exact");
            while (isspace(*cp))
                ++cp;

            strcpy(sg_partners[num_refs]->long_name, long_name);
            strncpy(sg_partners[num_refs]->url_to_match, cp, 512);
            sg_partners[num_refs]->url_to_match[511] = '\0';
            sg_partners[num_refs]->url_length = 
                strlen(sg_partners[num_refs]->url_to_match);
            sg_partners[num_refs]->printing_lines = dump_on;
            sg_partners[num_refs]->exact_match = 1;

            ++num_refs;
            continue;
        }

        /* Query strings. */
        if (!strncmp(buffer, "Add Query", strlen("Add Query")))
        {
            /* Skip the instruction. */
            cp = buffer + strlen("Add Query");
            while (isspace(*cp))
                ++cp;

            strcpy(sg_query_partners[num_queries]->long_name, long_name);
            strcpy(sg_query_partners[num_queries]->url_to_match, cp);
            sg_query_partners[num_queries]->url_to_match[511] = '\0';
            sg_query_partners[num_queries]->url_length = 
                strlen(sg_query_partners[num_queries]->url_to_match);
            sg_query_partners[num_queries]->printing_lines = dump_on;

            ++num_queries;
            continue;
        }

        /* Valid file extensions. */
        if (!strncmp(buffer, "Add Valid Type", strlen("Add Valid Type")))
        {
            /* Skip the instruction. */
            cp = buffer + strlen("Add Valid Type");
            while (isspace(*cp))
                ++cp;

            strncpy(sg_valid_types[num_types], cp, 16);
            sg_valid_types[num_types][15] = '\0';
            ++num_types;
            continue;
        }

        /* Set the long name for a partner */
        if (!strncmp(buffer, "Set Name", strlen("Set Name")))
        {
            /* Skip the instruction */
            cp = buffer + strlen("Set Name");
            while (isspace(*cp))
                ++cp;

            strncpy(long_name, cp, 512);
            long_name[511] = '\0';
            continue;
        }

        if (!strcmp(buffer, "Dump On"))
        {
            dump_on = 1;
            continue;
        }

        if (!strcmp(buffer, "Dump Off"))
        {
            dump_on = 0;
            continue;
        }

        if (!strcmp(buffer, "Errors On"))
        {
            sg_errors_on = 1;
            continue;
        }

        if (!strcmp(buffer, "Errors Off"))
        {
            sg_errors_on = 0;
            continue;
        }
        
        if (!strcmp(buffer, "Invalid On"))
        {
            sg_bad_type_on = 1;
            continue;
        }

        if (!strcmp(buffer, "Invalid Off"))
        {
            sg_bad_type_on = 0;
            continue;
        }
    }

    /* Now allocate and initialize the no partner structure */
    sg_no_partner = (partner_struct *) malloc(sizeof (partner_struct));
    strcpy(sg_no_partner->url_to_match, "#No Matched URL");
    strcpy(sg_no_partner->long_name, "#Unknown Referrer");
    sg_no_partner->url_length = 0;
    sg_no_partner->refer_count = 0;
    sg_no_partner->fail_count = 0;
    sg_no_partner->bounce_count = 0;
    sg_no_partner->printing_lines = dump_on;
    sg_no_partner->exact_match = 0;

    /* We sort */
    qsort(sg_partners, sg_partners_size, sizeof (partner_struct *),
        sort_referral_compare);
    qsort(sg_query_partners, sg_query_partners_size,
        sizeof (partner_struct *), sort_referral_compare);
    qsort(sg_valid_types, sg_valid_types_size,
        sizeof (char *), find_type_compare);

    /* And now we validate that we have no duplicates. */
    /* Since we know they're sorted, we only have to   */
    /* validate x and x + 1 for each. */
    for (num_refs = 0; num_refs < sg_partners_size - 1; ++num_refs)
    {
        /* Get the shorter length. */
        length = (sg_partners[num_refs]->url_length >
            sg_partners[num_refs + 1]->url_length ?
            sg_partners[num_refs + 1]->url_length :
            sg_partners[num_refs]->url_length);

        c1 = sg_partners[num_refs]->url_to_match[length];
        c2 = sg_partners[num_refs + 1]->url_to_match[length];

        sg_partners[num_refs]->url_to_match[length] = '\0';
        sg_partners[num_refs + 1]->url_to_match[length] = '\0';

        if (!strcasecmp(sg_partners[num_refs]->url_to_match,
            sg_partners[num_refs + 1]->url_to_match))
        {
            fprintf(stderr, "Fatal Error: Duplicate Refferer listings "
                "for URL %s",
                sg_partners[num_refs]->url_to_match);

            exit(1);
        }

        sg_partners[num_refs]->url_to_match[length] = c1;
        sg_partners[num_refs + 1]->url_to_match[length] = c2;
    }

    /* And now we validate that we have no duplicates. */
    /* Since we know they're sorted, we only have to   */
    /* validate x and x + 1 for each. */
    for (num_queries = 0; num_queries < sg_query_partners_size - 1; 
        ++num_queries)
    {
        if (!strcasecmp(sg_query_partners[num_queries]->url_to_match,
            sg_query_partners[num_queries + 1]->url_to_match))
        {
            fprintf(stderr, "Fatal Error: Duplicate Query listings "
                "for Query %s",
                sg_query_partners[num_queries]->url_to_match);

            exit(1);
        }
    }

    /* And we couldn't care less about duplicate file types, so */
    /* ignore them and return. */
    return;
}

/* Determine browser figures out what kind of browser the agent is. */
BrowserEnum determine_browser(const char *agent)
{
	const char *ccp;
	SystemEnum systype;

	/* Determine platform */
	if (strstr(agent, "Windows 95") || strstr(agent, "Win95"))
		systype = Win95System;
	else if (strstr(agent, "Windows NT") || strstr(agent, "WinNT") ||
		strstr(agent, "Win32"))
		systype = WinNTSystem;
	else if (strstr(agent, "Win16") || strstr(agent, "Windows 3.1"))
		systype = Win31System;
	else if (strstr(agent, "Mac"))
		systype = MacSystem;
	else
		systype = UnknownSystem;

	if (strstr(agent, "WebTV"))
        return WebTVBrowser;
    if (strstr(agent, "WENG"))
        return AolBrowser;
	else if ((ccp = strstr(agent, "MSIE")) != NULL)
	{
		/* Pass MSIE */
		ccp += 4;
		if (!*ccp)
			return OtherBrowser;
		++ccp;

		/* Pass space or slash */
		if (!*ccp)
			return OtherBrowser;

		switch (*ccp)
		{
		case '1':
			switch (systype)
			{
			case Win95System: return MSIE1OnWin95Browser;
			case WinNTSystem: return MSIE1OnWinNTBrowser;
			case Win31System: return MSIE1OnWin31Browser;
			case MacSystem: return MSIE1OnMacBrowser;
			default: return MSIE1Browser;
			}
		case '2':
			switch (systype)
			{
			case Win95System: return MSIE2OnWin95Browser;
			case WinNTSystem: return MSIE2OnWinNTBrowser;
			case Win31System: return MSIE2OnWin31Browser;
			case MacSystem: return MSIE2OnMacBrowser;
			default: return MSIE2Browser;
			}
		case '3':
			switch (systype)
			{
			case Win95System: return MSIE3OnWin95Browser;
			case WinNTSystem: return MSIE3OnWinNTBrowser;
			case Win31System: return MSIE3OnWin31Browser;
			case MacSystem: return MSIE3OnMacBrowser;
			default: return MSIE3Browser;
			}
		case '4':
			switch (systype)
			{
			case Win95System: return MSIE4OnWin95Browser;
			case WinNTSystem: return MSIE4OnWinNTBrowser;
			case Win31System: return MSIE4OnWin31Browser;
			case MacSystem: return MSIE4OnMacBrowser;
			default: return MSIE4Browser;
			}
		default:
			return OtherBrowser;
		}
	}
	else if (strstr(agent, "ompatible"))
	{
		if (strstr(agent, "AOL "))
			return AolBrowser;
        return OtherBrowser;
	}
	else if (strstr(agent, "Mozilla/0"))
    {
        if (strstr(agent, " I;") || strstr(agent, "(I;") ||
             strstr(agent, "( I ;") ||strstr(agent, " I ;") ||
            strstr(agent, "I)"))
		{
			switch (systype)
			{
			case Win95System: return Netscape0OnWin95IntBrowser;
			case WinNTSystem: return Netscape0OnWinNTIntBrowser;
			case Win31System: return Netscape0OnWin31IntBrowser;
			case MacSystem: return Netscape0OnMacIntBrowser;
			default: return Netscape0IntBrowser;
			}
		}

		switch (systype)
		{
		case Win95System: return Netscape0OnWin95Browser;
		case WinNTSystem: return Netscape0OnWinNTBrowser;
		case Win31System: return Netscape0OnWin31Browser;
		case MacSystem: return Netscape0OnMacBrowser;
		default: return Netscape0Browser;
		}
    }
	else if (strstr(agent, "Mozilla/1"))
    {
        if (strstr(agent, " I;") || strstr(agent, "(I;") ||
             strstr(agent, "( I ;") ||strstr(agent, " I ;") ||
            strstr(agent, "I)"))
		{
			switch (systype)
			{
			case Win95System: return Netscape1OnWin95IntBrowser;
			case WinNTSystem: return Netscape1OnWinNTIntBrowser;
			case Win31System: return Netscape1OnWin31IntBrowser;
			case MacSystem: return Netscape1OnMacIntBrowser;
			default: return Netscape1IntBrowser;
			}
		}

		switch (systype)
		{
		case Win95System: return Netscape1OnWin95Browser;
		case WinNTSystem: return Netscape1OnWinNTBrowser;
		case Win31System: return Netscape1OnWin31Browser;
		case MacSystem: return Netscape1OnMacBrowser;
		default: return Netscape1Browser;
		}
    }
	else if (strstr(agent, "Mozilla/2"))
    {
        if (strstr(agent, " I;") || strstr(agent, "(I;") ||
             strstr(agent, "( I ;") ||strstr(agent, " I ;") ||
            strstr(agent, "I)"))
		{
			switch (systype)
			{
			case Win95System: return Netscape2OnWin95IntBrowser;
			case WinNTSystem: return Netscape2OnWinNTIntBrowser;
			case Win31System: return Netscape2OnWin31IntBrowser;
			case MacSystem: return Netscape2OnMacIntBrowser;
			default: return Netscape2IntBrowser;
			}
		}

        if (strstr(agent, "Gold"))
		{
			switch (systype)
			{
			case Win95System: return Netscape2OnWin95GoldBrowser;
			case WinNTSystem: return Netscape2OnWinNTGoldBrowser;
			case Win31System: return Netscape2OnWin31GoldBrowser;
			case MacSystem: return Netscape2OnMacGoldBrowser;
			default: return Netscape2GoldBrowser;
			}
		}

		switch (systype)
		{
		case Win95System: return Netscape2OnWin95Browser;
		case WinNTSystem: return Netscape2OnWinNTBrowser;
		case Win31System: return Netscape2OnWin31Browser;
		case MacSystem: return Netscape2OnMacBrowser;
		default: return Netscape2Browser;
		}
    }
	else if (strstr(agent, "Mozilla/3"))
    {
        if (strstr(agent, " I;") || strstr(agent, "(I;") ||
             strstr(agent, "( I ;") ||strstr(agent, " I ;") ||
            strstr(agent, "I)"))
		{
			switch (systype)
			{
			case Win95System: return Netscape3OnWin95IntBrowser;
			case WinNTSystem: return Netscape3OnWinNTIntBrowser;
			case Win31System: return Netscape3OnWin31IntBrowser;
			case MacSystem: return Netscape3OnMacIntBrowser;
			default: return Netscape3IntBrowser;
			}
		}

        if (strstr(agent, "Gold"))
		{
			switch (systype)
			{
			case Win95System: return Netscape3OnWin95GoldBrowser;
			case WinNTSystem: return Netscape3OnWinNTGoldBrowser;
			case Win31System: return Netscape3OnWin31GoldBrowser;
			case MacSystem: return Netscape3OnMacGoldBrowser;
			default: return Netscape3GoldBrowser;
			}
		}

		switch (systype)
		{
		case Win95System: return Netscape3OnWin95Browser;
		case WinNTSystem: return Netscape3OnWinNTBrowser;
		case Win31System: return Netscape3OnWin31Browser;
		case MacSystem: return Netscape3OnMacBrowser;
		default: return Netscape3Browser;
		}
    }
	else if (strstr(agent, "Mozilla/4"))
    {
        if (strstr(agent, " I;") || strstr(agent, "(I;") ||
             strstr(agent, "( I ;") ||strstr(agent, " I ;") ||
            strstr(agent, "I)"))
		{
			switch (systype)
			{
			case Win95System: return Netscape4OnWin95IntBrowser;
			case WinNTSystem: return Netscape4OnWinNTIntBrowser;
			case Win31System: return Netscape4OnWin31IntBrowser;
			case MacSystem: return Netscape4OnMacIntBrowser;
			default: return Netscape4IntBrowser;
			}
		}

		switch (systype)
		{
		case Win95System: return Netscape2OnWin95Browser;
		case WinNTSystem: return Netscape2OnWinNTBrowser;
		case Win31System: return Netscape2OnWin31Browser;
		case MacSystem: return Netscape2OnMacBrowser;
		default: return Netscape2Browser;
		}
    }
	else if (strstr(agent, "Microsoft Internet Explorer/4"))
	{
		switch (systype)
		{
		case Win95System: return MSIE4OnWin95Browser;
		case WinNTSystem: return MSIE4OnWinNTBrowser;
		case Win31System: return MSIE4OnWin31Browser;
		case MacSystem: return MSIE4OnMacBrowser;
		default: return MSIE4Browser;
		}
	}
	else if (strstr(agent, "aolbrowser"))
        return AolBrowser;
	else if (strstr(agent, "Lynx"))
        return LynxBrowser;

    return OtherBrowser;
}

/* Tally up a line, and do whatever we need done with it. */
void count_line(char *line)
{
    partner_struct *pPartner;
    partner_struct *pReferral;
    char *url, *referral_string, *agent;
    int status_code;
    BrowserEnum agent_type;

    if (!scan_func(line, &url, &referral_string, &agent, &status_code))
    {
        if (sg_errors_on && sg_error_file)
            fprintf(sg_error_file, "Unparseable: %s\n", line);
        return;
    }

    if (!tallied_type(url))
    {
        if (sg_bad_type_on && sg_bad_type_file)
            fprintf(sg_bad_type_file, "Bad Type In URL: %s\n", url);
        return;
    }

    if (agent && (*agent != '-'))
        agent_type = determine_browser(agent);
    else
        agent_type = UnknownBrowser;

    ++(BrowserArray[agent_type].total_count);
	if (sg_other_agent_file && (agent_type == OtherBrowser))
		fprintf(sg_other_agent_file, "%s\n", agent);

    pPartner = find_referral_struct(url, referral_string, &pReferral);

    /* We are on a bounce server, and got a valid referrer field, and */
    /* then, we bounced. */
    if (sg_bounce_server && pReferral && (status_code == 302))
    {
        ++(pReferral->bounce_count);
    }

    if (pPartner == sg_no_partner && pReferral)
        ++(pReferral->refer_count);
    else
        ++(pPartner->refer_count);

    /* 200-299 are successful status codes. 302 is redirect.*/
    /* Here we handle bad status codes. */
    if ((status_code / 100) != 2 && (status_code != 302))
    {
        if (pPartner == sg_no_partner && pReferral)
        {
            if (pReferral->printing_lines && sg_dump_file)
                fprintf(sg_dump_file, "Bad Return: %s\n", line);
            ++(pReferral->fail_count);
        }
        else
        {
            if (pPartner->printing_lines && sg_dump_file)
                fprintf(sg_dump_file, "Bad Return: %s\n", line);
            ++(pPartner->fail_count);
        }
        ++(BrowserArray[agent_type].fail_count);
    }

    /* Dump the lines, if we're interested. */
    if (pPartner == sg_no_partner && pReferral)
    {
        if (pReferral->printing_lines && sg_dump_file)
            fprintf(sg_dump_file, "%s\n", line);
    }
    else if (pPartner->printing_lines && sg_dump_file)
    {
        fprintf(sg_dump_file, "%s\n", line);
    }
}

/* Just what it says. Dump out our results. */
void dump_out_results()
{
	int i;

	/* The date */
	fputc('#', stdout);
	fputs(sg_date_string, stdout);
	fputc('\n', stdout);

	/* The server */
	fputc('#', stdout);
	fputs(sg_server_name, stdout);
	fputc('\n', stdout);

	/* The legend line */
	fputs("#Match				Referrals	Failed	Bounced\n\n", stdout);

	/* The query partners */
	fputs("#Query Matches\n", stdout);

	for (i = 0; i < sg_query_partners_size; ++i)
	{
		fprintf(stdout, "%s\t%d\t%d\t%d\n", sg_query_partners[i]->url_to_match,
			sg_query_partners[i]->refer_count,
			sg_query_partners[i]->fail_count,
			sg_query_partners[i]->bounce_count);
	}

	fputs("\n#Referral Matches\n", stdout);

	for (i = 0; i < sg_partners_size; ++i)
	{
		fprintf(stdout, "%s%s\t%d\t%d\t%d\n", sg_partners[i]->url_to_match,
			(sg_partners[i]->exact_match ? "" : "*"),
			sg_partners[i]->refer_count,
			sg_partners[i]->fail_count,
			sg_partners[i]->bounce_count);
	}

	fprintf(stdout, "%s\t%d\t%d\t%d\n", sg_no_partner->url_to_match,
		sg_no_partner->refer_count,
		sg_no_partner->fail_count,
		sg_no_partner->bounce_count);

	fputs("\n#Browser Stats\n", stdout);
	fputs("#Name			Total Count		Failed Count\n\n", stdout);

	for (i = 0; i < BrowserArray_size; ++i)
	{
		fprintf(stdout, "%s\t%d\t%d\n",
			BrowserArray[i].name,
			BrowserArray[i].total_count,
			BrowserArray[i].fail_count);
	}

	fputs("\n#End File", stdout);

	return;
}

/* Read back in the results of dump_out_results -- Allows adding servers together. */
/* Doing -- 0 = queries, 1 = referrals, 2 = agents. */
void read_in_result_line(char *line)
{
	static int doing = 0;
	static char buffer[4096];
	char *pPosition;
	char *cp;
	int i1, i2, i3;
    partner_struct **ppQuery;
	browser_struct *pBrowser;

	pPosition = line;

	if (!*pPosition)
		return;

	/* Maybe a control line? */
	if (*pPosition == '#')
	{
		if (!strcmp(pPosition, "#Query Matches"))
		{
			doing = 0;
			return;
		}

		if (!strcmp(pPosition, "#Referral Matches"))
		{
			doing = 1;
			return;
		}

		if (!strcmp(pPosition, "#Browser Stats"))
		{
			doing = 2;
			return;
		}

		if (!strncmp(pPosition, "#No Matched URL", strlen("#No Matched URL")))
		{
			pPosition = strchr(line, '\t');
			if (!pPosition || (sscanf(pPosition + 1, "%d\t%d\t%d", &i1, &i2, &i3) != 3))
			{
				fprintf(stderr, "Bad format in file!\n");
				return;
			}

			sg_no_partner->refer_count = i1;
			sg_no_partner->fail_count = i2;
			sg_no_partner->bounce_count = i3;

			return;
		}

		return;
	}

	if (doing == 0)
	{
		pPosition = strchr(line, '\t');
		if (pPosition)
			*pPosition = '\0';
		if (!pPosition || (sscanf(pPosition + 1, "%d\t%d\t%d", &i1, &i2, &i3) != 3))
		{
			fprintf(stderr, "Bad format in file!\n");
			exit(1);
		}

        s_chop_referrer = 0;
		cp = line;
        ppQuery = bsearch(&cp, sg_query_partners,
            sg_query_partners_size, sizeof (partner_struct *),
            find_referral_compare);

		if (!ppQuery)
		{
			fprintf(stderr, "Unknown query partner -- perhaps "
				"control file is outdated?\n");
			return;
		}

		(*ppQuery)->refer_count = i1;
		(*ppQuery)->fail_count = i2;
		(*ppQuery)->bounce_count = i3;

		return;
	}

	if (doing == 1)
	{
		pPosition = strchr(line, '\t');
		if (pPosition)
			*pPosition = '\0';
		if (!pPosition || (sscanf(pPosition + 1, "%d\t%d\t%d", &i1, &i2, &i3) != 3))
		{
			fprintf(stderr, "Bad format in file!\n");
			exit(1);
		}

		pPosition = line + strlen(line) - 1;
		if (*pPosition == '*')
			*pPosition = '\0';

        s_chop_referrer = 1;
		cp = line;
        ppQuery = bsearch(&cp, sg_partners,
            sg_partners_size, sizeof (partner_struct *),
            find_referral_compare);

		if (!ppQuery)
		{
			fprintf(stderr, "Unknown referral partner -- perhaps "
				"control file is outdated?\n");
			return;
		}

		(*ppQuery)->refer_count = i1;
		(*ppQuery)->fail_count = i2;
		(*ppQuery)->bounce_count = i3;

		return;
	}

	if (doing == 2)
	{
		pPosition = strchr(line, '\t');
		if (pPosition)
			*pPosition = '\0';
		if (!pPosition || (sscanf(pPosition + 1, "%d\t%d", &i1, &i2) != 2))
		{
			fprintf(stderr, "Bad format in file!\n");
			exit(1);
		}

		pBrowser = find_browser_struct(line);

		if (!pBrowser)
		{
			fprintf(stderr, "Unknown Browser -- perhaps "
				"control file is outdated?\n");
			return;
		}

		pBrowser->total_count = i1;
		pBrowser->fail_count = i2;

		return;
	}

	return;
}

/* We read a template file so that we don't have to recompile */
/* every time they want a silly text change. */
void load_output_template_file(FILE *template_file)
{
	/* Some file offsets, to get buffer lengths. */
	long partner_start;
	long partner_end;
	long agent_start;
	long agent_end;
	long main_start;
	long main_end;

	long last_position;

	char buffer[4096];
	char *pIn;
	char *pOut;

	int doing;

	/* 0 = nothing, 1 = partner, 2 = agent, 3 = main */
	doing = 0;

	partner_start = partner_end = 0L;
	agent_start = agent_end = 0L;
	main_start = main_end = 0L;
	last_position = 0L;

	rewind(template_file);

	while (((last_position = ftell(template_file)) != -1L) &&
		(fgets(buffer, 4096, template_file) != NULL))
	{
		if (doing == 0)
		{
			if (!strcmp(buffer, "#Define Partner\n"))
			{
				if (partner_start)
				{
					fprintf(stderr, "Fatal Error: Multiple Partner Defs\n");
					exit(1);
				}

				/* Yes, we want the line _after_ the Define line. */
				partner_start = ftell(template_file);
				doing = 1;
				continue;
			}

			if (!strcmp(buffer, "#Define Agent\n"))
			{
				if (agent_start)
				{
					fprintf(stderr, "Fatal Error: Multiple Agent Defs\n");
					exit(1);
				}

				agent_start = ftell(template_file);
				doing = 2;
				continue;
			}

			if (!strcmp(buffer, "#Define Main Page\n"))
			{
				if (main_start)
				{
					fprintf(stderr, "Fatal Error: Multiple Main Defs\n");
					exit(1);
				}

				main_start = ftell(template_file);
				doing = 3;
				continue;
			}

			continue;
		}

		if (doing == 1)
		{
			if (!strcmp(buffer, "#End Partner\n"))
			{
				partner_end = last_position;
				doing = 0;
				continue;
			}
		}

		if (doing == 2)
		{
			if (!strcmp(buffer, "#End Agent\n"))
			{
				agent_end = last_position;
				doing = 0;
				continue;
			}
		}

		if (doing == 3)
		{
			if (!strcmp(buffer, "#End Main Page\n"))
			{
				main_end = last_position;
				doing = 0;
				continue;
			}
		}

		/* We should never get here. */
	}

	/* Allocate and fill. */
	if (!partner_end || !agent_end || !main_end)
	{
		fprintf(stderr, "Fatal Error: One or more blocks not defined or ended in template.\n");
		exit(1);
	}

	/* Add an extra 1 for the nulling. */
	sg_main_page = (char *) malloc(main_end - main_start + 2);
	sg_partner_block = (char *) malloc(partner_end - partner_start + 2);
	sg_agent_block = (char *) malloc(agent_end - agent_start + 2);

	/* And here we go to actually fill the buffers. */
	rewind(template_file);

	fseek(template_file, main_start, SEEK_SET);
	fread(sg_main_page, sizeof (char), main_end - main_start + 1,
		template_file);

	if (ferror(template_file))
	{
		fprintf(stderr, "Fatal System Error in reading template file.\n");
		exit(1);
	}

	fseek(template_file, agent_start, SEEK_SET);
	fread(sg_agent_block, sizeof (char), agent_end - agent_start + 1,
		template_file);

	if (ferror(template_file))
	{
		fprintf(stderr, "Fatal System Error in reading template file.\n");
		exit(1);
	}

	fseek(template_file, partner_start, SEEK_SET);
	fread(sg_partner_block, sizeof (char), partner_end - partner_start + 1,
		template_file);

	if (ferror(template_file))
	{
		fprintf(stderr, "Fatal System Error in reading template file.\n");
		exit(1);
	}

	/* Zero off the strings. */
	sg_main_page[main_end - main_start] = '\0';
	sg_partner_block[partner_end - partner_start] = '\0';
	sg_agent_block[agent_end - agent_start] = '\0';

	/* Now do the split-ups. */
	/* We null out between 'segments', and put a one character */
	/* identifier after to specify what goes in the space, then */
	/* we increment a segment counter. */
	/* R - referrer */
	/* A - agent */
	/* T - total */
	/* F - failed */
	/* B - bounced */
	/* N - name */
	for (pIn = pOut = sg_main_page, sg_main_page_num_segs = 1; *pIn; )
	{
		if (*pIn != '-')
		{
			*pOut = *pIn;
			++pIn; ++pOut;
			continue;
		}

		if (!strncmp(pIn, "--Referrals--", strlen("--Referrals--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'R'; /* For referrals. */
			++pOut;
			pIn += strlen("--Referrals--");
			++sg_main_page_num_segs; /* And we have more segments. */
			continue;
		}

		if (!strncmp(pIn, "--Agents--", strlen("--Agents--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'A'; /* For agents */
			++pOut;
			pIn += strlen("--Agents--");
			++sg_main_page_num_segs; /* And we have more segments. */
			continue;
		}

		if (!strncmp(pIn, "--Date--", strlen("--Date--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'D'; /* For date */
			++pOut;
			pIn += strlen("--Date--");
			++sg_main_page_num_segs; /* And we have more segments. */
			continue;
		}

		*pOut = *pIn;
		++pOut;
		++pIn;
	}
	*pOut = '\0';

	for (pIn = pOut = sg_partner_block, sg_partner_block_num_segs = 1; *pIn; )
	{
		if (*pIn != '-')
		{
			*pOut = *pIn;
			++pIn; ++pOut;
			continue;
		}

		if (!strncmp(pIn, "--Total--", strlen("--Total--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'T'; /* For total. */
			++pOut;
			pIn += strlen("--Total--");
			++sg_partner_block_num_segs; /* And we have more segments. */
			continue;
		}

		if (!strncmp(pIn, "--Fail--", strlen("--Fail--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'F'; /* For failures */
			++pOut;
			pIn += strlen("--Fail--");
			++sg_partner_block_num_segs; /* And we have more segments. */
			continue;
		}

		if (!strncmp(pIn, "--Bounce--", strlen("--Bounce--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'B'; /* For bounces */
			++pOut;
			pIn += strlen("--Bounce--");
			++sg_partner_block_num_segs;
			continue;
		}

		if (!strncmp(pIn, "--Name--", strlen("--Name--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'N'; /* For names */
			++pOut;
			pIn += strlen("--Name--");
			++sg_partner_block_num_segs;
			continue;
		}

		*pOut = *pIn;
		++pOut;
		++pIn;
	}
	*pOut = '\0';

	for (pIn = pOut = sg_agent_block, sg_agent_block_num_segs = 1; *pIn; )
	{
		if (*pIn != '-')
		{
			*pOut = *pIn;
			++pIn; ++pOut;
			continue;
		}

		if (!strncmp(pIn, "--Total--", strlen("--Total--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'T'; /* For total. */
			++pOut;
			pIn += strlen("--Total--");
			++sg_agent_block_num_segs; /* And we have more segments. */
			continue;
		}

		if (!strncmp(pIn, "--Fail--", strlen("--Fail--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'F'; /* For failures */
			++pOut;
			pIn += strlen("--Fail--");
			++sg_agent_block_num_segs; /* And we have more segments. */
			continue;
		}

		if (!strncmp(pIn, "--Name--", strlen("--Name--")))
		{
			*pOut = '\0';
			++pOut;
			*pOut = 'N'; /* For names */
			++pOut;
			pIn += strlen("--Name--");
			++sg_agent_block_num_segs;
			continue;
		}

		*pOut = *pIn;
		++pOut;
		++pIn;
	}
	*pOut = '\0';

	/* And we're done. */
	return;
}

/* Dump out one referrer, according to the template. */
void dump_out_one_referrer(partner_struct *pPartner)
{
	int i;
	char *pPosition;

	i = sg_partner_block_num_segs - 1;

	fputs(sg_partner_block, stdout);

	pPosition = sg_partner_block;
	while (i--)
	{
		pPosition = pPosition + strlen(pPosition) + 1;
		switch (*pPosition)
		{
		case 'T': /* Total */
			fprintf(stdout, "%d", pPartner->refer_count);
			break;
		case 'F': /* Failed */
			fprintf(stdout, "%d", pPartner->fail_count);
			break;
		case 'B': /* Bounced */
			fprintf(stdout, "%d", pPartner->bounce_count);
			break;
		case 'N': /* Name */
			fputs(pPartner->long_name, stdout);
		}

		++pPosition;
		fputs(pPosition, stdout);
		continue;
	}

	return;
}

/* Dump out one agent, according to the template. */
void dump_out_one_agent(browser_struct *pAgent)
{
	int i;
	char *pPosition;

	i = sg_agent_block_num_segs - 1;

	fputs(sg_agent_block, stdout);

	pPosition = sg_agent_block;
	while (i--)
	{
		pPosition = pPosition + strlen(pPosition) + 1;
		switch (*pPosition)
		{
		case 'T': /* Total */
			fprintf(stdout, "%d", pAgent->total_count);
			break;
		case 'F': /* Failed */
			fprintf(stdout, "%d", pAgent->fail_count);
			break;
		case 'N': /* Name */
			fputs(pAgent->name, stdout);
		}

		++pPosition;
		fputs(pPosition, stdout);
		continue;
	}

	return;
}

/* We prettify our results. Ooh, joy! */
void dump_out_results_nicely()
{
	partner_struct **pPartnerArray;
	int i, j;
	int segs;
	char *pPosition;

    /* Allocate for a sort together. */
    pPartnerArray = (partner_struct **)
        calloc(sg_partners_size + sg_query_partners_size, sizeof (partner_struct *));

	for (i = 0; i < sg_partners_size; ++i)
	{
		pPartnerArray[i] = sg_partners[i];
	}

	for (i = 0; i < sg_query_partners_size; ++i)
	{
		pPartnerArray[i + sg_partners_size] = sg_query_partners[i];
	}

    qsort(pPartnerArray, sg_partners_size + sg_query_partners_size, 
		sizeof (partner_struct *),
        sort_referral_compare_for_sum);

	/* Add up things with the same name. */
	for (i = 0, j = 0; i < sg_partners_size + sg_query_partners_size - 1; ++i)
	{
		if (!strcmp(pPartnerArray[i]->long_name, pPartnerArray[i + 1]->long_name))
		{
			pPartnerArray[i + 1]->refer_count += pPartnerArray[i]->refer_count;
			pPartnerArray[i + 1]->fail_count += pPartnerArray[i]->fail_count;
			pPartnerArray[i + 1]->bounce_count += pPartnerArray[i]->bounce_count;
			continue;
		}
		else
		{
			pPartnerArray[j] = pPartnerArray[i];
			++j;
		}
	}

	/* And do the last one. j holds the unique number size. */
	if (sg_partners_size + sg_query_partners_size != 0)
	{
		pPartnerArray[j] = pPartnerArray[sg_partners_size + sg_query_partners_size - 1];
		++j;
	}

	/* And do the dump */

	segs = sg_main_page_num_segs - 1;

	fputs(sg_main_page, stdout);

	pPosition = sg_main_page;

	while (segs--)
	{
		pPosition = pPosition + strlen(pPosition) + 1;
		switch (*pPosition)
		{
		case 'A': /* Agents */
			for (i = 0; i < BrowserArray_size; ++i)
				dump_out_one_agent(BrowserArray + i);
			break;
		case 'R': /* Referrers */
			for (i = 0; i < j; ++i)
				dump_out_one_referrer(pPartnerArray[i]);
			dump_out_one_referrer(sg_no_partner);
			break;
		case 'D':
			fputs(sg_date_string, stdout);
		}

		++pPosition;
		fputs(pPosition, stdout);
		continue;
	}

	/* Free up the memory we made */
	free((void *) pPartnerArray);
}

typedef void (*lineread_func)(char *);

/* main */
/* Do the options, then just loop on the lines. */
/* Syntax is: (name) -s servername -C controlfile -D dumpfile -E errorfile -T typefile */
/* -B bouncefile -d date -f logformat -P prettyfile */
int main(int argc, char *argv[])
{
	FILE *control_file = NULL;
	FILE *pretty_file = NULL;
	char buffer[4096];
	unsigned int length;
	lineread_func line_read_func = NULL;

	if (argc == 1)
	{
		fprintf(stderr, "No Information. Aborting.");
		exit(0);
	}

	--argc;
	++argv;

	if (argc % 2)
	{
		fprintf(stderr, "Format is: (name) -s servername -C controlfile -D dumpfile -E errorfile "
				"-T typefile -B bouncefile -d date\nAll fields except controlfile and "
				"logformat are optional.\n"
				"(You entered an impossible number of arguments for a syntactically valid command.\n");
		exit(1);
	}

	for ( ; argc > 0; argc -= 2, argv += 2)
	{
		if (argv[0][0] != '-')
		{
			fprintf(stderr, "Format is: (name) -s servername -C controlfile -D dumpfile -E errorfile "
				"-T typefile -B bouncefile -d date\nAll fields except controlfile and "
				"logformat are optional.\n");
			exit(1);
		}

		switch (argv[0][1])
		{
		case 's':
			sg_server_name = argv[1];
			continue;
		
		case 'd':
			sg_date_string = argv[1];
			continue;

		case 'E':
			if ((sg_error_file = fopen(argv[1], "w")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open error dump file.\n");
				exit(1);
			}
			continue;

		case 'C':
			if ((control_file = fopen(argv[1], "r")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open control file.\n");
				exit(1);
			}
			continue;

		case 'D':
			if ((sg_dump_file = fopen(argv[1], "w")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open line dump file.\n");
				exit(1);
			}
			continue;

		case 'T':
			if ((sg_bad_type_file = fopen(argv[1], "w")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open bad type dump file.\n");
				exit(1);
			}
			continue;

		case 'B':
			if ((sg_bounce_file = fopen(argv[1], "w")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open bounce count file.\n");
				exit(1);
			}
			sg_bounce_server = 1;
			continue;

		case 'f':
			if (!strcasecmp(argv[1], "apache"))
				scan_func = scan_apache_log;
			else if (!strcasecmp(argv[1], "iis"))
				scan_func = scan_iis_log;
			else if (!strcasecmp(argv[1], "extended"))
				scan_func = scan_extended_log;
			else
			{
				fprintf(stderr, "Fatal Error: Invalid Log Format. Valid Types are 'apache'"
					" 'iis' 'extended'.\n");
				exit(1);
			}
			continue;

		case 'A':
			if ((sg_other_agent_file = fopen(argv[1], "w")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open other agents file.\n");
				exit(1);
			}
			continue;

		case 'P':
			if ((pretty_file = fopen(argv[1], "rb")) == NULL)
			{
				fprintf(stderr, "Fatal Error: Cannot open pretty file.\n");
				exit(1);
			}
			sg_pretty = 1;
			continue;
		}
	}

	if (!control_file)
	{
		fprintf(stderr, "Fatal Error: No control file specified.\n");
		exit(1);
	}

	if (!scan_func && !sg_pretty)
	{
		fprintf(stderr, "Fatal Error: No log format specified.\n");
		exit(1);
	}

	/* And load the control file */
	load_control_file(control_file);
	fclose(control_file);

	if (sg_pretty)
	{
		load_output_template_file(pretty_file);
		line_read_func = read_in_result_line;
		fclose(pretty_file);
	}
	else
		line_read_func = count_line;

	/* And run through all the lines. */
	while (fgets(buffer, 4096, stdin) != NULL)
	{
		/* Zero off the ending whitespace */
		length = strlen(buffer);
		while (isspace(buffer[length - 1]) || buffer[length - 1] == ',')
			--length;

		buffer[length] = '\0';

		line_read_func(buffer);
	}

	if (!sg_server_name)
		sg_server_name = "Server Unspecified";
	if (!sg_date_string)
		sg_date_string = "Date Unspecified";

	/* And dump the output */
	if (sg_pretty)
		dump_out_results_nicely();
	else
		dump_out_results();

	exit(0);
	return 0;
}
