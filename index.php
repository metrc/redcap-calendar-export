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

$month = htmlspecialchars($_GET['month'], ENT_QUOTES);
$year = htmlspecialchars($_GET['year'], ENT_QUOTES);
?>

    <h4>Calendar Export to ICS Format</h4>
    Click events you want to export to as an .ics file
    <hr/>
<form method="get">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_REQUEST['id'], ENT_QUOTES) ?>"/>
    <input type="hidden" name="page" value="<?php echo htmlspecialchars($_REQUEST['page'], ENT_QUOTES) ?>"/>
    <input type="hidden" name="pid" value="<?php echo htmlspecialchars($_REQUEST['pid'], ENT_QUOTES) ?>"/>
    <input type="hidden" name="prefix" value="<?php echo htmlspecialchars($_REQUEST['prefix'], ENT_QUOTES) ?>"/>

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
$results = CalendarExport::getEvents($month, $year, PROJECT_ID);
print('<form method="post">');

// @TODO: add javascript powered 'select all' / 'select none' links
print('<button type="button" onclick="checkAll();">Select All</button>&nbsp;&nbsp;&nbsp;<button type="button" onclick="uncheckAll();">Unselect All</button><br/><br/>');



while($result = db_fetch_assoc($results)) {
    if (CalendarExport::hasAccessToDAG($_SESSION['username'], PROJECT_ID, $result['group_id']))
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