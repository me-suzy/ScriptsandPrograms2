


Quick Notes:


Notes 1 and 2 are carried over from 

Dan Smith

dan@cds-computers.com

1. The upload.xls includes the .csv file and has support for inventory quantity and weight shipping.

2. To use the weight shipping, you need to change the 'shipweight' field in the 'prod' table 
	from varchar(11) to decimal(10,3) or whatever matches your weight system. 
	If you were only using one decimal place and never over 9 pounds, it could be decimal(1,1). 
	It really doesn't matter whether the actual weight number is ounces, pounds or kilograms. 
	Also need to install the weight shipping files in the user contributed code section.

Unless it has been fixed in the user contributed code, there is one error in the weight shipping 
files contributed by Ramon Morros. 



In productupd.php in the update statement at or

about line 168, it reads:
"prodstart=$psdate,prodstop=$pedate,prodisbn='$prodisbn',prodweight='$weight' ".
 Notice the prodweight='$weight' It should be prodweight='$prodweight'


3. Recommend cleaning out your current csvparse directory and then uploading the new
	csvparse files.

4. Now read the Readme.txt file




