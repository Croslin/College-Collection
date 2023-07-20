--Determines whether a piece of media is a book or movie

DROP FUNCTION IF EXISTS get_media_type;

DELIMITER //
CREATE FUNCTION get_media_type(media_id INT)
RETURNS VARCHAR(5)
BEGIN
    IF EXISTS(
        SELECT MediaID
          FROM Books
         WHERE MediaID = media_id
    ) THEN 
        RETURN "Book";
    ELSE 
        RETURN "Movie";
    END IF;
END
//

DELIMITER ;