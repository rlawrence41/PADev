DELIMITER //

/* Insures that a payment record will be available for the submitted order. */

CREATE OR REPLACE PROCEDURE newPaymentForOrder(
	IN paramOrderKey INTEGER,
	IN paramAccountNo INTEGER,
	IN paramAmount DECIMAL(10, 2)
)
  COMMENT 'Generates a new payment for the submitted purchase order.'

  MODIFIES SQL DATA 

`newPaymentForOrder`:
  BEGIN
  
/*	DECLARE transAmount DECIMAL(10,2);
	DECLARE previousTrans INTEGER;  */
	DECLARE previousAccount INTEGER;
	DECLARE orderBalance DECIMAL(10,2);
	
	/*	Bail, if no order is submitted. */
	if (paramOrderKey is NULL) then
		leave `newPaymentForOrder`;
	end if;


	/* Initialize variables to numbers. */
	set @transAmount = 0.00,
		orderBalance = 0.00,
		@previousTrans = 0,
		previousAccount = 0;
	if (paramAmount IS NULL) then 
		set paramAmount = 0.00;
	end if;

	/* Find the purchase order balance by summing ledger transactions for the order. */
	SELECT MAX(id), accountNo, sum(amount) 
	  INTO @previousTrans, previousAccount, orderBalance
	  FROM ledger
	  WHERE acctType = "L"
	    AND orderKey = paramOrderKey;
	  
	/* Choose the lesser of the order total submitted, or the remaining balance as
		the transaction amount. */
	select if(paramAmount > orderBalance, orderBalance, paramAmount) INTO @transAmount;
	
	/* If needed, insert a new payment record into the ledger. 
		The payment amount should be NEGAGIVE.  */
	if (@transAmount > 0) then
		INSERT INTO ledger (transDate, transEvent, transType, accountNo, acctType, orderKey, amount) 
			VALUES (NOW(), "PAYMENT", "P.O.", paramAccountNo, "L", paramOrderKey, -@transAmount);
	end if;
	
	SELECT @previousTrans, @transAmount;
	
  END;

//
DELIMITER ;

