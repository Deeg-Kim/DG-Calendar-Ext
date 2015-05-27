<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\model;

class events
{
	/**
	* phpBB auth
	* @var \phpbb\auth\auth
	*/
	protected $auth;

	/**
	* phpBB config
	* @var \phpbb\config\config
	*/
	protected $config;

	/**
	* phpBB db driver
	* @var \phpbb\db\driver_interface
	*/
	protected $db;
	
	/**
	* phpBB user
	* @var \phpbb\user
	*/
	protected $user;
	
	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	* @param \phpbb\auth\auth			$auth	phpBB auth object
	* @param \phpbb\config\config			$config	phpBB config
	* @param \phpbb\db\driver_interface		$db	phpBB database driver
	* @param \phpbb\user				$user	phpBB user object
	*/
	public function __construct($auth, $config, $db, $user, $events_table, $comments_table, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->user = $user;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		
		define('CALENDAR_EVENTS_TABLE', $events_table);
		define('CALENDAR_COMMENTS_TABLE', $comments_table);
		
		if (!function_exists('generate_text_for_storage'))
		{
		   include $this->root_path . 'includes/functions_content.' . $this->php_ext; 
		}
	}

	/**
	* Get events in order
	*
	* @param int $month Month to get
	* @param int $day Day to get
	* @param int $year Year to get
	*/
	public function get_events($limit = false, $descending = false, $id = 0, $edit = false)
	{
		$events = array();
		
		if($id == 0) {
			if ($limit == false) {
				$sql_array = array(
					'SELECT'		=> '*',
					'FROM'		=> array(CALENDAR_EVENTS_TABLE => 'c'),
					'ORDER_BY'	=> 'post_time',
				);
				
				$sql = $this->db->sql_build_query('SELECT', $sql_array);
				$result = $this->db->sql_query($sql);
				
				while ($row = $this->db->sql_fetchrow($result))
				{
					$events[] = $row;
				}
			}
			else {
				if($descending == true) {
					$sql_array = array(
						'SELECT'		=> '*',
						'FROM'		=> array(CALENDAR_EVENTS_TABLE => 'c'),
						'ORDER_BY'	=> 'post_time DESC',
					);
				}
				else {
					$sql_array = array(
						'SELECT'		=> '*',
						'FROM'		=> array(CALENDAR_EVENTS_TABLE => 'c'),
						'ORDER_BY'	=> 'post_time ASC',
					);
				}
					
				$sql = $this->db->sql_build_query('SELECT', $sql_array);
				$result = $this->db->sql_query_limit($sql, $limit);
					
				while ($row = $this->db->sql_fetchrow($result))
				{
					$events[] = $row;
				}
			}
			return $events;
		}
		else {
			$sql_array = array(
				'SELECT'		=> '*',
				'FROM'		=> array(CALENDAR_EVENTS_TABLE => 'c'),
				'WHERE'		=> 'c.id = ' . $id,
			);
			
			$sql = $this->db->sql_build_query('SELECT', $sql_array);
			$result = $this->db->sql_query($sql);
			
			$event = $this->db->sql_fetchrow($result);
			
			if($edit == true) {
				decode_message($event['description'], $event['bbcode_uid']);
			}
			else {
				$event['description'] = generate_text_for_display($event['description'], $event['bbcode_uid'], $event['bbcode_bitfield'], $event['bbcode_options']);
			}
			
			return $event;
		}
	}

	/**
	* Get events by a date
	*
	* @param int $month Month to get
	* @param int $day Day to get
	* @param int $year Year to get
	*/
	public function get_events_of_day($month, $day, $year)
	{
		$events = array();
		
		$sql_array = array(
			'SELECT'		=> '*',
			'FROM'		=> array(CALENDAR_EVENTS_TABLE => 'c'),
			'WHERE'		=> 'c.month = ' . $month . ' AND c.day = ' . $day . ' AND c.year = ' . $year,
		);
			
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
			
		while ($row = $this->db->sql_fetchrow($result))
		{
			$events[] = $row;
		}
			
		return $events;
	}
	
	/**
	* New event
	*
	* @param int $user_id User ID of poster
	* @param int $timestamp Posting timestamp
	* @param int $month Month of event
	* @param int $day Day of event
	* @param int $year Year of event
	* @param string $start Start time
	* @param string $end End time
	* @param string $title Title of event
	* @param string $description Event description
	*/
	public function add_event($user_id, $timestamp, $month, $day, $year, $start, $end, $title, $description)
	{
		$description = utf8_normalize_nfc($description);
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($description, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
		
		$sql_array = array(
			'user_id'			=> $user_id,
			'post_time'			=> $timestamp,
			'month'				=> $month,
			'day'				=> $day,
			'year'				=> $year,
			'start'				=> $start,
			'end'				=> $end,
			'title'				=> $title,
			'description'		=> $description,
			'bbcode_uid'        => $uid,
			'bbcode_bitfield'   => $bitfield,
			'bbcode_options'    => $options,
			'event_status'		=> 0,
		);
		
		$sql = 'INSERT INTO ' . CALENDAR_EVENTS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
		$this->db->sql_query($sql);
		
		return $this->db->sql_nextid();
	}
	
	/**
	* Edit event
	*
	* @param int $id ID of event
	* @param int $user_id User ID of editor
	* @param int $month Month of event
	* @param int $day Day of event
	* @param int $year Year of event
	* @param string $start Start time
	* @param string $end End time
	* @param string $title Title of event
	* @param string $description Event description
	*/
	public function edit_event($id, $user_id, $month, $day, $year, $start, $end, $title, $description)
	{
		$description = utf8_normalize_nfc($description);
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($description, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
		
		$sql_array = array(
			'user_id'			=> $user_id,
			'month'				=> $month,
			'day'				=> $day,
			'year'				=> $year,
			'start'				=> $start,
			'end'				=> $end,
			'title'				=> $title,
			'description'		=> $description,
			'bbcode_uid'        => $uid,
			'bbcode_bitfield'   => $bitfield,
			'bbcode_options'    => $options,
		);
		
		$sql = 'UPDATE ' . CALENDAR_EVENTS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . ' WHERE `id` = ' . $id;
		$this->db->sql_query($sql);
	}
	
	/**
	* Delete event
	*
	* @param int $id The id of the event
	*/
	public function delete_event($id)
	{
		$sql = 'DELETE FROM ' . CALENDAR_EVENTS_TABLE . ' WHERE `id` = ' . $id;
		$this->db->sql_query($sql);
	}
	
	/**
	* Change status
	*
	* @param int $id The id of the event
	*/
	public function change_event_status($id, $status)
	{
		$sql = 'UPDATE ' . CALENDAR_EVENTS_TABLE . ' SET `event_status` = ' . $status . ' WHERE `id` = ' . $id;
		$this->db->sql_query($sql);
	}
	
	/**
	* New comment
	*
	* @param int $event_id ID of the event
	* @param int $user_id User ID of poster
	* @param int $timestamp Posting timestamp
	* @param int $month Month of event
	* @param int $day Day of event
	* @param int $year Year of event
	* @param string $start Start time
	* @param string $end End time
	* @param string $title Title of event
	* @param string $description Event description
	*/
	public function add_comment($event_id, $user_id, $timestamp, $subject, $text)
	{
		$text = utf8_normalize_nfc($text);
		$uid = $bitfield = $options = '';
		$allow_bbcode = $allow_urls = $allow_smilies = true;
		generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
		
		$sql_array = array(
			'event_id'			=> $event_id,
			'user_id'			=> $user_id,
			'post_time'			=> $timestamp,
			'subject'			=> $subject,
			'text'				=> $text,
			'bbcode_uid'        => $uid,
			'bbcode_bitfield'   => $bitfield,
			'bbcode_options'    => $options,
		);
		
		$sql = 'INSERT INTO ' . CALENDAR_COMMENTS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_array);
		$this->db->sql_query($sql);
	}
	
	/**
	* New comment
	*
	* @param int $id ID of the event
	*/
	public function get_comments($id)
	{
		$sql_array = array(
			'SELECT'		=> '*',
			'FROM'		=> array(CALENDAR_COMMENTS_TABLE => 'c'),
			'ORDER_BY'	=> 'post_time ASC',
			'WHERE'		=> 'c.event_id = ' . $id,
		);
		
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query($sql);
		
		while ($row = $this->db->sql_fetchrow($result))
		{
			$comments[] = $row;
		}
		
		for($i = 0; $i < count($comments); $i++) {
			$comments[$i]['text'] = generate_text_for_display($comments[$i]['text'], $comments[$i]['bbcode_uid'], $comments[$i]['bbcode_bitfield'], $comments[$i]['bbcode_options']);
		}
		
		return $comments;
	}
}
