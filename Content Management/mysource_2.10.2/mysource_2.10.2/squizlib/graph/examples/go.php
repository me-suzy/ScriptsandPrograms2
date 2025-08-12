<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: graph/examples/go.php
## Desc: Example usage of Graph class
## $Source: /home/cvsroot/squizlib/graph/examples/go.php,v $
## $Revision: 2.1 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:19 $
#######################################################################

include_once("../graph.inc");

#---------------------------------------------------------------------#
# The graph object is a system that allows you to take a bunch of
# numerical data, and create a pretty graphical representation of 
# it in GIF format.
#
# This is not a data analysis tool. You can't just pipe it a pile
# of raw data and expect useful information to come out. This is 
# simply a tool for producing graphics, analysis is up to you.

# Firstly we need some data. I did a quick search of the Internet
# and the first thing I stumbled across was a CSV of suicide rate
# information for Britain group by age bracket - hooray!

$data = array();
$f = fopen("suicide_rate.csv","r");
while($datumz = fgetcsv($f,8092)) $data[] = $datumz;
unset($datumz);
fclose($f);

# Now of course you can get your data from anywhere... a mysql
# database, Apache logs, file system calls, /dev/rand, number
# theory analysis.. hard code it, I don't care.

# Now just ignore the all the gross assumptions I've making
# about the format of this data file - I'm just the example guy!

# Your graph can have a title - and a subtitle !
list($title,$subtitle) = explode(":",$data[1][1]);

# We are now ready to create our object

$suicide_graph = new Graph("line",$title,$subtitle,600);

# The last parameter is the width of the graph in pixels.
# 600 is a nice A4 printable width (and also default).
#
# The first parameter is the graph "type".
# TYPES:
#  * column  - Regular vertically columned graph
#  * bar     - Regular horizontally barred graph
#  * area    - Like a spikey coloured alien landscape
#  * line    - Lines with dots
#  * stacked - Like column, except the "layers"
#              are piled on top of eachother

# "Layers!?" you cry.
# "Yes," I reply, in deadly earnest, "layers."
#
# Graph's aren't very interesting unless you can have
# three-dimentional data in them. Or two, or something.
#
# So there are three attributes to each datum you put in:
#  * The layer
#  * The candidate
#  * The value
#
# This isn't officialy Graphing Industry Terminology,
# I don't know what the official words are, but these
# concepts needed words, so I made them up.
#
# Say you had a column graph that measured the population
# of a country over the years. Years along the bottom, with
# a column each, are the candidates: 1996, 1997 etc 
#
# Say you split the graph in two, so that there we two sets
# of columns on top of each other, one for males, one for
# females. These represent layers: Males, Females..
#
# Candidates and Layers could be whatever you want, but each
# one must have a label. A value is just a number that
# applies to a particular layer/candidate combo.
# Theoretically each possible layer/candidate combo should
# have a value.
#
# So if you have 20 candidates and 4 layers, you have 80
# values. Get it? Good.
#
# You must set up the graph's layers and candidates first,
# and then apply the values.
#
# Now, in the business of suicide, years are candidates, and
# demographics are the layers.
#
# But first we explain what our candidates are. Ours are years
# so...

$suicide_graph->set_subject("Year");

# Also we have to explain we what are values are. Layers need
# to be self-explanatory.

$suicide_graph->set_units("suicides per million people");

# Alright now lets stick our candidates in.

for($i = 2; $candidate_label = $data[11][$i]; $i++) {
	$suicide_graph->add_candidate("$candidate_label");
	# ( Look at the CSV file under stand the hard-coded values )
}

# And now the layers. Bear with me while I mess around making
# these pretty, you'll often have to do this too.

for($j = 13; $age_group = $data[$j][1]; $j++) {
	if($data[$j][0]) $gender = $data[$j][0];
	$layer_label = "$age_group ($gender)";
	$suicide_graph->add_layer("$layer_label");
}

# And now we simply set all the values for these each
# candidate and layer combo. Notice how I ensure I
# hit all the candidates and layers exactly that 
# I put in - by using the same algorithm.

# Yes. I could have done it all in one go. But not all data
# sets are as as simple as this example, and can result in
# layers and candidates getting out of your preferred order.

for($i = 2; $candidate_label = $data[11][$i]; $i++) {
	for($j = 13; $age_group = $data[$j][1]; $j++) {
		if($data[$j][0]) $gender = $data[$j][0];
		$layer_label = "$age_group ($gender)";
		$suicide_graph->set_value($layer_label, $candidate_label, $data[$j][$i]);
	}
}


# Make sense? No? Read it again. set_value() takes layer
# identifier (must be the same string you used to create
# the layer), then a candidate identifier, then the value.

# Anyway another good thing to do is to set the limits.
# These set the upper and lower value limits of the graph.
# If omitted, they default to a little below and above
# the minimum and maximum values in the graph.
# It's often a good idea to set the minimum to 0.
# You don't have to set either of them.

$suicide_graph->set_limits(0,30);

# And at long last...

$suicide_graph->render();

# This function gets the object to process the data into
# GIF format which is ready to be either:
#  * Saved:

#$suicide_graph->save_image("suicide_chart");

# * Or printed - completed with "Content-type" http header.

$suicide_graph->print_image();

# The Graph object contains other under-development functionality
# which you can explore at your leisure.. including varying
# the "type" on a layer-by-layer basis, for mixed graphs;
# and also print HTML tables of tabulated data; customised colours
# and eventually HTML image maps and URL support.
#
# Happy graphicating!

# - Agi
#   agland@squiz.net

?>