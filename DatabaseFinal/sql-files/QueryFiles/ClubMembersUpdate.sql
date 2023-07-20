--update for ClubMembers
UPDATE ClubMembers
   SET PatronID = ?,
       ClubName = ?
 WHERE ClubName = ? AND PatronID = ?;

--Note from AD: Not sure when it's ever useful to update a linking table instead of adding/removing records
