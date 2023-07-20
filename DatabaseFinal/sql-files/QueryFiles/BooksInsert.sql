--Insert an entity into Books
--Minimal insertion, only required values
--Can use update for non required values or another script

--First do parent table
INSERT INTO Media (MediaTitle)
VALUES (?);

--Then subset with the id from the insert above
INSERT INTO Books (MediaID, BookISBN, BookAuthorFirstName, BookAuthorLastName)
VALUES (SELECT LAST_INSERT_ID(), ?, ?, ?);
