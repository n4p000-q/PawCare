-- Step 1: Create the Database
CREATE DATABASE IF NOT EXISTS  VetManagement;
USE VetManagement;

-- DROP DATABASE VetManagement;
-- CREATE DATABASE VetManagement;

-- Step 2: Create Tables
CREATE TABLE IF NOT EXISTS Owners (
    Owner_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Contact VARCHAR(20) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS Pets (
    Pet_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Species VARCHAR(50) NOT NULL,
    Breed VARCHAR(50),
    Age INT,
    Owner_ID INT,
    FOREIGN KEY (Owner_ID) REFERENCES Owners(Owner_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Vets (
    Vet_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Specialization VARCHAR(100),
    Contact VARCHAR(20) NOT NULL
);

DROP TABLE Appointments;
CREATE TABLE Appointments (
    Appt_ID INT AUTO_INCREMENT PRIMARY KEY,
    Pet_ID INT,
    Vet_ID INT,
    Date DATE NOT NULL,
    Time TIME NOT NULL,  -- Added for more precision
    Reason TEXT,
    Status ENUM('Scheduled', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
    FOREIGN KEY (Pet_ID) REFERENCES Pets(Pet_ID) ON DELETE CASCADE,
    FOREIGN KEY (Vet_ID) REFERENCES Vets(Vet_ID) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Medical_Records (
    Record_ID INT AUTO_INCREMENT PRIMARY KEY,
    Pet_ID INT,
    Treatment TEXT NOT NULL,
    Prescription TEXT,
    Notes TEXT,
    FOREIGN KEY (Pet_ID) REFERENCES Pets(Pet_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Products (
    Product_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Category VARCHAR(50),
    Price DECIMAL(10,2),
    Stock INT NOT NULL
);

CREATE TABLE IF NOT EXISTS Orders (
    Order_ID INT AUTO_INCREMENT PRIMARY KEY,
    Customer_ID INT,
    Date DATE NOT NULL,
    Total DECIMAL(10,2),
    FOREIGN KEY (Customer_ID) REFERENCES Owners(Owner_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Order_Details (
    Order_ID INT,
    Product_ID INT,
    Quantity INT NOT NULL,
    PRIMARY KEY (Order_ID, Product_ID),
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID) ON DELETE CASCADE,
    FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Inventory (
    Product_ID INT PRIMARY KEY,
    Supplier VARCHAR(100),
    Stock_Level INT NOT NULL,
    FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID) ON DELETE CASCADE
);
/*

-- Insert Owners
INSERT IGNORE INTO Owners (Name, Contact, Email) VALUES
('John Doe', '123-456-7890', 'johndoe@mail.com'),
('Jane Smith', '987-654-3210', 'janesmith@mail.com'),
('Michael Brown', '555-123-4567', 'michaelbrown@mail.com');

-- Insert Pets
INSERT IGNORE INTO Pets (Name, Species, Breed, Age, Owner_ID) VALUES
('Max', 'Dog', 'Beagle', 4, 1),
('Bella', 'Cat', 'Siamese', 2, 2),
('Rocky', 'Dog', 'Labrador', 5, 1),
('Luna', 'Rabbit', 'Angora', 1, 3);

-- Insert Vets
INSERT IGNORE INTO Vets (Name, Specialization, Contact) VALUES
('Dr. Emily Carter', 'Surgery', '111-222-3333'),
('Dr. James Wilson', 'Dermatology', '444-555-6666');

-- Insert Appointments
INSERT IGNORE INTO Appointments (Pet_ID, Vet_ID, Date, Reason, Status) VALUES
(1, 1, '2025-04-10', 'Vaccination', 'Scheduled'),
(2, 2, '2025-04-12', 'Skin Allergy Treatment', 'Scheduled');

-- Insert Medical Records
INSERT IGNORE INTO Medical_Records (Pet_ID, Treatment, Prescription, Notes) VALUES
(1, 'Rabies Vaccine', 'Antibiotics', 'Next dose in 6 months'),
(2, 'Skin treatment', 'Ointment', 'Check progress in 2 weeks');

-- Insert Products
INSERT IGNORE INTO Products (Name, Category, Price, Stock) VALUES
('Dog Food', 'Food', 15.99, 50),
('Cat Food', 'Food', 12.99, 40),
('Pet Shampoo', 'Hygiene', 8.99, 30);

-- Insert Orders
INSERT IGNORE INTO Orders (Customer_ID, Date, Total) VALUES
(1, '2025-04-05', 30.00),
(2, '2025-04-06', 20.00);

-- Insert Order Details
INSERT IGNORE INTO Order_Details (Order_ID, Product_ID, Quantity) VALUES
(1, 1, 2),
(2, 3, 1);

-- Insert Inventory
INSERT IGNORE INTO Inventory (Product_ID, Supplier, Stock_Level) VALUES
(1, 'Pet Supplies Ltd.', 50),
(2, 'Best Pet Food Co.', 40),
(3, 'Animal Care Products', 30);

-- SELECT * FROM Pets;
-- SELECT * FROM Appointments;
-- SELECT * FROM Products;


-- SELECT Pets.Name AS Pet, Owners.Name AS Owner
-- FROM Pets
-- JOIN Owners ON Pets.Owner_ID = Owners.Owner_ID;
*/