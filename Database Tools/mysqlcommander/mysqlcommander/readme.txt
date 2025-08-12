This is version 2.65 of MySQL Commander for php (http://www.php.net) 
Written Sep. 2000 
last modification August 2005

by Oliver Kührig <oliver@bitesser.de>
bitesser.de http://www.bitesser.de
viersicht - Medien und Kommunikation - http://www.viersicht.de

This software is distributed under GPL.

---------------------
UPDATE TO VERSION 2.6
We made changes in the data storage, so older backup files won't be restored correctly.
If you want to restore older data use MySQL Commander 2.51.
Make a new backup of all your Databases with the version 2.6.

UPDATE TO VERSION 2.5 AND ABOVE
You have to save the configuration again.
Go to Update->Configuration and submit the form again by clicking on the button at the bottom.
---------------------


Beschreibung
===========
Dieses kleine Tool bietet die Möglichkeit Backups von allen Datenbanken auf einem MySQL- Server zu erstellen.
Die Tabellen können einzeln oder alle auf einmal gesichert werden. Die Daten werden in Textdateien geschrieben, und können auch wieder in die Datenbank zurückgeschrieben werden.
Es wird PHP ab Version 4.1 und MySQL ab Version 3.23. benötigt.

Es ist auch ein Java Backup Tool vorhanden, welches Datenbanken über mehrere MySQL Server im MySQLCommander Format sichern kann.
Schaut einfach mal auf unserer Website vorbei unter Freeware-Java.

Description
===========
This tool makes backups of all the tables in a database. The data will be stored in textfiles located in the \"data\" directory. You can backup and restore the \"SQL create table command\" and the \"content\". So you can easily make copies of your tables. (i.e. copy a hole database with a few clicks).
You will need PHP since version 4.1 and MySQL since Version 3.23.

We also made a Java backup tool, to backup databases over multiple MySQL servers in the MySQLCommander format.
Take a look at our website under Freeware-Java.

Installation
============
1. After downloading, unzip the MySQL-Commander
2. Copy the unzipped files to a new folder on your webserver (FTP)
3. Normally the MySQL-Commander creates all neccessary folders, but if your PHP runs in safe mode or not allowd to create directories follow step 4. and 5.
4. If your PHP runs in 'safe mode' and the 'log' folder does not exist, create it in the new folder.
5. If your PHP runs in 'safe mode' create a folder 'data' in the new folder, also create in the 'data' folder subfolders with the name of the databases in your MySQL  you want to backup.
6. Change the permissions for 'log', 'data', 'online_update' and 'ressourcen' and all of its subfolder to CHMOD 777, so the scripts can create and change files in there.
7. To protect your backup and restore use a directory access restriction for the MySQL-Commander folder
8. Run the MySQL-Commander with your browser. Example: http://www.yourdomain.com/mysqlcommander/
9. The Message: Error: Please start the Configuration first! Click on the Link 'Configuration'
10. The Configuration Page will be opened. Configure the MySQL-Commander for your needs. You must Configure minimum the first MySQL-Server. If your Provider does not allow Database browsing for your account, you must type in your databases in the Databases section.
11. If all settings are made click 'Save configuration' at the bottom. All settings will be saved. Now you can click on '<< back to MySQL Commander'
12. Now you can find more information in the manual in the left navigation of the MySQL-Commander