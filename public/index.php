<?php

require_once __DIR__."/../src/Autoloader.class.php";

Autoloader::register();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>CodeAnalyzer</title>
        <link href="./stylesheets/layout.css" rel="stylesheet">
    </head>
    <body>
        <?php

        $path = __DIR__ . "/../src";

        $CodeAnalyzer = CodeAnalyzer::analyzePath($path);

        foreach($CodeAnalyzer->getCodeClasses() as $CodeClass) { ?>

            <div class="method-list">
                <div class="class-name"><?php echo $CodeClass->getTitle();?></div>
                <?php foreach($CodeClass->getClassParameters() as $ClassParameter) { ?>
                    <div class="parameter">
                        <?php echo $ClassParameter->toHTML(); ?>
                    </div>
                <?php } ?>
                <?php foreach($CodeClass->getMethods() as $Method) { ?>
                    <div class="method">
                        <?php echo $Method->toHTML(); ?>
                    </div>
                <?php } ?>
            </div>

        <?php } ?>
    </body>
</html>
