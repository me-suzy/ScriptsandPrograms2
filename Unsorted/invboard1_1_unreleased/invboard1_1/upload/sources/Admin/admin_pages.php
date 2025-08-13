<?php

// Simple library that holds all the links for the admin cp

// CAT_ID => array(  PAGE_ID  => (PAGE_NAME, URL ) )

// $PAGES[ $cat_id ][$page_id][0] = Page name
// $PAGES[ $cat_id ][$page_id][1] = Url

 
$PAGES = array(

				0 => array (
							 1 => array( 'IPS Latest News'         , 'act=ips&code=news'   ),
							 2 => array( 'Check for updates'      , 'act=ips&code=updates'  ),
							 3 => array( 'Documentation'     , 'act=ips&code=docs'    ),
							 4 => array( 'Get Support'       , 'act=ips&code=support' ),
							 5 => array( 'IPS Hosting'  , 'act=ips&code=host'   ),
							 6 => array( 'Purchase Services'    , 'act=ips&code=purchase'     ),
							 
						   ),

				1 => array (
							 1 => array( 'Basic Config', 'act=op&code=url'   ),
							 2 => array( 'Security & Privacy'      , 'act=op&code=secure'  ),
							 3 => array( 'Topics, Posts & Polls', 'act=op&code=post'    ),
							 4 => array( 'User Profiles'       , 'act=op&code=avatars' ),
							 5 => array( 'Date Formats'  , 'act=op&code=dates'   ),
							 6 => array( 'CPU Saving'    , 'act=op&code=cpu'     ),
							 7 => array( 'Cookies'       , 'act=op&code=cookie'  ),
							 8 => array( 'PM Set up'       , 'act=op&code=pm'    ),
							 9 => array( 'Board on/off'    , 'act=op&code=board' ),
							 10 =>array( 'News Set-up'    , 'act=op&code=news' ),
							 11 =>array( 'Calendar Set-up'    , 'act=op&code=calendar' ),
							 12 =>array( 'COPPA Set-up'       , 'act=op&code=coppa' ),
							 
							 
						   ),

				2 => array (
							 1 => array( 'New Category'  , 'act=cat&code=new'    ),
							 2 => array( 'New Forum'     , 'act=forum&code=newsp'    ),
							 3 => array( 'Manage'        , 'act=cat&code=edit'   ),
							 4 => array( 'Re-Order Categories' , 'act=cat&code=reorder'),
							 5 => array( 'Re-Order Forums', 'act=forum&code=reorder'),
							 6 => array( 'Moderators'    , 'act=mod'               ),
						   ),
						   
						   
				3 => array (
							1 => array ( 'Pre-Register'        , 'act=mem&code=add'  ),
							2 => array ( 'Find/Edit User'      , 'act=mem&code=edit' ),
							3 => array ( 'Delete User(s)'      , 'act=mem&code=del'  ),
							4 => array ( 'Ban Settings'        , 'act=mem&code=ban'  ),
							5 => array ( 'User Title/Ranks'    , 'act=mem&code=title'),
							6 => array ( 'Manage User Groups'  , 'act=group'         ),
							7 => array ( 'Manage Registrations', 'act=mem&code=mod'  ),
							8 => array ( 'Custom Profile Fields', 'act=field'       ),
							9 => array ( 'Bulk Email Members'   , 'act=mem&code=mail' ),
						   ),
						   
				4 => array (
							1 => array( 'Manage Word Filters', 'act=op&code=bw'    ),
							2 => array( 'Manage Emoticons', 'act=op&code=emo'   ),
							3 => array( 'Manage Help Files', 'act=help'         ),
							4 => array( 'Recount Statistics', 'act=op&code=count'    ),
							5 => array( 'View Moderator Logs', 'act=modlog'    ),
							6 => array( 'View Admin Logs'    , 'act=adminlog'    ),
						   ),
						   
				5 => array (
							1 => array( 'Manage Board Wrappers'   , 'act=wrap'                ),
							2 => array( 'Manage Skin Templates'   , 'act=templ'     ),
							3 => array( 'Manage Style Sheets'     , 'act=style'               ),
							4 => array( 'Manage Macros'           , 'act=image'               ),
							5 => array( '<b>Manage Skin Sets</b>' , 'act=sets'      ),
							6 => array( 'Import Skin files'       , 'act=import'      ),
							
						   ),
						   
				6 => array (
							1 => array( 'Manage Languages' , 'act=lang'             ),
							2 => array( 'Import a Language', 'act=lang&code=import' ),
						   ),
						   
				7 => array (
							1 => array( 'Registration Stats' , 'act=stats&code=reg'   ),
							2 => array( 'New Topic Stats'    , 'act=stats&code=topic' ),
							3 => array( 'Post Stats'         , 'act=stats&code=post'  ),
							4 => array( 'Private Message'    , 'act=stats&code=msg'   ),
							5 => array( 'Topic Views'        , 'act=stats&code=views' ),
						   ),
						   
				8 => array (
							1 => array( 'mySQL Toolbox'   , 'act=mysql'           ),
							2 => array( 'mySQL Back Up'   , 'act=mysql&code=backup'    ),
							3 => array( 'SQL Runtime Info', 'act=mysql&code=runtime'   ),
							4 => array( 'SQL System Vars' , 'act=mysql&code=system'    ),
							5 => array( 'SQL Processes'   , 'act=mysql&code=processes' ),
						   ),
				
							
						   
			   );
			   
			   
$CATS = array (   
				  0 => "IPS Services",
				  1 => "Board Settings",
			      2 => 'Forum Control',
				  3 => 'Users and Groups',
				  4 => 'Administration',
				  5 => 'Skins & Templates',
				  6 => 'Languages',
				  7 => 'Statistic Center',
				  8 => 'SQL Management',
			  );
			  
$DESC = array (
				  0 => "Get IPS latest news, documentation, request support, purchase extra services and more...",
				  1 => "Edit forum settings such as cookie paths, security features, posting abilities, etc",
				  2 => "Create, edit, remove and re-order categories, forums and moderators",
				  3 => "Edit, register, remove and ban members. Set up member titles and ranks. Manage User Groups and moderated registrations",
				  4 => "Manage Help Files, Bad Word Filters and Emoticons",
				  5 => "Manage templates, skins, colours and images.",
				  6 => "Manage language sets",
				  7 => "Get registration and posting statistics",
				  8 => "Manage your SQL database; repair, optimize and export data",
			  );
			  
			  
?>