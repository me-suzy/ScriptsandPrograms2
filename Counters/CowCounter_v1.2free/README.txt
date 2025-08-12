This is a relatively simple, text based counter.  All you have to do is enable your counters in the variables.php file and then call the image created by the CowCounter.php file where you want the  counter.  You do have to give it a file that it can keep track of the number and you do this by the "file" parameter.  In your HTML file, you would have this tag if CowCounter.php and your file were in the same directory as your HTML file: <IMG SRC="CowCounter.php?file=counterfile"> and that would create your image.  If "counterfile" didn't exist, it would be created, and in this case the file would be created RELATIVE TO COWCOUNTER.PHP.  So you can call many counters using this script just by changing the value of "counterfile".  The example file "index.html" is included just so you can test to see if your system has php installed and running (and to grab the HTML code).  If you are wondering, the count for the image is stored in the files "CounterFile_" and your counter name on the end.

The addition of "CounterFile_" to the name of the counter file and the ability of forcing the counter to validate a counter before using it was the fix in 1.1 over 1.0.

For those people who like to have leading zeros in their counter, otherwise known as a minimum length, this feature has now been added.  If you do not want to use this feature, then you can use the counter as described above.  If you do want to use this feature, then all you have to do is add a minlength=10 (or whatever length) to the location of the CowCounter script.  For example, say you wanted a minimum of 5 digits, you counter had only 3 digits, then you would have to get the script to add two leading zeros by changing this:

 <IMG SRC="CowCounter.php?file=counterfile"> 

to this:

 <IMG SRC="CowCounter.php?minlength=5&file=counterfile">

where the 5 in the above string specifies the minimum number of digits.  If you counter exceeds a digit count greater then the minimum digits, then the counter will simply expand.  In other words, if you had a minimum length set to 5, and your site got its 100,000th hit, then your counter would expand to a sixth digit instead of loosing a digit.