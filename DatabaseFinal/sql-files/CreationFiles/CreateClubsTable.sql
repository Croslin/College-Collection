--Create Clubs table

DROP TABLE IF EXISTS Clubs;

CREATE TABLE Clubs (
    PRIMARY KEY (ClubName),
    ClubName VARCHAR(128) NOT NULL,
    ClubDescription VARCHAR(4096) DEFAULT ''
);
