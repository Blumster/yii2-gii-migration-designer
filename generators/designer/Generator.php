<?php

namespace blumster\migration\generators\designer;

use yii\gii\CodeFile;

class Generator extends \yii\gii\Generator
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Migration Designer';
    }
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This tool lets you design your database, then generate the migrations for it.';
    }

    /**
     * Generates the code based on the current user input and the specified code template files.
     * This is the main method that child classes should implement.
     * Please refer to [[\yii\gii\generators\controller\Generator::generate()]] as an example
     * on how to implement this method.
     * @return CodeFile[] a list of code files to be created.
     */
    public function generate()
    {
        return [
            new CodeFile('migrations/test.php', '<?php echo "asd"; exit;')
        ];
    }
}
