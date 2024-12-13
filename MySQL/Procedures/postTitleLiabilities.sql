/*

	postTitleLiabilities() - This procedure accepts the details for an item receipt
			and generates the appropriate liability transactions for the ledger.
			The liability transactions are based on the liability contract 
			specifications for the title found in the table, titleLiabilitySp.
			There may be several specifications that apply, spanning multiple 
			accounts, and (liability) transaction types. (For example: royalties, 
			consignment costs, sales tax, etc. may all be liabilities associated
			with the sale of a title.)
			
			Liability specifications may be defined for a title and payee or 
			liability account.  The spec' may define a liability based on a 
			starting date, the number previously sold, the discount of the sale, 
			and whether the royalty is due when the item is shipped.  
			
			Liabilities may be posted for either customer sales or returns.  So, 
			item quantities may be positive or negative.  Care has been taken 
			here to split item quantities as they cross a number sold threshold.  
			Either a sale or a return may cross the threshold--causing the item
			quantity to be split into two parts and therefore two liability 
			transactions.

			Title Liability Specification will apply a rate to the sales amount
			for an item.  The spec' will indicate whether that rate should be 
			applied to the net or retail price of the title being sold.

			The default is for liabilities to become due when an item is PAID for.
			An item receipt is the indication that an item has been paid for. (i.e.
			a customer receipt has been applied to the item.)  
			
			This procedure specifically processes item receipts.


	Sample call:
	
	CALL postTitleLiabilities(12, 11, 19, 3, "2022-03-09 00:00:00", 200, 8.98, 19.95, 3, 45.01, 1);

 */


DELIMITER //

CREATE OR REPLACE PROCEDURE postTitleLiabilities
  (	
	IN parReceiptNo INTEGER,
	IN parOrderKey INTEGER,
	IN parItemNo INTEGER,
	IN parTitleNo INTEGER,
	IN parTitleID VARCHAR(20),
	IN parTransDate DATETIME,
	IN parItemQty DECIMAL(10,2),
	IN parItemAmount DECIMAL(10,2),
	IN parItemPrice DECIMAL(10,2),
	IN parRetailPrice DECIMAL(10,2),
	IN parNumberSold	DECIMAL(10,2),
	IN parDiscount DECIMAL(6,2),
	IN parUserNo INTEGER
  )
  MODIFIES SQL DATA

  BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE colAccountNo INTEGER;
	DECLARE colTransType VARCHAR(20);
	DECLARE colThreshold DECIMAL(10,2);
	DECLARE colDiscount DECIMAL(6,2);
	DECLARE colRate DECIMAL(6,2); 
	DECLARE colNet BOOLEAN;
	DECLARE colSpecNo INTEGER;
	DECLARE lastAccount INTEGER;
	DECLARE itemQty1 DECIMAL(10,2);
	DECLARE itemQty2 DECIMAL(10,2);
	DECLARE spanQty DECIMAL(10,2) DEFAULT If(parItemQty > 0, parNumberSold + parItemQty, parNumberSold);
	DECLARE liabPrice DECIMAL(10,2);
	DECLARE liabAmount DECIMAL(10,2);
	DECLARE itemComment VARCHAR(100);
	DECLARE userName VARCHAR(20);
	DECLARE lastUpdated DATETIME DEFAULT NOW();

	DECLARE cur1 CURSOR FOR 
	SELECT id, accountNo, transType, threshold, discount, rate, net 
	FROM titleLiabilitySp
	WHERE titleNo = parTitleNo
	  AND (startDate IS NULL OR startDate <= parTransDate)
	  AND threshold <= spanQty
	  AND discount <= parDiscount
	  AND !whenShipped
	ORDER BY accountNo, threshold DESC, discount DESC, id DESC ;

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	IF (parTransDate IS NULL) THEN
		SET parTransDate = CURRENT_TIMESTAMP;			/*	Now()!  */
	END IF;

	IF (parNumberSold IS NULL) THEN
		SET parNumberSold = 0;							/* Don't use */
	END IF;

	IF (parDiscount IS NULL) THEN
		SET parDiscount = 0.00;						/* Don't use */
	END IF;

	/* Capture the user name.	*/
	SELECT user_id INTO userName FROM user WHERE id = parUserNo;

	/* Remove prior liability transctions from the ledger.	*/
	DELETE FROM ledger 
	WHERE transevent = "RECEIPT" 
	  AND receiptNo = parReceiptNo
	  AND acctType = "L" 
	  AND itemNo = parItemNo;

	/* SCAN the liability specifications */
	SET lastAccount = 0;
	
	OPEN cur1;
	read_loop: LOOP
    FETCH cur1 INTO colSpecNo, colAccountNo, colTransType, colThreshold, colDiscount, colRate, colNet;

    IF done THEN
      LEAVE read_loop;
    END IF;

	IF (colAccountNo != lastAccount) THEN

		/* The liability amount is based on whether applied to the net or 
			retail price. */
		SET liabPrice = IF(colNet, parItemPrice, parRetailPrice);
		SET itemQty1 = parItemQty;
		SET itemQty2 = 0;

		/* Split the quantity if it crosses the number sold threshold. 
		   Note that the quantity MIGHT be negative!  					*/		   

		IF (colThreshold > 0) THEN
			IF (ABS(colThreshold - parNumberSold) < ABS(parItemQty)) THEN
				/* This part of the quantity is above the threshold. */
				SET itemQty1 = ROUND(parNumberSold + parItemQty - colThreshold);
				/* This part is below the threshold and needs to wait for the 
					next liability specification. */
				SET itemQty2 = ROUND(colThreshold - parNumberSold);
			END IF;
		END IF;

		/* Calculate the liability amount for the first part of the quantity. */

		/* NOTE: I need to apply the rate!  But I want to look at the base first. */
		SET liabAmount = ROUND(itemQty1 * liabPrice * colRate/100, 2);
		IF (liabAmount != 0) THEN
		
			SET itemComment = CONCAT("Receipt of ", 
										parItemAmount, 
										" for ", 
										itemQty1, 
										" copies of title, ",
										parTitleId,
										", at rate of ",
										colRate,
										"%.");
		
			INSERT INTO ledger (	  
				transDate, 
				transEvent, 
				transType, 
				accountNo, 
				acctType, 
				amount, 
				itemNo, 
				receiptNo, 
				orderKey,
				specNo,
				comment,
				updatedBy, 
				userNo,
				lastUpdated)
			  VALUES (
				parTransDate,
				"RECEIPT",
				colTransType,
				colAccountNo,
				"L",
				liabAmount,
				parItemNo,
				parReceiptNo,
				parOrderKey,
				colSpecNo,
				itemComment,
				userName,
				parUserNo,
				lastUpdated
			  );
		END IF;
		

	END IF;

	/* If there is a split quantity, loop to the next specification, but retain 
		the remaining quantity and don't change the last account number. */
	IF (itemQty2 != 0) THEN
		SET parItemQty = itemQty2;
	ELSE
		SET lastAccount = colAccountNo;
	END IF;

  END LOOP;

  CLOSE cur1;


END; //

DELIMITER ;

