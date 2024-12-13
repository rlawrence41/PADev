/*
	getItemDetails() captures item receipt details for calculating liabilities.

	### Explanation:

	1. **CTE `ItemDetails`**:
	   - Fetches basic details of items and their associated quantities and prices.
	   - Includes the `threshold` from the `titleLiabilitySp` table to determine if 
	   the item needs to be split.

	2. **Splitting Logic**:
	   - **`splitQty1`**: This is the portion of the quantity that is up to the 
	   threshold.
	   - **`splitQty2`**: This is the portion of the quantity that exceeds the 
	   threshold.
	   - **`splitAmount1`**: Amount corresponding to the quantity up to the 
	   threshold.
	   - **`splitAmount2`**: Amount corresponding to the quantity above the 
	   threshold.
	   - **`splitPrice1` and `splitPrice2`**: Prices for each split part.

	### Handling Results:

	- **Multiple Entries**: The query will output rows for each split quantity, 
	which can then be used to record the financial liability transactions 
	separately.
	- **Insertion into Tables**: If you need to insert these results into a 
	different table for further processing, you can use `INSERT INTO ... SELECT ...` based on this query.

	### Adjustments:

	- If your database has additional requirements or constraints, you may need to 
	adjust the query accordingly.
	- Consider the rounding and calculation of amounts based on your exact needs 
	and business logic.

	This approach helps you manage item receipts efficiently while handling cases 
	where quantities exceed defined thresholds. 

 */

DELIMITER //

CREATE OR REPLACE PROCEDURE getItemDetails(IN paramReceiptNo INTEGER)

  READS SQL DATA

  BEGIN
	DECLARE returnRate DECIMAL(6,2);

	-- Query 1: Fetch and split item receipts based on threshold
	WITH ItemDetails AS (
	  SELECT 
		r.id AS receiptNo,
		r.recptDate,
		ir.id AS itemRecptNo,
		ir.itemNo,
		ir.titleNo,
		ir.titleId,
		ir.applyQty,
		ir.amount,
		ROUND(ir.amount / ir.applyQty) AS applyPrice,
		oi.orderDate,
		oi.price AS retailPrice,  
		(oi.price - oi.deduction) AS effPrice, 
		(oi.price - ROUND(ir.amount / ir.applyQty)) AS deduction,
		getInventoryStatus(ir.titleNo, "R", 0, "", ir.itemNo, r.recptDate) AS numberSold,
		t.threshold,
		t.net,
		t.rate
	  FROM receipt r 
	  JOIN itemReceipt ir ON ir.receiptNo = r.id
	  JOIN orderItem oi ON oi.id = ir.itemNo
	  JOIN titleLiabilitySp t ON ir.titleNo = t.titleNo
	  WHERE r.id = paramReceiptNo
		AND (t.endDate IS NULL OR t.endDate >= NOW())
	)
	SELECT 
	  recptDate,
	  receiptNo,
	  itemRecptNo,
	  itemNo,
	  titleNo,
	  titleId,
	  -- Split quantities based on threshold
	  CASE 
		WHEN applyQty + numberSold <= threshold THEN applyQty
		WHEN numberSold > threshold THEN 0
		ELSE threshold - numberSold
	  END AS splitQty1,  -- Quantity up to the threshold
	  
	  CASE 
		WHEN applyQty + numberSold > threshold THEN applyQty - (threshold - numberSold)
		ELSE 0
	  END AS splitQty2,  -- Quantity above the threshold
	  
	  -- Calculate amounts for each split
	  CASE 
		WHEN applyQty + numberSold <= threshold THEN amount
		WHEN numberSold > threshold THEN 0
		ELSE ROUND((threshold - numberSold) * (amount / applyQty))
	  END AS splitAmount1,
	  
	  CASE 
		WHEN applyQty + numberSold > threshold THEN ROUND((applyQty - (threshold - numberSold)) * (amount / applyQty))
		ELSE 0
	  END AS splitAmount2,
	  
	  -- Effective and retail prices
	  applyPrice AS splitPrice1,
	  applyPrice AS splitPrice2,
	  threshold,
	  rate,
	  net
	FROM ItemDetails
	WHERE (threshold IS NULL OR threshold <= numberSold + applyQty)
;

  END;

//
DELIMITER ;