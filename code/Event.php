<?php

class Event extends DataObject {

	public static $db = array(
		'Name' => 'Text',
		'Date' => 'Date',
		'Location' => 'Text',
		'Link' => 'Text'
	);

	function getCMSFields_forPopup() {

		$dateField = new DateField('Date', 'Event Date');
		$dateField->setLocale('en_US');
		$dateField->setConfig('showcalendar', true);

		$fields = new FieldSet();
		$fields->push(new TextField('Name', 'Event Name'));
		$fields->push($dateField);
		$fields->push(new TextField('Location', 'Event Location'));
		$fields->push(new TextField('Link', 'Event Link URL'));

		return $fields;
	}
}