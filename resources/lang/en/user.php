<?php

return [
    'count'     => 'Total System Users: :count',
    'users'     => 'Users',
    'password'  => 'Password',
    'security'  => 'Security',

    'id'                => 'ID',
    'name'              => 'Name',
    '2fa_security'      => '2FA',
    'account_created'   => 'Account Created',
    'account_modified'  => 'Last Modified',
    
    'security_status'   => 'Two Factor Authentication (2FA) Status',
    '2fa'               => 'Two Factor Authentication (2FA)',
    'enable_2fa'        => 'Enable 2FA',
    'disable_2fa'       => 'Disable 2FA',
    'generate_2fa'      => 'Generate Secret Key to Enable 2FA',
    '2fa_setup_step2'   => '<span class="highlight">Scan this QR code</span> with your your Authenticator App App.',
    '2fa_setup_alt'     => 'Alternatively, you can use the code:',
    '2fa_setup_step3'   => '<span class="highlight">Enter the pin</span> from your Authenticator App app:',
    '2fa_authenticator_code' => 'Authenticator Code',
    '2fa_settings'      => 'Two Factor Authentication Settings',
    '2fa_status_long'   => '2FA is currently <strong class="font-strong">enabled</strong> on your account.',
    '2fa_key_generated' => 'Secret Key Generated',
    '2fa_enable_success' => '2FA has been enabled successfully',
    '2fa_enable_failed' => 'Invalid verification Code. Please try again',
    '2fa_disable_success' => '2FA has been disabled successfully',
    '2fa_enter_pin'     => 'Enter the pin from Google Authenticator app:',
    '2fa_otp'           => 'One Time Password',
    '2fa_authenticate'  => 'Authenticate',

    'invalid_user'      => 'Invalid or Unkown User!',

    'roles'             => 'User Roles',
    'add_role'          => 'Add Role',
    'no_roles'          => 'No Roles',
    'remove_role'       => 'Remove Role',
    'confirm_remove_button' => 'Remove Role',
    'duplicate_role'        => 'User is already in that role. Cannot add duplicate role.',
    'invalid_role'          => 'Invalid or unknown role.',
    'role_add_success'      => 'Role Added!',
    'role_del_success'      => 'Role Removed!',

    'view_all'              => 'View All Users',
    'list'                  => 'Site User List',

    'profile'               => 'Profile',
    'update_profile'        => 'Update Profile',
    'contact_settings'      => 'Contact Settings',
    'pushover_key'          => 'Pushover Key',
    'edit_profile'          => 'Edit Profile',
    'profile_updated'       => "Profile Updated",
    'profile_info'          => 'Profle Information',
    'email'                 => 'Email Address',

    '2fa_explain'       =>
        'Two Factor Authentication (2FA) strengthens security to certain parts of the site by requiring two methods
         (AKA factors) to verify your identity. Two Factor Authentication (2FA) protects against phishing, social
        engineering and password brute force attacks and secures your account from attackers exploiting weak or stolen
         credentials.',
    
    '2fa_setup_step1'   =>
        '<span class="highlight">Download an Authenticator App</span> (We recommend <a href="https://authy.com/" 
        class="link">Authy</a>).',

    '2fa_disable_explain' =>
        'If you want to <span class="highlight">disable</span> Two Factor Authentication, please 
        <span class="highlight">confirm your password and Click on the Disable 2FA Button</span>.',
    
    'remove_role_text'  => 'Remove <span class="highlight">:user</span> from the <span class="highlight">:role</span> role?',
];
