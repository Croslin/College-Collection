SELECT ClubName AS "Club Name", ClubDescription AS "Club Description", ClubMediaDateBegin AS "Reservation Start Date", ClubMediaDateEnd AS "Reservation End Date"
  FROM ClubMedia 
       INNER JOIN Clubs
       USING (ClubName)
 WHERE MediaID = ?;
