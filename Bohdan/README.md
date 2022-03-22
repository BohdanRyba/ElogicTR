Part 1
=============


1) Install module: bin/magento module:enable Bohdan_ProductScaner && bin/magento setup:upgrade
2) Import "Cart Price Rules" - **magento_salesrule.csv**
3) Import "Products" - **catalog_product.csv**
4) Run commands:
   1) ***bin/magento products:scan --products="ABCDABAA"***
        - Expeted result: 32.4 (4*2-1+24+1.25+0.15)
   2) ***bin/magento products:scan --products="CCCCCCC"***
       - Expeted result: 7.25 (6+1.25)
   3) ***bin/magento products:scan --products="ABCD"***
       - Expeted result: 15.4 (2+12+1.25+0.15)


Part 2
=============
###Steps:

- Step 1: Create module for ex.: CustomerCartExport with all needed files (module.xml, registration.php, etc.)
- Step 2: After creating the Vendor/CustomerCartExport/etc/adminhtml/system.xml file we will create a new tab for our module if needed
- Step 3: In the file Vendor/CustomerCartExport/etc/config.xml we will create a default value for our config.
- Step 4: For providing this feature for customers, we can add the custom button into the checkout page, for this, for this, we should create view\frontend\layout\checkout_cart_index.xml file
    - Where we will describe the container and our custom block, where will be placed out button.
    - Right after this we will create template for button for example: 'view/frontend/templates/export.phtml'
    - As we defined the template, need to create the block for this template.
    - The next step is creating the template behavior. For example, Block/Checkout/ExportCartItems.php where we will get configs that were created by us and all helper functions for our block (template).
    - After clicking the out button for export, we should define the behavior of this button(create the route for submitting data and feature of this export)
    - For creating the route, we should create the /etc/frontend/routes.xml with route description and Controller for this route. For example, /Controller/Checkout/Export.php
    - In this controller, we should implement the ActionInterface and realize the "execute" method
    - For exporting cart items to CSV-file, we should bind in constructor some classes.
        - Magento\Framework\File\Csv
        - Magento\Framework\App\Response\Http\FileFactory
    - These are the minimum steps for creating this feature
- Step 5: Test it.

Part 3
=============
To run tests in "warden":
    - **vendor/bin/phpunit -c dev/tests/unit/phpunit.xml app/code/Bohdan/ProductScaner/Test/Unit/Controller/Checkout/ExportTest.php**
If phpunit.xml not exists, run the next command:
    - **cp dev/tests/unit/phpunit.xml.dist dev/tests/unit/phpunit.xml**
