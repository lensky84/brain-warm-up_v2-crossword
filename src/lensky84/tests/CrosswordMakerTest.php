<?php
namespace lensky84\test;

use lensky84\CrosswordMaker;


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
        $this->crosswordMaker = new CrosswordMaker();
    }

    public function testCrosswordMaker()
    {
        $datas = self::provider();
        foreach ($datas as $key => $data) {
            if ($key == 1) {
                parent::testCrosswordMaker($data[0], $data[1]);
            }
        }
    }

}