<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\notification;

/**
* Event invite notifications class
* This class handles notifications for events when people are invited (for invitee)
*/

class event_invite extends \phpbb\notification\type\base 
{
	/**
	* Get notification type name
	*
	* @return string
	*/
	public function get_type()
	{
		return 'event_invite';
	}
	
	/**
	* Language key used to output the text
	*
	* @var string
	*/
	protected $language_key = 'EVENT_INVITED';
	
	/**
	* Notification option data (for outputting to the user)
	*
	* @var bool|array False if the service should use it's default data
	* 					Array of data (including keys 'id', 'lang', and 'group')
	*/
	public static $notification_option = array(
		'lang'	=> 'EVENT_INVITE_TYPE_OPTION',
		'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
	);
	
	/**
	* Is available
	*/
	public function is_available()
	{
		return true;
	}
	
	/**
	* Get the id of the item
	*
	* @param array $my_notification_data The data from the post
	*/
	public static function get_item_id($my_notification_data)
	{
		return (int) $my_notification_data['event_id'];
	}
}
?>