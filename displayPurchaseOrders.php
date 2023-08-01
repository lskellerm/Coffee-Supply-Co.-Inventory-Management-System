<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the track.php file when
    the user clicks on the show Purchase Order Buttons without reloading the webpage -->
<?php


require_once ("../../mysqli_Coffee_config.php"); // Connect to the database
echo("<script src=\"js/helpers.js\"></script>");


    $query = "SELECT trackingNum, storeName, dateOrdered 
              FROM PurchaseOrde r, Store
              WHERE PurchaseOrder.storeID = Store.storeID ORDER BY storeName";
    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    if (mysqli_num_rows($result) > 0){
        echo("<table class=\"purchaseOrderTable\">");
        echo ("<h2 class=\"tableHeader\">PURCHASE ORDER HISTORY</h2>");
        echo ("<tr>");
        echo ("<th>Tracking Number</th>");
        echo ("<th>Store Name</th>");
        echo ("<th>Order Date</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $order){
        echo("<tr class=\"orderDetailsRow\" onclick=\"getTrackingNum(this)\">");
        echo "<td>".$order['trackingNum']."</td>"   ;
        echo "<td>".$order['storeName']."</td>";
        echo "<td>".$order['dateOrdered']."</td>";
        echo("</tr>");
    }
    mysqli_free_result($result);
    echo("</table>");

    mysqli_close($dbc);


?>
