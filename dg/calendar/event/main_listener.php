<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'		=> 'load_language_on_setup',
			'core.page_header'		=> 'add_page_header_link',
			'core.permissions'		=> 'add_permissions'
		);
	}

	/** @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\user */
	protected $user;
	
	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template			$template	Template object
	* @param \phpbb\path_helper		$path_helper		phpBB path helper
	* @param \phpbb\user			$user			User object
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\path_helper $path_helper, \phpbb\user $user)
	{
		$this->auth = $auth;
		$this->helper = $helper;
		$this->template = $template;
		$this->path_helper = $path_helper;
		$this->user = $user;
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dg/calendar',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header_link($event)
	{
		if($this->auth->acl_get('u_calendar')) {
			$this->template->assign_vars(array(
				'U_CALENDAR_PAGE'	=> generate_board_url() . "/app.php/calendar",
			));
		}
	}

    public function add_permissions($event) {
        $permissions = $event['permissions'];
        $permissions['u_calendar_new_event'] = array('lang' => 'ACL_U_NEW_EVENT', 'cat' => 'misc');
        $permissions['m_calendar'] = array('lang' => 'ACL_M_CALENDAR', 'cat' => 'misc');

        $permissions['u_self_edit'] = array('lang' => 'ACL_U_SELF_EDIT', 'cat' => 'misc');
        $permissions['u_self_delete'] = array('lang' => 'ACL_U_SELF_DELETE', 'cat' => 'misc');
        $permissions['u_event_report'] = array('lang' => 'ACL_U_EVENT_REPORT', 'cat' => 'misc');

        $permissions['u_self_lock'] = array('lang' => 'ACL_U_SELF_LOCK', 'cat' => 'misc');
        $permissions['u_event_invite'] = array('lang' => 'ACL_U_EVENT_INVITE', 'cat' => 'misc');
        $permissions['u_event_invite_self'] = array('lang' => 'ACL_U_EVENT_INVITE_SELF', 'cat' => 'misc');

        $permissions['u_event_comment'] = array('lang' => 'ACL_U_EVENT_COMMENT', 'cat' => 'misc');
        $permissions['u_calendar'] = array('lang' => 'ACL_U_CALENDAR', 'cat' => 'misc');

        $event['permissions'] = $permissions;
    }
}
