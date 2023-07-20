SELECT CONCAT(PatronFirstName, ' ', PatronLastName) AS "Patron Name", PatronID AS "Patron ID", PatronEmail AS "Email", PatronPhoneNumber AS "Phone Number" 
  FROM ClubMembers 
       INNER JOIN Patrons
       USING (PatronID)
 WHERE ClubName = ?;