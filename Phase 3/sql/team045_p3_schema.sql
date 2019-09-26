
-- Tables

CREATE TABLE Manager (
email_address varchar(50) NOT NULL,
first_name varchar(50) NOT NULL,
last_name varchar(50) NOT NULL,
PRIMARY KEY (email_address)
);

CREATE TABLE City (
city_name varchar(50) NOT NULL,
population int NOT NULL,
state varchar(50) NOT NULL,
PRIMARY KEY (city_name)
);

CREATE TABLE Manufacturer (
manufacturer_name varchar(50) NOT NULL,
cap_discount float NOT NULL,
PRIMARY KEY (manufacturer_name)
);

CREATE TABLE Store (
store_number varchar(50) NOT NULL,
city_name varchar(50) NOT NULL,
phone_number varchar(10) NOT NULL,
street_address varchar(50) NOT NULL,
PRIMARY KEY (store_number),
FOREIGN KEY (city_name) REFERENCES City (city_name)
);

CREATE TABLE ActiveManager (
email_address varchar(50) NOT NULL,
store_number varchar(50) NOT NULL,
UNIQUE (email_address, store_number),
FOREIGN KEY (email_address) REFERENCES Manager (email_address),
FOREIGN KEY (store_number) REFERENCES Store (store_number)
);

CREATE TABLE Product (
product_ID varchar(50) NOT NULL,
product_name varchar(50) NOT NULL,
retail_price float NOT NULL,
manufacturer_name varchar(50) NOT NULL,
PRIMARY KEY (product_ID),
FOREIGN KEY (manufacturer_name) REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE Category (
product_ID varchar(50) NOT NULL,
category_name varchar(50) NOT NULL,
UNIQUE  (category_name,product_ID),
FOREIGN KEY (product_ID) REFERENCES Product (product_ID)
);

CREATE TABLE Sales (
product_ID varchar(50) NOT NULL,
sales_date date NOT NULL,
sales_price float NOT NULL,
PRIMARY KEY (product_ID, sales_date),
FOREIGN KEY (product_ID) REFERENCES Product (product_ID)
);

CREATE TABLE Transaction (
product_ID varchar(50) NOT NULL,
store_number varchar(50) NOT NULL,
transaction_date date NOT NULL,
quantity float NOT NULL,
PRIMARY KEY (product_ID,store_number,transaction_date),
FOREIGN KEY (product_ID) REFERENCES Product (product_ID),
FOREIGN KEY (store_number) REFERENCES Store (store_number)
);

CREATE TABLE Holiday (
holiday_date date NOT NULL,
holiday_name varchar(50) NOT NULL,
PRIMARY KEY (holiday_date)
);

