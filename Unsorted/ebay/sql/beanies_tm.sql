/*	$Id: beanies_tm.sql,v 1.3 1999/02/21 02:52:26 josh Exp $	*/
Update ebay_categories
Set
Name='Beanie Babies (TM)',
description=name3||' ' || name2||' '|| name1||'Beanie Babies (TM)'
Where id=436;

Update ebay_categories 
Set name1 = 'Beanie Babies (TM)',
description= name3||' '||name2||' '||' Beanie Babies (TM) ' ||name
Where level1 = 436;

Update ebay_categories 
Set name2 ='Beanie Babies (TM)',
description=  name3||' Beanie Babies (TM) '||name1||' ' ||name
Where level2 = 436;

Update ebay_categories 
Set name3 ='Beanie Babies (TM)',
description=  'Beanie Babies (TM) '||name2||' '||name1||' ' ||name
Where level3 = 436;

Update ebay_categories
Set
Name='Toys &' || ' Beanies (TM)',
description='Toys &' || ' Beanies (TM)' 
Where id=220;

Update ebay_categories 
Set name1 = 'Toys &' || ' Beanies (TM)' ,
description=  name3||' '||name2||' Toys &' || ' Beanies (TM) '  || name
Where level1 = 220;

Update ebay_categories 
Set name2 ='Toys &' || ' Beanies (TM)' ,
description=  name3||' Toys &' || ' Beanies (TM) '||name1|| ' ' ||name
Where level2 = 220;

Update ebay_categories 
Set name3 = 'Toys &' || ' Beanies (TM)' ,
description= 'Toys &' || ' Beanies (TM) ' || name2 || ' ' ||name1 || ' ' ||name
Where level3 = 220;

commit;
