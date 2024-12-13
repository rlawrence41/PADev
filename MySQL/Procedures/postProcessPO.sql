/* 

	This is the post-processing procedure for purchase orders.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postProcessPO
  (paramOrderKey INTEGER)			/* Id for the purchase order */
  COMMENT 'Executes the post-process for the submitted purchase order.'


  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN

	CALL postPOTotal(paramOrderKey);
	CALL receiveItems(paramOrderKey);

  END;

//
DELIMITER ;
