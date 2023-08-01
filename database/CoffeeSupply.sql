CREATE TABLE Store (
    storeID varchar(8),
    storeName varchar(25) NOT NULL,
    storeStreet varchar(50) NOT NULL, 
    storeZip varchar(5) NOT NULL,
    storeState varchar(25) NOT NULL,
    storeCity varchar(25) NOT NULL,
    PRIMARY KEY (storeID)
);

CREATE TABLE Truck (
    VIN char(17),
    licenseState varchar(2) NOT NULL,
    licenseNumber varchar(8) NOT NULL UNIQUE,
    PRIMARY KEY (VIN)
);

CREATE TABLE PurchaseOrder (
    trackingNum INT,
    dateOrdered DATE NOT NULL,
    storeID varchar(8),
    PRIMARY KEY (trackingNum),
    FOREIGN KEY(storeID) REFERENCES Store(storeID)
);

CREATE TABLE Warehouse (
    warehouseID varchar(8),
    warehouseStreet varchar(50) NOT NULL,
    warehouseZip varchar(5) NOT NULL,
    warehouseState varchar(25) NOT NULL,
    warehouseCity varchar(25) NOT NULL,
    PRIMARY KEY (warehouseID)
);

CREATE TABLE Supplier (
    supplierID varchar(8),
    supplierName varchar(25) NOT NULL UNIQUE,
    supplierStreet varchar(50) NOT NULL,
    supplierZip varchar(5) NOT NULL,
    supplierState varchar(25) NOT NULL,
    supplierCity varchar(25) NOT NULL,
    PRIMARY KEY (supplierID)
);

CREATE TABLE Item (
    supplierID varchar(8),
    productID varchar(8),
    itemName varchar(25) NOT NULL UNIQUE,
    itemPrice decimal(5,2) NOT NULL,
    itemWeight INT NOT NULL,
    expirationDate DATE NOT NULL
    PRIMARY KEY (productID)
    
    FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID);
);

CREATE TABLE StoreHasItem (
    storeID varchar(8),
    productID varchar(8),
    quantity INT(50),
    CONSTRAINT PRIMARY KEY StoreHasItemPK (storeID, productID)
    FOREIGN KEY (storeID) REFERENCES Store(storeID),
    CONSTRAINT ItemsSupplierFK FOREIGN KEY (productID) REFERENCES Item(productID)
);

CREATE TABLE SupplierHasItem(
    supplierID varchar(8),
    productID varchar(8),
    productName varchar(75),
    CONSTRAINT PRIMARY KEY SupplierHasItemPK (productID, supplierID),
    FOREIGN KEY (productID) REFERENCES Item(productID),
    FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID),
    FOREIGN KEY (productName) REFERENCES Item(itemName)
);


CREATE TABLE OrderHasItem(
    trackingNum INT,
    productID varchar(8),
    quantity INT(50),
    CONSTRAINT PRIMARY KEY OrderHasItem (productID, trackingNum),
    FOREIGN KEY (productID) REFERENCES Item(productID),
    FOREIGN KEY (trackingNum) REFERENCES PurchaseOrder(trackingNum)
);

CREATE TABLE ShipmentEntry (
    transactionNum varchar(25),
    trackingNum INT,
    timeShipped TIME NOT NULL,
    dateShipped DATE NOT NULL,
    CONSTRAINT PRIMARY KEY ShipmentEntryPK (transactionNum, trackingNum),
    FOREIGN KEY (trackingNum) REFERENCES PurchaseOrder(trackingNum)
);

CREATE TABLE TruckHasShipmentEntry (
    VIN char(17), 
    transactionNum varchar(25),
    trackingNum INT,
    CONSTRAINT PRIMARY KEY TruckHasShipmentEntryPK (VIN, transactionNum, trackingNum),
    FOREIGN KEY (VIN) REFERENCES Truck(VIN),
    FOREIGN KEY (transactionNum) REFERENCES ShipmentEntry(transactionNum),
    FOREIGN KEY (trackingNum) REFERENCES PurchaseOrder(trackingNum)
);

CREATE TABLE WarehouseHasShipmentEntry (
    warehouseID varchar(8),
    trackingNum INT,
    transactionNum varchar(25),
    CONSTRAINT PRIMARY KEY WarehouseHasShipmentEntryPK (warehouseID, trackingNum, transactionNum),
    FOREIGN KEY (warehouseID) REFERENCES Warehouse(warehouseID),
    FOREIGN KEY (trackingNum) REFERENCES PurchaseOrder(trackingNum),
    FOREIGN KEY (transactionNum) REFERENCES ShipmentEntry(transactionNum)
);

CREATE TABLE SupplierHasShipmentEntry(
supplierID varchar(8),
trackingNum INT,
transactionNum varchar(25),
PRIMARY KEY (supplierID, trackingNum, transactionNum),
FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID),
FOREIGN KEY (trackingNum) REFERENCES PurchaseOrder(trackingNum),
FOREIGN KEY (transactionNum) REFERENCES ShipmentEntry(transactionNum)
);