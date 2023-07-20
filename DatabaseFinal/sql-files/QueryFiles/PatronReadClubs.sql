SELECT ClubName AS "Club Name", ClubDescription AS "Club Description"
  FROM ClubMembers 
       INNER JOIN Clubs
       USING (ClubName)
 WHERE PatronID = ?;