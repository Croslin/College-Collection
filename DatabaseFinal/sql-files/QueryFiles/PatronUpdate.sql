UPDATE Patrons
   SET PatronEmail = ?,
       PatronPhoneNumber = ?,
       PatronBalance = ?,
       PatronAddressStreet = ?,
       PatronAddressCity = ?,
       PatronAddressState = ?,
       PatronAddressZip = ?
 WHERE PatronID = ?;
