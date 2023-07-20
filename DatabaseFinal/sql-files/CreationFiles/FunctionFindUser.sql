--Determines if a person exists in the database

DROP FUNCTION IF EXISTS find_user;

DELIMITER //
CREATE FUNCTION find_user(first_name VARCHAR(32), last_name VARCHAR(32))
RETURNS BOOLEAN
BEGIN
    IF EXISTS(
        SELECT LibrarianFirstName AS FirstName, LibrarianLastName AS LastName
          FROM Librarians
         WHERE LibrarianFirstName = first_name AND LibrarianLastName = last_name
        UNION ALL
        SELECT PatronFirstName AS FirstName, PatronLastName AS LastName
          FROM Patrons
         WHERE PatronFirstName = first_name AND PatronLastName = last_name
    ) THEN 
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END
//

DELIMITER ;