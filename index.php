<?php

if ($_REQUEST['cal_id']) {
    require_once 'downloadics.php';
    die();
}

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
print('<form method="post">');
while($result = db_fetch_assoc($results)) {
    \JHU\CalendarExport\CalendarExport::generateCheckbox($result);

}
print('<p><button type="submit">Download Events as ICS</button></p></form>');

require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';