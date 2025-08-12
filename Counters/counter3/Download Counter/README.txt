# Download Counter
# Version: 0.5
# File name: README.txt
# March 23, 2005
# Author: Carter Smith
# http://www.cpspros.com

#####################################################################



Install:

1. Extract this package and make sure the following files are included:
	download.php
	downloads.txt
	README.txt

2. If you decide to change the name of the counter file, make sure to replace "downloads.txt" in download.php with your text file.

3. Upload to your web server. Make sure the counter file is CHMODed to 666 for saving the hits.

Use:

1. Link any download or link that you want to track to:
	download.php?id=yourfile.zip
		or
	download.php?id=http://yoursite.com
		etc.
2. Thats it! Now your downloads/clicks will be recorded in the downloads.txt file.