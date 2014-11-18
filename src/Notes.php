<?php
namespace loicm\Forests;


class Notes
{
    public static function groupByYear($notes)
    {
        $notes_by_year = array();

        foreach($notes as $note) {
            $path_parts = pathinfo($note);
            list($year, $month, $day) = explode(
                '/',
                preg_replace(
                    '/^.+\/notes\//',
                    '',
                    $path_parts['dirname']
                )
            );
            $notes_by_year[$year][] = $note;
        }

        return $notes_by_year;
    }
}