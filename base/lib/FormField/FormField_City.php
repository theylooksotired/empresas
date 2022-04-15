<?php
/**
 * @class FormFieldSelectCity
 *
 * This is a helper class to generate a textarea form field.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\App\Base
 * @version 4.0.0
 */
class FormField_City extends FormField_DefaultSelect
{

    /**
     * The constructor of the object.
     */
    public function __construct($options)
    {
        $query = 'SELECT DISTINCT city_url, city, COUNT(id) AS number_places
                    FROM ' . (new Place)->tableName . '
                    GROUP BY city_url
                    HAVING number_places > 5
                    ORDER BY city_url';
        $results = Db::returnAll($query);
        $options['value'] = ['' => '-- Seleccione --'];
        foreach ($results as $result) {
            $options['value'][$result['city']] = $result['city'];
        }
        parent::__construct($options);
    }

}
