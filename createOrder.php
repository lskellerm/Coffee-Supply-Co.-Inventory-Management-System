<?php
include("includes/header.php");
require_once ("../../mysqli_Coffee_config.php"); // Connect to the database
include("includes/generateRandomIDs.php");

?>

<?php

    $query = "SELECT storeName FROM Store";
    $result = mysqli_query($dbc, $query);

    if ($result){
        $all_rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    mysqli_free_result($result);

    $query2 = "SELECT supplierName FROM Supplier";
    $result2 = mysqli_query($dbc, $query2);
    if ($result2){
        $all_rows2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);
    }
    else{
        exit;
    }
    mysqli_free_result($result2);

    if (isset($_POST['submitOrder'])){
        $errors = array();

        $selectedStore = $_POST['store'];
        if ($selectedStore == ""){
            $errors['store'] = "Please select the store you wish to make an order for";
        }

        $selectedSupplier = $_POST['supplier'];
        if ($selectedSupplier == ""){
            $errors['store'] = "Please select the supplier you wish to place an order from";
        }

        if(isset($_POST['items'])){
            $selectedItem = $_POST['items'];
               if (in_array("", $selectedItem)){
                $errors['item'] = "Please select the item you wish to place an order for";
            }       
        }   
        else{
            $errors['item'] = "Please select the item you wish to place an order for";
        }
        

        if (!$errors){
            // ini_set('display_errors', 1);

            $supplierQuery = "SELECT supplierID FROM Supplier WHERE supplierName = ?";
            $stmt6 = mysqli_prepare($dbc, $supplierQuery);

            mysqli_stmt_bind_param($stmt6, 's', $selectedSupplier);
            mysqli_stmt_execute($stmt6);
            $result3 = mysqli_stmt_get_result($stmt6);
            $row2 = mysqli_fetch_assoc($result3);
            $supplierID = $row2['supplierID'];


            $query3 = "SELECT storeID FROM Store WHERE storeName = ?";
            $stmt = mysqli_prepare($dbc, $query3);
            mysqli_stmt_bind_param($stmt, 's', $selectedStore);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $storeID = $row['storeID'];
          

            $query4 = "SELECT productID FROM Item WHERE itemName IN ('" . implode("', '", $selectedItem) . "')";
            $result2 = mysqli_query($dbc, $query4);

            /*
                        code block to check whether the item already exists or not 
            */

            $productIDs = array();

            if (mysqli_num_rows($result) >= 1 && mysqli_num_rows($result2) >= 1 && mysqli_num_rows($result3) >= 1){
                while ($row = mysqli_fetch_assoc($result2)){
                    $productIDs[] = $row['productID'];
                }

                $existingProducts = array();
                
                foreach($productIDs as $productID){ 
                    $sql = "SELECT productID FROM StoreHasItem WHERE storeID = '$storeID' AND productID = '$productID'";
                    $itemExists = mysqli_query($dbc, $sql);   

                    if(mysqli_num_rows($itemExists) >= 1){
                        $existingProducts[] = $productID;
                    }
                }
                
                if(empty($existingProducts)){
                    $trackingNumber = generateRandomTrackingNumber();
                    $dateOrdered = date("Y-m-d");
                    $query5 = "INSERT INTO PurchaseOrder(trackingNum, storeID, dateOrdered) VALUES (?,?,?)";
                    $stmt3 = mysqli_prepare($dbc, $query5);
                    mysqli_stmt_bind_param($stmt3, 'iss', $trackingNumber, $storeID, $dateOrdered);
                    mysqli_stmt_execute($stmt3);

                    foreach($productIDs as $productID){ 
                        $quantity = generateRandomPurchaseQuantity($storeID);
                        $query6 = "INSERT INTO OrderHasItem (productID, trackingNum, quantity) VALUES (?, ?, ?)";
                        $stmt4= mysqli_prepare($dbc, $query6);
                        mysqli_stmt_bind_param($stmt4, 'sii', $productID, $trackingNumber, $quantity);
                        mysqli_stmt_execute($stmt4);
        
                        $query7 = 'INSERT INTO StoreHasItem (storeID, productID, quantity) VALUES (?,?,?)';
                        $stmt5= mysqli_prepare($dbc, $query7);
                        mysqli_stmt_bind_param($stmt5, 'ssi', $storeID, $productID, $quantity);
                        mysqli_stmt_execute($stmt5);
    
                    }
                    generateShipmentEntryInfo($dbc, $trackingNumber, $supplierID , $dateOrdered);
                    

                    if (mysqli_stmt_affected_rows($stmt3) && mysqli_stmt_affected_rows($stmt4) && mysqli_stmt_affected_rows($stmt5)){
                    echo("<div class=\"success\"><h2>Order confirmation for order number $trackingNumber, 
                          shipment entries successful.</h2></div>");
                    }
                }
                else{
                    echo("<div class=\"success\"><h2>We're sorry, the item(s) with product code(s) <span class=\"textEmphasis\">" . implode("', '", $existingProducts). "</span> already exists for this store.</h2></div>");
                }

            }   
        }
        exit;
    }
?>


<h2 class="form_header">PURCHASE ORDER FORM</h2>
    <div class="formDescription">
        <p>Please fill out the form with your order details:
        </p>
    </div>
    <form class="createOrderForm" class="topBefore" method="POST" action="createOrder.php">
        <?php if ($errors){
            echo ("<h3 class=\"warning\">Please fix the item(s) indicated: </h3>");
        };
        ?>

        <?php if ($errors['store']) echo("<h2 class=\"warning\">{$errors['store']}</h2>");?>
        <select name="store" id="store">
            <option value="">SELECT STORE</option>
            <?php
                foreach($all_rows as $store){
                    echo("<option");
                    if ($selectedStore == $store['storeName']){
                        echo(" selected");
                    }
                    echo(">".$store['storeName']."</option>");
                }
            ?>
        </select>

        <?php if ($errors['supplier']) echo("<h2 class=\"warning\">{$errors['supplier']}</h2>");?>
        <select name="supplier" id="supplier" onchange="getSupplierInventory(this);" onfocus="this.selectedIndex = -1;">
            <option value="">SELECT SUPPLIER</option>
            <?php
                foreach($all_rows2 as $supplier){
                    echo("<option id=\"supplier\" ");
                    if ($selectedSupplier == $supplier['supplierName']){
                        echo(" selected");
                    }
                    echo(">".$supplier['supplierName']."</option>");
                }
            ?>
        </select>
        
        <?php if ($errors['item']) echo("<h2 class=\"warning\">{$errors['item']}</h2>");?>
        <select name="items[]" id="items" class="items" size="8" multiple <?php if (isset($selectedItem)){
            echo("style=\"display:block;\"");
        }
        else{
            echo("style=\"display:none;\"");
        }?>></select>
        
        <input id="submit" type="submit" value="ADD PRODUCT" name="submitOrder">
    </form>

</main>
</body>
</html>