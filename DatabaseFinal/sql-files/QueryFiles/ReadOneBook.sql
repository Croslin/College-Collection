SELECT MediaTitle AS "Title", MediaID, BookISBN AS "ISBN", CONCAT(BookAuthorFirstName, ' ', BookAuthorLastName) AS "Author Name", BookFormat AS "Format", MediaPublicationYear AS "Year of Release", MediaSeries AS "Series", 
      MediaGenre AS "Genre", BookLengthInPages AS "Length in Pages", BookPublisher AS "Publisher", MediaDescription AS "Description"
  FROM Books
       INNER JOIN Media
       USING (MediaID)
 WHERE MediaID = ?;