# Calendar Example with Google Calendar API, OAuth 2.0 and PHP

This example is a guide for sync a Google Calendar using PHP and OAuth 2.0

For more information please check the documentation on:

 1. [Google Calendar API](https://developers.google.com/google-apps/calendar/v3/reference/?hl=en_US)
 2. [OAuth 2.0](https://oauth.net/2/)
 3. [Google OAuth 2.0](https://developers.google.com/identity/protocols/OAuth2)

## Getting Started

These instructions will get you a copy of the project up and run on your local machine for development and testing purposes.

1. Download a GIT client as [TortoiseGIT](https://tortoisegit.org/)
2. Clone the repository from [SSH](git@gitlab.com:jasabino/CalendarSample.git) or [HTTPS](https://gitlab.com/jasabino/CalendarSample.git)

### Prerequisites

You need these programs in order to run this project:

1. XAMPP (or any PHP environment) [(Download)](https://www.apachefriends.org/es/index.html)

## Starting up the Server

1. Put the root folder in the root path folder of the web server
2. Start Xampp or Apache Server on the default port: 80
3. Write in the browser the following URL

```
http://localhost/CalendarSample/SyncCalendar.php
```

**Important:** make sure of run it locally on the port 80 and don't change the name of the folder or install in another path, because this URL was registered on the Google application

If you want to run in a different path, please follow the next instructions:

1. Go to https://console.developers.google.com/start/api?id=calendar
2. Log in and follow the steps
3. Download the JSON file
4. replace `$client_id` and `$client_secret` on SyncCalendar.php
5. replace `client_id` HTML input on SyncCalendar.php

## Explaining the example

This example gets all Google Calendars of a Google User and then it gets all events belongs to the calendars selected

Steps:
1. Click on `Show Calendars`
2. If the user is not authenticated, a google windows appear for login, and Google will ask you for permission of accessing to your calendars and events
3. A list of Calendars will be displayed
4. Select the calendars and click on `Sync`
5. A list of programmed events will be displayed (only events belongs to the next 12 months since the current day)

## Project Structure

It contains two files:

1. CalendarApi.php
> It contains the methods for getting the info from API Calendar

2. SyncCalendar.php
> This is the front HTML and performs the authentication in two steps/calls on the same file.

## Built With

* [PHP](http://php.net/) - The Programming Language
* [OAuth 2.0](https://oauth.net/2/) - The Autentication Protocol
* [Google Calendar API](https://developers.google.com/google-apps/calendar/v3/reference/?hl=en_US) - The API for sync the calendars