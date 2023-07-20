--Insert an entity into MediaReservations
--Minimal insertion, only required values
--Can use update for non required values or another script

INSERT INTO MediaReservations (PatronID, MediaID)
VALUES (?, ?);
