<?php

class Event extends DataObject {

	public static $db = array(
		'EventName' => 'Text',
		'EventDate' => 'Date',
		'Location' => 'Text',
		'Link' => 'Text'
	);

	function getCMSFields_forPopup() {

		$dateField = new DateField('EventDate', 'Event Date');
		$dateField->setLocale('en_US');
		$dateField->setConfig('showcalendar', true);

		$fields = new FieldSet();
		$fields->push(new TextField('EventName', 'Event Name'));
		$fields->push($dateField);
		$fields->push(new TextField('Location', 'Event Location'));
		$fields->push(new TextField('Link', 'Event Link URL'));

		return $fields;
	}
}