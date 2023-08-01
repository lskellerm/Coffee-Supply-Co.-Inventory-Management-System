<?php
include("includes/header.php");
require_once ("../../mysqli_Coffee_config.php"); // Connect to the database

?>

<div id="bttnContainer">
        <button id="displayFormBttn" onclick="displayPurchaseOrders()">SHOW PURCHASE ORDERS</button>
</div>
<div class="purchaseOrderContainer" id="purchaseOrders"></div>

<div id="bttnContainer">
        <button id="displayFormBttn" onclick="displayTotalWeights()">SHOW PURCHASE ORDER TOTAL WEIGHTS</button>
</div>
<div class="totalWeightsContainer" id="totalWeights"></div>




<div id="bttnContainer">
        <button id="displayFormBttn" onclick="displayOrderTrackingForm()">TRACK ORDER</button>
</div>



<div id="trackingOrderFormContainer" style="display:none">
        <?php 
            if ($searchErrors){
            echo("<script>displayOrderTrackingForm();</script>");
            }
        ?>
<h2 class="form_header">TRACKING HISTORY SEARCH</h2>
    <div class="formDescription">
        <p>Please enter the <span class="textEmphasis">Tracking Number</span> you wish to show order history for
        </p>
    </div>

    <?php

    if (isset($_POST['showTracking'])){
        $searchErrors = array();
        $trackingNumber = $_POST['trackingNumber'];
        if ($selectedSupplier == ""){
            $errors['trackingNumber'] = "Please enter the tracking number you wish to show order history for";
        }
    }
    ?>

    <form class="user_form" class="topBefore" id="trackingForm">
        <?php if ($searchErrors['trackingNumber']) echo("<h2 class=\"warning\">{$searchErrors['trackingNumber']}</h2>");?>
        <input type="text" id="trackingNumber" placeholder="TRACKING NUMBER" name="trackingNumber">
        <input id="trackingSubmit" type="submit" value="SHOW TRACKING" name="showTracking" onclick="getTrackingHistory()">
    </form>
</div>

<div class="trackingHistoryContainer" id="trackingHistory"></div>

<!-- The Modal -->
<div id="myModal" class="modal" style="display:none;">
  <!-- Modal content -->
  <div id='supplierInventoryContainer'>
  </div>
</div>
</main>
</body>
</html>