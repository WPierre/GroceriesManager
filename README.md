Groceries List Manager
======================

A simple Bundle for Symfony helping managing groceries lists : Whether you're in front of the fridge or at work, you can browse and add groceries to your lists.

When it's time to go shopping, just check the condensed list and save time !

Requires Symfony 2.6+ with the bootstrap form template activated

Setup
-----

###Symfony setup
Install symfony 2.6 (or later) and set it up with a database. If you're not familiar with Symfony, please check [Composer](https://getcomposer.org/) and [Symfony](http://symfony.com/download) documentation. Basically, you have to run 
> php composer.phar create-project symfony/framework-standard-edition GroceriesManager/ 

on your webserver. Follow the steps for installing your Symfony instance and then go to the newly created folder using
> cd GroceriesManager

###Install VirtualHost
Set up a virtual host for your server. There's a standard Apache Virtualhost file ready to be adapted in the bundle's Extra/VirtualHost folder.

###Add GroceriesManager Bundle
> php ../composer.phar require "wpierre/groceries_manager":"dev-master"

###Enable GroceriesManager in Symfony
Edit your /app/AppKernel.php and add the text below to the list of enabled bundles :
> new WPierre\GroceriesManagerBundle\WPierreGroceriesManagerBundle(),  

###Enable Scafo's routes
Edit your /app/config/routing.yml and add :
> w_pierre_groceries_manager:  
> &nbsp;&nbsp;&nbsp;&nbsp;resource: "@WPierreGroceriesManagerBundle/Resources/config/routing.yml"  
> &nbsp;&nbsp;&nbsp;&nbsp;prefix:   /  

Please mind the spaces if you're not familiar with YAML syntax (no leading space for the first line, four for the others).

###Install the assets
GroceriesManager bundle contains several assets, including Bootstrap and jQuery. You have to install them using :
> php app/console assets:install

You should see a line about GroceriesManagerBundle.

###Setup the database
Groceries Manager needs a database and you configured one while installing Symfony.
If the database already exists, please use : 
> php app/console doctrine:schema:update --force

If it doesn't exist yet, please use :
> php app/console doctrine:database:create

###Clear the caches
Run these two commands to clear your Symfony caches :
> php app/console cache:clear --env=dev
> php app/console cache:clear --env=prod

###Test it !
According to your virtual host configuration, this might change, but if you didn't change anything, open [http://localhost:8087](http://localhost:8087).  
Begin by creating categories (Fruits, Vegetables, Breakfast, Beverages...), then add items to categories and finally, create a list and start using your new tool !