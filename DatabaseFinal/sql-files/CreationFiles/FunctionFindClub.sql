--Determines if a person exists in the database

DROP FUNCTION IF EXISTS find_club;

DELIMITER //
CREATE FUNCTION find_club(club_name VARCHAR(128))
RETURNS BOOLEAN
BEGIN
    IF EXISTS (
        SELECT ClubName
          FROM Clubs
         WHERE ClubName = club_name
    ) THEN 
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END
//

DELIMITER ;