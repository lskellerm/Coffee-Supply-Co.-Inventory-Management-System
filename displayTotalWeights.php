<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the track.php file when
    the user clicks on the button to show total weight of purchase Order Buttons without reloading the webpage -->
<?php


require_once ("../../mysqli_Coffee_config.php"); // Connect to the database


    $query = "SELECT PurchaseOrder.trackingNum, SUM(Item.itemWeight * OrderHasItem.quantity) as totalWeight
              FROM PurchaseOrder
              INNER JOIN OrderHasItem ON PurchaseOrder.trackingNum = OrderHasItem.trackingNum
              INNER JOIN Item ON OrderHasItem.productId = Item.productId
              GROUP BY PurchaseOrder.trackingNum
              HAVING totalWeight > 50";

    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    if (mysqli_num_rows($result) > 0){
        echo("<table class=\"totalWeightsTable\">");
        echo ("<h2 class=\"tableHeader\">PURCHASE ORDER TOTAL WEIGHTS</h2>");
        echo ("<tr>");
        echo ("<th>Tracking Number</th>");
        echo ("<th>Total Weights</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $order){
        echo("<tr class=\"orderRow\">");
        echo "<td>".$order['trackingNum']."</td>"   ;
        echo "<td>".$order['totalWeight']."</td>";
        echo("</tr>");
    }
    mysqli_free_result($result);
    echo("</table>");

    mysqli_close($dbc);
?>