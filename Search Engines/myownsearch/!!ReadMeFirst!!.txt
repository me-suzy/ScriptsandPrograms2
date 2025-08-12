MyOwnSearch
By NukedWewb
Email: nukedweb@yahoo.com

Get More Free Scripts!: http://nukedweb.pxtek.net/

#####################################################

Before you can start using MyOwnSearch, there are just a few things you'll have to do first. By now I can assume you've already opened the zip file and unzipped it into it's own folder somewhere. First step is to open the config.php file....

The first 4 variables are empty, and these are NEEDED before you can begin. These are the settings for MySQL. Hopefully you already have this information, if not, you'll need to ask whoever hosts your website.

After those variables are filled in, save it. You can go back and edit the rest of the variables later. The default settings will be fine. :)

Now, you can upload all the files to your website. When you've done that, access create_tables.php from your web browser. After reviewing the settings, click the Create Tables button, and you're finished setting up the tables. ;)

Thats it! Now you're running the bare-boned version of MyOwnSearch. By default, DMOZ and Google are used for meta searches. You can change these by accessing admin.php and entering "thepass" (no quotes) as the password. You can change this in the config.php file. Access index.php and start doing some searches. If your database is empty (and at this point, I'm guessing it is!), then every search you do, will search DMOZ and Google (for now), and each time you search, those entries are added to the database.


Now, you're probably wondering about the rest of the config.php file. Open up config_variables.html for descriptions of each variable. These settings instruct MyOwnSearch how to look, and how to act. Read each one carefully, and if you don't understand it, don't change it. :) Note:: Towards the bottom of config.php, you'll need to set the admin password. You MUST change this! Leaving it as it is, would be somewhat dangerous. ;)

How it works:
When you start off, your database is empty, as I've mentioned. Each time you do a search, results from your own database are displayed first. A link will appear at the bottom for performing a meta search. If there were zero matching entries in your database, this meta search is performed automatically.

Each time a meta search is performed, the script will grab matching results from meta engines, and will be displayed on your website. Those entries are automatically added to your database if you configured it to do so. If an entry already exists in the database, it's ignored.

Text-Based Advertisements:
MyOwnSearch has a built-in advertisement engine that displays ads depending on keywords. If a person does a search with more than one word, each of those words are used as keywords, and are matched against the keywords of the ads in the database. Each word is matched against the database, and when a word matches a keyword, that ad will be displayed. Ads are displayed at the top of both result result pages and meta search result pages. You can add/edit/delete these ads by accessing editads.php

Admin Area:
The admin area is quite small, but there's not really much you would need to do here! By accessing admin.php and entering the password, (if you haven't changed it from the default and reuploaded config.php, then the default password is 'thepass' [no quotes]), then you can delete entries in the database either singly, or mass-delete by performed searches. You're also given links at the bottom to add/edit/delete Text Ads and to configure the meta search engines.

Configuring Meta Engines:
It's quite a simple but effective procedure. You're given a list of available search engines, the order number, and whether or not to save results to the database. To exclude an engine, set the order number to 0. For the engines you wish to use, enter a number to indicate the order to use it. For example, to display SearchFeed results, then Google, then DMOZ, you would set SearchFeed to 1, Google to 2, DMOZ to 3, and leave the rest of them as 0. You can instruct each of the engines to save results to the database. Note: It's HIGHLY UNRECOMMENDED to save PPC engine results to the database, since the links become deactivated in no time at all. :)

Header and Footer:
These settings (in config.php) must point to files that contain the HTML that appears at the top and bottom of all pages. If your website uses a template, then you probably already have a head and foot file. Just set these variables to point to those files, otherwise you can create (for example) "head.php" and "foot.php" and point it to these files.

Main Page Content:
This should be rather easy to understand. This file, content.php, holds the HTML that will be displayed on the main page of your search engine, when no search is performed. If the search engine is the main part of your website, then content.php would hold all the HTML of your website's main page. :)

That's it! I'm done ;) Remember to re-upload any files you've made changes to while reading this. :)

 ~ Tim
nukedweb@yahoo.com