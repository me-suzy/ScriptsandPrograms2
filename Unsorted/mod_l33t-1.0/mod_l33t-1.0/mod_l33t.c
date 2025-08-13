/*
=============================mod_l33t 1.0===================================
																			  
This will be filled with nothing of importance and wangs for now.

- L33t Kr3w - Mr.Oreo and DethPigeon

B========D~~~~~~~~~~
8-----------b~~~~~~~~~~~~~
O//\\//\\//\\//\\//\\//\\//\\//\\//\\D========

==============================================================================
*/

/*
    CopyRight 2002, Justin Reynen, Dan Carter

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
#include "httpd.h"
#include "http_config.h"
#include "http_request.h"
#include "http_core.h"
#include "http_log.h"
#include "http_main.h"
#include "http_protocol.h"
#include "util_script.h"
#include "http_conf_globals.h"

MODULE_VAR_EXPORT module l33t_module;

typedef struct l33t_ll_node l33t_ll_node;
typedef struct l33t_ll_head l33t_ll_head;
typedef struct l33t_alias l33t_alias;

//Alias linked list
struct l33t_alias
{
	char* is;
	char* should;
	l33t_alias* next;
};

//Virtual Host node
struct l33t_ll_node
{
	l33t_ll_node* next;
	l33t_ll_head* head;

	char* hostName;
	char* user;
	char* group;
	char* root;
	l33t_alias* aliasList;

};

//Virtual Host Linked List
struct l33t_ll_head
{
	int numNodes;
							
	l33t_alias* gAliasList;
	l33t_ll_node* start;
	l33t_ll_node* end; //Points to the last available node
};

//Configuration Structure
typedef struct
{
	l33t_ll_head* head;
	pool* master_pool;  //Pointer to a pool that won't be cleared every request
	pool* l33t_pool; //We'll handle our own pooling since apache has a habit
					 //of gaining weight like a black housewife.
	char * conf_file;
	char * base_path;
	char * log_file;
	long modified; //When the file was last modified...
} l33t_config;


///////////////////
///Internal Functions
///////////////////

//Take off the slash at the end
int L33t_TrimSlash(char* in)
{
	int i = strlen(in);

	if( in[i-1] == '/')
		in[i-1] = 0;

return(0);
}

//Log Method
int L33t_Log(l33t_config* cfg, const char* error,...)
{

	if (cfg->log_file) {
		va_list args;
		char com[2048];
		FILE* fout;
	
		va_start(args, error);
		vsprintf(com, error, args);
	
		if( (fout = fopen(cfg->log_file,"a")) == NULL)
			printf("Error opening log file\n");
		else {
			fprintf(fout,"%s\n",com);
		}
		fclose(fout);
		va_end(args);
	}

return(1);
}

//Initializes a Virtual Host node
l33t_ll_node* L33t_CreateNode(l33t_config* cfg)
{
	l33t_ll_node* ret = (l33t_ll_node*)ap_pcalloc(cfg->l33t_pool, sizeof(l33t_ll_node));
	ret->aliasList = (l33t_alias*) ap_pcalloc(cfg->l33t_pool, sizeof(l33t_alias));
	ret->head = cfg->head;

return(ret);
}

//Initializes the Virtual Host Linked List Head Node
l33t_ll_head* L33t_CreateHeadNode(l33t_config* cfg)
{
	l33t_ll_head* ret = (l33t_ll_head*)ap_pcalloc(cfg->l33t_pool, sizeof(l33t_ll_node));

return(ret);
}

//Initializes the Virtual Host Linked List
int L33t_InitLL(l33t_config* cfg)
{
	
	cfg->l33t_pool = ap_make_sub_pool(cfg->master_pool); //Damn you apache!! I murder you a thousand times!!!
	cfg->head = L33t_CreateHeadNode(cfg);
	cfg->head->start = L33t_CreateNode(cfg);
	cfg->head->end = cfg->head->start;
	cfg->head->numNodes = 0;
	cfg->head->gAliasList = (l33t_alias*) ap_pcalloc(cfg->l33t_pool, sizeof(l33t_alias));

return(0);
}


//Make sure the string starts with the requested tag
//if it doesn't then it returns NULL, if it does, then 
//it returns a pointer to the parameter....
char* L33t_ParseTag(char* line, char* req)
{
	char* ret = line;

	while(*req)
		if(*ret++ != *req++)
			return(NULL);
	
return(ret+1);
}

//Add an Alias to the alias list
int L33t_AddAlias(l33t_alias* dest, char* pIs, char* pShould, pool* p)
{
	if(dest->next)
	{
		L33t_AddAlias(dest->next, pIs, pShould, p);
	}
	else
	{
		L33t_TrimSlash(pIs);
		dest->is = pIs;
		dest->should = pShould;
		dest->next = (l33t_alias*) ap_pcalloc(p, sizeof(l33t_alias));
	}

return(0);
}


int L33t_LoadConfigFile(l33t_config* cfg)
{
	FILE* fin;
	char line[2048]; //That's a pretty fucking long line!
	char* param;
	char tmp1[2048];
	char tmp2[2048]; //Used for the Alias tag...
	struct stat buf;
	long timer;
	L33t_Log(cfg, "Loading in config file...");

	L33t_InitLL(cfg);

	//Before we start loading shit in, check to see when the file was last modified
	if (stat(cfg->conf_file,&buf))
        return(L33t_Log(cfg, "Config file does not exist"));

	cfg->modified = buf.st_mtime;

	//loop until the the file can be opened or 20
	//seconds pass...
    timer = time(NULL);
    while ((fin = fopen(cfg->conf_file, "r")) == NULL && timer + 20 > time(NULL)); 
    if(fin == NULL)
        return(L33t_Log(cfg, "Error opening config file."));

	//Load in the file
	while(!feof(fin))
	{
		memset(line,0,2048);
		if(fgets(line, 2048,fin) == NULL)
			if(!feof(fin))
				return(L33t_Log(cfg, "Error Reading in line from config file."));

		if(line[strlen(line)-1] == '\n')
			line[strlen(line)-1] = 0;
		if(line[0] == '#') {
		}
		else if((param = L33t_ParseTag(line,"GAlias")))
		{
			sscanf(param,"%s %s",tmp1, tmp2);
			L33t_AddAlias(cfg->head->gAliasList, ap_pstrdup(cfg->l33t_pool,tmp1), ap_pstrdup(cfg->l33t_pool,tmp2), cfg->l33t_pool);
		}
		else if((param = L33t_ParseTag(line,"ServerName")))
		{
			cfg->head->end->hostName = ap_pstrdup(cfg->l33t_pool, param);
		}
		else if((param = L33t_ParseTag(line,"User")))
		{
			cfg->head->end->user = ap_pstrdup(cfg->l33t_pool, param);
		}
		else if((param = L33t_ParseTag(line,"Group")))
		{
			cfg->head->end->group = ap_pstrdup(cfg->l33t_pool, param);
		}
		else if((param = L33t_ParseTag(line,"DocumentRoot")))
		{
			cfg->head->end->root = ap_pstrdup(cfg->l33t_pool, param);
		}
		else if((param = L33t_ParseTag(line,"Alias")))
		{
			sscanf(param,"%s %s",tmp1, tmp2);
			L33t_AddAlias(cfg->head->end->aliasList, ap_pstrdup(cfg->l33t_pool,tmp1), ap_pstrdup(cfg->l33t_pool,tmp2), cfg->l33t_pool);
		}
		else if((param = L33t_ParseTag(line,"End")))
		{
			cfg->head->end->next = L33t_CreateNode(cfg);
			cfg->head->end = cfg->head->end->next;
			cfg->head->numNodes++;
		}
		else
		{
			if(*line)
				L33t_Log(cfg, "Unknown Directive found in config file.");
		}
		
	}
	
	L33t_Log(cfg, "Finished, total of %d virtual servers loaded.", cfg->head->numNodes);

	fclose(fin);
return(0);
}

int L33t_UnLoadConfigFile(l33t_config* cfg)
{
	L33t_Log(cfg, "Unloading Config file...");

	if(cfg->l33t_pool)
		ap_destroy_pool(cfg->l33t_pool);

	cfg->modified = 0;

	L33t_Log(cfg, "Finished unloading config file.");

return(0);
}

//Get the correct node according to the hostname passed in.
//This function recursively loops through the nodes in such a way that
//if a host like "stuff.www.l33t.ca" isn't found, then it will look for
//"www.l33t.ca", then try for "l33t.ca"...it keeps trimming up to the next
//dot until either a match is found or no more trimming can be done.
l33t_ll_node* L33t_GetNode(l33t_config* cfg, const char* host, l33t_ll_node* pNode)
{
	if(strcmp(pNode->hostName, host) == 0)
		return(pNode);
	else {
		if(pNode->next->next == NULL)//don't ask :)
		{
			while(*host++ != '.')
				if(*host == 0)
					return(NULL);
			
			return(L33t_GetNode(cfg, host, pNode->head->start));
		}	
		else
			return(L33t_GetNode(cfg, host, pNode->next));
	}
return(NULL);
}

//Single test. Test an Alias against the URI to see if there's a match
int L33t_URIMatch(char* alias, char* uri)
{
	int lenAlias = strlen(alias);
	int lenUri = strlen(uri);
	int i;

	if(lenAlias > lenUri)
		return(0);

	for(i=0; i < lenAlias; i++)
	{
		if( alias[i] != uri[i])
			return(0);
	}

	if( (uri[i] == 0) || (uri[i] == '/') )
		return(1);

return(0);
}

//Loop through all the aliases in a Virtual Host node
//and return the matching alias if one exists
l33t_alias* L33t_IsAlias(l33t_alias* n, char* uri)
{
	if((!*uri)||(n->next==NULL))
		return(NULL);

	if( L33t_URIMatch( n->is, uri))
		return(n);
	else
		if(n->next->next == NULL) //Don't Ask!! :)
			return(NULL);	
		else
			return(L33t_IsAlias(n->next, uri));

return(NULL);
}


//When an Alias-URI match is found, this function is called to
//trim the found Alias from the URI as it shouldn't be there
char* L33t_TrimURI(char* p, char* uri)
{
	while(*p)
	{
		p++;
		uri++;
	}
	
return(uri);
}


//Set the Server Group for suexec
int L33t_SetGroupID(char* arg, request_rec* cmd)
{

    cmd->server->server_gid = ap_gname2id(arg);

return(0);
}

//Set the User for suexec
int L33t_SetUserID(char* arg, request_rec* cmd)
{
	ap_user_name =  ap_pstrdup(cmd->pool, arg);

	cmd->server->server_uid = ap_uname2id(arg);

return(0);
}
	

//////////////////////////////
//Functions that Apache Calls/
//////////////////////////////

//Load in the crap from httpd.conf

static const char *cmd_l33tconf(cmd_parms *cmd, void *dconf, char *str)
{
    l33t_config* cfg;

    cfg = (l33t_config*)
            ap_get_module_config(cmd->server->module_config, &l33t_module);

    cfg->conf_file = ap_pstrdup(cfg->master_pool, str);

    return NULL;
}

static const char *cmd_l33tbase(cmd_parms *cmd, void *dconf, char *str)
{
    l33t_config* cfg;

    cfg = (l33t_config*)
            ap_get_module_config(cmd->server->module_config, &l33t_module);

	L33t_TrimSlash(str);
    cfg->base_path = ap_pstrdup(cfg->master_pool, str);

    return NULL;
}

static const char *cmd_l33tlog(cmd_parms *cmd, void *dconf, char *str)
{
    l33t_config* cfg;

    cfg = (l33t_config*)
            ap_get_module_config(cmd->server->module_config, &l33t_module);

    cfg->log_file = ap_pstrdup(cfg->master_pool,str);

    return NULL;
}

static const command_rec command_table[] = {
    { "L33TConfFile",     cmd_l33tconf,     NULL, RSRC_CONF,   TAKE1,
      "Path to the l33t dynamic virtual-host database" },
    { "L33TBasePath",     cmd_l33tbase,     NULL, RSRC_CONF,   TAKE1,
      "Path to the base document root" },
    { "L33TLogFile",      cmd_l33tlog,      NULL, RSRC_CONF,   TAKE1,
      "Log file path" },
    { NULL }
};

//Get a pointer to a "master pool" that isn't cleared every request
void* ServerConfig(pool* p, server_rec* s)
{	
	l33t_config* config = (l33t_config*) ap_pcalloc(p, sizeof(l33t_config) );
	config->master_pool = p;
	config->base_path = ap_pstrdup(config->master_pool, "");
	return((void*)config);
}


//The guts of this mod...
int FilenameTranslation(request_rec *r)
{
	char* res;//resultant filename to be appended to base path
	char* uri;//local copy of uri
	struct stat buf;//Used to check if we need to reload the config file...
	l33t_ll_node* reqNode;
	l33t_alias* a;

	l33t_config* cfg = (l33t_config*)ap_get_module_config (r->server->module_config,
					       &l33t_module);
	
	if(!cfg)
		return(DECLINED); //We ain't got no config file!!!

	L33t_Log(cfg, "-------------------------------------");
	L33t_Log(cfg, "Processing request : %s",r->the_request);
	L33t_Log(cfg, "HostName : %s", r->hostname);
	L33t_Log(cfg, "URI : %s", r->uri);
	L33t_Log(cfg, "Server Hostname : %s",r->server->server_hostname);

	//Check to see if the file has been modified, reload it if it has
	if (stat(cfg->conf_file,&buf)) {
		L33t_Log(cfg, "Config file does not exist");
		return(DECLINED); //We ain't got no config file!!!
	}
	if(cfg->modified != buf.st_mtime)
	{
		L33t_Log(cfg, "Changes detected on config file, re-loading.");
		L33t_UnLoadConfigFile(cfg);
		L33t_LoadConfigFile(cfg);
	}
	//Get the correct Virtual Host Node
	reqNode = L33t_GetNode(cfg, r->hostname, cfg->head->start);
	if(!reqNode)
	{
		L33t_Log(cfg, "Couldn't locate virtual server node, unknown host");
		return(HTTP_NOT_FOUND);
	}

	//Check to see if the uri matches any specified aliases, global and local.
	uri = ap_pstrdup(r->pool, r->uri);
	L33t_TrimSlash(uri);
	if( (a = L33t_IsAlias(reqNode->head->gAliasList, uri)) )
	{
		//Global Alias found
		//The URI should be appended to the found Alias
		res = ap_pstrdup(r->pool, a->should);
		L33t_Log(cfg, "Request is a Global Alias, after translation : %s",res);
		L33t_TrimSlash(res);
		uri = L33t_TrimURI(a->is, uri);
	}
	else if( (a = L33t_IsAlias(reqNode->aliasList, uri)) )
	{
		//Local alias found
		//The URI should be appended to the found Alias
		res = ap_pstrdup(r->pool, a->should);		
		L33t_Log(cfg, "Request is an Alias, after translation : %s",res);
		L33t_TrimSlash(res);
		uri = L33t_TrimURI(a->is, uri);
	}
	else
	{
		//No Aliases found
		//The uri should be appended to the node's document root 
		res = ap_pstrdup(r->pool, reqNode->root);
		L33t_TrimSlash(res);
	}

	//Put the three path parts together: base_path - res - uri
	res = ap_pstrcat(r->pool, res, uri, NULL);
	r->filename = ap_pstrcat(r->pool, cfg->base_path, res, NULL);

	//Set the server name so it logs correctly
	r->server->server_hostname = ap_pstrdup(r->pool, reqNode->hostName);

	//Set the group and user for suexec
	L33t_SetGroupID( reqNode->group, r);
	L33t_SetUserID( reqNode->user, r);

	//We're done! Now log it for debugging
	L33t_Log(cfg, "User : %d", r->server->server_uid);
	L33t_Log(cfg, "Group : %d", r->server->server_gid);
	L33t_Log(cfg, "Node Root + URI: %s", res);
	L33t_Log(cfg, "Final Path: %s", ap_pstrcat(r->pool, cfg->base_path, res, NULL));

	return(OK);		  
}


/*
Required Apache exports and crap...
*/

module MODULE_VAR_EXPORT l33t_module =
{
    STANDARD_MODULE_STUFF,
    NULL,                 /* initializer */
    NULL,						/* create per-dir config */
    NULL,						/* merge per-dir config */
    ServerConfig,				/* server config */
    NULL,		/* merge server config */
    command_table,              /* command table */
    NULL,                       /* handlers */
    FilenameTranslation,        /* filename translation*/
    NULL,						/* check_user_id */
    NULL,						/* check auth */
    NULL,						/* check access */
    NULL,                       /* type_checker DON'T FUCK WITH THIS!!*/
    NULL,						/* fixups */
    NULL,						/* logger */
    NULL,						/* header parser */
    NULL,                 /* child_init */
    NULL,                 /* child_exit */
    NULL						/* post read-request */
};
