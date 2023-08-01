<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the orders.php file when
    the user clicks on the show Tracking Order Buttons without reloading the webpage -->
<?php


require_once ("../../mysqli_Coffee_config.php"); // Connect to the database

    $value = $_GET['value'];

    $query = "SELECT trackingNum AS trackingNum, transactionNum AS entryNumber, dateShipped, timeShipped, 
              get_location(transactionNum) AS Location 
              FROM ShipmentEntry 
              WHERE trackingNum = '$value'
              ORDER BY dateShipped, timeShipped";

    $result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($result) > 0){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        echo("<div class=\"success\"><h2>$value doesn't seem to be a tracking number. 
        Please check the number and try again</h2></div>");
       exit;
    }
    
    if (mysqli_num_rows($result) > 0){
        echo("<table class=\"trackingHistoryTable\">");
        echo ("<h2 class=\"tableHeader\">Tracking History</h2>");
        echo ("<tr>");
        echo ("<th>Tracking Number</th>");
        echo ("<th>Shipment Entry Number</th>");
        echo ("<th>Date Shipped</th>");
        echo ("<th>Time Shipped</th>");
        echo ("<th>Location</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $entry){
        echo("<tr class=\"orderRow\">");
        echo "<td>".$entry['trackingNum']."</td>"   ;
        echo "<td>".$entry['entryNumber']."</td>";
        echo "<td>".$entry['dateShipped']."</td>";
        echo "<td>".$entry['timeShipped']."</td>";

        if (substr($entry['entryNumber'], 0, 2) == "TR"){
            echo "<td> On Truck, VIN#".$entry['Location']."</td>";
        }
        if (substr($entry['entryNumber'], 0, 2) == "WA"){
            echo "<td> In Warehouse, ID#".$entry['Location']."</td>";
        }
        if (substr($entry['entryNumber'], 0, 2) == "SU"){
            echo "<td> At Supplier, ID#".$entry['Location']."</td>";
        }
        echo("</tr>");
    }
    mysqli_free_result($result);
    echo("</table>");

    mysqli_close($dbc);
?>