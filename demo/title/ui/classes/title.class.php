<?php

/*
 *	title.class.php - These classes are specific to the title resource.
 */

// Define a page component.
class titlePage extends page {
	
	public function __construct() {
		
		parent::__construct("title", 
							"titlePage", 
							"Manage titles");
		
		// The context component is a convenient container for a list of properties
		// to share among multiple components.
		$context = new context(	"title", 
								"id");
		$this->context = $context;			// Rendered separately.

 		// Add the page navigation control.
		$pageNav = new pageNav("pageNav", 
								"pageNavId", 
								"Manage titles", 
								"Allows you to navigate to a page within the resource.");
		$pageNav->context = $context;
		$this->addChild($pageNav);

		$table = new titleTable();
		$table->context = $context;
		$this->addChild($table);

		$form = new titleForm("titleForm", "adminForm", "title");
		$this->addChild($form); 

	}
}


// Define a table component.
class titleTable extends table {
	
	public function __construct() {
		
		// Parameters are:  resource, keyFieldName, title (a label).
		parent::__construct("title", "id", "titles");

		// It is generally not advisable to include the primary key field in the table.
#		$this->addColumn("id","title Number");	// No need to show in the table.

		// Syntax: $obj->addColumn(<columnName>:string, [<column header>]:string, [<description>]:string);
		
		/* Sample columns for a table...		
		$this->addColumn("title_id","title Id", "A short, memorable name for the account");
		$this->addColumn("company","Company", "Company or organization name");
		$this->addColumn("last_name", "Last Name");
		$this->addColumn("first_name", "First Name");
		$this->addColumn("zip_code", "Zip Code", "Postal Code");
		*/
		
		// There will generally be too many columns to reasonably display in the table.
		// Keep only the columns that will be useful to distinguish an instance.
		// Add table columns specific to this resource.
#		$this->addColumn("id","Id");
		$this->addColumn("titleId","Title Id");
		$this->addColumn("title","Title");
#		$this->addColumn("titleNoPre","TitleNoPre");
#		$this->addColumn("subtitle","Subtitle");
#		$this->addColumn("isbn","Isbn");
		$this->addColumn("isbn13","Isbn13");
#		$this->addColumn("ean13","Ean13");
#		$this->addColumn("upc","Upc");
#		$this->addColumn("lccn","Lccn");
#		$this->addColumn("classId","ClassId");
#		$this->addColumn("supplierNo","Supplier No");
		$this->addColumn("supplierNoSearch","Supplier");
#		$this->addColumn("imprintNo","ImprintNo");
#		$this->addColumn("vendorNo","VendorNo");
#		$this->addColumn("vendorRole","VendorRole");
#		$this->addColumn("lSubscript","Subscription?");
		$this->addColumn("lInventory","Inventory?");
#		$this->addColumn("lConsignment","Consignment?");
#		$this->addColumn("lTaxable","Taxable?");
		$this->addColumn("retPrice","Ret. Price");
#		$this->addColumn("cartonCount","CartonCount");
#		$this->addColumn("cartonWgt","CartonWgt");
#		$this->addColumn("salesCount","SalesCount");
#		$this->addColumn("bookLbs","BookLbs");
#		$this->addColumn("bookOz","BookOz");
#		$this->addColumn("weight","Weight");
#		$this->addColumn("weightUnit","WeightUnit");
#		$this->addColumn("pageCount","PageCount");
#		$this->addColumn("height","Height");
#		$this->addColumn("width","Width");
#		$this->addColumn("thickness","Thickness");
#		$this->addColumn("linearUnit","LinearUnit");
#		$this->addColumn("lBarCode","BarCode?");
#		$this->addColumn("barcodetyp","Barcodetyp");
#		$this->addColumn("shelfId","ShelfId");
#		$this->addColumn("saftyStock","SaftyStock");
#		$this->addColumn("reordrQty","ReordrQty");
#		$this->addColumn("leadTime","LeadTime");
#		$this->addColumn("lOutOfPrint","LOutOfPrint");
		$this->addColumn("copyRtYear","CopyRt Year");
		$this->addColumn("edition","Edition");
#		$this->addColumn("editionNo","EditionNo");
#		$this->addColumn("pubStatus","PubStatus");
		$this->addColumn("pubDate","Pub Date");
#		$this->addColumn("discCode","DiscCode");
		$this->addColumn("genre","Genre");
#		$this->addColumn("audience","Audience");
#		$this->addColumn("audRangTyp","AudRangTyp");
#		$this->addColumn("audRange1","AudRange1");
#		$this->addColumn("audRange2","AudRange2");
#		$this->addColumn("language","Language");
		$this->addColumn("formCode","Form Code");
#		$this->addColumn("formDetail","FormDetail");
#		$this->addColumn("eFormURL","EFormURL");
#		$this->addColumn("available","Available");
#		$this->addColumn("availDate","AvailDate");
#		$this->addColumn("returnCode","ReturnCode");
#		$this->addColumn("collection","Collection");
#		$this->addColumn("parentId","ParentId");
#		$this->addColumn("nameInSet","NameInSet");
#		$this->addColumn("volumeNo","VolumeNo");
#		$this->addColumn("promoText","PromoText");
#		$this->addColumn("boComment","BoComment");
#		$this->addColumn("TOC","TOC");
#		$this->addColumn("sampleText","SampleText");
#		$this->addColumn("imageFile","ImageFile");
#		$this->addColumn("imageFile2","ImageFile2");
#		$this->addColumn("reviews","Reviews");
#		$this->addColumn("comment","Comment");
#		$this->addColumn("lApproved","Approved?");
#		$this->addColumn("updatedBy","Updated By");
#		$this->addColumn("userNo","User No.");
#		$this->addColumn("lastUpdated","Last Updated");


	}
}


// Define a form component.
class titleForm extends form {

	public function __construct(...$args) {
		
		parent::__construct(...$args);

		/* Form fields can be set to the following field types:
		// checkbox, date, email, hidden, message, password, radio, readonly, text, textarea
		
		// Sample Field Group...
		// Identifiers
		$fieldGroup = $this->addFieldGroup("Identifiers", true);
		$fieldGroup->addField("hidden", "id", 	"title Id", "");
		$fieldGroup->addField("text", 	"company", 		"Company", "Company or organization name");
		$fieldGroup->addField("text", 	"first_name", 	"First Name", "first name");
		$fieldGroup->addField("text", 	"last_name", 	"Last Name", "last name");

		// Sample Standard fields... 
		$this->addField("textarea", 	"po_addr", 		"Street", "Postal Service Street Address ");
		$this->addField("text", 		"city", 		"City", "City or town");
		$this->addField("text", 		"state_abbr", 	"State", "State Abbreviation");
		$this->addField("text", 		"zip_code", 	"Zip Code", "Postal Code");
		*/
		
		// Fields unique to this resource...
		$this->addField("readonly", "id", "Id");
		$this->addField("text", "titleId", "TitleId");
		$this->addField("textarea", "title", "Title");
		$this->addField("textarea", "titleNoPre", "TitleNoPre");
		$this->addField("textarea", "subtitle", "Subtitle");
		$this->addField("text", "isbn", "Isbn");
		$this->addField("text", "isbn13", "Isbn13");
		$this->addField("text", "ean13", "Ean13");
		$this->addField("text", "upc", "Upc");
		$this->addField("text", "lccn", "Lib. of Congress #");
		$this->addField("text", "classId", "ClassId");
		$this->addField("hidden", "supplierNo", "Supplier No");

		// Allow a search for a supplier.
		$searchField = new searchField("supplierNo", 	// Foreign key field
					"Select a Supplier", 				// label
					"Enter supplier's ID, company, or last name.",	// description
					"contact");							// target resource
		// Add a change event to the supplierNo input element. 
		// SupplierNo is the foreign key element embedded in the searchField.
#		$searchField->foreignKey->action = "onchange='getSuppler(this.value)'";
		$this->addChild($searchField);

		$this->addField("hidden", "imprintNo", "Imprint No");

		// Allow a search for a imprint.
		$searchField = new searchField("imprintNo", 	// Foreign key field
					"Select an Imprint", 				// label
					"Enter imprint's ID, company, or last name.",	// description
					"contact");							// target resource
		// Add a change event to the imprintNo input element. 
		// imprintNo is the foreign key element embedded in the searchField.
#		$searchField->foreignKey->action = "onchange='getSuppler(this.value)'";
		$this->addChild($searchField);


		$this->addField("hidden", "vendorNo", "VendorNo");

		// Allow a search for a vendor.
		$searchField = new searchField("vendorNo", 	// Foreign key field
					"Select a vendor", 				// label
					"Enter vendor's ID, company, or last name.",	// description
					"contact");							// target resource
		// Add a change event to the vendorNo input element. 
		// vendorNo is the foreign key element embedded in the searchField.
#		$searchField->foreignKey->action = "onchange='getSuppler(this.value)'";
		$this->addChild($searchField);


		$this->addField("text", "vendorRole", "Vendor Role");
		$this->addField("checkbox", "lSubscript", "Subscription?");
		$this->addField("checkbox", "lInventory", "Inventory?");
		$this->addField("checkbox", "lConsignment", "Consignment?");
		$this->addField("checkbox", "lTaxable", "Taxable?");
		$this->addField("text", "retPrice", "Retail Price");
		$this->addField("text", "cartonCount", "Carton Count");
		$this->addField("text", "cartonWgt", "Carton Wgt");
		$this->addField("text", "salesCount", "Sales Count");
		$this->addField("text", "bookLbs", "Book Lbs.");
		$this->addField("text", "bookOz", "Book Oz.");
		$this->addField("text", "weight", "Weight");
		$this->addField("text", "weightUnit", "Weight Unit");
		$this->addField("text", "pageCount", "Page Count");
		$this->addField("text", "height", "Height");
		$this->addField("text", "width", "Width");
		$this->addField("text", "thickness", "Thickness");
		$this->addField("text", "linearUnit", "Linear Unit");
		$this->addField("checkbox", "lBarCode", "Bar Code?");
		$this->addField("text", "barcodetyp", "Bar Code Type");
		$this->addField("text", "shelfId", "Shelf Id");
		$this->addField("text", "saftyStock", "Safty Stock");
		$this->addField("text", "reordrQty", "Reordr Qty");
		$this->addField("text", "leadTime", "Lead Time");
		$this->addField("checkbox", "lOutOfPrint", "Out Of Print?");
		$this->addField("text", "copyRtYear", "CopyRt Year");
		$this->addField("text", "edition", "Edition");
		$this->addField("text", "editionNo", "Edition No");
		$this->addField("text", "pubStatus", "Pub Status");
		$this->addField("text", "pubDate", "Pub Date");
		$this->addField("text", "discCode", "Disc Code");
		$this->addField("text", "genre", "Genre");
		$this->addField("text", "audience", "Audience");
		$this->addField("text", "audRangTyp", "Aud Rang Typ");
		$this->addField("text", "audRange1", "Aud Range 1");
		$this->addField("text", "audRange2", "Aud Range 2");
		$this->addField("text", "language", "Language");
		$this->addField("text", "formCode", "Form Code");
		$this->addField("text", "formDetail", "Form Detail");
		$this->addField("text", "eFormURL", "EForm URL");
		$this->addField("text", "available", "Available");
		$this->addField("text", "availDate", "Avail Date");
		$this->addField("text", "returnCode", "Return Code");
		$this->addField("text", "collection", "Collection");
		$this->addField("text", "parentId", "Parent Id");
		$this->addField("text", "nameInSet", "Name In Set");
		$this->addField("text", "volumeNo", "Volume No");
		$this->addField("textarea", "promoText", "Promo Text");
		$this->addField("textarea", "boComment", "Backorder Comment");
		$this->addField("textarea", "TOC", "Table of Contents");
		$this->addField("textarea", "sampleText", "Sample Text");
		$this->addField("text", "imageFile", "Image File");
		$this->addField("text", "imageFile2", "Image File 2");
		$this->addField("textarea", "reviews", "Reviews");
		$this->addField("textarea", "comment", "Comment");
		$this->addField("checkbox", "lApproved", "Approved?");
		$this->addField("readonly", "updatedBy", "Updated By");
		$this->addField("readonly", "userNo", "User No.");
		$this->addField("date", "lastUpdated", "Last Updated");


	}

}


