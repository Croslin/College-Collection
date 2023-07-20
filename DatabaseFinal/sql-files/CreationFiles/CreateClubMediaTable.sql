--Creates table ClubMedia

DROP TABLE IF EXISTS ClubMedia;

CREATE TABLE ClubMedia(
    PRIMARY KEY(ClubName, MediaID),
    ClubName VARCHAR(128) NOT NULL,
    MediaID INT NOT NULL,
    ClubMediaDateBegin DATE DEFAULT CURRENT_DATE NOT NULL,
    ClubMediaDateEnd DATE,
    FOREIGN KEY (ClubName) REFERENCES Clubs (ClubName) ON DELETE CASCADE,
    FOREIGN KEY (MediaID) REFERENCES Media (MediaID) ON DELETE CASCADE
);
