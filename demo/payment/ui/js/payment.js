/*
	numberToWords() -- converts and amount to words suitable for printing on a check.
	
	Produced by ChatGPT on December 2, 2024.

    // Example usage
    const amount = 1234.56;
    console.log(numberToWords(amount)); // Output: "One Thousand Two Hundred Thirty-Four Dollars and Fifty-Six Cents"
 */


function numberToWords(amount) {
	const units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
	const teens = ["Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
	const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
	const scales = ["", "Thousand", "Million", "Billion"];

	function convertChunk(num) {
		let chunkText = "";
		if (num >= 100) {
			chunkText += units[Math.floor(num / 100)] + " Hundred ";
			num %= 100;
		}
		if (num >= 11 && num <= 19) {
			chunkText += teens[num - 11] + " ";
		} else if (num >= 10 || num > 0) {
			chunkText += tens[Math.floor(num / 10)] + " ";
			chunkText += units[num % 10] + " ";
		}
		return chunkText.trim();
	}

	if (amount === 0) return "Zero Dollars";

	let [dollars, cents] = amount.toFixed(2).split(".").map(Number);
	let words = "";
	let scaleIndex = 0;

	while (dollars > 0) {
		let chunk = dollars % 1000;
		if (chunk > 0) {
			words = convertChunk(chunk) + " " + scales[scaleIndex] + " " + words;
		}
		dollars = Math.floor(dollars / 1000);
		scaleIndex++;
	}

	words = words.trim() + " Dollars";

	if (cents > 0) {
		words += " and " + convertChunk(cents) + " Cents";
	}

	return words;
}



// Default the transType field when the account is selected.
updateTransType(accountNo){
	
	var transType="";
	transType = getLastPayment
}


// Update the the amount field whenever the payment amount is updated.
function updateAmount(payAmount){

	var elements = document.getElementsByName("column[amount]");
	var amount = -Number(payAmount).toFixed(2);
	elements[0].value = amount;
}

