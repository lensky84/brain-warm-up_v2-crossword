<?php

namespace lensky84;

/**
 * Class Word
 */
class Word
{
    /**
     * @var null $index
     */
    protected $index = null;

    /**
     * @var string $word
     */
    protected $word;


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