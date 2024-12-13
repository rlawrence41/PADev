DELIMITER //

CREATE OR REPLACE PROCEDURE getItemLiabilities(IN paramReceiptNo INTEGER)

  READS SQL DATA

  BEGIN
	DECLARE returnRate DECIMAL(6,2);

	WITH ItemDetails AS (
	  SELECT MAX(t.id) AS specNo,
		t.accountNo,
		t.threshold,
		t.net,
		t.deduction AS dedThresh,
		t.rate,
		r.recptDate,
		ir.id,
		ir.receiptNo,
		ir.itemNo,
		ir.titleNo,
		ir.applyQty,
		ir.amount,
		oi.price,
		oi.deduction,
		getInventoryStatus(ir.titleNo, "R", 0, "", ir.itemNo, r.recptDate) AS numberSold
	  FROM receipt r
	  JOIN itemReceipt ir ON ir.receiptNo = r.id
	  JOIN orderItem oi ON oi.id = ir.itemNo
	  JOIN titleLiabilitySp t ON t.titleNo = ir.titleNo
	  WHERE (t.endDate IS NULL or t.endDate >= r.recptDate)
	    AND r.id = paramReceiptNo
	  GROUP BY t.id, t.accountNo, ir.id
	  HAVING threshold < numberSold
	)
	  SELECT 
		recptDate AS transDate,
		receiptNo,
		itemNo,
		specNo,
		threshold,
		dedThresh,
		rate,
		net,
		applyQty,
		numberSold,
		amount,

		-- Split quantities and amounts based on threshold
		CASE
		  WHEN threshold = 0 THEN applyQty
		  WHEN applyQty + numberSold <= threshold THEN applyQty
		  ELSE threshold - numberSold
		END AS splitQty1,  -- Quantity up to the threshold

		CASE 
		  WHEN threshold = 0 THEN amount
		  WHEN applyQty + numberSold <= threshold THEN amount
		  ELSE ROUND((threshold - numberSold) * (amount / applyQty), 2)
		END AS splitAmount1,

		CASE 
		  WHEN (threshold > 0) AND applyQty + numberSold > threshold THEN (applyQty + numberSold - threshold)
		  ELSE 0
		END AS splitQty2,  -- Quantity above the threshold

		CASE 
		  WHEN (threshold > 0) AND (applyQty + numberSold > threshold) THEN ROUND((applyQty + numberSold - threshold) * (amount / applyQty), 2)
		  ELSE 0.00
		END AS splitAmount2

	FROM ItemDetails;

  END;

//
DELIMITER ;
