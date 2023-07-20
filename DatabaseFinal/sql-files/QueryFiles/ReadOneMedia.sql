SELECT MediaTitle AS "Title", MediaPublicationYear AS "Year of Release", MediaSeries AS "Series", MediaDescription AS "Description", MediaGenre as Genre
  FROM Media
 WHERE MediaID = ?;