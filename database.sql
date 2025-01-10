-- Create database
DROP DATABASE IF EXISTS inventory_management;
CREATE DATABASE IF NOT EXISTS inventory_management;
USE inventory_management;

-- Product table
CREATE TABLE Product (
                         id int PRIMARY KEY AUTO_INCREMENT,
                         name VARCHAR(255) NOT NULL,
                         description TEXT,
                         barcode VARCHAR(50) UNIQUE,
                         price DECIMAL(10,2),
                         category_id INT,
                         user_id INT,
                         location VARCHAR(100),
                         min_stock DECIMAL(10,2),
                         max_stock DECIMAL(10,2),
                         unit VARCHAR(20),
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Category table
CREATE TABLE Category (
                          id int PRIMARY KEY AUTO_INCREMENT,
                          name VARCHAR(100) NOT NULL,
                          description TEXT,
                          parent_category_id INT,
                          user_id INT NOT NULL ,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          FOREIGN KEY (parent_category_id) REFERENCES Category(id)

);

-- Warehouse table
CREATE TABLE Warehouse (
                           id int PRIMARY KEY AUTO_INCREMENT,
                           name VARCHAR(100) NOT NULL,
                           address TEXT,
                           capacity DECIMAL(10,2),
                           manager VARCHAR(100),
                           is_active BOOLEAN DEFAULT TRUE,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Stock table
CREATE TABLE Stock (
                       id int PRIMARY KEY AUTO_INCREMENT,
                       product_id INT,
                       warehouse_id INT,
                       quantity DECIMAL(10,2) DEFAULT 0,
                       status VARCHAR(50),
                       last_check_date TIMESTAMP,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       FOREIGN KEY (product_id) REFERENCES Product(id),
                       FOREIGN KEY (warehouse_id) REFERENCES Warehouse(id)
);

-- User table
CREATE TABLE User (
                      id int PRIMARY KEY AUTO_INCREMENT,
                      name VARCHAR(100) NOT NULL,
                      username VARCHAR(50) UNIQUE,
                      role VARCHAR(50) DEFAULT 'user',
                      email VARCHAR(255) UNIQUE,
                      password VARCHAR(255),
                      department VARCHAR(100),
                      is_active BOOLEAN DEFAULT TRUE,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Supplier table
CREATE TABLE Supplier (
                          id int PRIMARY KEY AUTO_INCREMENT,
                          name VARCHAR(100) NOT NULL,
                          contact VARCHAR(100),
                          email VARCHAR(255),
                          phone VARCHAR(20),
                          address TEXT,
                          rating DECIMAL(3,2),
                          payment_terms TEXT,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Purchase Order table
CREATE TABLE PurchaseOrder (
                               id int PRIMARY KEY AUTO_INCREMENT,
                               supplier_id INT,
                               order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               expected_delivery_date TIMESTAMP,
                               total_amount DECIMAL(10,2),
                               status VARCHAR(50),
                               payment_status VARCHAR(50),
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                               FOREIGN KEY (supplier_id) REFERENCES Supplier(id)
);

-- Order Item table
CREATE TABLE OrderItem (
                           id int PRIMARY KEY AUTO_INCREMENT,
                           purchase_order_id INT,
                           product_id INT,
                           quantity INT,
                           unit_price DECIMAL(10,2),
                           total_price DECIMAL(10,2),
                           status VARCHAR(50),
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                           FOREIGN KEY (purchase_order_id) REFERENCES PurchaseOrder(id),
                           FOREIGN KEY (product_id) REFERENCES Product(id)
);

-- Stock Movement table
CREATE TABLE StockMovement (
                               id int PRIMARY KEY AUTO_INCREMENT,
                               product_id INT,
                               user_id INT,
                               type VARCHAR(50),
                               quantity DECIMAL(10,2),
                               date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               reason TEXT,
                               reference_document VARCHAR(100),
                               status VARCHAR(50),
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                               FOREIGN KEY (product_id) REFERENCES Product(id),
                               FOREIGN KEY (user_id) REFERENCES User(id)
);

-- Inventory table
CREATE TABLE Inventory (
                           id int PRIMARY KEY AUTO_INCREMENT,
                           date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           type VARCHAR(50),
                           status VARCHAR(50),
                           conducted_by INT,
                           notes TEXT,
                           created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                           updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                           FOREIGN KEY (conducted_by) REFERENCES User(id)
);

-- Inventory Item table
CREATE TABLE InventoryItem (
                               id int PRIMARY KEY AUTO_INCREMENT,
                               inventory_id INT,
                               product_id INT,
                               expected_quantity DECIMAL(10,2),
                               actual_quantity DECIMAL(10,2),
                               discrepancy DECIMAL(10,2),
                               notes TEXT,
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                               FOREIGN KEY (inventory_id) REFERENCES Inventory(id),
                               FOREIGN KEY (product_id) REFERENCES Product(id)
);

-- Alert table
CREATE TABLE Alert (
                       id int PRIMARY KEY AUTO_INCREMENT,
                       type VARCHAR(50),
                       severity VARCHAR(50),
                       message TEXT,
                       is_read BOOLEAN DEFAULT FALSE,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       product_id INT,
                       stock_id INT,
                       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       FOREIGN KEY (product_id) REFERENCES Product(id),
                       FOREIGN KEY (stock_id) REFERENCES Stock(id)
);

-- Stock Return table
CREATE TABLE StockReturn (
                             id int PRIMARY KEY AUTO_INCREMENT,
                             date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             reason TEXT,
                             status VARCHAR(50),
                             approved_by INT,
                             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                             FOREIGN KEY (approved_by) REFERENCES User(id)
);

-- Stock Return Items table (junction table for StockReturn and Product)
CREATE TABLE StockReturnItem (
                                 stock_return_id INT,
                                 product_id INT,
                                 quantity DECIMAL(10,2),
                                 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                 PRIMARY KEY (stock_return_id, product_id),
                                 FOREIGN KEY (stock_return_id) REFERENCES StockReturn(id),
                                 FOREIGN KEY (product_id) REFERENCES Product(id)
);

-- Add foreign key for category in Product table
ALTER TABLE Product
    ADD FOREIGN KEY (category_id) REFERENCES Category(id);

-- Add foreign key for user in Product table
ALTER TABLE Product
    ADD FOREIGN KEY (user_id) REFERENCES User(id);

-- Add foreign key for product in Stock table
ALTER TABLE Stock
    ADD FOREIGN KEY (product_id) REFERENCES Product(id);

-- Add foreign key for warehouse in Stock table
ALTER TABLE Stock
    ADD FOREIGN KEY (warehouse_id) REFERENCES Warehouse(id);

-- Add foreign key for user in StockMovement table
ALTER TABLE StockMovement
    ADD FOREIGN KEY (user_id) REFERENCES User(id);

-- Add foreign key for product in StockMovement table
ALTER TABLE StockMovement
    ADD FOREIGN KEY (product_id) REFERENCES Product(id);

-- Add foreign key for product in OrderItem table
ALTER TABLE OrderItem
    ADD FOREIGN KEY (product_id) REFERENCES Product(id);

-- Add foreign key for purchase order in OrderItem table
ALTER TABLE OrderItem
    ADD FOREIGN KEY (purchase_order_id) REFERENCES PurchaseOrder(id);

-- Add foreign key for supplier in PurchaseOrder table
ALTER TABLE PurchaseOrder
    ADD FOREIGN KEY (supplier_id) REFERENCES Supplier(id);

-- Add foreign key for product in InventoryItem table
ALTER TABLE InventoryItem
    ADD FOREIGN KEY (product_id) REFERENCES Product(id);

-- Add foreign key for inventory in InventoryItem table
ALTER TABLE InventoryItem
    ADD FOREIGN KEY (inventory_id) REFERENCES Inventory(id);

-- Add foreign key for product in Alert table
ALTER TABLE Alert
    ADD FOREIGN KEY (product_id) REFERENCES Product(id);

-- Add foreign key for stock in Alert table
ALTER TABLE Alert
    ADD FOREIGN KEY (stock_id) REFERENCES Stock(id);

-- Add foreign key for user in Inventory table
ALTER TABLE Inventory
    ADD FOREIGN KEY (conducted_by) REFERENCES User(id);

-- Add foreign key for product in StockReturnItem table
ALTER TABLE StockReturnItem
    ADD FOREIGN KEY (product_id) REFERENCES Product(id);

-- Add foreign key for stock return in StockReturnItem table
ALTER TABLE StockReturnItem
    ADD FOREIGN KEY (stock_return_id) REFERENCES StockReturn(id);

-- Add foreign key for user in StockReturn table
ALTER TABLE StockReturn
    ADD FOREIGN KEY (approved_by) REFERENCES User(id);

-- Add foreign key for user in Category table
ALTER TABLE Category
    ADD FOREIGN KEY (user_id) REFERENCES User(id);



