<?php

require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';
?>

    <h4>Calendar Export to Outlook</h4>
    Click events you want to export to Outlook as an .ics file
    <hr/>
<?php

if (!isset($_GET['year'])) {
    $_GET['year'] = date("Y");
}
if (!isset($_GET['month'])) {
    $_GET['month'] = date("n");
}

$month = $_GET['month'];
$year = $_GET['year'];

$results = \JHU\CalendarExport\CalendarExport::getEvents($month, $year, $_REQUEST['pid']);
print('<ul>');
while($result = db_fetch_assoc($results)) {
    \JHU\CalendarExport\CalendarExport::generateForm(\JHU\CalendarExport\CalendarExport::decodeEvent($result));
}
print('</ul>');
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';