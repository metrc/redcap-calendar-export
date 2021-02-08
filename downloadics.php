<?php
include 'ICS.php';

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=invite.ics');

$ics = new ICS(array(
    'description' => urldecode($_REQUEST['description']),
    'dtstart' => urldecode($_REQUEST['date_start']),
    // 'dtend' => urldecode($_REQUEST['date_end']),
    'summary' => urldecode($_REQUEST['description'])
));

echo $ics->to_string();