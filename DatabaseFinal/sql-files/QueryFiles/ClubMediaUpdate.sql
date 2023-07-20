--update for ClubMedia
UPDATE ClubMedia
   SET ClubName = ?,
       MediaID = ?,
       ClubMediaDateBegin = ?,
       ClubMediaDateEnd = ?
 WHERE ClubName = ? AND MediaID = ?;
