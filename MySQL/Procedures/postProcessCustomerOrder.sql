/* 

	This is the post-processing procedure for customer orders.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postProcessCustomerOrder
  (paramOrderKey INTEGER)			/* Id for the customer order */
  COMMENT 'Executes the post-process for the submitted customer order.'

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN

	CALL postOrderTotal(paramOrderKey);
	CALL shipItems(paramOrderKey);

  END;

//
DELIMITER ;
