<?php
include("includes/header.php");
include("includes/states.php");
include("includes/generateRandomIDs.php");
require_once ("../../mysqli_Coffee_config.php"); // Connect to the database
?>
    <div id="bttnContainer">
        <button id="displayFormBttn" onclick="displaySupplierForm()">ADD NEW SUPPLIER</button>
    </div>

    <?php
        // ini_set('display_errors', 1);
              
        if (isset($_POST['searchProduct'])){
            $errors = array();
       
            $supplierName = filter_var(trim($_POST['supplierName']), FILTER_SANITIZE_STRING); // returns a string
            if (empty($supplierName)){
                $errors['supplierName'] = 'Please enter a name for the supplier';
            }

            $supplierStreet = filter_var(trim($_POST['supplierStreet']), FILTER_SANITIZE_STRING); // returns a string
            if (empty($supplierStreet)){
                $errors['supplierStreet'] = 'Please enter the street address for the supplier';
            }

            $supplierCity= filter_var(trim($_POST['supplierCity']), FILTER_SANITIZE_STRING); // returns a string
            if (empty($supplierCity)){
                $errors['supplierCity'] = 'Please enter the City the supplier is located in';
            }

            $supplierState = filter_var(trim($_POST['supplierState']), FILTER_SANITIZE_STRING); // returns a string

            $supplierZip= filter_var(trim($_POST['supplierZip']), FILTER_SANITIZE_STRING); // returns a string
            if (empty($supplierZip)){
                $errors['supplierZip'] = 'Please enter the ZIP code for the supplier';
            }


            // Check to see if supplier already exists
            // Handle as an error if yes
            $query = "SELECT supplierName from Supplier where supplierName = ?";
            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, 's', $supplierName);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) >= 1){
                $errors['duplicateSupplier'] = 'Supplier already exists, please enter a new one';
            }
            mysqli_free_result($result);

            if (!$errors){
                $randGeneratedSupplierID = generateSupplierID($supplierName);
                $query2 = "INSERT INTO Supplier (supplierID, supplierName, supplierStreet, supplierCity, SupplierState, supplierZip) VALUES (?,?,?,?,?,?)";
                $stmt2 = mysqli_prepare($dbc, $query2);
                mysqli_stmt_bind_param($stmt2, 'ssssss', $randGeneratedSupplierID, $supplierName, $supplierStreet, $supplierCity, $supplierState, $supplierZip);
                mysqli_stmt_execute($stmt2);

                if (mysqli_stmt_affected_rows($stmt2) > 0){
                    echo("<div class=\"success\"><h2>The supplier $supplierName has been successfully added.</h2></div>");
                }
                exit;
            }
        }
    ?>
    <div id="supplierFormContainer" style="display:none">
        <?php 
            if ($errors){
            echo("<script>displaySupplierForm();</script>");
            }
        ?>
        <h2 class="form_header">NEW SUPPLIER FORM</h2>
        <div class="formDescription">
            <p>Please provide the specified details of the New Supplier You Wish to Add:
            </p>
        </div>

        <form id="newSupplierForm" class="topBefore" method="POST" action="supplier.php">
            <?php if ($errors){
                echo ("<h3 class=\"warning\">Please fix the item(s) indicated: </h3>");
            };
            ?>
            
            <?php if ($errors['supplierName']) echo("<h2 class=\"warning\">{$errors['supplierName']}</h2>");?>
            <input id="supplierName" type="text" placeholder="SUPPLIER NAME" name="supplierName" <?php if (isset($supplierName)){
                            echo("value=\"". htmlspecialchars($supplierName). "\"");
                        }?>>
            
            <?php if ($errors['supplierStreet']) echo("<h2 class=\"warning\">{$errors['supplierStreet']}</h2>");?>
            <input id="supplierStreet" type="text" placeholder="STREET" name="supplierStreet" <?php if (isset($supplierStreet)){
                            echo("value=\"". htmlspecialchars($supplierStreet). "\"");
                        }?>>


            <?php if ($errors['supplierCity']) echo("<h2 class=\"warning\">{$errors['supplierCity']}</h2>");?>
            <input id="supplierCity" type="text" placeholder="CITY" name="supplierCity" <?php if (isset($supplierCity)){
                            echo("value=\"". htmlspecialchars($supplierCity). "\"");
                        }?>>

            <select name="supplierState" id="state">
                <?php
                    foreach ($states as $state){
                        echo("<option value=\"$state\">$state</option>");
                    }
                ?>
            </select>

            <?php if ($errors['supplierZip']) echo("<h2 class=\"warning\">{$errors['supplierZip']}</h2>");?>      
            <input id="supplierZip" type="text" placeholder="ZIP" name="supplierZip" <?php if (isset($supplierZip)){
                            echo("value=\"". htmlspecialchars($supplierZip). "\"");
                        }?>>
                        
            <input id="submit" type="submit" value="ADD NEW SUPPLIER" name="searchProduct">
        </form>
    </div>

<?php
    $query3 = "SELECT * FROM Supplier ORDER BY supplierName";
    $result2 = mysqli_query($dbc, $query3);

    if ($result2)
    {
        $all_rows = mysqli_fetch_all($result2, MYSQLI_ASSOC); // get the result as an associative 2-dimensional array
    }
    else { 
		echo "<h2>We are unable to process this request right now.</h2>"; 
		echo "<h3>Please try again later.</h3>";
		exit;
	} 
    
    if (mysqli_num_rows($result2 ) >=1){
        echo("<table class=\"suppliersTable\">");
        echo ("<h2 class=\"tableHeader\">SUPPLIER DETAILS</h2>");
        echo ("<tr>");
        echo ("<th>SupplierID</th>");
        echo ("<th>Supplier Name</th>");
        echo ("<th colspan=\"4\">Address</th>");
        echo ("</tr>");
    }

    foreach ($all_rows as $supplier){
        echo("<tr class=\"supplierRow\" id=\"supplier\" onclick=\"getSupplierID(this)\">");
        echo "<td>".$supplier['supplierID']."</td>";
        echo "<td>".$supplier['supplierName']."</td>";
        echo "<td>".$supplier['supplierStreet']."</td>";
        echo "<td>".$supplier['supplierCity']."</td>";
        echo "<td>".$supplier['supplierState']."</td>";
        echo "<td>".$supplier['supplierZip']."</td>";
        echo("</tr>");
    }
    mysqli_free_result($result2);
    echo("</table>");   
    mysqli_close($dbc);
?>

<div id="bttnContainer">
        <button id="displayFormBttn" onclick="displayInventoryCountForm()">SHOW INVENTORY COUNT</button>
</div>

<div id="inventoryCountFormContainer" style="display:none">
        <?php 
            if ($errors){
            echo("<script>displayInventoryCountForm();</script>");
            }
        ?>
<h2 class="form_header">SUPPLIER INVENTORY COUNT SEARCH</h2>
    <div class="formDescription">
        <p>Please select the <span class="textEmphasis">Supplier Name</span> you wish to retrieve inventory count for
        </p>
    </div>

    <?php

    if (isset($_POST['retrieveCount'])){
        $searchErrors = array();
        
        $selectedSupplier = $_POST['supplier'];
        if ($selectedSupplier == ""){
            $errors['store'] = "Please select the supplier you wish to place an order from";
        }
        
    }
    ?>

    <form class="user_form" class="topBefore">
    
    <?php if ($searchErrors['selectedSupplier']) echo("<h2 class=\"warning\">{$searchErrors['selectedSupplier']}</h2>");?>
        <select name="supplier" id="supplier" onchange="getSupplierInventoryCount(this);" onfocus="this.selectedIndex = -1;">
            <option value="">SELECT SUPPLIER</option>
            <?php
                foreach($all_rows as $supplier){
                    echo("<option id=\"supplier\" ");
                    if ($selectedSupplier == $supplier['supplierName']){
                        echo(" selected");
                    }
                    echo(">".$supplier['supplierName']."</option>");
                }
            ?>
        </select>
    </form>
</div>

<div class="supplierCountContainer" id="inventoryCount"></div>

<!-- The Modal -->
<div id="myModal" class="modal" style="display:none;">
  <!-- Modal content -->
  <div id='supplierInventoryContainer'>
  </div>
</div>
</main>
</body>
</html>