<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	public function __construct($rules = array()) {
		parent::__construct();
	}

	public function set_rules($field, $label = '', $rules = array(), $errors = array()) {
		// No reason to set rules if we have no POST data
		// or a validation array has not been specified
		// if ($this->CI->input->method() !== 'post' && empty($this->validation_data))
		if (empty($this->validation_data))
		{
			//die;
			return $this;
		}

		// If an array was passed via the first parameter instead of individual string
		// values we cycle through it and recursively call this function.
		if (is_array($field))
		{
			foreach ($field as $row)
			{
				// Houston, we have a problem...
				if ( ! isset($row['field'], $row['rules']))
				{
					continue;
				}

				// If the field label wasn't passed we use the field name
				$label = isset($row['label']) ? $row['label'] : $row['field'];

				// Add the custom error message array
				$errors = (isset($row['errors']) && is_array($row['errors'])) ? $row['errors'] : array();

				// Here we go!
				$this->set_rules($row['field'], $label, $row['rules'], $errors);
			}

			return $this;
		}

		// No fields or no rules? Nothing to do...
		if ( ! is_string($field) OR $field === '' OR empty($rules))
		{
			return $this;
		}
		elseif ( ! is_array($rules))
		{
			// BC: Convert pipe-separated rules string to an array
			if ( ! is_string($rules))
			{
				return $this;
			}

			$rules = preg_split('/\|(?![^\[]*\])/', $rules);
		}

		// If the field label wasn't passed we use the field name
		$label = ($label === '') ? $field : $label;

		$indexes = array();

		// Is the field name an array? If it is an array, we break it apart
		// into its components so that we can fetch the corresponding POST data later
		if (($is_array = (bool) preg_match_all('/\[(.*?)\]/', $field, $matches)) === TRUE)
		{
			sscanf($field, '%[^[][', $indexes[0]);

			for ($i = 0, $c = count($matches[0]); $i < $c; $i++)
			{
				if ($matches[1][$i] !== '')
				{
					$indexes[] = $matches[1][$i];
				}
			}
		}

		// Build our master array
		$this->_field_data[$field] = array(
			'field'		=> $field,
			'label'		=> $label,
			'rules'		=> $rules,
			'errors'	=> $errors,
			'is_array'	=> $is_array,
			'keys'		=> $indexes,
			'postdata'	=> NULL,
			'error'		=> ''
		);

		return $this;
	}

}
