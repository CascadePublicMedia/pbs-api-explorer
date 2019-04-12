# PBS API Explorer

This package provides a simple Symfony app for syncing, viewing, and exporting 
data from various PBS API services.

## Installation (development)

The application is configured for a dev environment using sqlite out of the box.
Following the steps below to run the development server:

1. `composer create-project cascadepublicmedia/pbs-api-explorer pbs-api-explorer`
1. `cd pbs-api-explorer`
1. `php bin/console app:init`
    * Prompts will be provided to create an initial user, be sure to give the 
    user the `ROLE_ADMIN` role.
1. `php bin/console server:run`

The console will output the URL of the app. Visit the URL and login with the 
user created during setup.

## Configuration

1. Log in (with a user with the `ROLE_ADMIN` role).
1. Visit `/settings`.
1. Fill in and save API endpoints and credentials.
1. Visit an API data list page (e.g. `/station-manager/stations`) and click the
"Sync..." button.

## API Support TODOs

*   [~~**Station Manager**~~](https://docs.pbs.org/display/SMA)
    *   [~~Stations (internal)~~](https://docs.pbs.org/display/SM/Station+Manager+Internal+API)
    *   [~~Stations (public)~~](https://docs.pbs.org/display/SM/Station+Manager+Public+API)
*   [**Media Manager**](https://docs.pbs.org/display/CDA)
    *  ~~Genres~~
    *  [~~Franchises~~](https://docs.pbs.org/display/CDA/Franchises)
    *  [~~Shows~~](https://docs.pbs.org/display/CDA/Shows)
    *  [Seasons](https://docs.pbs.org/display/CDA/Seasons)
    *  [Episodes](https://docs.pbs.org/display/CDA/Episodes)
    *  [Specials](https://docs.pbs.org/display/CDA/Specials)
    *  [Assets](https://docs.pbs.org/display/CDA/Assets)
    *  [Topics](https://docs.pbs.org/display/CDA/Topics) (???)
*   [**TV Schedules Service**](https://docs.pbs.org/display/tvsapi)
    *   Listings
    *   KIDS listings (???)
    *   Channels/feeds
    *   Programs list
*   [**Membership Vault**](https://docs.pbs.org/display/MV/Membership+Vault+API)
    *   Memberships
*   [**Transaction Vault**](https://docs.pbs.org/display/TVA/Transaction+Vault+API)
    *   Transactions
