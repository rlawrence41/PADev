DELIMITER //

/* Insures that a receipt record will be available for the submitted order. */

CREATE OR REPLACE PROCEDURE newReceiptForOrder(
	IN paramOrderKey INTEGER,
	IN paramCustomerNo INTEGER,
	IN paramAmount DECIMAL(10, 2)
)
  COMMENT 'Generates a new receipt for the submitted customer order.'

  MODIFIES SQL DATA 

`newReceiptForOrder`:
  BEGIN
  
	DECLARE previousAmt DECIMAL(10,2);
	DECLARE previousReceipt INTEGER;
	DECLARE previousCustomer INTEGER;
	
	/* This routine must work for brand new receipts as well.  
		If no order is submitted, just post a new receipt. */
	if (paramOrderKey is NULL) then
		call newReceipt();
		leave `newReceiptForOrder`;
	end if;


	/* Initialize variables to numbers. */
	set previousAmt = 0.00,
		previousReceipt = 0,
		previousCustomer = 0;
	if (paramAmount IS NULL) then 
		set paramAmount = 0.00;
	end if;

	/* Find an existing receipt if one is available. */
	SELECT MAX(receiptNo), customerNo, sum(orderPaid) INTO previousReceipt, previousCustomer, previousAmt
	  FROM orderReceipt
	  WHERE orderKey = paramOrderKey;
	  
	/* If a previous amount was returned, deduct it from the amount for the receipt. */
	if (previousAmt > 0) then
		set paramAmount = paramAmount - previousAmt;
	end if;
	
	/* If needed, insert a new receipt record. */
	if (previousReceipt is NULL and paramAmount > 0) then
		INSERT INTO receipt (recptDate, customerNo, orderKey, amount) 
			VALUES (NOW(), paramCustomerNo, paramOrderKey, paramAmount);
	end if;
	
  END;

//
DELIMITER ;

