<?php

namespace MetarDecoder\Service;

use MetarDecoder\Exception\DatasetLoadingException;

class DatasetProvider
{
    private $base_dir;

    private $delimiter;

    public function __construct($base_dir = './', $delimiter = ';')
    {
        $this->base_dir = $base_dir;
        $this->delimiter = $delimiter;
    }

    /**
     * Get a dataset (php associative array) built from a csv file
     * Catefory (in/out) types and names are extracted from the first 3 lines of the csv
     */
    public function getDataset($csv_file_path)
    {
        // init objects to build
        $dataset_definition = array();
        $dataset = array();

        // open file and loop on the lines to convert dataset as a PHP array
        if (($handle = fopen($this->base_dir.'/'.$csv_file_path, "r")) !== FALSE) {
            $row_id = 1;
            $dataset_row_id = 1;
            while (($line = fgetcsv($handle, 2000, $this->delimiter)) !== FALSE) {
                switch ($row_id) {
                    // cases 1, 2, 3 : build dataset definition
                    case 1:
                        $this->populateDefinition('name', $line, $dataset_definition);
                        break;
                    case 2:
                        $this->populateDefinition('category', $line, $dataset_definition);
                        break;
                    case 3:
                        $this->populateDefinition('type', $line, $dataset_definition);
                        break;
                    default:
                        // build dataset content: for each value, cast to correct type and add to final dataset
                        foreach ($line as $id => $data) {
                            $name = $dataset_definition[$id]['name'];
                            $category = $dataset_definition[$id]['category'];
                            $type = $dataset_definition[$id]['type'];
                            $typed_data = $this->evalToType($data, $type);
                            $dataset[$dataset_row_id][$category][$name] = $typed_data;
                        }
                        $dataset_row_id++;
                        break;
                }
                $row_id++;
            }
            fclose($handle);
        }

        return $dataset;
    }

     /**
     * Populate the definition array for the given key
     */
    private function populateDefinition($key, $line, &$dataset_definition)
    {
        $definitions = array();
        foreach ($line as $id => $data) {
            $checker_name = 'check'.ucfirst($key);
            $clean_data = $this->$checker_name(strtolower($data));
            $definitions[$id] = $clean_data;
        }

        foreach ($definitions as  $id => $definition) {
            $dataset_definition[$id][$key] = $definition;
        }
    }

    /**
     * Check name declaration: [a-z_0-9] only
     * Replace spaces by _
     * Throws an exception if invalid format
     */
    private function checkName($value)
    {
        $value_without_spaces = preg_replace('# #', '_', $value);
        if (! preg_match('#^[a-z_0-9]+$#', $value_without_spaces)) {
            throw new DatasetLoadingException('Invalid name format: "'.$value_without_spaces.'". Expected chars, numbers and _ ');
        }

        return $value_without_spaces;
    }

    /**
     * Check validity of category declaration
     * Throws an exception if invalid
     */
    private function checkCategory($value)
    {
        $categories = array(
            'input',
            'expected',
        );
        if (!in_array($value, $categories)) {
            throw new DatasetLoadingException('Invalid category: "'.$value.'". Expected: '.implode(',', $categories));
        }

        return $value;
    }

    /**
     * Check validity of type declaration
     * Throws an exception if invalid
     */
    private function checkType($value)
    {
        $types = array(
            'int',
            'string',
            'bool',
            'float',
            'json',
        );
        if (!in_array($value, $types)) {
            throw new DatasetLoadingException('Invalid type :"'.$value.'". Expected: '.implode(',', $types));
        }

        return $value;
    }

    /**
     * Convert a value to its type
     * Throw an exception if the format is invalid
     */
    private function evalToType($value, $type)
    {
        if ($value == '-') {
            return;
        }
        if ($type == 'bool' && $value == '') {
            $value = 'no';
        }
        if ($type == 'json') {
            $value = json_decode($value);
            if ($value === null) {
                throw new DatasetLoadingException('Invalid '.$type.' format for value: "'.$value.'"');
            }
        }
        $types = array(
            'bool'   => FILTER_VALIDATE_BOOLEAN,
            'int'    => FILTER_VALIDATE_INT,
            'float'  => FILTER_VALIDATE_FLOAT,
        );
        if (array_key_exists($type, $types)) {
            $eval = filter_var($value, $types[$type], FILTER_NULL_ON_FAILURE);
            if ($eval === null) {
                throw new DatasetLoadingException('Invalid '.$type.' format for value: "'.$value.'"');
            }
        } else {
            $eval = $value;
        }

        return $eval;
    }
}
