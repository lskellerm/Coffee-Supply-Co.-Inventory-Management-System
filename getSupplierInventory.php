<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the supplier.php file when
    the user clicks on the supplierRow -->
<?php


require_once ("../../mysqli_Coffee_config.php"); // Connect to the database

$value = $_GET['value'];

    $query = "SELECT productID, itemName, itemPrice, expirationDate FROM Item WHERE supplierID = '$value' ORDER BY itemName";
    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    if (mysqli_num_rows($result) > 0){
        echo("<table class=\"supplierInventoryTable\">");
        echo ("<h2 class=\"tableHeader\">Supplier Inventory</h2>");
        echo ("<tr>");
        echo ("<th>ProductID</th>");
        echo ("<th>Item Name</th>");
        echo ("<th>Item Price</th>");
        echo ("<th>Expiration Date</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $product){
        echo("<tr class=\"supplierRow\">");
        echo "<td>".$product['productID']."</td>"   ;
        echo "<td>".$product['itemName']."</td>";
        echo "<td>".$product['itemPrice']."</td>";
        echo "<td>".$product['expirationDate']."</td>";
        echo("</tr>");
    }
    mysqli_free_result($result);
    echo("</table>");

    mysqli_close($dbc);
?>