--Retrieves a patronID from the database

DROP FUNCTION IF EXISTS GetPatronID;

DELIMITER //
CREATE FUNCTION GetPatronID(first_name VARCHAR(32), last_name VARCHAR(32))
RETURNS INT
BEGIN
    IF EXISTS(
        SELECT PatronFirstName AS FirstName, PatronLastName AS LastName
          FROM Patrons
         WHERE PatronFirstName = first_name AND PatronLastName = last_name
    ) THEN 
        RETURN (SELECT PatronID FROM Patrons WHERE PatronFirstName = first_name AND PatronLastName = last_name);
    ELSE
        RETURN NULL;
    END IF;
END
//

DELIMITER ;