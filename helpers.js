function displaySupplierForm() {
    var formEl = document.getElementById("supplierFormContainer");
    if (formEl.style.display === 'none' || formEl.style.display === "") {
      formEl.style.display = "block";
    } else {
      formEl.style.display = "none";
    }
  }

  function displayInventoryCountForm() {
    var formEl = document.getElementById("inventoryCountFormContainer");
    if (formEl.style.display === 'none' || formEl.style.display === "") {
      formEl.style.display = "block";
    } else {
      formEl.style.display = "none";
    }
  }

  function displayOrderTrackingForm() {
    var formEl = document.getElementById("trackingOrderFormContainer");
    if (formEl.style.display === 'none' || formEl.style.display === "") {
      formEl.style.display = "block";
      document.getElementById("trackingForm").scrollIntoView();
    } else {
      formEl.style.display = "none";
    }
  }


  function displayPurchaseOrders(){

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
      if (this.readyState == 4 && this.status == 200){
        var response = xmlhttp.responseText;
        document.getElementById("purchaseOrders").innerHTML = response;
        document.getElementById("purchaseOrders").scrollIntoView();

        console.log("Success");
      }
    };
    xmlhttp.open("GET", 'displayPurchaseOrders.php', true);
    xmlhttp.send();
  }

  function displayTotalWeights(){

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
      if (this.readyState == 4 && this.status == 200){
        var response = xmlhttp.responseText;
        document.getElementById("totalWeights").innerHTML = response;
        document.getElementById("totalWeights").scrollIntoView();
        console.log("Success");
      }
    };
    xmlhttp.open("GET", 'displayTotalWeights.php', true);
    xmlhttp.send();
  }

  // Function which creates an AJAX request to obtain the supplierID of the row which is clicked on in the suppliers table
  function getSupplierID(el){
    var supplierID = el.getElementsByTagName('td')[0].innerHTML;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
      if (this.readyState == 4 && this.status == 200){
        var response = xmlhttp.responseText;
        document.getElementById("supplierInventoryContainer").innerHTML = response;
        console.log("Success");
      }
    };
    xmlhttp.open("GET", "getSupplierInventory.php?value=" + supplierID, true);
    xmlhttp.send();
  }

  function getTrackingNum(el){
    var trackingNum= el.getElementsByTagName('td')[0].innerHTML;
    var modal = document.getElementById("myModal");

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function(){
      if (this.readyState == 4 && this.status == 200){
        var response = xmlhttp.responseText;
        document.getElementById("supplierInventoryContainer").innerHTML = response;
        modal.style.display = "block";
        console.log("Success");
      }
    };
    xmlhttp.open("GET", "displayOrderDetails.php?value=" + trackingNum, true);
    xmlhttp.send();
  }

  window.onload = function(){
    var supplierRows = document.querySelectorAll(".supplierRow");
    var orderRows = document.querySelectorAll(".orderDetailsRow");
    var modal = document.getElementById("myModal");


    supplierRows.forEach(function(row){
      row.addEventListener("click", function(event){
        modal.style.display = "block";
      });
    });

    

    window.onclick = function(event){
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }


    var targetForm = document.querySelector('#trackingForm');
    if (targetForm){
      document.querySelector('#trackingForm').addEventListener('submit', function(event){
        event.preventDefault();
      })  
    }

  }

  // Function to which creates an AJAX request to obtain the supplier Inventory of the supplier which is clicked on in the suppliers selection option
  function getSupplierInventory(el){
    var supplierOption = document.querySelector("#supplier option:checked");
    var supplierName = supplierOption.textContent;
    
    if (supplierName != 'SELECT SUPPLIER'){
      console.log(supplierName);
      
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200){
          var response = xmlhttp.responseText;
          document.getElementById("items").innerHTML = response;
          document.getElementById("items").style.display = "block";
          console.log("Success");
        }
      };
      xmlhttp.open("GET", "supplierInventoryOption.php?value=" + supplierName, true);
      xmlhttp.send();
    }
    else{
          document.getElementById("items").style.display = "none";
    }
    
  }

  function getSupplierInventoryCount(el){
    var supplierOption = document.querySelector("#supplier option:checked");
    var supplierName = supplierOption.textContent;
    
    if (supplierName != 'SELECT SUPPLIER'){
      
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200){
          var response = xmlhttp.responseText;
          document.getElementById("inventoryCount").innerHTML = response;
          document.getElementById("inventoryCount").style.display = "block";
          document.getElementById("inventoryCount").scrollIntoView();

          console.log("Success");
        }
      };
      xmlhttp.open("GET", "displayInventoryCounts.php?value=" + supplierName, true);
      xmlhttp.send();
    }
    else{
          document.getElementById("inventoryCount").style.display = "none";
    }
    
  }

  function getTrackingHistory(el){
    var inputElement =  document.getElementById('trackingNumber');

    var trackingNumber = document.getElementById('trackingNumber').value;
    console.log(trackingNumber);
    
    if (trackingNumber != ''){
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200){
          var response = xmlhttp.responseText;
          document.getElementById("trackingHistory").innerHTML = response;
          document.getElementById("trackingHistory").style.display = "block";
          document.getElementById("trackingHistory").scrollIntoView();

          console.log("Success");
        }
      };
      xmlhttp.open("GET", "displayTrackingHistory.php?value=" + trackingNumber, true);
      xmlhttp.send();
    }
    else{
      inputElement.focus();
      document.getElementById("trackingHistory").scrollIntoView();
      console.log("Failure");
    }
    
  }
  
