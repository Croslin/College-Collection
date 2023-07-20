SELECT MediaTitle AS "Title", MediaID, CONCAT(MovieDirectorFirstName, ' ', MovieDirectorLastName) AS "Director Name", MovieFormat AS "Format", MediaPublicationYear AS "Year of Release", MediaSeries AS "Series", 
      MediaGenre AS "Genre", MovieLengthInMinutes AS "Length in Minutes", MovieStudio AS "Studio", MediaDescription AS "Description"
  FROM Movies
       INNER JOIN Media
       USING (MediaID)
 WHERE MediaID = ?;