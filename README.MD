### How to run
You need to have installed PHP 7.4, [Symfony](https://symfony.com/download)
and [Composer](https://getcomposer.org/doc/00-intro.md) in your local machine.

And then run the following commands:

`composer install`

`symfony server:start`

And then check result to get the URL for your
local enviroment.


### How to use
For the basic usage you just need to go into
`Survey Maintenance` menu and insert a new survey
, which then will enable you to go into `Vote for
the next lunch` and do what the menu says! One email
can vote just once per each survey, and options of
restaurants are removed during the week if they are
chosen in one day. For ending a vote process go
back to the `Survey Maitenance` and set that survey to inactive.
If you are getting to much used to the Restaurants in the list
just go to the `Restaurant` menu and add new ones.

### Things to notice
I tried to use the less code repetition as possible
and for that I wrote a few abstract classes mainly
for the controller and CRUD process. I also used
Doctrine as an ORM for this project which made very
easy for add a database, and makes your life
even more easy when you use
the SQLite basic file database ( which also makes
easier to share this project reducing database configs).
