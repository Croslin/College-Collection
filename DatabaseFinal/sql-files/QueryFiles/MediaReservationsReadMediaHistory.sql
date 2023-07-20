SELECT CONCAT(PatronFirstName, ' ', PatronLastName) AS "Patron Name", PatronID AS "Patron ID", ReservationIsRenewed AS "Number of Renewals", ReservationCheckoutDate AS "Checkout Date", get_due_date(get_media_type(MediaID), ReservationIsRenewed, ReservationCheckoutDate) AS "Due Date", ReservationReturnDate AS "Return Date", ReservationFee AS "Late Fee" 
  FROM MediaReservations 
       INNER JOIN Patrons
       USING (PatronID)
 WHERE MediaID = ?;
