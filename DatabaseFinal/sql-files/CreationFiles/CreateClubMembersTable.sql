--Create ClubMembers table

DROP TABLE IF EXISTS ClubMembers;

CREATE TABLE ClubMembers(
    PRIMARY KEY (ClubName, PatronID),
    ClubName VARCHAR(128) NOT NULL,
    PatronID INT,
    FOREIGN KEY (ClubName) REFERENCES Clubs (ClubName) ON DELETE CASCADE,
    FOREIGN KEY (PatronID) REFERENCES Patrons (PatronID) ON DELETE CASCADE
);
