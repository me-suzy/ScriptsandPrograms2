/*	$Id: rename_cat266.sql,v 1.3 1999/02/21 02:54:56 josh Exp $	*/
/ * rename Books, Mags & Misic */
Update ebay_categories
Set
Name='Books, Movies, Music',
description='Books, Movies, Music' 
Where id=266;

Update ebay_categories 
Set name1 = 'Books, Movies, Music',
description= 'Books, Movies, Music ' || name
Where level1 = 266;

Update ebay_categories 
Set name2 = 'Books, Movies, Music',
description= 'Books, Movies, Music ' || name1 || ' ' ||name
Where level2 = 266;

Update ebay_categories 
Set name3 = 'Books, Movies, Music',
description= 'Books, Movies, Music ' || name2 || ' ' ||name1 || ' ' ||name
Where level3 = 266;

commit;
