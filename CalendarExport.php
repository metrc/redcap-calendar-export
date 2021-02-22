<?php
namespace JHU\CalendarExport;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;


class CalendarExport extends AbstractExternalModule {

    static function getEvents($month, $year, $pid) {
        return db_query(sprintf('SELECT * FROM redcap_events_calendar WHERE MONTH(event_date) = %d AND YEAR(event_date) = %d AND project_id = %d',
            $month, $year, $pid));
    }

    static function getEvent($cal_id) {
        return db_fetch_assoc(db_query(sprintf('SELECT * FROM redcap_events_calendar WHERE cal_id = %d', $cal_id)));
    }


    static function decodeEvent($eventResult) {
        $returnResults = $eventResult;

        // get project information
        $returnResults['project_name'] = db_fetch_assoc(db_query(sprintf('SELECT app_title FROM  redcap_projects WHERE project_id = %d',
            $eventResult['project_id'])))['app_title'];

        // get event information
        $returnResults['event_description'] = db_fetch_assoc(db_query(sprintf('SELECT descrip FROM  redcap_events_metadata WHERE event_id = %d',
            $eventResult['event_id'])))['descrip'];

        // get group information
        $returnResults['group_name'] = db_fetch_assoc(db_query(sprintf('SELECT group_name FROM  redcap_data_access_groups WHERE group_id = %d',
            $eventResult['group_id'])))['group_name'];

        if ($eventResult['record']) {
            // this is an event that is connected to a record
            $returnResults['string_description'] = sprintf('%s - %s : %s for record %d [%s]', $eventResult['event_date'], $returnResults['project_name'],
                $returnResults['event_description'], $eventResult['record'], $returnResults['group_name']);
        } else {
            // general project task
            $returnResults['string_description'] = sprintf('%s - %s : %s', $eventResult['event_date'], $returnResults['project_name'], $returnResults['notes']);
        }


        return $returnResults;
    }

    static function generateCheckbox($event) {
        $eventInfo = self::decodeEvent($event);

        printf('<label><input type="checkbox" name="cal_id[]" value="%d">&nbsp;%s</label><br/>', $eventInfo['cal_id'], $eventInfo['string_description']);
    }

    static function hasAccessToDAG($user, $project, $dag) {
        $sql = sprintf('SELECT group_id FROM redcap_user_rights WHERE project_id = %d AND username = "%s"', $project, $user);
        $queryResult = db_query($sql);
        if (db_num_rows($queryResult) == 1) return true;    // user has access to all groups
        $resultArray = db_fetch_assoc($queryResult);
        foreach($resultArray as $result) {
            if ($result['group_id'] == $dag) return true;
        }
        return false;
    }

}