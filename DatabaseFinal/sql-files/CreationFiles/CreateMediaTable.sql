--Create media table
--Probably want to turn MediaSeries into a linking table for more functionality
--MediaGenre currently only support a single genre, maybe need a linking table to expand robustness

DROP TABLE IF EXISTS Media;

CREATE TABLE Media (
    PRIMARY KEY             (MediaID),
    MediaID                 INT AUTO_INCREMENT NOT NULL,
    MediaPublicationYear    YEAR,
    MediaTitle              VARCHAR(256) NOT NULL,
    MediaSeries             VARCHAR(128),       
    MediaDescription        VARCHAR(1024),
    MediaGenre              VARCHAR(32)        
);
    
