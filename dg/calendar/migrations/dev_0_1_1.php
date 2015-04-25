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
	public function effectively_installed()
	{
		return isset($this->config['calendar_dg']);
	}

	static public function depends_on()
	{
		return array('\dg\calendar\migrations\dev_0_1_0');
	}
  
 	public function update_data()
	{
		return array(
			array('permission.add', array('u_self_edit')),
			array('permission.add', array('u_self_delete')),
			array('permission.add', array('u_event_report')),

			array('config.update', array('calendar_dg_version', '0.1.1')),
		);
	}
  
  	public function revert_data()
	{
		return array(
			array('permission.remove', array('m_calendar')),
     		array('permission.remove', array('u_new_event')),
		);
	}
  
  	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'calendar_events'
			),
		);
	}
}
