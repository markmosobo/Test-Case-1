CREATE DATABASE demo;

CREATE TABLE cities (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE addresses (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email varchar(255) NOT NULL,
    street VARCHAR(255) NOT NULL,
    zipcode VARCHAR(100) NOT NULL,
    city VARCHAR(255) NOT NULL
);

INSERT INTO cities VALUES("1","Nairobi");
INSERT INTO cities VALUES("2","Mombasa");
INSERT INTO cities VALUES("3","Kisumu");
INSERT INTO cities VALUES("4","New York");
INSERT INTO cities VALUES("5","London");
INSERT INTO cities VALUES("6","Kuala Lumpur");