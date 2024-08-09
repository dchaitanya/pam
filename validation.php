<?php

    function is_not_empty($val) {
        if (empty($val) && !is_numeric($val))
            return false;
        return true;
    }

    function is_valid_name($name) {
        if (is_not_empty($name))
            if (preg_match('/^[A-Za-z ]*$/', $name))
                return true;
        return false;
    }

    function is_valid_number($number) {
        if (is_not_empty($number))
            if (is_numeric($number))
                if (is_gt_than_zero($number))
                    return true;
        return false;
    }

    function is_valid_date($date) {
        if (is_not_empty($date)) {
            $date_para = explode('-', $date);
            if (count($date_para) == 3)
                if (checkdate($date_para[1], $date_para[2], $date_para[0]))
                    return true;
        }
        return false;
    }

    function is_gt_than_zero($number) {
        if ($number > 0)
            return true;
        return false;
    }

    function is_number($number) {
        if (is_not_empty($number) && is_numeric($number)) {
            return true;
        }
        return false;
    }

?>
