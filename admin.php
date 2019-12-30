<?php

require_once("common.php");

# perform a cheap version of basic auth
if (!( isset($_SERVER['PHP_AUTH_USER'])
    && $_SERVER['PHP_AUTH_USER'] == "root"
    && isset($_SERVER['PHP_AUTH_PW'])
    && $_SERVER['PHP_AUTH_PW'] == "rachel")
) {
    header('WWW-Authenticate: Basic realm="RACHEL Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You need permission to view this page.';
# even thoug it works to send html (with a redirect) instead of text,
# this apparently breaks login: credentials don't stick until you
# get re-logged out without the redirect
#    echo '<html><head><title>You need permission to view this page</title>';
#    echo '</head>';
#    echo '<meta http-equiv="refresh" content="2;URL=index.php"></head>';
#    echo '<body>You need permission to view this page.</body></html>';
    exit;
}

# If we've got a list of moddirs, we update the DB to
# reflect that ordering. This all takes place as an AJAX
# request, and the success/failure is reflected in the
# "Save" button on the page.
if (isset($_GET['moddirs'])) {
    # if we don't do this, even caught db problems
    # will print to the browser (as a "200 OK"), breaking
    # our ability to signal failure
    ini_set('display_errors', '0');
    $position = 1;
    try {
        $db = getdb();
        if (!$db) { throw new Exception($db->lastErrorMsg); }
        # figure out which modules to hide
        $hidden= array();
        foreach (explode(",", $_GET['hidden']) as $moddir) {
            $hidden[$moddir] = 1;
        }
        # go to the DB and set the new order and new hidden state
        foreach (explode(",", $_GET['moddirs']) as $moddir) {
            $moddir = $db->escapeString($moddir);
            if (isset($hidden[$moddir])) { $is_hidden = 1; } else { $is_hidden = 0; }
            $rv = $db->exec(
                "UPDATE modules SET position = '$position', hidden = '$is_hidden'" .
                " WHERE moddir = '$moddir'"
            );
            if (!$rv) { throw new Exception($db->lastErrorMsg()); }
            ++$position;
        }
    } catch (Exception $ex) {
        error_log($ex);
        header("HTTP/1.1 500 Internal Server Error");    
        exit;
    }
    header("HTTP/1.1 200 OK");    
    exit;

# We also allow shutting down the server so as to avoid
# damaging the SD/HD. This requires that www-data has
# sudo access to /sbin/shutdown, which should be set up
# automatically during rachelpiOS installation
} else if (isset($_POST['shutdown'])) {
    exec("sudo /sbin/shutdown now", $exec_out, $exec_err);
    if ($exec_err) {
        echo 'Unable to shutdown server.';
    } else {
        echo 'The server going down now.';
    }
    exit;
} else if (isset($_POST['reboot'])) {
    exec("sudo /sbin/shutdown -r now", $exec_out, $exec_err);
    if ($exec_err) {
        echo 'Unable to reboot server.';
    } else {
        echo 'The server rebooting now.';
    }
    exit;
}

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>TechSpace Admin</title>
<link rel="stylesheet" href="css/normalize-1.1.3.css">
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/mdb.min.css">
<style>
    body { 
        margin: 0;
        padding: 0;
    }
    button { margin: 3px; padding: .25em 1em; }
    .ui-icon { background-image: url(css/ui-lightness/images/ui-icons_ef8c08_256x240.png); }
    #sortable { 
        list-style-type: none; 
        margin: 0; 
        padding: 0; }
    #sortable li {
        margin: 0 3px 3px 3px;
        padding-top: 20px;
        padding: .25em;
        padding-left: 1.5em;
        background-color: #fff !important;
        height: 1em; width: 40em;
        overflow: hidden;
        position: relative;
    }
    #sortable li span { position: absolute; margin-left: -1.3em; }
    #sortable .checkbox { position: absolute; right: 10px; top: 5px; font-size: small; color: gray; }
    .error { border: 1px solid #c00; background: #fee; color: #c00; padding: 10px; }
    .error h2, .error h3 { margin: 0 0 10px 0; }
    .error p { margin: 0 }
</style>
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script>
    $(function() {
        $( "#sortable" ).sortable({
            change: function(event, ui) {
                $("button").css("color", "");
                $("button").html("Save Changes");
                $("button").prop("disabled", false);
            }
        });
        $( "#sortable" ).disableSelection();
        $(":checkbox").change( function() {
                $("button").css("color", "");
                $("button").html("Save Changes");
                $("button").prop("disabled", false);
        });
    });
    function saveState() {
        $("button").html("Saving...");
        $("button").prop("disabled", true);
        var ordered = $("#sortable").sortable("toArray");
        var hidden = [];
        for (var i = 0; i < ordered.length; ++i) {
            if ($("#"+ordered[i]+"-hidden").prop("checked")) {
                hidden.push(ordered[i]);
            }
        }
        //alert("admin.php?moddirs=" + ordered.join(",") + "&hidden=" + hidden.join(","));
        $.ajax({
            url: "admin.php?moddirs=" + ordered.join(",")
                + "&hidden=" + hidden.join(","),
            success: function() {
                $("button").css("color", "green");
                $("button").html("&#10004; Saved");
            },
            error: function() {
                $("button").css("color", "#c00");
                $("button").html("X Not Saved - Internal Error");
            }
        });
    }
</script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark indigo">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="images/logo.jpg" style="heigt: 70px; width: 100px;"></a>
    </div>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="home.php">index</a> &bull;
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo "http://x:x@$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">logout</a>
        </li>
    </ul>
</nav>
<?php

$basedir = "modules";

# if there's no modules directory, we can't do anything
if (is_dir($basedir)) {

    # at this point, checking the db is just informational
    # -- and we warn about it at the top - but later we
    # will try to actually read/write to the DB and we
    # will need to check this before doing those
    $db = getdb();
    if (!$db) {
        echo "<div class=\"error\">\n";
        echo "<h2>Couldn't Open Database</h2>\n";
        echo "<h3>You probably need to <tt>chmod 777</tt>\n";
        echo "the web root directory</h3>\n";
        echo "<p>Until you do, saving the sort order and hiding modules\n";
        echo "will not work.<br>Everything in the modules directory will show\n";
        echo "up in alphabetical order\n</p></div>";
    } else {
        # we do a test write so we can signal problems to the user
        $rv = $db->exec("CREATE TABLE writetest (col INTEGER)");
        if (!$rv) {
            echo "<div class=\"error\">\n";
            echo "<h2>Couldn't Write To Database</h2>\n";
            echo "<h3>You probably need to <tt>chmod 666 admin.sqlite</tt>\n";
            echo "and <tt>chmod 777</tt> the web root directory</h3>\n";
            echo "<p>Until you do, saving the sort order and hiding modules\n";
            echo "will not work.<br>Everything in the modules directory will show\n";
            echo "up in alphabetical order\n</p></div>";
        } else {
           $db->exec("DROP TABLE writetest"); 
        }
    }

    $fsmods = getmods_fs();
    $dbmods = getmods_db();

    foreach (array_keys($dbmods) as $moddir) {
        if (isset($fsmods[$moddir])) {
            $fsmods[$moddir]['position'] = $dbmods[$moddir]['position'];
            $fsmods[$moddir]['hidden'] = $dbmods[$moddir]['hidden'];
        }
    }

    # sorting function in the common.php module
    uasort($fsmods, 'bypos');

    # display the sortable list
    $disabled = " disabled";
    $found_nohtmlf = false;
    echo "<p>Found in /modules/:</p><ul id=\"sortable\">\n";
    // echo "<div class=\"row\">\n";
    foreach (array_keys($fsmods) as $moddir) {
        if ($fsmods[$moddir]['nohtmlf']) {
            $found_nohtmlf = true;
            continue;
        }
        
        echo "<div id=\"$moddir\" class=\"col-md-6 mt-3\">\n";
        echo "<div id=\"$moddir\" class=\"card\">\n";
        echo "<li id=\"$moddir\" class=\"p-5\">\n";
        if ($fsmods[$moddir]['hidden']) {
            $checked = " checked";
        } else {
            $checked = "";
        }
        echo "\t<span class=\"checkbox\"><input type=\"checkbox\" id=\"$moddir-hidden\"$checked>\n";
        echo "\t<label for=\"$moddir-hidden\">hide</label></span>\n";
        echo "\t<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>\n";
        echo "\t$moddir - " . $fsmods[$moddir]['title'];
        if ($fsmods[$moddir]['position'] < 1) {
            echo "<small style=\"color: green;\">(new)</small>\n";
            $disabled = "";
        }
        echo "</li>\n";
        echo "</div>\n";
        echo "</div>\n";
    }
    // echo "</div>\n";
    echo "</ul>\n";
    
    echo "<button class=\"btn btn-primary\" onclick=\"saveState();\"$disabled>Save Changes</button>\n";

    if ($found_nohtmlf) {
        echo "<h3>The following modules were ignored because they had no index.htmlf</h3><ul>\n";
        foreach (array_keys($fsmods) as $moddir) {
            if ($fsmods[$moddir]['nohtmlf']) {
                echo "<li> $moddir </li>\n";
            }
        }
        echo "</ul>\n";
    }

    # we update the db with whatever we've seen in the filesystem
    if ($db) {

        # insert anything we found in the fs that wasn't in the db
        foreach (array_keys($fsmods) as $moddir) {
            if (!isset($dbmods[$moddir])) {
                $db_moddir =   $db->escapeString($moddir);
                $db_title  =   $db->escapeString($fsmods[$moddir]['title']);
                $db_position = $db->escapeString($fsmods[$moddir]['position']);
                $db->exec(
                    "INSERT into modules (moddir, title, position, hidden) " .
                    "VALUES ('$db_moddir', '$db_title', '$db_position', '0')"
                );
            }
        }

        # delete anything from the db that wasn't in the fs
        foreach (array_keys($dbmods) as $moddir) {
            if (!isset($fsmods[$moddir])) {
                $db_moddir =   $db->escapeString($moddir);
                $db->exec("DELETE FROM modules WHERE moddir = '$db_moddir'");
            }
        }

    }

} else {

    echo "<h2>No module directory found.</h2>\n";

}

# Totally separate from module management, we also offer
# a shutdown option for raspberry pi systems (which otherwise
# might corrupt themselves when unplugged)
if (file_exists("/usr/bin/raspi-config")) {
    echo '
        <div style="margin: 50px 0 50px 0; padding: 10px; border: 1px solid red; background: #fee;">
        <form action="admin.php" method="post">
        <input type=submit name="shutdown" value="Shutdown System" onclick="if (!confirm(\'Are you sure you want to shut down?\')) { return false; }">
        <input type=submit name="reboot" value="Reboot System" onclick="if (!confirm(\'Are you sure you want to reboot?\')) { return false; }">
        </form>
        <p>Shutting down here is safer for the SD/HD than simply unplugging the power.</p>
        <p>If you shut down (as opposed to reboot), you will need to unplug your system and plug it back in to restart.</p>
        </div>
    ';
}

?>

</body>
</html>
