/* 

	This procedure will show stored procedures associated with PubAssist.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE showStoredProcedures()
  COMMENT 'Displays this list of procedures.'


  READS SQL DATA                 /* Data access clause */
  BEGIN
  
	SELECT routine_name, routine_type, routine_comment 
		FROM information_schema.routines 
		WHERE routine_schema = "pubassistDemo"  
		ORDER BY routine_type, routine_name;

  END;

//
DELIMITER ;


/*

	Sample command to update the comment for a stored procedure...
	
	alter procedure unshipItems 
	  comment "Removes inventory transactions for the submitted order.";


 */