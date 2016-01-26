Simple File Based Search  with Sample Files
===========================================

clone this repository

`php composer.phar require beecms/search-bundle`

`php composer.phar update`

`./bin/console search:setup`



The above command will create db update file name and its content to db 
to use db based search. By default file based search is configured. It can be 
changed by updating service id in service.yml.

Whats Next?
-----------

Caching on file based content array, for performance improvement in file based search.
Working on rest api for this search. Will soon release it...

Other useful available commands
--------------------------------

`./bin/console search:generate-index`

Above command will clear existing db index from db and index again.

`./bin/console search:setup`

Above symfony 2 command will create tables in mysql and will run index command.

Please check 2nd command before running for better understanding.

Hope it helps someone...