 <html>
<head>
<title>Database Settings</title>
<link rel="stylesheet" type="text/css"
href="style.css" />
</head>
<body bgcolor="#66CCFF">


<?php
     //this is to create the file settings.php which contains the db settings
import_request_variables("gP", "r_");
$title="<?php \n //This page contains the database settings for your website \n";
$file = fopen( "settings.php", "w" );
fwrite( $file, $title );
$setings="$" ."username='" .$r_username ."';\n";
$setings.="$" ."password='" .$r_password ."';\n";
$setings.="$" ."hostname='" .$r_hostname ."';\n";
$setings.="$" ."databasename='" .$r_databasename ."';\n";
        if (isset($r_create)) {
        $setings.="$" ."create='" .$r_create ."';\n";}
        else{$setings.="$" ."create='off'" .";\n"; }
$setings.="$" ."prefix='" .$r_prefix ."';\n";
$setings.="$" ."admin='" .$r_admin ."';\n";
$setings.="$" ."adpass='" .$r_adpass ."';\n";

fwrite( $file, $setings );
fwrite( $file, "?>" );
print "Database parameters set.  Press Continue <br><br>";
print "<a href='dbsetup.php' >Continue</a>";
?>

</body>
 </html>
