/* 

	This is the post-processing procedure for customer returns.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postProcessCustomerReturn
  (paramOrderKey INTEGER)			/* Id for the customer return */
  COMMENT 'Executes the post-process for the submitted customer return.'


  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN

	CALL postReturnTotal(paramOrderKey);
	CALL returnItems(paramOrderKey);

  END;

//
DELIMITER ;
