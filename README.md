# Extendmate - Login Tracker Bundle For Pimcore

The Bundle offers comprehensive user login tracking functionalities within Pimcore. It efficiently captures login-related details of Pimcore users, including:

1. **User ID**: Unique identifier assigned to the user.
1. **Username**: Name used by the user for login purposes.
1. **Roles**: Represents the user's assigned roles at the time of login.
1. **IP Address**: The IP address from which the user logged in.
1. **isAdmin**: Indicates whether the user is an admin or not.
1. **Login At**: Datetime of the user's login attempt.
1. **Logout At**: Datetime of the user's logout attempt.
1. **Last Seet At**: Datetime indicating the user's last activity.
1. **User Agent**: User's browser or application details.
1. **Firewall Name**: Specific firewall used during the login.
1. **Login Status**: Monitors login/logout/fail/error statuses. It registers as 'login' for successful logins, 'logout' for successful logouts, 'fail' for incorrect credentials, and 'error' when multiple failed login attempts occur.

## Requirements

- Pimcore 11.x

(Looking for Pimcore 5.x bundle? [Click Here](https://github.com/extendmategit/pimcore-bundle-user-login-history))


## Installation

To install the bundle, execute the following commands:

1. To install the Composer package, execute the following command:
```command
composer require extendmate/pimcore-bundle-login-tracker
```
2. Open the `/config/bundle.php` file and include the following line to enable the bundle::
```php
Extendmate\Pimcore\LoginTracker\ExtendmateLoginTrackerBundle::class => ['all' => true]
```
3. Install the bundle by running the command:
```command
./bin/console pimcore:bundle:install ExtendmateLoginTrackerBundle
```
4. (Optional) To explore available configurations for the bundle, run:
```command
./bin/console config:dump-reference ExtendmateLoginTrackerBundle
```
You can customize these settings by overriding them in your `config.yaml` file.

5. You're all set! Re-login to observe user login attempts within the **ExtendmateLoginTracker-AllLoginAttempts** report section.
For viewing the reports, ensure the **PimcoreCustomReportsBundle** is enabled.



## Update
To update the bundle, execute the following commands:

1. Update the bundle using Composer:
```command
composer update extendmate/pimcore-bundle-login-tracker
```
2. Run the migrations with the specified prefix:
```command
./bin/console doctrine:migrations:migrate --prefix=Extendmate\\Pimcore\\LoginTrackerBundle 
```

## Uninstallation
To uninstall the bundle, perform the following steps:

1. Uninstall the bundle via Pimcore console:
```command
./bin/console pimcore:bundle:uninstall ExtendmateLoginTrackerBundle
```
2. Disable the bundle by removing the following line from `/config/bundle.php`:
```php
Extendmate\Pimcore\LoginTracker\ExtendmateLoginTrackerBundle::class => ['all' => true]
```
3. Lastly, eliminate any bundle-related configurations from your config.yaml file, if present.


## Suggest New Feature

Do you have an innovative idea for a new feature? We'd love to hear from you!

## Bug Fixes

Spot a bug? Kindly create an issue, providing a step-by-step description to reproduce the problem. Please search the forum before opening a new issue.

## Support Development

If you found this bundle helpful, consider donating $5 to support its ongoing enhancements and improvements.


[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/erfaiyazalam/)

## Copyright and Licensing

Copyright (C) [extendmate.com](https://extendmate.com)  
For licensing details, please visit [LICENSE.md](LICENSE.md)

## About Author

Greetings! I'm Faiyaz, the owner of extendmate.com. I have over 10 years of experience in website development.

Explore more about me on my [LinkedIn profile](https://www.linkedin.com/in/erfaiyazalam/ "Faiyaz Alam LinkedIn Profile") .

## Keywords

user login history, login history, login tracker, login log, login attempt detector, security audit trail, login monitor, login insights
