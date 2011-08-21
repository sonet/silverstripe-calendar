<?php

/**
 * Calendar Page type with month view with event management in the CMS
 * 
 * @todo Navigation to be added
 * @todo Modify events class to DataObjectDecorator/loosely coupled
 * @todo Modify template files to work with the default theme
 *
 * @package calendar
 */

class Calendar extends Page {
	
	protected $year;
	protected $month;
	protected $day;
	/**
	 * @var DataObjectSet storing all events for the month
	 */
	protected $events;

	static $has_many = array ( 
		'Events' => 'Event'
	);

	public function getCMSFields($cms)
	{
		$fields = parent::getCMSFields($cms);
		$fields->addFieldToTab("Root.Content.Events", new DataObjectManager(
			$this,
			'Events',
			'Event',
			array(
				'EventName' => 'EventName',
				'EventDate' => 'EventDate',
				'Location'=> 'Location',
				'Link' => 'Link'
			),
			'getCMSFields_forPopup'
		));
		return $fields;
	}

	/**
	 * Initialize month view
	 *
	 * @return void
	 */
	protected function calendarInit($o) {

		// add or subtract month offset
		$date = new DateTime(date('Y-m-d'));
		switch ($o) {
			case ($o > 0):
				$date->add(new DateInterval('P' . abs($o) . 'M'));
				break;
			case ($o < 0):
				$date->sub(new DateInterval('P' . abs($o) . 'M'));
				break;
			default:
				break;
		}

		$this->year = $date->format('Y');
		$this->month = $date->format('n');
		$this->day = $date->format('j');

		$this->getAllEvents();
	}

	/**
	 * Calculate the number of days in month
	 *
	 * @return integer
	 */
	protected function getDaysInMonth() {
		return date('t', mktime(0, 0, 0, $this->month, 1, $this->year));
	}

	/**
	 * Calculate the first day of the week 0..6
	 *
	 * @return integer
	 */
	protected function getFirstDay() {
		return date('w', mktime(0, 0, 0, $this->month, 1, $this->year));
	}

	/**
	 * Calculate the total number of required/used calendar cells
	 *
	 * @return integer
	 */
	protected function getActiveDayCells() {
		return self::getFirstDay() + self::getDaysInMonth();
	}

	/**
	 * Calculate the number if weeks in month
	 *
	 * @return integer
	 */
	protected function getWeeksInMonth() {
		return ceil( self::getActiveDayCells() / 7);
	}

	/**
	 * Get all events for the month
	 *
	 * @return integer
	 */
	protected function getMonthName() {
		return date('F', mktime(0, 0, 0,
			$this->month, 1, $this->year)) . ' ' . $this->year;
	}
	
	/**
	 * Get all events for the month
	 *
	 * @param Date
	 * @return void
	 */
	protected function getAllEvents() {
		$first_day = new DateTime(date(
			$this->year . '-' . 
			$this->month . '-1'
		));
		$last_day =  new DateTime(date(
			$this->year . '-' .
			$this->month . '-' .
			date('t', strtotime($first_day->format('Y-n-j')))
		));
		$this->events = DataObject::get(
			'Event',
			'EventDate BETWEEN \'' . 
				$first_day->format('Y-m-d') . '\' AND \'' .
				$last_day->format('Y-m-d') . '\''
		);
	}

	public function Weeks() {
		$weekSet = new DataObjectSet();
		foreach (range(0, $this->getWeeksInMonth() - 1) as $weekIndex) {
			$weekSet->push(new ArrayData(
				array(
					'Week' => $weekIndex,
					'Days' => $this->Days($weekIndex)
				)
			));
		}
		return $weekSet;
	}

	public function Days($weekIndex) {
		$daysSet = new DataObjectSet();

		for ($i = 1; $i <= 7; $i++) {
			// starting cell offset
			$day = $i + $weekIndex * 7 - $this->getFirstDay();
			// exclude not valid day indexes
			if (($day < 1) || ($day > $this->getDaysInMonth()))
				$day_num = '';
			else
				$day_num = $day;
			// custom EvenOdd()
			if ($day % 2)
				$oddEven = 'odd';
			else
				$oddEven = 'even';
			$daysSet->push(new ArrayData(
				array(
					'Day' => $day_num,
					'OddEven' => $oddEven,
					'Events' => $this->Events($day)
					)
				)
			);
		}
		return $daysSet;
	}

	public function Events($day) {
		$eventSet = new DataObjectSet();
		if ($day <= $this->getDaysInMonth() && $day > 0) {
			$date = new DateTime(date(
				$this->year . '-' . 
				$this->month . '-' . $day
			));
			if ($this->events) {
				foreach ($this->events as $event) {
					if ($event->EventDate == $date->format('Y-m-d')) {
						$eventSet->push(new ArrayData(
							array(
								'Event' => $event->EventName
							)
						));
					}
				}
			}
		}
		return $eventSet;
	}

}

class Calendar_Controller extends Page_Controller {

	public function init() {
		parent::init();
		Requirements::css('calendar/css/calendar.css');

		$_SESSION['month'] = (isset($_SESSION['month'])) ? $_SESSION['month'] : 0;
		if(!isset($_GET['month']) || !is_numeric($_GET['month']))
			$_GET['month'] = 0;
		$_SESSION['month'] = ($_GET['month'] == 0) ? 0 : $_SESSION['month'] + $_GET['month'];

		$this->calendarInit($_SESSION['month']);
	}

}