<?php  //$Id: upgrade.php,v 1.1.8.1 2008/05/01 20:39:47 skodak Exp $

// This file keeps track of upgrades to 
// the booking module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_booking_upgrade($oldversion) {

    global $CFG, $DB;

	$dbman = $DB->get_manager(); /// loads ddl manager and xmldb classes
    
    if ($oldversion < 2011020401) {

    /// Rename field text on table booking to text
        $table = new xmldb_table('booking');
        $field = new xmldb_field('text', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null, 'name');

    /// Launch rename field text
        $dbman->rename_field($table, $field, 'intro');

    /// booking savepoint reached
        upgrade_mod_savepoint(true, 2009042000, 'booking');
    }

    if ($oldversion < 2011020401) {

    /// Rename field format on table booking to format
        $table = new xmldb_table('booking');
        $field = new xmldb_field('format', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'intro');

    /// Launch rename field format
        $dbman->rename_field($table, $field, 'introformat');

    /// booking savepoint reached
        upgrade_mod_savepoint(true, 2009042001, 'booking');
        
        // Define field bookingpolicyformat to be added to choice
        $table = new xmldb_table('booking');
        $field = new xmldb_field('bookingpolicyformat', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0', 'bookingpolicy');

        // Conditionally launch add field completionsubmit
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // choice savepoint reached
        upgrade_mod_savepoint(true, 2010101300, 'booking');
    }
    if ($oldversion < 2011020403) {
       // Define field bookingpolicyformat to be added to choice
        $table = new xmldb_table('booking_options');
        $field = new xmldb_field('descriptionformat', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0', 'description');

        // Conditionally launch add field completionsubmit
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    	
    }
    return true;
}

?>