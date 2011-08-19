<?php

/**
 * Monthly Calendar Page type that lets users manage Events in the CMS.
 * 
 * @todo Navigation to be added
 * @todo Event management in CMS
 * @todo Events rendering
 *
 * @package calendar
 */

class Calendar extends Page {
	
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
				'Date' => 'Date',
				'Location'=>'Location'
			),
			'getCMSFields_forPopup'
		));
		return $fields;
	}

	static $year;
	static $month;
	static $day;

	static function monthInit() {
		
		// get month offset from session
		$_SESSION['m'] = (isset($_SESSION['m'])) ? $_SESSION['m'] : 0;
		// get URL offset param
		if(!isset($_GET['m']) || !is_numeric($_GET['m']))
			$_GET['m'] = 0;
		$_SESSION['m'] += $_GET['m'];
		$o = $_SESSION['m'];
		
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
		// init current date
		self::$year = $date->format('Y');
		self::$month = $date->format('n');
		self::$day = $date->format('j');
	}

	/**
	 * Calculate the number of days in month
	 *
	 * @return integer
	 */
	protected static function getDaysInMonth() {
		return date('t', mktime(0, 0, 0, self::$month, 1, self::$year));
	}

	/**
	 * Calculate the first day of the week 0..6
	 *
	 * @return integer
	 */
	protected static function getFirstDay() {
		return date('w', mktime(0, 0, 0, self::$month, 1, self::$year));
	}

	/**
	 * Calculate the total number of required/used calendar cells
	 *
	 * @return integer
	 */
	protected static function getActiveDayCells() {
		return self::getFirstDay() + self::getDaysInMonth();
	}

	/**
	 * Calculate the number if weeks in month
	 *
	 * @return integer
	 */
	protected static function getWeeksInMonth() {
		return ceil( self::getActiveDayCells() / 7);
	}

	/**
	 * Get all events for the month
	 *
	 * @return integer
	 */
	protected static function getMonthName() {
		return date('F', mktime(0, 0, 0, self::$month, 1, self::$year)) . ' ' . self::$year;
	}
	
	protected static function getMonthEvents() {
	}

}

Calendar::monthInit();

class Calendar_Controller extends Page_Controller {

	public function init() {
		parent::init();

		Requirements::css('calendar/css/calendar.css');
		// @todo Move model init call into controller's init(), pass request date (month) as argument
		// @todo Have all event for the particular month available in here?
	}

	function CurrentMonth() {
		return $this->getMonthName();
	}

	function Weeks() {
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

	function Days($weekIndex) {
		$daysSet = new DataObjectSet();

		for ($i = 1; $i <= 7; $i++) {
			// starting cell offset
			$dayIndex = $i + $weekIndex * 7 - $this->getFirstDay();
			// exclude not valid day indexes
			if (($dayIndex < 1) || ($dayIndex > $this->getDaysInMonth()))
				$dayValue = '';
			else
				$dayValue = $dayIndex;
			// custom EvenOdd()
			if ($dayIndex % 2)
				$oddEven = 'odd';
			else
				$oddEven = 'even';

			$daysSet->push(new ArrayData(
				array(
					'Day' => $dayValue,
					'OddEven' => $oddEven,
					'Events' => '' //$this->Events($dayIndex)
					)//$this->Events($dayIndex) // not good idea to get all events by one
				)
			);
		}
		return $daysSet;
	}
		
	function Events($dayIndex) {
		// @todo Store all event for month somewhere - init()? Push event for the particular day only
		$eventSet = new DataObjectSet();
		$eventSet->push(new ArrayData(
			array(
				'Event' => 'Event info'
			)
		));
		return $eventSet;
	}

	/**
	 * Get current month offset based on user selection
	 *
	 * @return integer
	 */
	protected function getMonthSelection() {
		if (isset($_SESSION['month']))
			return $_SESSION['month'];
		else
			return 0;
	}

}


//	function Events($day) {
//
//	if(!in_array($region, self::$regionStrings)) 
//		return false; 
//
//	return DataObject::get('Events', "Date = '$day'"); 
//	}


//http://silverstripe.org/template-questions/show/8518
//echo "<pre>"; print_r($daysSet); echo "\n</pre>";