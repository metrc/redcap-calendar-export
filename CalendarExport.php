<?php
namespace JHU\CalendarExport;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;


class CalendarExport extends AbstractExternalModule {

    static function getEvents($month, $year, $pid) {
        return db_query(sprintf('SELECT * FROM redcap_events_calendar WHERE MONTH(event_date) = %d AND YEAR(event_date) = %d AND project_id = %d',
            $month, $year, $pid));
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

        return $returnResults;
    }

    static function generateForm($event) {
        if ($event['record']) {
            // this is an event that is connected to a record
            $stringDescription = sprintf('%s %s for record %d [%s]', $event['project_name'],
                $event['event_description'], $event['record'], $event['group_name']);
        } else {
            // general project task
            $stringDescription = sprintf('%s - %s', $event['project_name'], $event['notes']);
        }

        printf('<li><a href="../../modules/calendar_export_v1.0.0/downloadics.php?description=%s&date_start=%s">%s - %s</a></li>',
            urlencode($stringDescription), urlencode($event['event_date']), $event['event_date'], $stringDescription);


    }


}