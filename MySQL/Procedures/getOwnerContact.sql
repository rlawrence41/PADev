DELIMITER //

CREATE OR REPLACE PROCEDURE getOwnerContact
  (OUT paramOwnerContactNo integer)
  COMMENT 'Returns the current owner contact for the application.'

  MODIFIES SQL DATA 
  BEGIN

	SELECT lastKey INTO paramOwnerContactNo FROM keyField WHERE alias="owner";

	IF paramOwnerContactNo IS NULL THEN 

		SELECT id INTO paramOwnerContactNo FROM contact 
			WHERE UPPER(contactId) = "OWNER"; 
		INSERT INTO keyField (resource, keyField, alias, lastKey) 
		VALUES ("contact", "id","owner", paramOwnerContactNo) ;

	END IF;
	
  END;

//
DELIMITER ;
