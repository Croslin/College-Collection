--Determines if a person exists in the database

DROP PROCEDURE IF EXISTS find_user_password;

DELIMITER //
CREATE PROCEDURE find_user_password(first_name VARCHAR(32), last_name VARCHAR(32))
BEGIN
    SELECT LibrarianWebPassword AS Pass
      FROM Librarians
     WHERE LibrarianFirstName = first_name AND LibrarianLastName = last_name
    UNION ALL
    SELECT PatronWebPassword AS Pass
      FROM Patrons
     WHERE PatronFirstName = first_name AND PatronLastName = last_name;
END
//

DELIMITER ;