<?php
namespace METRC\CalendarExport;
include 'ICS.php';

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=redcap_events.ics');


echo 'BEGIN:VCALENDAR' . "\r\n" .
     'VERSION:2.0' . "\r\n" .
     'PRODID:-//METRC/calendar_export//NONSGML v1.0//EN'. "\r\n" .
     'CALSCALE:GREGORIAN'. "\r\n" .
     'NAME:REDCap Events' . "\r\n";

foreach(htmlspecialchars($_REQUEST['cal_id'], ENT_QUOTES) as $eventId) {
    $event = CalendarExport::getEvent($eventId);
    $eventInfo = CalendarExport::decodeEvent($event);


    $ics = new ICS([
        'description' => $eventInfo['string_description'],
        'dtstart' => $eventInfo['event_date'],
        'summary' => $eventInfo['string_description']
    ]);
    echo $ics->to_string();
}

echo 'END:VCALENDAR'. "\r\n";