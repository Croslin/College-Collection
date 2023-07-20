--Create Books Table

DROP TABLE IF EXISTS Books;

CREATE TABLE Books (
    PRIMARY KEY           (MediaID),
    MediaID               INT NOT NULL,
    BookISBN              VARCHAR(13) NOT NULL,
    BookAuthorFirstName   VARCHAR(32) NOT NULL,
    BookAuthorLastName    VARCHAR(32) NOT NULL, -- Missing support for multiple authors
    BookFormat            VARCHAR(64),          -- Maybe needs a validation table for types
    BookLengthInPages     INT,
    BookPublisher         VARCHAR(256),
    FOREIGN KEY           (MediaID) REFERENCES Media (MediaID) ON DELETE CASCADE
);
