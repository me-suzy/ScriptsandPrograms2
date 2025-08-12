<?php

    /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

    /*
        File:           JoDB_Example.php
        Package:        JoDB
        Description:    Example file for JoDB version 0.0.2
        Platform:       PHP 5
        Author:         Jari Jokinen <jari.jokinen@iki.fi>
        Homepage URL:   http://jari.sigmatic.fi/jodb/
        License:        Free for non-commercial use.
                        For commercial use, contact author.
                        Redistributing the modified source code isn't allowed!
    */

    // Initialize settings by using an associative array
    $settings = array(
        'dbms'      => 'mysql',
        'username'  => 'daniel',
        'password'  => 'super14',
        'database'  => 'MyDatabase',
        'hostname'  => 'localhost',
        'hostport'  => '3306'
    );

    // Or just simply by DSN (data source name)
    $settings = 'mysql://daniel:super14@localhost:3306/MyDatabase';

    // Using try and catch blocks is highly recommended
    try {
    
        require_once  'JoDB.php';
        
        // Open connection to DBMS
        $db = JoDB::connect($settings);

        // Prepare SQL
        $db->setTables('People');
        $db->setFields('surname');
        $db->setLimit(5);

        // And execute SELECT query
        $db->select();

        // Insert new row
        $db->setValues('Vopat');
        $db->insert();

        // Or update row WHERE surname = Vopat
        $db->update('People', "firstname='Roman'", "surname='Vopat'");

        // There is another way to SELECT too
        $db->select('People', 'surname');

        // Or you can prepare some values and then SELECT
        $db->setOrder('id DESC');
        $db->select('People', 'surname');

        // Anytime you can access resultset by getRow() method
        $row = $db->getRow();
        echo $row['surname'];

        // Fetch rest of rows
        while ($row = $db->getRow()) {
            echo $row['surname'];
        }

        // Reset some prepared SQL values
        $db->setTable();
        $db->setOrder();

        // Reset all prepared SQL values
        $db->resetSQL();

        // Then start new query, and SELECT only one row
        $db->setOrder('id ASC');
        $row = $db->selectRow('Dogs', 'name');
        echo $row['name'];

        // Make user input safe
        $name = $_GET['name'];
        $db->quote($name);

        // You can also send raw SQL queries to DBMS

        // Use method query() to fetch rows to resulset
        $num_of_rows = $db->query('SELECT name FROM Dogs');

        // Method execute() does not touch resultset
        $num_of_rows = $db->execute("INSERT INTO Dogs (name) VALUES ('$name')");

        // Now you can access result of SELECT by using getRow()
        $row = getRow();

        // And get last inserted ID of INSERT statement
        $id = $db->getLastID();

        // You can fetch single row by using getRow() method
        $row = getRow('SELECT name FROM Dogs');
        echo $row['name'];

        // At last, close connection to DBMS
        $db->disconnect();
        
    }
    catch (JoDB_Exception $e) {
    
        // --------------------------------------------------------------
        // Prepared error message
        // --------------------------------------------------------------
        echo $e->__toString();
        //
        // Example output
        //
        // An error occurred in method "JoDB_mysql::connect" of class
        // "JoDB_mysql": Access denied for user 'daniel'@'localhost'
        // --------------------------------------------------------------

        // --------------------------------------------------------------
        // Prepare your own error message
        // --------------------------------------------------------------
        $error = $e->getError();
        echo 'Message: ' . $error[0] . "\n";
        echo 'Class:   ' . $error[1] . "\n";
        echo 'Method:  ' . $error[2] . "\n";
        //
        // Example output
        //
        // Message: Access denied for user 'daniel'@'localhost'
        // Class:   JoDB_mysql
        // Method:  JoDB_mysql::connect
        // --------------------------------------------------------------
        
    }

?>
