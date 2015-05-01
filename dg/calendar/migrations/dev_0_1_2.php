<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\migrations;

class dev_0_1_1 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\dg\calendar\migrations\dev_0_1_1');
	}
  
 	public function update_data()
	{
		return array(
			array('permission.add', array('u_self_lock')),
			array('permission.add', array('u_event_invite')),
		
			array('config.update', array('calendar_dg_version', '0.1.2')),
		);
	}
	
	public function update_schema()
	{
		return array(
		);
	}
  
  	public function revert_data()
	{
		return array(
			array('permission.remove', array('u_self_lock')),
			array('permission.remove', array('u_event_invite')),
		);
	}
  
  	public function revert_schema()
	{
		return array(
		);
	}
}
