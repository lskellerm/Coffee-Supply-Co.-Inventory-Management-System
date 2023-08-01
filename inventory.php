<?php
include("includes/header.php");
include("includes/generateRandomIDs.php");
require_once ("../../mysqli_Coffee_config.php"); // Connect to the database

// ini_set('display_errors', 1);


if (isset($_POST['addProduct'])){
    $errors = array();

    $supplierID = filter_var(trim($_POST['supplierID']), FILTER_SANITIZE_STRING); // returns a string
    if (empty($supplierID)){
        $errors['supplierID'] = 'Please enter a supplierID for this product item';
    }

    $itemName = filter_var(trim($_POST['itemName']), FILTER_SANITIZE_STRING); // returns a string
    if (empty($itemName)){
        $errors['itemName'] = 'Please enter a name for the product item';
    }

    $itemPrice = filter_var(trim($_POST['itemPrice']), FILTER_SANITIZE_STRING); // returns a string
    if (empty($itemPrice)){
        $errors['itemPrice'] = 'Please enter the unit price for this product item';
    }

    $itemWeight = filter_var(trim($_POST['itemWeight']), FILTER_SANITIZE_NUMBER_INT); // returns a string
    if (empty($itemWeight)){
        $errors['itemWeight'] = 'Please enter the unit weight for this product item';
    }

    $expirationDate = filter_var(trim($_POST['expirationDate']), FILTER_SANITIZE_STRING); // returns a string
    if (empty($expirationDate)){
        $errors['expirationDate'] = 'Please enter the expiration date for this product item product';
    }

    
    // Check to see if item already exists
    // Handle as an error if yes
    $query = "SELECT itemName from Item where itemName = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 's', $itemName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) >= 1){
        $errors['duplicateItem'] = 'Duplicate item, please add a new item';
    }
    mysqli_free_result($result);

    if (!$errors){
        $randomProductID = generateProductID();
        $query2 = "INSERT INTO Item (supplierID, productID, itemName, itemPrice, itemWeight, expirationDate) VALUES (?,?,?,?,?,?)";
        $stmt2 = mysqli_prepare($dbc, $query2);
        mysqli_stmt_bind_param($stmt2, 'sssdis', $supplierID, $randomProductID, $itemName, $itemPrice, $itemWeight, $expirationDate);
        mysqli_stmt_execute($stmt2);

        $procedureQuery = "CALL update_Itemprice()";
        mysqli_query($dbc, $procedureQuery);
        
        if (mysqli_stmt_affected_rows($stmt2) > 0){
            echo("<div class=\"success\"><h2>The item $itemName has been successfully added.</h2></div>");
        }
        exit;
    }

}

?>
    <h2 class="form_header">NEW PRODUCT FORM</h2>
    <div class="formDescription">
        <p>Please provide the specified details of the item you want to add.
        </p>
    </div>
    <form class="user_form" class="topBefore" method="POST" action="inventory.php">
        <?php if ($errors){
            echo ("<h3 class=\"warning\">Please fix the item(s) indicated: </h3>");
        };
        ?>

        <?php if ($errors['duplicateItem']) echo("<h2 class=\"warning\">{$errors['duplicateItem']}</h2>");?>

        <?php if ($errors['supplierID']) echo("<h2 class=\"warning\">{$errors['supplierID']}</h2>");?>
        <input id="supplierID" type="text" placeholder="SUPPLIER ID" name="supplierID" <?php if (isset($supplierID)){
                        echo("value=\"". htmlspecialchars($supplierID). "\"");
                    }?>>
        <?php if ($errors['itemName']) echo("<h2 class=\"warning\">{$errors['itemName']}</h2>");?>
        <input id="itemName" type="text" placeholder="ITEM NAME" name="itemName" <?php if (isset($itemName)){
                        echo("value=\"". htmlspecialchars($itemName). "\"");
                    }?>>
        
        <?php if ($errors['itemPrice']) echo("<h2 class=\"warning\">{$errors['itemPrice']}</h2>");?>
        <input id="itemPrice" type="text" placeholder="ITEM PRICE" name="itemPrice" <?php if (isset($itemPrice)){
                        echo("value=\"". htmlspecialchars($itemPrice). "\"");
                    }?>>

        <?php if ($errors['itemWeight']) echo("<h2 class=\"warning\">{$errors['itemWeight']}</h2>");?>      
	    <input id="itemWeight" type="text" placeholder="ITEM WEIGHT" name="itemWeight" <?php if (isset($itemWeight)){
                        echo("value=\"". htmlspecialchars($itemWeight). "\"");
                    }?>>

        <?php if ($errors['expirationDate']) echo("<h2 class=\"warning\">{$errors['expirationDate']}</h2>");?>      
	    <input id="expirationDate" type="date" placeholder="EXPIRATION DATE" name="expirationDate">

        <input id="submit" type="submit" value="ADD PRODUCT" name="addProduct">
    </form>


    <h2 class="form_header">SEARCH STORE INVENTORY</h2>
    <div class="formDescription">
        <p>Please provide the <span class="textEmphasis">ProductID</span> or <span class="textEmphasis">Product Name</span> of the item you wish to search for
        </p>
    </div>

    <?php
    if (isset($_POST['searchProduct'])){
        $searchErrors = array();
    
        $productID = filter_var(trim($_POST['itemProductID']), FILTER_SANITIZE_STRING); // returns a string
        $itemQueryName = filter_var(trim($_POST['itemQueryName']), FILTER_SANITIZE_STRING); // returns a string

        if (empty($productID) AND empty($itemQueryName)){
            $searchErrors['itemErrors'] = 'Please enter either the Product ID or the name for the product you wish to search for:';
        }
    
        if (!$searchErrors){
            header("Location: storeInventoryResults.php?productID=$productID&itemName=$itemQueryName");
            exit;
        }
        
    }
    ?>

    <form class="user_form" class="topBefore" method="POST" action="inventory.php">
        <?php if ($searchErrors){
            echo ("<h3 class=\"warning\">Please fix the item(s) indicated: </h3>");
        };
        ?>
        
        <?php 
            if ($searchErrors['itemErrors']) {
                echo("<h2 class=\"warning\">{$searchErrors['itemErrors']}</h2>");
            }
        ?>
        <input id="productID" type="text" placeholder="PRODUCTID" name="itemProductID" <?php if (isset($productID)){
                        echo("value=\"". htmlspecialchars($productID). "\"");
                    }?>>
        
        <input id="itemQueryName" type="text" placeholder="ITEM NAME" name="itemQueryName" <?php if (isset($itemQueryName)){
                        echo("value=\"". htmlspecialchars($itemQueryName). "\"");
                    }?>>
        <input id="submit" type="submit" value="SEARCH PRODUCTS" name="searchProduct">
    </form>
</main>
</body>
</html>