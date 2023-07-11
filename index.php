<?php
namespace METRC\CalendarExport;
if ($_REQUEST['cal_id']) {
    require_once 'downloadics.php';
    die();
}

require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

if (!isset($_GET['year'])) {
    $_GET['year'] = date("Y");
}
if (!isset($_GET['month'])) {
    $_GET['month'] = date("n");
}

$month = $_GET['month'];
$year = $_GET['year'];
?>

    <h4>Calendar Export to ICS Format</h4>
    Click events you want to export to as an .ics file
    <hr/>
<form method="get">
    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>"/>
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
    <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"/>
    <input type="hidden" name="prefix" value="<?php echo $_REQUEST['prefix'] ?>"/>

    Month: <select name="month">
        <?php
            for($i = 1; $i <= 12; $i++) {
                $selected = ($i == $month) ? ' selected="selected"' : null;
                printf('<option value="%d"%s>%1$d</option>', $i, $selected);
            }
        ?>
    </select>  Year: <select name="year">
        <?php
        for($i = 2021; $i <= 2046; $i++) {
            $selected = ($i == $year) ? ' selected="selected"' : null;
            printf('<option value="%d"%s>%1$d</option>', $i, $selected);
        }
        ?>
    </select>
    <button type="submit">Update Events</button>
</form>


<hr/>
<?php
$results = CalendarExport::getEvents($month, $year, $_REQUEST['pid']);
print('<form method="post">');

// @TODO: add javascript powered 'select all' / 'select none' links
print('<button type="button" onclick="checkAll();">Select All</button>&nbsp;&nbsp;&nbsp;<button type="button" onclick="uncheckAll();">Unselect All</button><br/><br/>');



while($result = db_fetch_assoc($results)) {
    if (CalendarExport::hasAccessToDAG($_SESSION['username'], $_REQUEST['pid'], $result['group_id']))
        CalendarExport::generateCheckbox($result);
}
print('<p><button type="submit">Download Events as ICS</button></p></form>');
?>

<script>
    function checkAll() {
        $("input[type=checkbox]").prop("checked", true);
    }
    function uncheckAll() {
        $("input[type=checkbox]").prop("checked", false);
    }
</script>


<?php
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';