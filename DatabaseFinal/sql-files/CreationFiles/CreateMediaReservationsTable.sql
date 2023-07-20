--Create MediaReservations Table

DROP TABLE IF EXISTS MediaReservations;

CREATE TABLE MediaReservations (
    PRIMARY KEY             (PatronID, MediaID, ReservationCheckoutDate),
    PatronID                INT NOT NULL,
    MediaID                 INT NOT NULL,
    ReservationIsRenewed    INT DEFAULT 0 CHECK (ReservationIsRenewed <= 2),
    ReservationCheckoutDate DATE DEFAULT CURRENT_DATE NOT NULL,
    ReservationFee          DECIMAL(18, 2) DEFAULT 0,
    ReservationReturnDate   DATE,
    FOREIGN KEY             (PatronID) REFERENCES Patrons (PatronID) ON DELETE CASCADE,
    FOREIGN KEY             (MediaID) REFERENCES Media (MediaID) ON DELETE CASCADE
);
