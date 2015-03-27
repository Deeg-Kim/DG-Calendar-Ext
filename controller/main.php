<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\controller;

class main
{	
	/** @var \phpbb\auth\auth */
	protected $auth;
	
	/* @var \phpbb\config\config */
	protected $config;
	
	/* @var \phpbb\db\driver_interface */
	protected $db;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;
  
  	/* @var \dg\calendar\calendar\fetch_events */
  	protected $fetch_events;
  
  	/** @var dg.calendar.config.table */
  	protected $config_table;
	
	/** @var dg.calendar.events.table */
  	protected $events_table;
	
	/** @var core.root_path */
	protected $root_path;
	
	/**
	* PHP file extension
	* @var string
	*/
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \dg\calendar\calendar\fetch_events $fetch_events
	* @param string $config_table
	* @param string $events_table
	*/
	public function __construct($auth, $config, $db, $helper, $template, $user, $events, $config_table, $events_table, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->events = $events;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		define('CALENDAR_CONFIG_TABLE', $config_table);
		define('CALENDAR_EVENTS_TABLE', $events_table);
		
		include $this->root_path . 'includes/functions_user.' . $this->php_ext;
	}

	/**
	* Controller for route /calendar
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle()
	{	 
		if($this->auth->acl_get('m_calendar')) {
			$this->template->assign_vars(array(
				'U_MODERATE_CALENDAR'	=> $this->helper->route('moderate'),
			));
		}
	
		$month = date('n');
		$year = date('Y');
		
		$month_array = array(
			1   => $this->user->lang('JANUARY'),
			2   => $this->user->lang('FEBRUARY'),
			3   => $this->user->lang('MARCH'),
			4   => $this->user->lang('APRIL'),
			5   => $this->user->lang('MAY'),
			6   => $this->user->lang('JUNE'),
			7   => $this->user->lang('JULY'),
			8   => $this->user->lang('AUGUST'),
			9   => $this->user->lang('SEPTEMBER'),
			10  => $this->user->lang('OCTOBER'),
			11  => $this->user->lang('NOVEMBER'),
			12  => $this->user->lang('DECEMBER'),
		);
		
		// assign variables
		$this->template->assign_vars(array(
			'U_CALENDAR_PAGE'	=> $this->helper->route('main'),
			'U_CREATE_LINK'		=> $this->helper->route('create'),
			
			'S_CAN_MAKE_EVENT'	=> $this->auth->acl_get('u_new_event'),
			
			'CALENDAR_TITLE' 	=> $month_array[$month].' '.$year,
			'CALENDAR_OUTPUT'	=> $this->draw_calendar($month, $year),
		));
		
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'			=> $this->user->lang('CALENDAR_PAGE'),
			'U_VIEW_FORUM'		=> $this->helper->route('main'),
		));

		return $this->helper->render('calendar_body.html', $this->user->lang('CALENDAR'));
	}
	
	/**
	* Controller for route /calendar
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function view($month, $year)
	{	
		$month_array = array(
			1   => $this->user->lang('JANUARY'),
			2   => $this->user->lang('FEBRUARY'),
			3   => $this->user->lang('MARCH'),
			4   => $this->user->lang('APRIL'),
			5   => $this->user->lang('MAY'),
			6   => $this->user->lang('JUNE'),
			7   => $this->user->lang('JULY'),
			8   => $this->user->lang('AUGUST'),
			9   => $this->user->lang('SEPTEMBER'),
			10  => $this->user->lang('OCTOBER'),
			11  => $this->user->lang('NOVEMBER'),
			12  => $this->user->lang('DECEMBER'),
		);
		
		// assign variables
		$this->template->assign_vars(array(
			'U_CALENDAR_PAGE'	=> $this->helper->route('main'),
			'U_CREATE_LINK'		=> $this->helper->route('create'),
			
			'S_CAN_MAKE_EVENT'	=> $this->auth->acl_get('u_new_event'),
			
			'CALENDAR_TITLE' 	=> $month_array[$month].' '.$year,
			'CALENDAR_OUTPUT'	=> $this->draw_calendar($month, $year),
		));

		return $this->helper->render('calendar_body.html', $this->user->lang('CALENDAR'));
	}

	private function draw_calendar($month, $year){
		/* draw table */
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
		  
		/* table headings */
		$headings = array($this->user->lang('SUNDAY'), $this->user->lang('MONDAY'), $this->user->lang('TUESDAY'), $this->user->lang('WEDNESDAY'), $this->user->lang('THURSDAY'), $this->user->lang('FRIDAY'), $this->user->lang('SATURDAY'));
		$calendar .= '<thead class="calendar-head"><tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">', $headings).'</td></tr></thead>';
		
		/* generation found at http://davidwalsh.name/php-calendar */  
		/* days and weeks vars now ... */
		$running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
		$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();
		  
		/* row for week one */
		$calendar .= '<tr class="calendar-row">';
		  
		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++) {
			$calendar .= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		}
		  
		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
			if($list_day == date('j') && $month == date('n') && $year == date('Y')) {
				$calendar .= '<td class="calendar-day today">';
			}
			else {
				$calendar .= '<td class="calendar-day">';
			}
			/* add in the day number */
			$calendar .= '<div class="day-number">'.$list_day.'</div>';
		  
			$events = $this->events->get_events_of_day($month, $list_day, $year);

			if(!empty($events)) {
				for($i = 0; $i < count($events); $i++) {
					$events[$i]['time_number'] = $this->time_to_number($events[$i]['start'], $this->user->lang('PM'));
				}
				usort($events, function ($a, $b) {
					return strnatcmp($a['time_number'], $b['time_number']);
				});
			}
			
			foreach($events as $event) {
		 		$calendar .= '<div class="calendar-event"><b>' . $event['title']. '</b>';
				if($event['start'] != NULL) {
					$calendar .= '<br/>' . $event['start'] . ' - ' . $event['end'];
				}
				$calendar .= '</div>';
			}
			unset($events);
		
			$calendar .= '</td>';
			
			if($running_day == 6) {
				$calendar .= '</tr>';
				
				if(($day_counter+1) != $days_in_month) {
		  			$calendar .= '<tr class="calendar-row">';
				}
			
				$running_day = -1;
				$days_in_this_week = 0;
			}
			
			$days_in_this_week++; $running_day++; $day_counter++;
		}
		  
		/* finish the rest of the days in the week */
		if($days_in_this_week < 8) {
			for($x = 1; $x <= (8 - $days_in_this_week); $x++) {
				$calendar .= '<td class="calendar-day-np"> </td>';
			}
		}
  
		/* final row */
		$calendar .= '</tr>';
  
		/* end the table */
		$calendar .= '</table>';

		/* all done, return result */
		return $calendar;
	}
	
	private function time_to_number($time, $pm) {
		// explode time into parts
		$parts = array();
		$parts = explode(':', $time);
		
		$second_parts = array();
		$second_parts = explode(' ', $parts[1]);
		
		$parts[1] = $second_parts[0];
		$parts[2] = $second_parts[1];
		
		// calculate minute number of day
		if($parts[2] == $pm) {
			$parts[0] = $parts[0] + 12;
		}
		
		$number = $parts[0] * 60 + $parts[1];
		return $number;
	}
	
	/**
	* Controller for route /calendar/create
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function create()
	{
		if(!$this->auth->acl_get('u_new_event')) {
			trigger_error('NOT_AUTHORISED');
		}
		
		$submit = (isset($_POST['post'])) ? true : false;
		
		// get data from form
		$user_id 		= $this->user->data['user_id'];
		$title 			= request_var('title', '');
		$month 			= request_var('month', 0);
		$day			= request_var('day', 0);
		$year			= request_var('year', 0);
		if(request_var('start_hour', 0) == -1 || request_var('start_minute', 0) == -1 || request_var('end_hour', 0) == -1 || request_var('end_minute', 0) == -1 || request_var('start_meridiem', '') == '--' || request_var('end_meridiem', '') == '--') {
			$start_time = NULL;
			$end_time	= NULL;
		}
		else {
			$start_time		= request_var('start_hour', 0) . ':' . str_pad(request_var('start_minute', 0), 2, '0', STR_PAD_LEFT) . ' ' . request_var('start_meridiem', '');
			$end_time		= request_var('end_hour', 0) . ':' . str_pad(request_var('end_minute', 0), 2, '0', STR_PAD_LEFT) . ' ' . request_var('end_meridiem', '');
		}
		$description	= request_var('description', '');
		if($description == '') {
			$description = NULL;
		}
		
		// build error array
		$errors = array();
		if($submit) {
			if (strlen($title) == 0) {
				$errors[] = $this->user->lang('FIELD_REQUIRED', $this->user->lang('TITLE'));
			}
			if ($year == '') {
				$errors[] = $this->user->lang('FIELD_REQUIRED', $this->user->lang('YEAR'));
			}
			if ($this->time_to_number($start_time, $this->user->lang('PM')) > $this->time_to_number($end_time, $this->user->lang('PM'))) {
				$errors[] = $this->user->lang('WRONG_TIME');
			}
		}
		
		// if no error
		if($submit && empty($errors)) {
			$this->events->add_event($user_id, time(), $month, $day, $year, $start_time, $end_time, $title, $description);
			
			meta_refresh(3, $this->helper->route('main'));
			
			$message =  $this->user->lang['EVENT_SUCCESSFUL'] . '<br /><br /><a href="' . generate_board_url() . '/app.php/calendar">'. $this->user->lang['RETURN_CALENDAR'] . '</a>';
			trigger_error($message);
		}
		// if not submitted
		else {
			$c_action = $this->helper->route('create');
			
			$month_array = array(
				1   => $this->user->lang('JANUARY'),
				2   => $this->user->lang('FEBRUARY'),
				3   => $this->user->lang('MARCH'),
				4   => $this->user->lang('APRIL'),
				5   => $this->user->lang('MAY'),
				6   => $this->user->lang('JUNE'),
				7   => $this->user->lang('JULY'),
				8   => $this->user->lang('AUGUST'),
				9   => $this->user->lang('SEPTEMBER'),
				10  => $this->user->lang('OCTOBER'),
				11  => $this->user->lang('NOVEMBER'),
				12  => $this->user->lang('DECEMBER'),
			);
			
			// make default current date
			$months_options = "";
			$i = 1;
			foreach($month_array as $month) {
				if($i == date('n')) {
					$months_options .= '<option value=' . $i . ' selected="selected">' . $month .'</option>';
				}
				else {
					$months_options .= '<option value=' . $i . '>' . $month .'</option>';
				}
				$i++;
			}
			
			$days_options = "";
			for($i = 1; $i <= 31; $i++) {
				if($i == date('j')) {
					$days_options .= '<option value=' . $i . ' selected="selected">' . $i .'</option>';
				}
				else {
					$days_options .= '<option value=' . $i . '>' . $i .'</option>';
				}
			}
			
			$hours_options = "";
			$hours_options .= '<option value="-1" selected="selected">--</option>';
			for($i = 1; $i <= 12; $i++) {
				$hours_options .= '<option value=' . $i . '>' . $i .'</option>';
			}
			
			$minutes_options = "";
			$minutes_options .= '<option value="-1" selected="selected">--</option>';
			for($i = 0; $i < 12; $i++) {
				$minutes_options .= '<option value=' . str_pad($i * 5, 2, '0', STR_PAD_LEFT) . '>' . str_pad($i * 5, 2, '0', STR_PAD_LEFT) .'</option>';
			}
			
			$meridiem_options = "";
			$meridiem_array = array($this->user->lang('AM'), $this->user->lang('PM'));
			$meridiem_options .= '<option value="--" selected="selected">--</option>';
			foreach($meridiem_array as $option) {
				$meridiem_options .= '<option value=' . $option . '>' . $option . '</option>';
			}
			
			$this->template->assign_vars(array(
				'S_CREATE_ACTION'			=> $c_action,
				'S_HAS_ERRORS'				=> !empty($errors),
			
				'U_CALENDAR_PAGE'			=> $this->helper->route('main'),
				
				'DAYS_OPTIONS'				=> $days_options,
				'ERRORS'						=> implode($errors, '<br />'),
				'HOURS_OPTIONS'				=> $hours_options,
				'MERIDIEM_OPTIONS'			=> $meridiem_options,
				'MINUTES_OPTIONS'			=> $minutes_options,
				'MONTHS_OPTIONS'			=> $months_options,
				'YEAR'						=> date('Y'),
			));
		}
		
		return $this->helper->render('event_create_body.html', $this->user->lang('CREATE_EVENT'));
	}
	
	/**
	* Controller for route /calendar/moderate
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function moderate()
	{	 
		if(!$this->auth->acl_get('m_calendar')) {
			trigger_error('NOT_AUTHORISED');
		}
	
		$events = $this->events->get_events(5, true);
		
		$last_5 = "";
		for($i = 0; $i < count($events); $i++) {
			$sql = 'SELECT *
				 FROM ' . USERS_TABLE . '
				 WHERE user_id = ' . $events[$i]['user_id'];
			$result = $this->db->sql_query($sql);
			$member = $this->db->sql_fetchrow($result);
			
			user_get_id_name($user_id_ary, $username_ary);
			if($i % 2 == 0) {
				$last_5 .= '<tr class="bg1"><td>' . $events[$i]['title'] . '</td><td>' . get_username_string("full", $member['user_id'], $member['username'], $member['user_colour']) . '</td><td><a href="' . $this->helper->route('event', array('id' =>  $events[$i]['id'])) . '">' . $this->user->lang('VIEW_EVENT') . '</a></td><td>' . date($member['user_dateformat'], $events[$i]['post_time']) . '</td></tr>';
			}
			else {
				$last_5 .= '<tr class="bg2"><td>' . $events[$i]['title'] . '</td><td>' . get_username_string("full", $member['user_id'], $member['username'], $member['user_colour']) . '</td><td><a href="' . $this->helper->route('event', array('id' => $events[$i]['id'])) . '">' . $this->user->lang('VIEW_EVENT') . '</a></td><td>' . date($member['user_dateformat'], $events[$i]['post_time']) . '</td></tr>';
			}
		}
		$this->template->assign_vars(array(
			'LAST_5_EVENTS'			=> $last_5,
		));
		
		if(!empty($events)) {
			$this->template->assign_vars(array(
				'S_HAS_EVENTS'			=> true,
			));
		}
		
		$navlinks_array = array(
			array(
				'FORUM_NAME'			=> $this->user->lang('CALENDAR_PAGE'),
				'U_VIEW_FORUM'		=> $this->helper->route('main'),
			),
			array(
				'FORUM_NAME'			=> $this->user->lang['MCP_CALENDAR'],
				'U_VIEW_FORUM'		=>$this->helper->route('moderate'),
			),
		);
		
		foreach( $navlinks_array as $name )
		{
			$this->template->assign_block_vars('navlinks', array(
				'FORUM_NAME'	=> $name['FORUM_NAME'],
				'U_VIEW_FORUM'	=> $name['U_VIEW_FORUM'],
			));
		}
		
		return $this->helper->render('calendar_moderate_body.html', $this->user->lang('CALENDAR'));
	}
}
