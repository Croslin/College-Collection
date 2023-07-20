--Determines if a person exists in the database

DROP FUNCTION IF EXISTS find_movie;

DELIMITER //
CREATE FUNCTION find_movie(movie_title VARCHAR(256), movie_director_first VARCHAR(32), movie_director_last VARCHAR(32))
RETURNS BOOLEAN
BEGIN
    IF EXISTS (
        SELECT MediaID
          FROM Movies
               INNER JOIN Media
               USING (MediaID)
         WHERE MediaTitle = movie_title AND movie_director_first = MovieDirectorFirstName AND movie_director_last = MovieDirectorLastName
    ) THEN 
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END
//

DELIMITER ;