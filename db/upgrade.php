<?php function xmldb_block_pushnotification_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2019080110) {

        // Define table block_pushnotification to be created.
        $table = new xmldb_table('block_pushnotification');

        // Adding fields to table block_pushnotification.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('idnumber', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timestamp', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('message', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_pushnotification.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_pushnotification.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Pushnotification savepoint reached.
        upgrade_block_savepoint(true, 2019080110, 'pushnotification');
    }
    return true;
}