/*	$Id: train_rename.sql,v 1.3 1999/02/21 02:55:10 josh Exp $	*/
Update ebay_categories
Set
Name='Trains, RR Models',
description=name3||' ' || name2||' '|| name1||' Trains, RR Models'
Where id=479;

Update ebay_categories 
Set name1 = 'Trains, RR Models',
description= name3||' '||name2||' '||' Trains, RR Models ' ||name
Where level1 = 479;

Update ebay_categories 
Set name2 ='Trains, RR Models',
description=  name3||' Trains, RR Models '||name1||' ' ||name
Where level2 = 479;

Update ebay_categories 
Set name3 ='Trains, RR Models',
description=  'Trains, RR Models '||name2||' '||name1||' ' ||name
Where level3 = 479;

commit;
