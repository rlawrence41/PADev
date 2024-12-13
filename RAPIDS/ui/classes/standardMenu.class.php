<?php

class standardMenu extends menu{

	public function __construct(...$args) {// (Name, Id, Title, Description, Action)
		parent::__construct(...$args);

		$menuItem = new menuDropDown("addNav", "addNav", "Add New", "Add new instances");
			$subMenuItem = new menuDropDownItem("addInstance", "addInstance", "Add New Instance", "Add a new instance (one at a time).", 'onclick="newInstance()"');
			$menuItem->addChild($subMenuItem);
			$subMenuItem = new menuDropDownItemDisabled("addBatch", "addBatch", "Add New Batch", "Add a new batch of instances.");
			$menuItem->addChild($subMenuItem);
		$this->addChild($menuItem);
		
		$menuItem = new menuDropDown("filterNav", "filterNav", "Filter", "Set a filter the table.");
			$subMenuItem = new menuDropDownItem("filter", "filter", "Set Filter", "Set a filter on the table.", 'onclick="newFilter()"');
			$menuItem->addChild($subMenuItem);
			$subMenuItem = new menuDropDownItem("filterClear", "filterClear", "Clear Filter", "Clear the current filter for the table.", 'onclick="clearFilter()"');
			$menuItem->addChild($subMenuItem);
		$this->addChild($menuItem);
		
		$menuItem = new menuDropDown("sortByNav", "sortByNav", "Sort Order", "Click table column headers to set the sort order.");
			$subMenuItem = new menuDropDownItem("sortByClear", "sortByClear", "Clear Sort Order", "Clear the current sort order for the table.", 'onclick="clearSortBy()"');
			$menuItem->addChild($subMenuItem);
		$this->addChild($menuItem);		

		$menuItem = new profileDropDown("loginNav", "loginNav", "My Account", "Manage your account.");
			$subMenuItem = new menuDropDownItem("login", "login", "Sign In...", "Sign in to this application.", "onclick=\"branchToLogin('loginForm')\"");
			$menuItem->addChild($subMenuItem);
			$subMenuItem = new menuDropDownItem("profile", "profile", "Profile", "Manage your account profile.");
			$menuItem->addChild($subMenuItem);
			$subMenuItem = new menuDropDownItem("logout", "logout", "Sign Out", "Sign out of this session.", "onclick=\"logout()\"");
			$menuItem->addChild($subMenuItem);
		$this->addChild($menuItem);
	}
}


class profileDropDown extends menuDropDown {
	
	public function __construct(...$args){
		
		parent::__construct(...$args);
	
		// The title of this menu option should already be set, but...
		// reset the title to the user's name if they are logged in.
		if (!empty($_SESSION['auth'])) {
			$this->title = friendlyName();
		}

	}
}
