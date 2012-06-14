<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Overloaded CI Unit Test class
 *
 * @author Istvan Pusztai
 * @version $Id: MY_Unit_test.php 6 2009-09-17 07:47:35Z Istvan $
 * @copyright Copyright (C) 2009 Istvan Pusztai (twitter.com/istvanp)
 **/
 
class MY_Unit_test extends CI_Unit_Test
{
	var $CI;

	function MY_Unit_test()
	{
		parent::CI_Unit_Test();
		
		$this->CI =& get_instance();
	}
	
	/**
	 * Run the tests
	 *
	 * Runs the supplied tests
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @param	bool
	 * @return	string
	 */	
	function run($test, $expected = TRUE, $test_name = 'undefined', $is_model = NULL)
	{
		if ($this->active == FALSE)
		{
			return FALSE;
		}
	
		if (in_array($expected, array('is_string', 'is_bool', 'is_true', 'is_false', 'is_int', 'is_numeric', 'is_float', 'is_double', 'is_array', 'is_null'), TRUE))
		{
			$expected = str_replace('is_float', 'is_double', $expected);
			$result = ($expected($test)) ? TRUE : FALSE;	
			$extype = str_replace(array('true', 'false'), 'bool', str_replace('is_', '', $expected));
		}
		else
		{
			if ($this->strict == TRUE)
				$result = ($test === $expected) ? TRUE : FALSE;	
			else
				$result = ($test == $expected) ? TRUE : FALSE;	
			
			$extype = gettype($expected);
		}
		
		$sql_error = '';
		$sql_query = '';
				
		if ($is_model === NULL && strripos($test_name, "model") !== FALSE)
		{
			$is_model = TRUE;
		}
		
		if ($is_model == TRUE && $result === FALSE)
		{
			$sql_error = $this->CI->db->_error_message();
			
			if (empty($sql_error))
			{
				$sql_error = "Query affected " . $this->CI->db->affected_rows() . " row(s)";
			}
			
			$sql_query = $this->CI->db->last_query();
		}
		
		$back = $this->_backtrace();
	
		$report[] = array (
							'test_name'			=> $test_name,
							'test_datatype'		=> gettype($test),
							'test_value'		=> $test,
							'res_datatype'		=> $extype,
							'res_value'			=> $expected,
							'res_sql_error'		=> $sql_error,
							'res_sql_query'		=> $sql_query,
							'result'			=> ($result === TRUE) ? 'passed' : 'failed',
							'file'				=> $back['file'],
							'line'				=> $back['line']
						);

		$this->results[] = $report;		
				
		return($this->report($this->result($report)));
	}	
}
/* End of file MY_Unit_test.php */