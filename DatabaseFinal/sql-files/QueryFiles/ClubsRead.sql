SELECT ClubName AS "Club Name", ClubDescription AS "Description", COUNT(PatronID) AS "Number of Members"
  FROM Clubs
       LEFT OUTER JOIN ClubMembers
       USING (ClubName)
 GROUP BY ClubName;
