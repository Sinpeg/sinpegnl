<?php
class Validacao {
    public function is_yearempty($year) {
        return ($year == "") ? true : false;
    }
    public function is_validyear($year) {
        $pattern = "/^[2]0[0-9]{2,2}$/";
        return (preg_match($pattern, $year)) ? true : false;
    }
    public function is_nameempty($name) {
        return ($name) ? true : false;
    }
    public function is_validname($name) {
        return preg_match("/^[a-z|A-Z]{4,}$/", str_replace(' ', '', $name)) ? true : false;
    }
    public function is_date($date) {
        $part = explode("/", $date);
        return (count($part) < 3) ? (false) : (checkdate($part[1], $part[0], $part[2]));
    }
    public function is_date_matched($date1, $date2) {
        $part1 = explode("/", $date1);
        $part2 = explode("/", $date2);
        return (($part2[2] == $part1[2]) && ($part2[1] > $part1[1])) || (($part2[2] > $part1[2]) ||
                (($part1[1] == $part2[1] && $part1[2] == $part2[2]) && ($part2[0] > $part1[0]))) ? true : false;
    }
}
?>
