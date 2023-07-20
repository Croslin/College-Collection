--Gets the due date of a media reservation
--Returns a date

DROP FUNCTION IF EXISTS get_due_date;

DELIMITER //
CREATE FUNCTION get_due_date(media_type VARCHAR(5), num_of_reservations INT, checkout_date DATE)
RETURNS DATE
BEGIN
    IF media_type = "Book" THEN
        RETURN date_add(checkout_date, interval (3 *(num_of_reservations + 1)) week);
    ELSE
        RETURN date_add(checkout_date, interval (num_of_reservations + 1) week);
    END IF;
END
//

DELIMITER ;