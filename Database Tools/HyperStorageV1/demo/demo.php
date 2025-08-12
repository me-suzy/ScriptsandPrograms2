<?

include "../locations.php"; # You will need this to make the functions below work 

$type = 'users'; # This is the database created to store your users. 
$mykey = 'bob@aol.com'; # This is the key, his unique identifier in 'users'. 

# Lets make a nice spot for bob@aol.com to be stored. 
$mylocation = MakeLocation($type,$mykey); 

   if ($mylocation > 0) {
   # Location was created!
   echo "Location for bob@aol.com is now $mylocation <br><br>"; 

       # since the location was created, we will continue our code here.
       echo "Inserting some data into table $type.$mylocation <b>(Database: $type, Table: $mylocation)</b><br><br>"; 
        
        # Insert some dummie data into the location, as a test. 
        SmartQuery("INSERT INTO $type.$mylocation SET email='$mykey', password='test', firstname='Bob', lastname='Smith';");
        
        # Pull back his information, so we can see what happened... 
        # You can use my SmartQuery to pull back single data, or an Array, ARR before the command does arrays. 
        $myinfo = SmartQuery("ARR SELECT email, password, firstname, lastname FROM $type.$mylocation WHERE email='$mykey';");
        
        $myemail = $myinfo[0]; # pulls back from array 
        $mypassword = $myinfo[1]; # pulls back from array
        $myfirstname = $myinfo[2]; # pulls back from array
        $mylastname = $myinfo[3]; # pulls back from array
        
       if ($myemail=="") { 

       # It did not work... Somewhere, something is not working. 
       echo "Problem: Failed to return inserted data. Most likely there was a failure creating the database 'locations' and database '$type'. There then may have been an error creating the table '$mylocation' under database '$type'. Please verify that everything was created by viewing your MySQL Databases & Tables, using the MySQL command prompt or PHP Administration Tool<br>"; 
        
        } ELSE { 
        
        # Data came back! 
        # Success! The engine worked 100%! 
       echo "This shows you that it worked correctly. '$mykey' in table '$mylocation' in database '$type' were created successfully. Here is some details about the created information: <br><br>Password: $mypassword<br>E-Mail:$myemail<br>First Name:$myfirstname<br>Last Name:$mylastname<br><br>TO LEARN HOW THIS WORKS, CHECK THE DEMO CODE. SEE MAIN INDEX.HTML FOR FINDLOCATION EXAMPLE.<BR><BR>DEMO WAS SUCCESSFUL! "; 
        
        } 
        
        
        
        
        
        
   } ELSE {
   # Location had an error. 
   echo "Had an error while trying to make a location. The error is [$mylocation]."; 
    } 

# This is the end of the example. See output below. 

?>

