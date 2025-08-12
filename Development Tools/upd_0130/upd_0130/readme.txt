Upgrade:
Please replace all .php-Files on your server

So you determine which script you need:
Take the actually installed versionnumber (e.g. 0.102).
Remove the point in the versionnumber (in our example this results in 0102).
Now you have the upgrade version.
Now you need to sequentially use all upgrade scripts from this version to version 0.130.
Use upgrade_0102_to_0103.php, after this upgrade_0103_0109_to_0110.php
and so on till you reach upgrade_0122_0125_to_0126.php
If a script is missing in this sequence, there were no database changes for this upgrade.
In this case, just continue with the next script in the sequence.

After upgrading to 0.130, please continue with the way described in the main archive to
upgrade to the newest version.

This archiv doesn't contain upgrade scripts for versions <0.70. If you are using such an old version,
you have to do a fresh installation (sorry).
