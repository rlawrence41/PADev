/* 

	This is the post-processing procedure for a customer receipt.
	
 */

DELIMITER //

CREATE OR REPLACE PROCEDURE postProcessReceipt
  (paramReceiptNo INTEGER)			/* Id for the receipt */
  COMMENT 'Executes the post-process for the submitted receipt.'

  MODIFIES SQL DATA                 /* Data access clause */
  BEGIN

	CALL postReceipt(paramReceiptNo);
	CALL receiveItems(paramReceiptNo);

  END;

//
DELIMITER ;
