<?php
    // ini_set('display_errors', 1);
    include("includes/header.php");
    require_once ("../../mysqli_Coffee_config.php"); // Connect to the database

    $productID = $_GET['productID'];
    $itemName = "%".$_GET['itemName']."%";

    $query = "SELECT * FROM Item WHERE productID = ? 
              UNION 
              SELECT * FROM Item WHERE itemName LIKE ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $productID, $itemName);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($results) >= 1){
        $searchResults = mysqli_fetch_assoc($results);
        echo("<table class=\"queryResults\">");
        echo ("<h2 class=\"tableHeader\">Product Details</h2>");
        echo ("<tr>");
        echo ("<th>SupplierID</th>");
        echo ("<th>ProductID</th>");
        echo ("<th>Item Name</th>");
        echo ("<th>Item Price</th>");
        echo ("<th>Item Weight</th>");
        echo ("<th>Expiration Date</th>");
        echo ("</tr>");

        echo("<tr>");
            foreach($searchResults as $product => $productDetails){
                echo("<td>$productDetails</td>");   
            }
            echo("</tr>");
    }
    else{
        echo("<h2 class=\"noResult\">No product found</h2>");
    }
    mysqli_free_result($results);
?>
    </table>
</main>
</body>
</html>