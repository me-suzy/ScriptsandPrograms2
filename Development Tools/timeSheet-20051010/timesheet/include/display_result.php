<?php
function display_result($client,$result = NULL)
{
    // Check for a fault
    if ($client->fault)
    {
        echo '<p><b>Fault: ';
        print_r($result);
        echo '</b></p>';
    }
    else
    {
        // Check for errors
        $err = $client->getError();
        if ($err)
        {
            // Display the error
            echo '<p><b>Error: ' . $err . '</b></p>';
        }
        else
        {
            // Display the result
            print "<pre>Result:";
            print_r($result);
            print "</pre>";
        }
    }
}
?>
