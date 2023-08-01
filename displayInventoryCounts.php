<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the supplier.php file when
    the user clicks on the show inventory count button without reloading the webpage -->
<?php


require_once ("../../mysqli_Coffee_config.php"); // Connect to the database

$value = "%".$_GET['value']."%";

    $query = "SELECT supplierName, COUNT(Item.productID) AS numItems 
              FROM Item 
              JOIN Supplier ON Item.supplierID = Supplier.supplierID 
              WHERE Supplier.supplierName LIKE '$value';";

    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    if (mysqli_num_rows($result) > 0){
        echo("<table class=\"inventoryCountTable\">");
        echo ("<h2 class=\"tableHeader\">Supplier Inventory Count</h2>");
        echo ("<tr>");
        echo ("<th>Supplier Name</th>");
        echo ("<th>Number Of Items</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $order){
        echo("<tr class=\"inventoryCountRow\">");
        echo "<td>".$order['supplierName']."</td>"   ;
        echo "<td>".$order['numItems']."</td>";
        echo("</tr>");
    }
    mysqli_free_result($result);
    echo("</table>");

    mysqli_close($dbc);
?>