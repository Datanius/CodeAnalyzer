# CodeAnalyzer

This project lists all classes from a given path and shows all methods (with parameters) for each class.

## Usage
```php
$path = __DIR__."/../src";

$CodeAnalyzer = CodeAnalyzer::analyzePath($path);

foreach($CodeAnalyzer->getCodeClasses() as $CodeClass) { ?>

    <div class="method-list">
        <div class="class-name"><?php echo $CodeClass->getTitle();?></div>
        <?php foreach($CodeClass->getMethods() as $Method) { ?>
            <div class="method">
                <?php echo $Method->toHTML(); ?>
            </div>
        <?php } ?>
    </div>
    
<?php } ?>
``` 
## Preview
![Preview](https://i.ibb.co/T2BN8jj/grafik.png)
