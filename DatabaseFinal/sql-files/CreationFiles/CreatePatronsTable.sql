--Create Patrons Table

DROP TABLE IF EXISTS Patrons;

CREATE TABLE Patrons (
    PRIMARY KEY           (PatronID),
    PatronID              INT AUTO_INCREMENT NOT NULL,
    PatronWebPassword     VARCHAR(255) NOT NULL,
    PatronFirstName       VARCHAR(32) NOT NULL,
    PatronLastName        VARCHAR(32) NOT NULL,
    PatronAddressStreet   VARCHAR(128),
    PatronAddressCity     VARCHAR(64),
    PatronAddressState    VARCHAR(2),
    PatronAddressZip      INT,
    PatronEmail           VARCHAR(128) NOT NULL,
    PatronPhoneNumber     VARCHAR(10) NOT NULL,
    PatronBalance         DECIMAL(18, 2) DEFAULT 0
);
