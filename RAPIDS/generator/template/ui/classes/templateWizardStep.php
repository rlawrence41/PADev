

		$step = $this->addStep(
			"<<txView>>", 				// The view used to manage the transaction.
			"id",				 		// usually the resource id field.
			"<<parentKeyField>>",		// foreign key in the parent record.
			"<<stepName>>",				// The id for the step, will appear in the wizard menu.
			"<<txDescription>>"			// Will appear when mouse is hovered over menu.
			)

			$step->launchAction = "<<launchAction>>";
			$step->addAction = "<<addAction>>";
			$step->exitAction = "<<exitAction>>";
			$step->selectAction = "<<selectAction>>";
			$step->summaryBandTemplate = "<<summaryBandTemplate>>";
		
			