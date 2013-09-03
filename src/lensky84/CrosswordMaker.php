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
     * @var array $pairs Pairs of words
     */
    protected $pairs = array();

    /**
     * @var int $maxLength
     */
    protected $maxLength = 0;

    /**
     * Generate
     *
     * @param array $words
     *
     * @return bool
     */
    public function generate($words)
    {
        $this->setWords($words);
        $this->setPairs();

        $this->generateCrossword();

        return false;
    }

    /**
     * Set pairs
     */
    protected function setPairs ()
    {
        /** Поиск вариантов первой пары  (1,2)*/
        /** @var \lensky84\Word $word */
        foreach ($this->words as $word) {
            /** @var \lensky84\Word $word2 */
            foreach ($this->words as $word2) {
                if ($word2->getLength() >= $this->maxLength) {
                    continue;
                }
                if ($word->compareWords($word2)) {
                    continue;
                }
                if ($word->compareFirstFirst($word2)) {
                    $this->pairs[Word::PAIR_1_2][] = array($word, $word2);
                }
            }
        }

        /** Поиск вариантов второй пары (1,3)*/
        foreach ($this->pairs[Word::PAIR_1_2] as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($pair[0]->getLength() >= $this->maxLength) {
                    continue;
                }
                if (($word->getLength()) <= $pair[1]->getLength()) {
                    continue;
                }
                if ($word->compareWords($pair[0])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[0])) {
                    $this->pairs[Word::PAIR_1_3][] = array($pair[0], $word);
                    $validPair = true;
                }
            }
            if (!$validPair) {
                unset($this->pairs[Word::PAIR_1_2][$pairKey]);
            }
        }

        /** Поиск вариантов 3 пары (2,4) */
        foreach ($this->pairs[Word::PAIR_1_2] as $pairKey => $pair) {
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
                    foreach ($this->pairs[Word::PAIR_1_3] as $pairKey2 => $pair2) {
                        $firstWordChar = $word->getChar($pair[0]->getLength());
                        $secondWordChar = $pair2[1]->getChar($pair[1]->getLength());
                        if (strcmp($firstWordChar, $secondWordChar) == 0) {
                            $this->pairs[Word::PAIR_2_4][] = array($pair[1], $word);
                            $validPair = true;
                            break 2;
                        }
                    }
                }
            }
            if (!$validPair) {
                unset($this->pairs[Word::PAIR_1_2][$pairKey]);
            }
        }

        /** Поиск вариантов 4й пары (4,5) */
        foreach ($this->pairs[Word::PAIR_2_4] as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($word->getLength() >= $this->maxLength) {
                    continue;
                }
                if ($word->compareWords($pair[1])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[1])) {
                    $this->pairs[Word::PAIR_4_5][] = array($pair[1], $word);
                    $validPair = true;
                    break;
                }
            }
            if (!$validPair) {
                unset($this->pairs[Word::PAIR_2_4][$pairKey]);
            }
        }

        /** Поиск вариантов 5й пары (3,6) */
        foreach ($this->pairs[Word::PAIR_1_3] as $pairKey => $pair) {
            $validPair = false;
            foreach ($this->words as $word) {
                if ($word->getLength() >= $this->maxLength) {
                    continue;
                }
                if ($word->compareWords($pair[1])) {
                    continue;
                }
                if ($word->compareFirstLast($pair[1])) {
                    $this->pairs[Word::PAIR_3_6][] = array($pair[1], $word);
                    $validPair = true;
                    break;
                }
            }
            if (!$validPair) {
                unset($this->pairs[Word::PAIR_1_3][$pairKey]);
            }
        }

    }

    /**
     * Generate crossword
     */
    protected function generateCrossword()
    {
        if (count($this->pairs[Word::PAIR_1_2]) > 1) {
            $this->chooseVariant();
        }
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