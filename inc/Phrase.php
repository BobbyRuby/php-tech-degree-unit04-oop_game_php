<?php

/**
 * Class Phrase
 * @param string $currentPhrase
 * @param array $selected
 * @param array $phrases
 */
class Phrase
{
    private $currentPhrase;
    private $selected;
    private $phrases;

    /**
     * Phrase constructor.
     * @param $currentPhrase
     * @param $selected
     */
    public function __construct($currentPhrase = '', $selected = [])
    {
        // If no custom phrase has been passed in, get a random phrase from the list of phrases
        if (empty($currentPhrase)):
            $this->setPhrases();
            $this->setCurrentPhrase($this->getRandomPhrase());
        else:
            $this->setCurrentPhrase($currentPhrase);
        endif;
        $this->setSelected($selected);
    }

    /**
     * @return string
     */
    public function getCurrentPhrase()
    {
        return $this->currentPhrase;
    }

    /**
     * @param string $currentPhrase
     */
    public function setCurrentPhrase($currentPhrase)
    {
        $this->currentPhrase = $currentPhrase;
    }

    /**
     * @return array
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * @param array $selected
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;
    }

    /**
     * Get a random phrase from $phrases field
     * @param mixed $phrases
     * @return string
     */
    public function getRandomPhrase()
    {
        return $this->getPhrases()[rand(0, 3)];
    }

    /**
     * @return mixed
     */
    public function getPhrases()
    {
        return $this->phrases;
    }

    /**
     * Set phrases
     */
    public function setPhrases()
    {
        $this->phrases = [
            'Boldness be my friend',
            'Leave no stone unturned',
            'Broken crayons still color',
            'The adventure begins'
        ];
    }

    /**
     * Adds place holders when new game is started.
     */
    public function addPhraseToDisplay()
    {
        $phraseSection = '
<div id="phrase" class="section">
    <ul>';
        $chars = str_split(strtolower($this->getCurrentPhrase()));
        foreach ($chars as $char) {
            if (!ctype_space($char)) :
                // Is selected
                if (in_array($char, $this->getSelected())) {
                    $phraseSection .= '<li class="show letter ' . $char . '">' . $char . '</li>';
                } // Not selected
                else {
                    $phraseSection .= '<li class="hide letter ' . $char . '">' . $char . '</li>';
                }
            else:
                $phraseSection .= '<li class="space">' . $char . '</li>';
            endif;
        }
        $phraseSection .= '
</div>
    </ul>';
        return $phraseSection;
    }

    /**
     * Checks to see if a letter matches a letter in the phrase. Accepts a single letter to check against the phrase. Returns true or false
     * @param $checkChar
     * @return boolean
     */
    public function checkLetter($checkChar)
    {
        $chars = array_filter(
            str_split(
                strtolower($this->getCurrentPhrase()
                )
            ),
            self::filterOutSpaces()
        );
        foreach ($chars as $char) {
            if (($checkChar == $char)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Closure
     */
    public static function filterOutSpaces()
    {
        return function ($value) {
            if (!ctype_space($value)) return $value;
        };
    }

}