<?php

class menu extends webObj{
	public $name = "menu";
	public $template = "menu.html";
}

class menuItem extends webObj{
	public $template = "menuItem.html";
}

// These are items in the main menu, but have drop down items of their own.
class menuDropDown extends webObj{
	public $template = "menuDropDown.html";	
}

// These are drop down items for top-level menu items.
class menuDropDownItem extends webObj{
	public $template = "menuDropDownItem.html";	
}

// These are disabled drop down items.
class menuDropDownItemDisabled extends webObj{
	public $template = "menuDropDownItemDisabled.html";	
}
