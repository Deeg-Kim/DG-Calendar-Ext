<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\migrations;

class dev_0_1_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['calendar_dg']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\rc6');
	}
  
  public function update_data()
	{
		return array(
    		array('permission.add', array('m_calendar')),
    
	      	array('permission.add', array('u_new_event')),
    
			array('config.add', array('calendar_dg', 0)),
			array('config.add', array('calendar_dg_version', '0.1.0')),
		);
	}

	public function update_schema()
	{
		return array(
      'add_tables' => array(
        $this->table_prefix . 'calendar_events' => array(
          'COLUMNS' => array(
            'id' => array('INT:11', NULL, 'auto_increment'),
            'user_id' => array('INT:8', 0),
            'post_time' => array('INT:11', 0),
            'month' => array('INT:2', 0),
            'day' => array('INT:2', 0),
            'year' => array('INT:4', 0),
            'start' => array('VCHAR:255', NULL),
            'end' => array('VCHAR:255', NULL),
            'title' => array('VCHAR:255', ''),
            'description' => array('TEXT', NULL),
          ),
          
          'PRIMARY_KEY' => 'id',
        ),
      ),
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
