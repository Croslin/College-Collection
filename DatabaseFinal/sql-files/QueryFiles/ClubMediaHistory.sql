SELECT MediaTitle AS "Title", MediaID, get_media_type(MediaID) AS "Media Format", ClubMediaDateBegin AS "Reservation Start Date", ClubMediaDateEnd AS "Reservation End Date"
  FROM ClubMedia 
       INNER JOIN Media
       USING (MediaID)
 WHERE ClubName = ?;
