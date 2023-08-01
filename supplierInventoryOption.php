<!-- AJAX handling php file to receive the response from the request in the helper.js
     file which is triggered by the the onclick function in the createOrder.php file when
    the user clicks on the SUPPLIER select element to display suppliers inventory inside items <select> element -->
<?php
    require_once ("../../mysqli_Coffee_config.php"); // Connect to the database
    
    $value = $_GET['value'];
    $query = "SELECT Item.itemName 
              FROM Item INNER JOIN Supplier ON Item.supplierID = Supplier.supplierID 
              WHERE supplierName = '$value'
              ORDER BY ItemName";

    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }

    echo("<option id=\"first\" value=\"\">SELECT ITEM</option>");
    foreach($all_rows as $items){
        echo("<option");
        echo(" class=\"item\">".$items['itemName']."</option>");
        echo("<input type=\"number\">");
    }

    mysqli_free_result($result);
    mysqli_close($dbc);

?>
