<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
    <head>
           <title>Easy Framework</title>
           <link rel='stylesheet' type='text/css' href='default.css'>
    </head>
<body>
<h3><font color=red>Easy</font> Framework - <font color=red>E</font>vandor <font color=red>A</font>pplication <font color=red>Sy</font>stem MVC</h3>
<table cellpadding="3" width='90%' align='center'>
<tr>
	<th class="dots" width='200' background-image='img/dots.gif'>About</th>
	<td>
	    <b>Easy Framework</b> is an open source framework for PHP Web applications implementing the Model-View-Controller pattern.
        Additionally, it offers support for logging, assertion handling and datatypes.
    </td>
</tr>
<tr>
	<th class="dots" width='200' background-image='img/dots.gif'>Philosophy</th>
	<td>
	    Keep it simple(r).
    </td>
</tr>
<tr>
	<th class="dots" width='200' background-image='img/dots.gif'>more specific...</th>
	<td>
	    <b>Easy Framework</b> implements the Model-View-Controller (MVC) design pattern, and encourages
	    application design based on this paradigm. MVC allows the Web page (i.e. View) to be mostly separated 
	    from the internal application code (Controller/Model), making it easier for designers and programmers
	    to maintain and organise their code.
		<br><br>
		The <b>Model</b> contains the business logic for the application, whereas the <b>Controller</b> forwards the
		request to the appropriate <b>View</b> component. The view itself should not contain any further logic (apart from
		some design topics) or even database requests.
		<br><br>
		<b>Easy Framework</b> is <u>not</u> a port of Jakarta Struts or anything else. It doesn't try to
		mimic anything (of the Java world), but is a collection of (my) "best-practice" experience.
        <br><br>
        Additionally, Easy Framework offers you some (basic) functionality which you might need when
        programming:
        <ul>
            <li>"Datatypes"</li>
            <li>Assertion handling</li>
        </ul>

    </td>
</tr>
<tr>
	<th class="dots" width='200' background-image='img/dots.gif'>How to use in your scripts?</th>
	<td>
        Should be as simple as
        <pre>require_once (PATH_TO_EASY.'/easy_framework.inc.php');</pre>
    </td>
</tr>
<tr>
	<th class="dots" width='200' background-image='img/dots.gif'>See also</th>
	<td>
	    <a href='http://217.172.179.216/evandor/html/index.php?id=42' target='_blank'>Easy Framework Project Page</a>&nbsp;-&nbsp;
	    <a href='http://217.172.179.216/easy_framework/doc/' target='_blank'>API Documentation</a>&nbsp;
	    <!--<a href='http://217.172.179.216/evandor/html/index.php?id=42' target='_blank'>Documentation</a>&nbsp;-->
    </td>
</tr>
<!--<tr>
	<th class="dots" width='200' background-image='img/dots.gif'>Documentation</th>
	<td>
	    <a href="http://217.172.179.216/easy_framework/doc/index.html" target='_blank'>Easy Framework Documentation</a>
    </td>
</tr>
<tr>
	<th class="dots">Forum</th>
	<td><a href="http://217.172.179.216/easy_BB2/" target='_blank'>Developer Forum</a></td>
</tr>-->
<tr>
	<th class="dots">Introduction</th>
	<td>
		Here are some basic examples of how easy framework is supposed to help you write better applications.
	    <br>
        If you are interested, stay tuned and subscribe to our projet on freshmeat.net	    
	    <br>
	    <br>
		<a href='http://217.172.179.216/evandor/html/index.php?id=46' target='_blank'>What is MVC?</a><br>
		<!--<a href='testapps/assertions/example.php'>Basic MVC Example</a><br>-->
	    <!--What easy framework can do for you:<br><br>
		<a href='testapps/assertions/example.php'>Assertion Handling</a><br>
		<a href='testapps/datatypes/index.php'>Datatypes</a><br>
		<a href='testapps/logging/example1.php'>Logging Example 1</a><br>
		<a href='testapps/logging/example2.php'>Logging Example 2</a><br>-->
		<ul>
            <li><a href='testapps/simple_mvc/index.php?command=input'>MVC Example 1 - Demonstrates the seperation of model, view & controller</a></li>
		    <li><a href='testapps/simple_mvc2/index.php?command=input'>MVC Example 2 - Example 1 plus use of datatypes</a></li>
		</ul>

		<a href='http://217.172.179.216/evandor/html/index.php?id=46' target='_blank'>Why Assertions?</a><br>
        <ul>
            <li><a href='testapps/assertions/example.php'>Assertions Example 1 - Using Assertions</a></li>
        </ul>

		<!--<a href='http://217.172.179.216/evandor/html/index.php?id=46' target='_blank'>Logging</a><br>
        <ul>
            <li><a href='testapps/logging/example1.php'>Logging Example 1 - Simple Use</a></li>
            <li><a href='testapps/logging/example2.php'>Logging Example 2 - Simple Use</a></li>
        </ul>-->
        <!--<a href='testapps/calculator/index.php'>Calculator</a><br>-->
	</td>
</tr>
<!--<tr>
	<th class="dots">PHP Unit Tests</th>
	<td>
		<a href="PHPUnit/easy_model.class.test.php">Easy Model Testcase</a><br>
		<a href="PHPUnit/easy_script.class.test.php">Easy Script Testcase</a>
	</td>
</tr>-->
<tr>
	<th class="dots">Links</th>
	<td>
		<a href="http://java.sun.com/blueprints/patterns/MVC-detailed.html" target='_blank'>Java Blue Prints</a><br>
	</td>
</tr>
<tr>
	<th class="dots">Created</th>
	<td><font face=Verdana size=1 color='#000066'>
		<?php
			$s = filectime("index.php");
			$chg = date("F j, Y H:i:s", $s);
			echo("on " . $chg . "\n");
		?>
		</font>
	</td>
</tr> 
</table>

</body>
</html>
