Calorie Queen is a food management system that displays the nutrition facts for foods. You can download the USDA database which has thousands of foods for starters. There is also the ability to add and delete foods in the admin section.
Copyright (C) 2005 Christopher Theberge

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Instructions:
I wrote this program based on the USDA nutrient data and mathematical functions to convert different servings for foods. The initial serving size for foods is 100 grams. To convert the other foods to different servings, you need to have the gram weight for the serving sizes. For example, if 100 grams = 387 calories and you know that 28 grams = 1 oz, 1 oz would be about 108 calories (387x28/100).

Some definitions:
Serving size = 100 grams
GmWt1=gram weight
GmWt_desc1= description for GmWt1 (ie, 1 tablespoon)
Similar for gmwt2.

If you don't have foods with more than one serving size, just leave the gmwt variables blank. Self-explanatory I hope.

Installation Instructions:

1.First, extract the zip file and change all of the pages to your database name and put in your database username and password. Also, just change the headers on each page to your website name. Open food_menu.php and show_addfood.php and change the username ("admin") and password ("password").
2. Once done, upload the files to your directory.
3. Create your sql table with foodcomp.sql. You can insert the data for rice cakes, which I left to use for example to make sure it is set up correctly.
4. Next, download the usda nutrient data http://www.ars.usda.gov/Services/docs.htm?docid=10093. I would recommend saving the excel file as comma delimited and change the names to the names of the fields in the mysql database. Then just insert into your database.
5. Test It and Enjoy.
6. I will install this script and database for $30 if you need help. If so, please contact me through the contact form on my website: http://nafwa.org/contact.php. 

If you find this script useful, please provide a link to nafwa. <A HREF="http://www.nafwa.org" target="_blank" title="NAFWA.org">Form Provided By The Nutrition and Food Web Archive</A>