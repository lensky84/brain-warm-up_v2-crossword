<?php

namespace lensky84;


/**
 * Class CrosswordMaker
 */
class CrosswordMaker
{
    /**
     * @var array $words Words
     */
    protected $words = array();

    /**
     * @var array $crossword Crossword matrix
     */
    protected $crossword = array();

    /**
     * @var int $maxLength
     */
    protected $maxLength = 0;

    protected $topPairs = array();

    protected $bottomPairs = array();

    /**
     * Generate
     *
     * @param array $words
     *
     * @return bool
     */
    public function generate($words)
    {
        $crosswordStr = false;
        $this->setWords($words);
        $this->setPairs();
        $this->generateCrossword();

        return $crosswordStr;
    }

    public function getCornerPairs()
    {
        foreach ($this->words as $word) {
            if ($word->getLength() == $this->maxLength) {
                continue;
            }
            foreach ($this->words as $word2) {
                if (($word2->getLength() == $this->maxLength) || ($word->compareWords($word2))) {
                    continue;
                }
                if ($word->compareFirstFirst($word2)) {
                    $this->topPairs[] = array($word, $word2);
                }
                if ($word->compareLastLast($word2)) {
                    $this->bottomPairs[] = array($word, $word2);
                }
            }
        }
        if (empty($this->topPairs) || empty($this->bottomPairs)) {
            return false;
        }

        return true;
    }

    protected function getVertical()
    {
        foreach ($this->words as $word) {
            if ($word->getLength() != $this->maxLength) {
                continue;
            }
            foreach ($this->topPairs as $topPair) {
                if (!$word->compareFirstLast($topPair[0])) {
                    continue;
                }
                foreach ($this->bottomPairs as $bottomPair) {
                    if (!$bottomPair[1]->compareFirstLast($word)) {
                        continue;
                    }
                    $this->verticals[] = $word;
                }
            }
        }

    }

    /**
     * Set pairs
     */
    protected function setPairs ()
    {
        /** Поиск вариантов первой пары  (1,2)*/
        /** @var \lensky84\Word $word */
        foreach ($this->words as $word) {
            if ($word->getLength() >= $this->maxLength) {
                continue;
            }
            /** @var \lensky84\Word $word2 */
            foreach ($this->words as $word2) {
                if ($word2->getLength() >= $this->maxLength) {
                    continue;
                }
                if ($word->compareWords($word2)) {
                    continue;
                }
                if ($word->compareFirstFirst($word2)) {
                    $pairIndex = $word->getIndex() . $word2->getIndex();
                    $this->pairs[self::PAIR_1_2][$pairIndex] = array($word, $word2);
                }
            }
        }

        /** Поиск вариантов второй пары (1,3)*/
        $pair12 = $this->pairs[self::PAIR_1_2];
        foreach ($pair12 as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($word->getLength() != $this->maxLength) {
                    continue;
                }
                if ($word->getLength() <= $pair[1]->getLength()) {
                    continue;
                }
                if ($word->compareWords($pair[0])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[0])) {
                    $pairIndex = $pair[0]->getIndex() . $word->getIndex();
                    $this->pairs[self::PAIR_1_3][$pairIndex] = array($pair[0], $word);
                    $validPair = true;
                    break;
                }
            }
            if (!$validPair) {
                unset($this->pairs[self::PAIR_1_2][$pairKey]);
            }
        }

        /** Поиск вариантов 3 пары (2,4) */
        $pair12 = $this->pairs[self::PAIR_1_2];
        foreach ($pair12 as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($pair[1]->getLength() >= $this->maxLength) {
                    continue;
                }
                if (($word->getLength()) <= $pair[0]->getLength()) {
                    continue;
                }
                if ($word->compareWords($pair[1])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[1])) {
                    $pair13 = $this->pairs[self::PAIR_1_3];
                    foreach ($pair13 as $pair2) {
                        $firstWordChar = $word->getChar($pair[0]->getLength());
                        $secondWordChar = $pair2[1]->getChar($pair[1]->getLength());
                        if (strcmp($firstWordChar, $secondWordChar) == 0) {
                            $pairIndex = $pair[1]->getIndex() . $word->getIndex();
                            $this->pairs[self::PAIR_2_4][$pairIndex] = array($pair[1], $word);
                            $validPair = true;
                            break 2;
                        }
                    }
                }
            }
            if (!$validPair) {
                unset($this->pairs[self::PAIR_1_2][$pairKey]);
            }
        }

        /** Поиск вариантов 4й пары (4,5) */
        $pair24 = $this->pairs[self::PAIR_2_4];
        foreach ($pair24 as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($word->getLength() >= $this->maxLength) {
                    continue;
                }
                if ($word->compareWords($pair[1])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[1])) {
                    $pairIndex = $pair[1]->getIndex() . $word->getIndex();
                    $this->pairs[self::PAIR_4_5][$pairIndex] = array($pair[1], $word);
                    $validPair = true;
                    break;
                }
            }
            if (!$validPair) {
                unset($this->pairs[self::PAIR_2_4][$pairKey]);
            }
        }

        /** Поиск вариантов 5й пары (3,6) */
        $pair13 = $this->pairs[self::PAIR_1_3];
        foreach ($pair13 as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($word->getLength() >= $this->maxLength) {
                    continue;
                }
                if ($word->compareWords($pair[1])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[1])) {
                    $this->pairs[self::PAIR_3_6][] = array($pair[1], $word);
                    $validPair = true;
                    break;
                }
            }
            if (!$validPair) {
                unset($this->pairs[self::PAIR_1_3][$pairKey]);
            }
        }

    }

    /**
     * Generate crossword
     */
    protected function generateCrossword()
    {
        if (count($this->pairs[self::PAIR_1_2]) > 1) {
            $this->chooseVariant();
        }
        for ($row = 1; $row <= $this->getMatrixHeight(); $row++) {
            for ($column = 1; $column <= $this->getMatrixWidth(); $column++) {
                $pair12 = reset($this->pairs[self::PAIR_1_2]);
                $pair13 = reset($this->pairs[self::PAIR_1_3]);
                $pair24 = reset($this->pairs[self::PAIR_2_4]);
                $pair45 = reset($this->pairs[self::PAIR_4_5]);
                $pair36 = reset($this->pairs[self::PAIR_3_6]);

                if (($row == 1) && ($column <= $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = $pair12[0]->getChar($column);
                } elseif (($row == 1) && ($column > $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = '.';
                } elseif (($row > 1) && ($row < $pair12[1]->getLength()) && ($column == 1)) {
                    $this->crossword[$row][$column] = $pair12[1]->getChar($row);
                } elseif (($row > 1) && ($row < $pair12[1]->getLength()) && ($column == $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = $pair13[1]->getChar($row);
                } elseif (($row > 1) && ($row < $pair12[1]->getLength()) && ($column != $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = '.';
                } elseif ($row == $pair12[1]->getLength()) {
                    $this->crossword[$row][$column] = $pair24[1]->getChar($column);
                } elseif (($row > $pair12[1]->getLength()) && ($row < $pair13[1]->getLength()) && ($column == $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = $pair13[1]->getChar($row);
                } elseif (($row > $pair12[1]->getLength()) && ($row < $pair13[1]->getLength()) && ($column == $pair24[1]->getLength())) {
                    $this->crossword[$row][$column] = $pair45[1]->getChar($row - $pair12[1]->getLength() + 1);
                } elseif (($row > $pair12[1]->getLength()) && ($row < $pair13[1]->getLength()) && ($column < $pair24[1]->getLength())) {
                    $this->crossword[$row][$column] = '.';
                } elseif (($row == $pair13[1]->getLength()) && ($column >= $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = $pair36[1]->getChar($column - $pair12[0]->getLength() + 1);
                } elseif (($row == $pair13[1]->getLength()) && ($column < $pair12[0]->getLength())) {
                    $this->crossword[$row][$column] = '.';
                }
            }
        }
    }

    protected function getMatrixWidth()
    {
        $pair24 = reset($this->pairs[self::PAIR_2_4]);

        return $pair24[1]->getLength();
    }

    protected function getMatrixHeight()
    {
        return $this->maxLength;
    }

    protected function chooseVariant()
    {

    }

    /**
     * Set words
     *
     * @param array $words
     */
    public function setWords($words)
    {
        foreach ($words as $key => $word) {
            $this->words[] = new Word($word, $key + 1);
            $this->maxLength = ($this->maxLength < mb_strlen($word))?mb_strlen($word):$this->maxLength;
        }
    }

}