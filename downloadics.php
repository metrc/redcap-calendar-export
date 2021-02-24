<?php
namespace JHU\CalendarExport;
include 'ICS.php';

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=redcap_events.ics');


echo 'BEGIN:VCALENDAR' . "\r\n" .
     'VERSION:2.0' . "\r\n" .
     'PRODID:-//JHU/redcap_calendar_export//NONSGML v1.0//EN'. "\r\n" .
     'CALSCALE:GREGORIAN'. "\r\n" .
     'NAME:REDCap Events' . "\r\n";

foreach($_REQUEST['cal_id'] as $eventId) {
    $event = \JHU\CalendarExport\CalendarExport::getEvent($eventId);
    $eventInfo = \JHU\CalendarExport\CalendarExport::decodeEvent($event);


    $ics = new ICS([
        'description' => $eventInfo['string_description'],
        'dtstart' => $eventInfo['event_date'],
        'summary' => $eventInfo['string_description']
    ]);
    echo $ics->to_string();
}

echo 'END:VCALENDAR'. "\r\n";



/*
$ics = new ICS(array(
    'description' => urldecode($_REQUEST['description']),
    'dtstart' => urldecode($_REQUEST['date_start']),
    // 'dtend' => urldecode($_REQUEST['date_end']),
    'summary' => urldecode($_REQUEST['description'])
));

echo $ics->to_string();
*/