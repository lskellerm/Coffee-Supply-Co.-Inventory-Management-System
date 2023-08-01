<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the track.php file when
    the user clicks on the show Purchase Order Buttons without reloading the webpage -->
<?php


require_once ("../../mysqli_Coffee_config.php"); // Connect to the database
    $value = $_GET['value'];
        $query = "SELECT OrderHasItem.productID, Item.itemName, OrderHasItem.quantity
                  FROM OrderHasItem
                  JOIN Item ON OrderHasItem.productID = Item.productID
                  WHERE OrderHasItem.trackingNum = '$value'";
    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    if (mysqli_num_rows($result) > 0){
        echo("<table class=\"orderDetailsTable\">");
        echo ("<h2 class=\"tableHeader\">ORDER DETAILS</h2>");
        echo ("<tr>");
        echo ("<th>ProductID</th>");
        echo ("<th>Item Name</th>");
        echo ("<th>Quantity</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $order){
        echo("<tr class=\"orderRow\">");
        echo("<td>".$order['productID']."</td>");
        echo("<td>".$order['itemName']."</td>");
        echo("<td>".$order['quantity']."</td>");
        echo("</tr>");
    }
    mysqli_free_result($result);
    echo("</table>");

    mysqli_close($dbc);
?>


