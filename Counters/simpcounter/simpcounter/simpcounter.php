<?php

// CONFIGURATION SECTION - You CAN EDIT THE SETTINGS BELOW  //  

$countfile = '/home/speedyco/public_html/scripts/simpcounter/count.txt';                
# The system path to the count log file. You need to change this.

$iplogfile = '/home/speedyco/public_html/scripts/simpcounter/iplog.txt';                
# The system path to the ip log file. You need to change this.

$imgdir = 'http://speedycode.com/scripts/simpcounter/';    
# The url to the directory contatining your images including the traling slash

$imgext = '.gif';                    
# The extension of your images, ( jpg, png, etc. )

$usetextcounter = 0;                    
# 1 = use text based hit counter, 0 = use graphical counter

$countonlyunique = 1;                    
# 0 = count all hits/pageloads, 1 = count only unique visits

$useflock = 1;                        
# 1 = use file locking, 0 = don't.


// END CONFIGURATION SECTION //


// Lock and/or read or write file //
function lock_r_w ( $file , $mode, $content, $useflock ) 
{
    if ( is_writeable( $file )) {
        $fh = fopen( $file, $mode );
            if ( $useflock == 1 ) 
            {
                if ( flock( $fh, LOCK_EX )) 
                {
                    if ( $mode == 'r' ) 
                    {
                        return fgets( $fh );
                    }
                    else 
                    {
                        fwrite( $fh, $content );
                    }
                    flock( $fh, LOCK_UN );
                } 
                else 
                {
                    fclose( $fh );
                    echo "Couldn't lock the file $file.<br />
                    Your system may not support file locking.<br />
                    You probably need to change \$useflock to 0.";
                    exit;
               }
            } 
            else 
            {
                if ($mode == 'r') {
                    return fgets($fh);
                }
                fwrite( $fh, $content );
            }
        fclose($fh);
    }
    else 
    {
        echo "The file $file is not writeable.<br /> 
        Change the permissions and try again.";
        exit;
    }
}


$count = lock_r_w( $countfile, "r", '', $useflock );
if ( $count == 0 ) { $count = 1; }

if ( $countonlyunique == 1 ) 
{
    $curtime = getdate();

    # Empties the ip log file if script is accessed between 12 and 12:01 AM #
    # Hopefully someone will access your page between 12 and 12:01 AM :D #
    # If you have cron access and are using this as a unique visitor counter # 
    # you should set up a cron job to access this script between 12:00 and 12:01 # 
    # AM so that you will have an accurate count of unique visitors per day #
    # Visit http://speedycode.com for help and questions with setting up cron jobs #
 
    if ( $curtime['hours'] == 00 && $curtime['minutes'] == 00 ) 
    {
        lock_r_w( $iplogfile, "w", "", $useflock );  
    }

    $ip = getenv( "REMOTE_ADDR" );
    $ips = lock_r_w( $iplogfile, "r", '', $useflock );
    $curips = explode("|", $ips);
    
#    foreach ( $curips as $key => $value ) 
#    {
#        $curips[$key] = trim( $value ); 
#    }

    $curvisitor = ( in_array( $ip, $curips )) ? 1 : 0;
    
    if ( $curvisitor != 1 ) 
    {
        lock_r_w( $iplogfile, "a", "$ip|", $useflock );
        lock_r_w( $countfile, "w", $count+1, $useflock );
    }
} 
else
{
    lock_r_w( $countfile, "w", $count+1, $useflock );
}

if ( $usetextcounter == 1 )
{
    echo $count;
}
else
{
    for( $i = 0; $i < strlen( $count ); $i++ ) 
    {
        $curcount = substr( $count, $i, 1 );
        echo "<a href=\"http://speedycode.com/archives/17-Simp-Counter-1.1-Released.html\"><img src=\"$imgdir$curcount$imgext\" border=\"0\" alt=\"PHP Counter\"></a>\n";
    }
}

#################################################
# Simp Counter 1.1                              #
# Code by deadserious                           #
# http://www.speedycode.com                     #
#                                               #
#                                               #
#                                               #
#################################################

?>

