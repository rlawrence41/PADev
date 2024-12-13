/*
	getLiabilitySpec() returns a collection of liability specifications that
			apply to the submitted item receipt.
 */
DELIMITER //

CREATE OR REPLACE PROCEDURE getLiabilitySpecs
  (	IN paramTitleNo INTEGER,
	IN paramTransDate DATETIME,
	IN paramNumSold	DECIMAL(10,2),
	IN paramDiscount DECIMAL(6,2)
  )
  COMMENT "Returns liability spec's that are pertinent to the submitted item."

  READS SQL DATA

  BEGIN
	DECLARE specNo INTEGER;

	IF (paramTransDate IS NULL) THEN
		SET paramTransDate = CURRENT_TIMESTAMP;			/*	Now()!  */
	END IF;
	
	IF (paramNumSold IS NULL) THEN
		SET paramNumSold = 0;							/* Don't use */
	END IF;

	IF (paramDiscount IS NULL) THEN
		SET paramDiscount = 0.00;
	END IF;


	/*
		Finding the specifications (plural) that apply can be done by... 
			1. selecting spec’s for a title, 
			2. filtered by the current date, 
			3. filtered by the quantity previously sold for the title, and 
			4. the item discount (deduction) and 
			5. sorted by account (there may be multiple) and 
			6. obtaining the minimum sequence number.
		This should yield a list of specifications that apply.
	 */

	SELECT *
	FROM titleLiabilitySp tls
	WHERE tls.titleNo = paramTitleNo
	  AND (tls.startDate IS NULL OR tls.startDate <= paramTransDate)
	  AND tls.threshold <= paramNumSold
	  AND tls.discount <= paramDiscount
	  AND !whenShipped ;

  END;

//
DELIMITER ;