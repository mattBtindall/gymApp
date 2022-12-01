To do:

[In no particular order]

. Style popover -> force the details to stay on one line.

. Sort navbar.php -> currently it doesn't look good on mobiles - the searchbar hides when it shoulnd't and the file as a whole is a bit of a mess. Need to figure out which files the modals/popovers should be in.

. Figure out how you're going to pass account information from the backend to the frontend in an efficent manner -> currently only the search bar works using an ajax call to the Users controller which then uses the User model to fetch data from the database. This is then returned to Main.js. But from here admin users should be able to click on users names and then there account information should be displayed through a modal. Should i store all the users account information in the front end? This doesn't seem secure (even though passwords have been filtered out) but it does seem rather inefficent to send another ajax call to fetch the data when this has previosuly been done.

. Memberships -> should these have there own dedicated table - should each admin account have their own database for memberships. I imagine in a large scale application this should be the case.

. Organise main.js -> really messy now that there is more code in here.

. Nav links ->
    Home: Access page - Simply display all of the users that swipe in.
    Members: Members pages - Displays all members of the gym. Brief over view: Name, membership expirym, and button to add to access page. Can click on each member and a model appears which shows more details, from here you can revoke members memberships or even bar members.