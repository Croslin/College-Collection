SELECT CONCAT(PatronFirstName, ' ', PatronLastName) AS "Patron Name", PatronEmail AS "Email", PatronPhoneNumber AS "Phone Number", PatronBalance AS "Balance",
       PatronAddressStreet AS "Street Address", PatronAddressCity AS "City", PatronAddressState AS "State", PatronAddressZip AS "ZIP Code"
  FROM Patrons
 WHERE PatronID = ?;