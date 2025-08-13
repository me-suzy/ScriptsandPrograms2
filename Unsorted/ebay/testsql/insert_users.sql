/*	$Id: insert_users.sql,v 1.2 1999/02/21 02:57:01 josh Exp $	*/
/* creates several dummy users for testing */

insert into ebay_users
	(	marketplace,
		id,			
		userid,		
		user_state,		
		password,	
		salt,		
		last_modified
	)				
	values	
	(	0,
		1,	
		'skippy@ebay.com',	
		3,
		'$1$4329$/NMdZxIqZw16oAs4MUmKe/',
		4329,		
		sysdate			
	);

insert into ebay_users
	(	marketplace,
		id,			
		userid,		
		user_state,		
		password,	
		salt,		
		last_modified
	)				
	values	
	(	0,
		2,	
		'michael@ebay.com',	
		3,
		'$1$4329$/NMdZxIqZw16oAs4MUmKe/',
		4329,		
		sysdate			
	);

	insert into ebay_users
	(	marketplace,
		id,			
		userid,		
		user_state,		
		password,	
		salt,		
		last_modified
	)				
	values	
	(	0,
		3,	
		'tini@ebay.com',	
		3,
		'$1$4329$/NMdZxIqZw16oAs4MUmKe/',
		4329,		
		sysdate			
	);


insert into ebay_feedback
	(	id,								
		created,						
		last_update,				
		score,						
		flags							
	)									
	values							
	(	1,					
		sysdate,				
		sysdate,				
		0,				
		0		
	);

insert into ebay_feedback
	(	id,								
		created,						
		last_update,				
		score,						
		flags							
	)									
	values							
	(	2,					
		sysdate,				
		sysdate,				
		0,				
		0		
	);

insert into ebay_feedback
	(	id,								
		created,						
		last_update,				
		score,						
		flags							
	)									
	values							
	(	3,					
		sysdate,				
		sysdate,				
		0,				
		0		
	);


insert into ebay_feedback_detail
  (	id,					
	time,				
	commenting_id,		
	commenting_host,	
	comment_type,		
	comment_score,		
	comment_text	
  )							
  values								
  (	1,								
	sysdate,							
	2,								
	'208.111.207.1',								
	3,							
	0,							
	'skippys feedback from michael'							
  );

  insert into ebay_feedback_detail
  (	id,					
	time,				
	commenting_id,		
	commenting_host,	
	comment_type,		
	comment_score,		
	comment_text	
  )							
  values								
  (	2,								
	sysdate,							
	1,								
	'208.111.207.1',								
	3,							
	0,							
	'michaels feedback from skippy'						
  );

  insert into ebay_feedback_detail
  (	id,					
	time,				
	commenting_id,		
	commenting_host,	
	comment_type,		
	comment_score,		
	comment_text	
  )							
  values								
  (	3,								
	sysdate,							
	1,								
	'208.111.207.1',								
	3,							
	0,							
	'tinis feedback from skippy'						
  );

/* insert into ebay_admin */

insert into ebay_admin
  (marketplace, id)
   values
   (0, 1);

insert into ebay_admin
  (marketplace, id)
   values
   (0, 3);
