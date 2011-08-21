<?php

class Event extends DataObject {

	public static $db = array(
		'EventName' => 'Text',
		'EventDate' => 'Date',
		'EventLink' => 'Text'
	);

	function getCMSFields_forPopup() {

		$dateField = new DateField('EventDate', _t('Event.EVENTDATE', 'Event Date'));
		$dateField->setLocale('en_US');
		$dateField->setConfig('showcalendar', true);

		$fields = new FieldSet();
		$fields->push(new TextField('EventName', _t('Event.EVENTNAME', 'Event Name')));
		$fields->push($dateField);
		$fields->push(new TextField('EventLink', _t('Event.EVENTLINK', 'Event Link URL (optional)')));

		return $fields;
	}
}