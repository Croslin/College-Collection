--Determines if a person exists in the database
--BOOLEAN: true if librarian, false if patron

DROP FUNCTION IF EXISTS find_user_type;

DELIMITER //
CREATE FUNCTION find_user_type(first_name VARCHAR(32), last_name VARCHAR(32))
RETURNS BOOLEAN
BEGIN
    IF EXISTS(
        SELECT LibrarianFirstName AS FirstName, LibrarianLastName AS LastName
          FROM Librarians
         WHERE LibrarianFirstName = first_name AND LibrarianLastName = last_name
    ) THEN 
        RETURN TRUE;
    ELSE 
        RETURN FALSE;
    END IF;
END
//

DELIMITER ;