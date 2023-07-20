--Determines if a person exists in the database

DROP PROCEDURE IF EXISTS insert_movie;

DELIMITER //
CREATE PROCEDURE insert_movie(movie_title VARCHAR(256), publication_year YEAR, movie_series VARCHAR(128), movie_description VARCHAR(1024), movie_genre VARCHAR(32),
                              director_first_name VARCHAR(32), director_last_name VARCHAR(32), movie_format VARCHAR(128), length_in_minutes INT, studio VARCHAR(256))
BEGIN
    INSERT INTO Media (MediaTitle, MediaPublicationYear, MediaSeries, MediaDescription, MediaGenre)
    VALUES (movie_title, publication_year, movie_series, movie_description, movie_genre);

    INSERT INTO Movies (MediaID, MovieDirectorFirstName, MovieDirectorLastName, MovieFormat, MovieLengthInMinutes, MovieStudio)
    VALUES (LAST_INSERT_ID(), director_first_name, director_last_name, movie_format, length_in_minutes, studio);
END
//

DELIMITER ;