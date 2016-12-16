<?php

/**
 * Application Messages
 */

return [

    // General
    'nothingToUpdate' => 'You must provide at least one field to update the record.',

    // Auth
    'notAdmin' => "Sorry, you don't have privileges to view this page.",
    'authFailed' => "Sorry, your username and password don't match our records.",

    // Users
    'userNotFound' => "Sorry, we could not find that user.",
    'userRoleNotFound' => "Sorry, we could not find that user role.",
    'userExists' => "Sorry, a user with the email :email already exists.",
    'userUpdated' => "Success! User :email has been updated.",
    'userCreated' => "Success! User :email has been created.",
    'userDeleted' => "Success! User has been deleted.",
    'userDeleteYoureADummy' => "Oh no! You almost deleted yourself. Maybe don't do that?",

    // Haulers
    'haulerInvalidEmail' => 'Email must be comma-delimited string or an array.',
    'haulerValidationErrors' => 'The following fields are required to create a new Hauler: :fields',
    'haulerNotFound' => 'Unable to locate a Hauler with matching id: :id',
    'haulerCreated' => 'The Hauler was successfully created.',
    'haulerUpdated' => 'The changes to the Hauler were successfully saved.',
    'haulerExists' => 'A Hauler with that name already exists in that city.',
    'haulerDeleted' => 'The Hauler has been deleted.',
    'haulerArchived' => 'Tha Hauler has been archived.',

    // Leads
    'invalidEmailAddress' => 'The email address :email is not a valid email.',
    'leadValidationErrors' => 'The following fields are required to create a new Lead: :fields',
    'leadExists' => 'A lead already exists for that address.',
];
