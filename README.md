# Hi Wes!

I have worked on this from 4-7.30 pm. 

## run the app
use php artisan serve to run the parser in FusionController.php

This created an xml in storage/app/public/products.xml

the WriteXmlService contains the process how I recursively created the array from the csv. 
I have run out of time at the point where I need to separate the products and their variants. 
Unfortunately I figured out too late that using an object instead of an array would have been much more beneficial, I underestimated the complexity of the xml file.

I hope it gives a good presentation of my general knowledge.

## test
tests\Feature\ExportXmlTest.php contains a small comparison test, since I couldn't finish the project, this will fail. I used php artisan test

I have been analysing the ProductsTest.php file in the repo you've sent, and I found some validations that I wanted my xml to match to but I didn't finish creating the xml. 

### thank you, best regards, 
&copy; Beatrix

## update
Sunday I've continued to add tests and figured out some of the build with test funcionality. However I still need to use the csv data instead of the mock.
This is in tests/Browser/ProductsTest.php

## update2
testing using php artisan dusk on tests/Browser/ProductsTest.php
I've built the xml for the indivitual lines, but the variants is a separate story. I was planning to hack a new array, but I am 100% that's not the right thing to do. 
If you still didn't check this by then, I might browse the original repo for more information on how to build variants into products.
# job_app2
