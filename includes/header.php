<?php
    include './includes/title.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css" type="text/css">
    <link rel="stylesheet" href="styles/forms.css" type="text/css">
    <title><?php if(isset($title)) {echo "$title";} ?></title>
    <script src="js/helpers.js"></script>

</head>

<body>
    <header>
        <h1>CoffeeSupplyCo.</h1>
        <nav class="top_nav">
            <a href="index.php">Home</a>
            <a href="inventory.php">Inventory</a>
            <a href="orders.php">Orders</a>
            <a href="createOrder.php">Create Order</a>
            <a href="supplier.php">Suppliers</a>
            <div class="animation start-home"></div>
        </nav>   
    </header>
    <main>