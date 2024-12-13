<?php

// The table top is a banner (<div>) that spans the top of the table.
// It presents information about the table, like the current filter and sort order.
// It seems like it should belong to the table, but it must be rendered after the 
// pageNav control) to keep from being duplicated when the table is refreshed.

class tableTop extends webObj{

	public $name = "tableTop";
	public $template = "tableTop2.html";

	public function __construct(...$args){

		parent::__construct(...$args);

		// Add the table top controls.
		$column = new tableTopColumn("filterControlsCol", "col1");
			$tableControl = new tableTopButton("addButton", "addButton", "", "Add a new instance.");
				$tableControl->enabledImage = "/images/Add_50.png";
				$tableControl->enabledAction = 'onclick="newInstance()"';
				$column->addChild($tableControl);
			$tableControl = new tableTopButton("filterButton", "filterButton", "", "Enter values to filter the results.");
				$tableControl->enabledImage = "/images/filter_button_50.png";
				$tableControl->enabledAction = 'onclick="newFilter()"';
				$column->addChild($tableControl);
			$tableControl = new tableTopButton("filterClearButton", "filterClearButton", "", "Clear the filter condition.");
				$tableControl->enabledImage = "/images/filter_clear_50.png";
				$tableControl->enabledAction = 'onclick="clearFilter()"';
				$column->addChild($tableControl);
		$this->addChild($column);

		$column = new tableTopColumn("filterDisplayCol", "col2");
			$tableControl = new tableTopDisplay("filterDisplay", "filterStr", "No Filter", "Current Filter Condition");
				$column->addChild($tableControl);
		$this->addChild($column);

		$column = new tableTopColumn("reportControlsCol", "col3");
			$tableControl = new tableTopButton("downloadButton", "downloadButton", "", "Download results to a CSV file.");
				$tableControl->enabledImage = "/images/DownloadCSV_50.png";
				$tableControl->enabledAction = 'onclick="downloadCSV()"';
				$column->addChild($tableControl);
			$tableControl = new tableTopButton("reportButton", "reportButton", "", "Generate a report.");
				$tableControl->enabledImage = "/images/Report-50.png";
				$tableControl->enabledAction = 'onclick="report()"';
				$tableControl->disabledImage = "/images/Report-50_disabled.png";
				$column->addChild($tableControl);
		$this->addChild($column);

		$column = new tableTopColumn("sortDisplayCol", "col4");
			$tableControl = new tableTopDisplay("sortDisplay", "sortBy", "No sort order", "Current Sort Order");
				$column->addChild($tableControl);
		$this->addChild($column);

		$column = new tableTopColumn("sortControlsCol", "col5");
			$tableControl = new tableTopButton("sortClearButton", "sortClearButton", "", "Clear the Sort Order.");
				$tableControl->enabledImage = "/images/sort_clear50.png";
				$tableControl->enabledAction = 'onclick="clearSortBy()"';
				$column->addChild($tableControl);
		$this->addChild($column);

	}
}


// Group controls together into columns for display.
class tableTopColumn extends webObj{
	
	public $template = "tableTopColumn.html";

}


class tableTopButton extends webObj{

	public $template = "tableTopButton.html";
	public $enabled = true;
	public $image = "";
	public $enabledImage = "";
	public $disabledImage = "";
	public $enabledAction = "";

	public function render($record=null){

		// Set button properties based on whether it is enabled.
		if ($this->enabled) {
			$this->action = $this->enabledAction;
			$this->image = $this->enabledImage;
		}
		else {
			$this->action = "";
			$this->image = $this->disabledImage;
		}

		$html = parent::render();
		$html = str_replace("{image}", $this->image, $html);
		return $html;	
	}

}

class tableTopDisplay extends webObj{

	public $template = "tableTopDisplay.html";
	
}

?>
