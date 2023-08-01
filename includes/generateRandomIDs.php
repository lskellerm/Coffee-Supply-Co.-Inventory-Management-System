<?php 

function generateProductID (){
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $productId = '';

    for ($i = 0; $i < 8; $i++) {
    $productId .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $productId;
    }

function generateSupplierID ($supplierName){
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $supplierID = strtoupper(substr($supplierName, 0,2));

    for ($i = 0; $i < 6; $i++) {
    $supplierID .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $supplierID;
    }


?>

<!-- Helper form to randomly generate storeID for manual entry in phpmyAdmin
<form action="generateRandomIDs.php" method="POST" style="margin:0 au1to">
    <input type="text" placeholder="STORE NAME" name="storeName">
    <input type="submit" name="submit">
</form> -->

<?php   
    // Helper function to randomly generate the storeID and echo it back once the store name has been submitted
    function generateStoreID($myStoreName){
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $storeID = strtoupper(substr($myStoreName, 0,2));

    for ($i = 0; $i < 6; $i++) {
    $storeID .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $storeID;
}


// if (!empty($_POST['storeName'])){
//     $storeName = $_POST['storeName'];
//     echo("<p>".generateStoreID($storeName)."</p>");
// }

function generateRandomTrackingNumber(){
    return mt_rand(1000000000, 2147483647);
}

function generateRandomPurchaseQuantity($storeID){
    if ($storeID == 'BU4FEZE7'){
        $randomQuantity = mt_rand(1, 20);
    }
    elseif ($storeID == 'COD7TIF9'){
        $randomQuantity = mt_rand(1, 10);
    }
    else{
        $randomQuantity = mt_rand(1, 30);
    }

    return $randomQuantity;
}

?>

<!-- Helper buttom to randomly generate wareHouseID for manual entry in phpmyAdmin
<form action="generateRandomIDs.php" method="POST" style="margin:0 au1to">
    <input type="submit" name="submit">
</form> -->

<?php   
    // Helper function to randomly generate the storeID and echo it back once the store name has been submitted
    function generateWarehouseID(){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $warehouseID= '';
    
        for ($i = 0; $i < 8; $i++) {
        $warehouseID.= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $warehouseID;
}

// if (!empty($_POST['submit'])){
//     echo("<p>".generateWarehouseID()."</p>");
// }

/*
    Function which will be used to simulate the order being moved and making various shipment entires
*/
function add_random_hours($time_str) {
    // Parse the time string into an hour, minute, and second value
    list($hour, $minute, $second) = explode(':', $time_str);

    // Generate a random number of hours to add (between 0 and 15)
    $rand_hours = rand(0, 15);

    // Add the random number of hours to the current hour value
    $new_hour = ($hour + $rand_hours) % 24;

    // Return the new time string with the updated hour value
    return sprintf('%02d:%02d:%02d', $new_hour, $minute, $second);
}

?>

<!-- 
Helper buttom to randomly generate truck VIN for manual entry in phpmyAdmin
<form action="generateRandomIDs.php" method="POST" style="margin:0 au1to">
    <input type="submit" name="submit">
</form> -->

<?php   
    // Helper function to randomly generate the storeID and echo it back once the store name has been submitted
    function generateTruckVIN(){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $truckVIN= '';
    
        for ($i = 0; $i < 22; $i++) {
        $truckVIN.= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $truckVIN;
}
// if (!empty($_POST['submit'])){
//     echo("<p>".generateTruckVIN()."</p>");
// }



function generateShipmentEntryInfo($dbc, $trackingNum, $supplierID, $date){       
    /*
        Block of code to create initial shipment entry for supplier
    */

    $supplierTransactionNum = "SU" . mt_rand(10000, 50000);
    $current_time = date('H:i:s');

    $entryQuery = "INSERT INTO ShipmentEntry(transactionNum, trackingNum, timeShipped, dateShipped) VALUES (?, ?, ?,?)";
    $stmt = mysqli_prepare($dbc, $entryQuery);
    mysqli_stmt_bind_param($stmt, 'ssss', $supplierTransactionNum, $trackingNum, $current_time, $date);
    mysqli_stmt_execute($stmt);

    $supplierQuery = "INSERT INTO SupplierHasShipmentEntry (supplierID, trackingNum, transactionNum) VALUES (?, ?, ?)";
    $stmt11 = mysqli_prepare($dbc, $supplierQuery);
    mysqli_stmt_bind_param($stmt11, 'sss', $supplierID, $trackingNum, $supplierTransactionNum);
    mysqli_stmt_execute($stmt11);

    /*  
        End of code block to create initial shipment entry for supplier
    */




    /*
        Block of code to create shipment entry for warehouse
    */
    $warehouseTransactionNum = "WA" . mt_rand(10000, 50000);

    $warehouseidsQuery = "SELECT warehouseID FROM Warehouse";
    $wareHouseResult = mysqli_query($dbc, $warehouseidsQuery);
    $warehouseRows= mysqli_fetch_assoc($wareHouseResult);
    $warehouseID = $warehouseRows[array_rand($warehouseRows)];

    mysqli_free_result($wareHouseResult);

    $newTime = add_random_hours($current_time);
    $entryQuery2 = "INSERT INTO ShipmentEntry(transactionNum, trackingNum, timeShipped, dateShipped) VALUES (?, ?, ?,?)";
    $stmt10 = mysqli_prepare($dbc, $entryQuery2);
    mysqli_stmt_bind_param($stmt10, 'ssss', $warehouseTransactionNum, $trackingNum, $newTime, $date);
    mysqli_stmt_execute($stmt10);

    $warehouseQuery = "INSERT INTO WarehouseHasShipmentEntry (warehouseID, trackingNum, transactionNum) VALUES (?, ?, ?)";
    $stmt13 = mysqli_prepare($dbc, $warehouseQuery);
    mysqli_stmt_bind_param($stmt13, 'sss', $warehouseID, $trackingNum, $warehouseTransactionNum);
    mysqli_stmt_execute($stmt13);

     /*
        End of code block to create Block of code to create shipment entry for warehouse
    */





    /*
        Block of code to create shipment entry for truck
    */
    $truckTransactionNum = "TR" . mt_rand(10000, 50000);

    $truckVINQuery = "SELECT VIN FROM Truck";
    $truckResult = mysqli_query($dbc, $truckVINQuery);
    $truckRows= mysqli_fetch_assoc($truckResult);
    $truckVIN = $truckRows[array_rand($truckRows)];

    mysqli_free_result($truckResult);

    $newTime2 = add_random_hours($newTime);
    $entryQuery3 = "INSERT INTO ShipmentEntry(transactionNum, trackingNum, timeShipped, dateShipped) VALUES (?, ?, ?,?)";
    $stmt10 = mysqli_prepare($dbc, $entryQuery3);
    mysqli_stmt_bind_param($stmt10, 'ssss', $truckTransactionNum, $trackingNum, $newTime2, $date);
    mysqli_stmt_execute($stmt10);  

    $truckQuery = "INSERT INTO TruckHasShipmentEntry (VIN, trackingNum, transactionNum) VALUES (?, ?, ?)";
    $stmt15 = mysqli_prepare($dbc, $truckQuery);
    mysqli_stmt_bind_param($stmt15, 'sss', $truckVIN, $trackingNum, $truckTransactionNum);
    mysqli_stmt_execute($stmt15);

    /*
        End of code block to create Block of code to create shipment entry for truck
    */

}


