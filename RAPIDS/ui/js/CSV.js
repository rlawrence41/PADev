/*
 *	CSV.js -- These javascript functions are used to read and process CSV data.
 *
 */

//csvObj = new CSV(headerStr, csvStr);  // Create an object of CSV class.

class CSV {  // Create a class

	constructor(headerStr, csvStr="") {  
		this.headerStr = headerStr;  	// Holds the header record for the CSV string.
		this.csvStr = csvStr;			// Holds the CSV (comma separated values) string.

		// These properties are used to accumulate output.
		this.echoStr = "";
		this.outStr = "";
		this.jsonStr = "";
		
	}


	addQuotes(string) {
		
		if (string === "undefined"){ return ""; }
		let rStr = string ;
		if (!string.startsWith('"')){rStr =  '"' + string + '"';}
		return rStr ;
	}


	CSVtoJSON(header, csvStr) {

		// Separate values by commas.
		var headerArr = this.headerStr.split(",");
		var csvArray = this.csvStr.split(",");

		// Feedback each entry in the array.
//		csvArray.forEach(this.echoList);		// forEach() doesn't work in this context!!!
		this.echoList(csvArray);

		// Look through the csvArray for beginning and ending quotes.
//		csvArray.forEach(this.gatherQuotes);
		this.gatherQuotes(csvArray);

		// And now create a JSON record for the CSV array.
		this.jsonFromList(headerArr, csvArray);

	}


	echoList(csvArray) {
		
		let csvLen = csvArray.length;
		for (let i = 0; i < csvLen; i++){
			this.echoStr += csvArray[i].trim() + "<br/>"; 
		}
	}

	gatherQuotes(csvArray) {

		const delimiter = ", ";
		var value, next;

		for (let i = 0; i < csvArray.length; i++){
			value = csvArray[i].trim();
			next = i + 1;
		
			// Search ahead to combine csvArray elements between quotation marks.
			if (value.startsWith('"') && !value.endsWith('"')) {
				
				// Gather subsequent array values until the end quote is found.
				do {
					csvArray[i] += delimiter + csvArray[next];
					csvArray.splice(next, 1);
					// NOTE: Because of the splice, DO NOT increment NEXT!
				}
				while (!(csvArray[i].endsWith('"') || next == csvArray.length))	// ensures execution until the closing quote is included in csvArray[i].
			}

			this.outStr += i.toString() + ", " + value.startsWith('"') +
				delimiter + csvArray[i] + delimiter + next.toString() + "<br/>";
		}
	}


	jsonFromList(headerArr,csvArray){

		var i, cols, jsonStr;
		const delimiter = ", ";
		cols = headerArr.length ;
		this.jsonStr = "{";
		for (i=0; i < cols; i++) {
			this.jsonStr += this.addQuotes(headerArr[i]) + " : " + 
							this.addQuotes(csvArray[i]);
			if (i < cols-1) {this.jsonStr += delimiter ;}
		}
		this.jsonStr += "}" ;
	}

}
