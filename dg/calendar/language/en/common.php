<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'AM'							=> 'AM',
	'APRIL'						=> 'April',
	'AUGUST'						=> 'August',
	
	'CALENDAR'					=> 'Calendar',
	'CALENDAR_PAGE'				=> 'Calendar',
	'COMMENT'					=> 'Comment',
	'CREATE_EVENT'				=> 'Create Event',
	
	'DATE'						=> 'Date',
	'DECEMBER'					=> 'December',
	'DELETE'						=> 'Delete',
	'DESCRIPTION'				=> 'Description',
		
	'EDIT'						=> 'Edit',
	'EDIT_EVENT'					=> 'Edit Event',
	'END_TIME'					=> 'End Time',
	'EVENT_BY'					=> 'Event by %s',
	'EVENT_DELETE'				=> 'Delete event',
	'EVENT_DELETE_CONFIRM'		=> 'Are you sure you want to delete this event?',
	'EVENT_LOCK'					=> 'Lock event',
	'EVENT_DELETE_SUCCESSFUL'	=> 'Event deleted successfully!',
	'EVENT_SUCCESSFUL'			=> 'Event created successfully!',
	
	'FEBRUARY'					=> 'February',
	'FIELD_OPTIONAL'			=> 'This field optional',
	'FIELD_REQUIRED'			=> 'The %s field is required.',
	'FRIDAY'						=> 'Friday',
	'FRONT_PAGE'					=> 'Front page',
	
	'JANUARY'					=> 'January',
	'JULY'						=> 'July',
	'JUNE'						=> 'June',
	
	'LAST_5_EVENTS'				=> 'Last 5 Events Posted',
	
	'MARCH'						=> 'March',
	'MAY'						=> 'May',
	'MCP_CALENDAR'				=> 'Calendar MCP',
	'MODERATE_CALENDAR'			=> 'Calendar Moderator Control Panel',
	'MONDAY'						=> 'Monday',
	'MONTH'						=> 'Month',
	
	'NEW_EVENT'					=> 'New Calendar Event',
	'NO_EVENTS'					=> 'There are no events to show.',
	'NOVEMBER'					=> 'November',
	
	'OCTOBER'					=> 'October',
	
	'PM'							=> 'PM',
	
	'QUICKMOD_TOOLS'			=> 'Quick-mod tools',
	
	'REPORT'						=> 'Report',
	'RETURN_CALENDAR'			=> 'Return to calendar',
	
	'THURSDAY'					=> 'Thursday',
	'TITLE'						=> 'Title',
	'TUESDAY'					=> 'Tuesday',
	
	'SATURDAY'					=> 'Saturday',
	'SEPTEMBER'					=> 'September',
	'START_TIME'					=> 'Start Time',
	'SUNDAY'						=> 'Sunday',
	
	'VIEW'						=> 'View',
	'VIEW_EVENT'					=> 'View Event',
	
	'WEDNESDAY'					=> 'Wednesday',
	'WEEK'						=> 'Week',
	'WRONG_TIME'					=> 'Start time must be before end time.',
	
	'YEAR'						=> 'Year',
	
	// permissions
	'ACL_U_EVENT_REPORT'		=> 'Can report events',
	'ACL_U_NEW_EVENT'			=> 'Can create events',
	'ACL_U_SELF_DELETE'			=> 'Can delete own events',
	'ACL_U_SELF_EDIT'			=> 'Can edit own events',
	
	'ACL_M_CALENDAR'			=> 'Can moderate calendar',
));
