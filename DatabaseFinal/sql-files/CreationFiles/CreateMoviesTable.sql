--Create Movies Table

DROP TABLE IF EXISTS Movies;

CREATE TABLE Movies (
    PRIMARY KEY             (MediaID),
    MediaID                 INT NOT NULL,
    MovieDirectorFirstName  VARCHAR(32) NOT NULL,
    MovieDirectorLastName   VARCHAR(32) NOT NULL, -- Missing support for other relevant credits
    MovieStudio             VARCHAR(256),
    MovieFormat             VARCHAR(128) NOT NULL, -- Maybe want a validation table later
    MovieLengthInMinutes    INT,
    FOREIGN KEY             (MediaID) REFERENCES Media (MediaID) ON DELETE CASCADE
);
