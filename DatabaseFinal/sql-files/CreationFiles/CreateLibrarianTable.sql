--Create Librarian Table

DROP TABLE IF EXISTS Librarians;

CREATE TABLE Librarians (
    PRIMARY KEY           (LibrarianID),
    LibrarianID              INT AUTO_INCREMENT NOT NULL,
    LibrarianWebPassword     VARCHAR(255) NOT NULL,
    LibrarianFirstName       VARCHAR(32) NOT NULL,
    LibrarianLastName        VARCHAR(32) NOT NULL,
    LibrarianAddressStreet   VARCHAR(128),
    LibrarianAddressCity     VARCHAR(64),
    LibrarianAddressState    VARCHAR(2),
    LibrarianAddressZip      INT,
    LibrarianEmail           VARCHAR(128) NOT NULL,
    LibrarianPhoneNumber     VARCHAR(10) NOT NULL
);
