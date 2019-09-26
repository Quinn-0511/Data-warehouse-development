-- Tables

CREATE TABLE Manager(
managerEmail varchar(50) NOT NULL,
First_Name varchar(50) NOT NULL,
Last_Name varchar(50) NOT NULL,
PRIMARY KEY (managerEmail)
);

CREATE TABLE ActiveManage(
managerEmail varchar(50) NOT NULL,
Store_Number varchar(50) NOT NULL,
UNIQUE (managerEmail, Store_Number),
FOREIGN KEY (managerEmail) REFERENCES Manager (managerEmail),
FOREIGN KEY (Store_Number) REFERENCES Store (Store_Number)
);


CREATE TABLE Store(
Store_Number varchar(50) NOT NULL,
City_Name varchar(50) NOT NULL,
Phone_Number varchar(10) NOT NULL,
Street_Address varchar(50) NOT NULL,
PRIMARY KEY (Store_Number),
FOREIGN KEY (City_Name) REFERENCES City (City_Name)
);

CREATE TABLE City(
City_Name varchar(50) NOT NULL,
Population int NOT NULL,
State varchar(50) NOT NULL,
PRIMARY KEY (CityName));





CREATE TABLE Product(
PID varchar(50) NOT NULL,
Product_Name varchar(50) NOT NULL,
Manufacturer_Name varchar(50) NOT NULL,
Retail_Price float NOT NULL,
PRIMARY KEY (PID),
FOREIGN KEY (Manufacturer_Name) REFERENCES Manufacturer
 (Manufacturer_Name));

 CREATE TABLE Categories(
PID varchar(50) NOT NULL,
Categories_Name varchar(50) NOT NULL,
UNIQUE  (Categories_Name,PID)
FOREIGN KEY(PID) REFERENCES Product (PID));


CREATE TABLE Manufacturer (
Manufacturer_Name varchar(50) NOT NULL,
Cap_Discount float NOT NULL,
PRIMARY KEY (Manufacturer_Name)
);


CREATE TABLE Sales (
PID varchar(50) NOT NULL,
Sales_Date date NOT NULL,
Sales_Price float NOT NULL,
PRIMARY KEY (PID, Sales_Date),
FOREIGN KEY (PID) REFERENCES Product (PID)
);

CREATE TABLE Transaction(
PID varchar(50) NOT NULL,
Store_Number varchar(50) NOT NULL,
Transaction_Date date NOT NULL,
Quantity float NOT NULL,
PRIMARY KEY (PID,Store_Number,Transaction_Date)
FOREIGN KEY (PID) REFERENCES Product (PID),
FOREIGN KEY (Store_Number) REFERENCES Store (Store_Number));

CREATE TABLE Holiday(
Holiday_Date date NOT NULL,
Holiday_Name varchar(50) NOT NULL,
PRIMARY KEY (Holiday_Date));
