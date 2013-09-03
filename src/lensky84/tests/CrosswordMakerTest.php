<?php
namespace lensky84\test;

use lensky84;


require_once('../../../vendor/autoload.php');

/**
 * Class CrosswordMakerTest
 */
class CrosswordMakerTest extends \BaseTest
{

    /**
     * Set up crossword maker
     */
    protected function setUpCrossword()
    {
        $this->crosswordMaker = new \lensky84\CrosswordMaker();
    }

    public function testCrosswordMaker()
    {
        $datas = self::provider();
        foreach ($datas as $key => $data) {
            if ($key == 3) {
                parent::testCrosswordMaker($data[0], $data[1]);
            }
        }
    }

}