<?php

/**
 * Application Messages
 */

return [

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
];
