<?php

class Calendar {

	private $active_year, $active_month, $active_day, $prior_date, $next_date, $grid_date;
	private $events = array();
	private $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	private $first_day_of_week = 0;
	private $num_days_last_month = 31;
	private $num_days = 31;
	private $days_wide = 7;

	public function __construct($date = null, $days_wide=7) 
	{
		$this->active_year = (!empty($date) ) ? date('Y', strtotime($date) ) : date('Y');
		$this->active_month = (!empty($date) ) ? date('m', strtotime($date) ) : date('m');
		$this->active_day = (!empty($date) ) ? date('d', strtotime($date) ) : date('d');
		$this->prior_date = date('Y-m-01', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day . '-1 month') );
		$this->next_date = date('Y-m-01', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day . '+1 month') );
		
		$this->num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year) );
		$this->num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year) ) );
		$this->first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1') ), $this->days);
		
		if (!in_array($days_wide, range(1,7) ) ) {$days_wide = 7;}
		$this->days_wide = $days_wide;
		}

	public function add_event($txt, $date, $numdays=1, $color = '', $balloon=null) 
	{
		$this->events[] = [$txt, $date, $numdays, $color, $balloon];
		}

	public function __toString() 
	{
		global $aoo;
		
		$self = $_SERVER['PHP_SELF'];
		
		$link_prior = $aoo('a', "class=month-year;\nhref={$self}?date={$this->prior_date};\ncore=⮜&nbsp;");
		$link_next = $aoo('a', "class=month-year;\nhref={$self}?date={$this->next_date};\ncore=&nbsp;⮞");
		$current = date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day) );
		$curlink = $aoo('a', "class=month-year;\nhref={$self};\ncore=$current");
		$header = $aoo('div', "class=header;\ncore={$aoo('div', "class=month-year;\ncore=$link_prior{$curlink}$link_next")}");
		//"
		$days = $aoo('div', "class=days;\ncore={$this->day_names()}{$this->prior_month()}{$this->month()}{$this->next_month()}");
		
		return $aoo('div', "class=calendar;\ncore=$header$days");
		}

	private function day_names()
	{
		global $aoo;
		
		$html = '';
		
		if ($this->days_wide == 7)
		{
			foreach ($this->days as $day) 
				{$html .= $aoo('div', "class=day_name;\ncore=$day");}
			}
			
		return $html;
		}
		
	private function prior_month()
	{
		global $aoo;
		
		$html = '';
		
		if ($this->days_wide == 7)
		{
			$set = $this->num_days_last_month + 1;
			
			for ($i = $this->first_day_of_week; $i > 0; $i--) 
			{
				$core = $set - $i;
				$html .= $aoo('div', "class=day_num ignore;\ncore=$core");
				}
			}
			
		return $html;
		}
		
	private function next_month()
	{
		global $aoo;
		
		$html = '';
		
		if ($this->days_wide == 7)
		{
			$final_day = 42 - $this->num_days - max($this->first_day_of_week, 0);
			
			if ($final_day >= $this->days_wide) {$final_day = $final_day - $this->days_wide;}
			
			for ($i = 1; $i <= $final_day; $i++) 
				{$html .= $aoo('div', "class=day_num ignore;\ncore=$i");}
			}
			
		return $html;
		}
		
	private function event($i=1)
	{
		global $aoo;
		
		$html = '';
		
		// $i is the day of the month we are looking at.
		// We can create the grid date of interest out of it.
		$this->grid_date = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i) );
		
		// This is the main loop for populating events on the calendar.
		// You can have more than one event on a given day.
		foreach ($this->events as $event_num=>$event) 
		{
			$event_start_date = $event[1];
			$event_class = $event[3];
			$event_summary = $event[0];
			
			// The number of days long for the event (default 1)
			for ($d = 0; $d <= ($event[2] - 1); $d++) 
			{
				// yyyy-mm-dd + $d day (where if 1 day, adds 0)
				$event_date = date('Y-m-d', strtotime($event_start_date . ' +' . $d . ' day') );
				
				if ($this->grid_date == $event_date) 
				{
					$html .= $aoo('div', "id=event{$event_num}|||class=event {$event_class}|||core={$event_summary}|||onclick=showDetails($event_num)", "|||");
					}
				}	# End of for loop
			}	# End of foreach

		return $html;
		}	# End of event() function
		
	private function month()
	{
		global $aoo;
		
		$html = '';
		
		// This is the main loop for creating the calendar, one day at a time.
		// $this->num_days is the number of days in the selected month.
		// $i is therefore each day of the month.
		// $i (day of the month) gets passed to the procedure $this->event()
		for ($i = 1; $i <= $this->num_days; $i++) 
		{
			$selected = '';
			
			if ($i == $this->active_day) {$selected = ' selected';}
			
			$core=$i;
			
			if ($this->days_wide == 1)
			{
				$dow = date('D', strtotime($this->active_year . '-' . $this->active_month . '-' . $i) );
				
				$core .= " $dow";
				}

			$html .= $aoo('div', "class=day_num $selected;\ncore={$aoo('span', "core=$core")}{$this->event($i)}");
			}	# End of for

		return $html;
		}	# End of month() function
	}
	
//_______________________________________________________________________________________________________
function create_calendar($date=null, $events=array(), $days_wide=7)
{
	if (!in_array($days_wide, range(1,7) ) ) {$days_wide = 7;}
	
	$calendar = new Calendar($date, $days_wide);

	if (is_array($events) && !empty($events) )
	{
		foreach ($events as $event)
		{
			$label = (isset($event[0]) ) ? $event[0] : "Event";
			$d = (isset($event[1]) ) ? $event[1] : date('Y-m-d');
			$numdays = (isset($event[2]) ) ? $event[2] : 1;
			$color = (isset($event[3]) ) ? $event[3] : null;
			$balloon = (isset($event[4]) && is_array($event[4]) && count($event[4]) > 0) ? $event[4] : null;
			
			$calendar->add_event($label, $d, $numdays, $color, $balloon);
			}
		}	# End of if conditional
		
	$cal = $calendar;
	
	unset($calendar);
	
	return $cal;
	}	# End of create_calendar function

//_______________________________________________________________________________________________________

?>