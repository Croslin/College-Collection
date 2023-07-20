--Determines if a person exists in the database

DROP FUNCTION IF EXISTS find_book;

DELIMITER //
CREATE FUNCTION find_book(book_title VARCHAR(256), book_author_first VARCHAR(32), book_author_last VARCHAR(32))
RETURNS BOOLEAN
BEGIN
    IF EXISTS (
        SELECT MediaID
          FROM Books
               INNER JOIN Media
               USING (MediaID)
         WHERE MediaTitle = book_title AND book_author_first = BookAuthorFirstName AND book_author_last = BookAuthorLastName
    ) THEN 
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END
//

DELIMITER ;