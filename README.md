# Mautic Name Sanitizer

Very simple script to fix common issues with names on email lists.

As the emails and names come from many different sources like forms, csv files, manual input and other... it is not always possible to spot and avoid these mistakes before they hit the database.

It will fetch the entire roll of user names from Mautic database but will only update the ones that actually will need to change.

It is intended to run periodically via cron in order to keep the names always clean!

The first time you run this... it will take more time as more names will need to be updated.
The subsequent runs will take just a couple of seconds (tested with  database of 56k items and a single core machine).   
