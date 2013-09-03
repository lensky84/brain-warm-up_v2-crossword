<?php

namespace lensky84;

/**
 * Class Word
 */
class Word
{
    const ORIENTATION_HORIZONTAL = 1;

    const ORIENTATION_VERTICAL = 2;
    const PAIR_1_2 = 1;
    const PAIR_1_3 = 2;
    const PAIR_2_4 = 3;
    const PAIR_4_5 = 4;
    const PAIR_3_6 = 5;

    /**
     * @var null $index
     */
    protected $index = null;

    /**
     * @var string $word
     */
    protected $word;

    /**
     * @var int $pair
     */
    protected $pair = 0;

    /**
     * @var int $orientation
     */
    protected $orientation = self::ORIENTATION_HORIZONTAL;

    /**
     * Constructor
     *
     * @param string $word  Word
     * @param int    $index Index
     */
    public function __construct($word, $index)
    {
        $this->word = $word;
        $this->index = $index;
    }

    /**
     * Get index
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord ()
    {
        return $this->word;
    }

    /**
     * Get orientation
     *
     * @return int
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set pair
     *
     * @param int $pair Pair
     */
    public function setPair($pair)
    {
        $this->pair = $pair;
    }

    /**
     * Get pair
     *
     * @return int
     */
    public function getPair()
    {
        return $this->pair;
    }

    /**
     * Is set pair
     *
     * @return bool
     */
    public function isSetPair()
    {
        return (bool) $this->pair;
    }

    /**
     * Set orientation
     *
     * @param int $orientation
     */
    public function setOrientation ($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * Get char
     *
     * @param int $number Number
     *
     * @return bool|string
     */
    public function getChar ($number)
    {
        return (isset($this->word[$number - 1]))?$this->word[$number - 1]:false;
    }

    /**
     * Get length
     *
     * @return int
     */
    public function getLength ()
    {
        return mb_strlen($this->word);
    }

    /**
     * Compare words
     *
     * @param Word $word
     *
     * @return bool
     */
    public function compareWords(Word $word)
    {
        if ((strcmp($this->getWord(), $word->getWord()) === 0) && ($this->getIndex() == $word->getIndex())) {
            return true;
        }

        return false;
    }

    /**
     * Compare first chars of words
     *
     * @param Word $word Word
     *
     * @return bool
     */
    public function compareFirstFirst (Word $word)
    {
        return (strcmp($this->getChar(1), $word->getChar(1)) === 0)? true : false ;
    }

    /**
     * Compare first and last chars of words
     *
     * @param Word $word Word
     *
     * @return bool
     */
    public function compareFirstLast (Word $word)
    {
        return (strcmp($this->getChar(1), $word->getChar($word->getLength())) === 0)? true : false ;
    }

    /**
     * Compare last chars of words
     *
     * @param Word $word Word
     *
     * @return bool
     */
    public function compareLastLast (Word $word)
    {
        return (strcmp($this->getChar($this->getLength()), $word->getChar($word->getLength())) === 0)? true : false ;
    }
}