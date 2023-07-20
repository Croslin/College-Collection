--Determines if a person exists in the database

DROP PROCEDURE IF EXISTS insert_book;

DELIMITER //
CREATE PROCEDURE insert_book(book_title VARCHAR(256), publication_year YEAR, book_series VARCHAR(128), book_description VARCHAR(1024), book_genre VARCHAR(32),
                              author_first_name VARCHAR(32), author_last_name VARCHAR(32), isbn VARCHAR(13), book_format VARCHAR(64), length_in_pages INT, publisher VARCHAR(256))
BEGIN
    INSERT INTO Media (MediaTitle, MediaPublicationYear, MediaSeries, MediaDescription, MediaGenre)
    VALUES (book_title, publication_year, book_series, book_description, book_genre);

    INSERT INTO Books (MediaID, BookISBN, BookAuthorFirstName, BookAuthorLastName, BookFormat, BookLengthInPages, BookPublisher)
    VALUES (LAST_INSERT_ID(), isbn, author_first_name, author_last_name, book_format, length_in_pages, publisher);
END
//

DELIMITER ;