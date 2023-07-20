SELECT MediaTitle AS "Title", MediaID AS "Media ID", get_media_type(MediaID) AS "Media Format", ReservationIsRenewed AS "Number of Renewals", ReservationCheckoutDate AS "Checkout Date", get_due_date(get_media_type(MediaID), ReservationIsRenewed, ReservationCheckoutDate) AS "Due Date",ReservationReturnDate AS "Return Date", ReservationFee AS "Late Fee" 
  FROM MediaReservations 
       INNER JOIN Media
       USING (MediaID)
 WHERE PatronID = ?;
