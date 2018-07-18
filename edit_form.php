<?php

class block_pushnotification_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_text', get_string('headers_app_key', 'block_pushnotification'));
        $mform->setDefault('config_text', '');
        $mform->setType('config_text', PARAM_RAW);

    }
}