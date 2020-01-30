<?php

/**
 * Class Game
 * @param Phrase $phrase - A phrase object
 * @param int $lives - Number of lives
 * @param int $incorrect - Amount of keys selected that are incorrect
 * @param int $correct - Amount of keys selected that are correct
 * @param int $letterCount - Amount of letters matching the phrase letters from the selected keys
 */
class Game
{
    private $phrase;
    private $lives;
    private $incorrect;
    private $correct;
    private $letterCount;

    /**
     * Game constructor.
     * @param Phrase $phrase
     */
    public function __construct($phrase)
    {
        // Set Varaibles
        $this->setPhrase($phrase);
        $this->setLives(5);
        $this->setCorrect(0);
        $this->setIncorrect(0);
    }

    /**
     * @return Phrase
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @param Phrase $phrase
     */
    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * @return int
     */
    public function getLives()
    {
        return $this->lives;
    }

    /**
     * @param int $lives
     */
    public function setLives($lives)
    {
        $this->lives = $lives;
    }

    /**
     * @return int
     */
    public function getIncorrect()
    {
        return $this->incorrect;
    }

    /**
     * @param $int
     */
    private function setIncorrect($int)
    {
        $this->incorrect = $int;
    }

    /**
     * @return int
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * @param $int
     */
    private function setCorrect($int)
    {
        $this->correct = $int;
    }

    /**
     * @return mixed
     */
    public function getLetterCount()
    {
        return $this->letterCount;
    }

    /**
     * @param mixed $letterCount
     */
    public function setLetterCount($letterCount)
    {
        $this->letterCount = $letterCount;
    }

    /**
     * Builds letter button and determines if it is selected or not
     * @param $key
     * @return boolean
     */
    public function buildLetterButton($key)
    {
        $button = '<button name="key" class="key';
        $selected = $this->getPhrase()->getSelected();
        // Is selected?
        if (in_array($key, $selected)) {
            // Is in the phrase?
            if ($this->getPhrase()->checkLetter($key)) :
                $button .= ' correct" value="' . $key . '" disabled>' . $key . '</button>';
            // Not in the phrase
            else:
                $button .= ' incorrect" value="' . $key . '" disabled>' . $key . '</button>';
            endif;
        } // Has not been selected
        else {
            $button .= '" value="' . $key . '">' . $key . '</button>';
        }
        return $button;
    }

    /**
     * @return string
     */
    public function displayResetButton()
    {
        $button = '<button name="reset" class="key" value="reset">Space / Reset</button>';
        return $button;
    }

    public function determineCorrectIncorrectKeyPresses()
    {
        $keyRows = [
            [
                "q",
                "w",
                "e",
                "r",
                "t",
                "y",
                "u",
                "i",
                "o",
                "p"
            ],
            [
                "a",
                "s",
                "d",
                "f",
                "g",
                "h",
                "j",
                "k",
                "l"
            ],
            [
                "z",
                "x",
                "c",
                "v",
                "b",
                "n",
                "m",
            ]
        ];
        foreach ($keyRows as $keyRow) {
            foreach ($keyRow as $key) {
                // Is selected?
                if (in_array($key, $this->getPhrase()->getSelected())) {
                    // Is in the phrase?
                    if ($this->getPhrase()->checkLetter($key)) :
                        $this->correct++;
                    // Not in the phrase
                    else:
                        $this->incorrect++;
                    endif;
                } // Ha
            }
        }
    }

    /**
     * This method checks to see if the player has selected all of the letters in the phrase.
     * @return bool
     */
    public function checkForWin()
    {
        $selected = $this->getPhrase()->getSelected();
        $phraseChars = array_filter(
            str_split(
                strtolower(
                    $this->getPhrase()->getCurrentPhrase()
                )
            ),
            $this->getPhrase()::filterOutSpaces()
        );
        $phraseCount = count($phraseChars);
        $letterCount = 0; // Holds the total amount of letters corresponding to the selected keys inside the phrase to check against phraseCount to determine WIN
        // Loop through selected keys
        foreach ($selected as $key) {
            // Identify if key is in phrase
            if ($this->getPhrase()->checkLetter($key)):
                // Loop through phrase letters
                foreach ($phraseChars as $phraseChar) {
                    // If the letter is equal to the key, add 1 to the letterCount
                    if ($key == $phraseChar) $letterCount++;
                }
            endif;
        }
        $this->setLetterCount($letterCount); // Store so we can use for debug
        return $phraseCount == $letterCount ? true : false;
    }

    /**
     * This method checks to see if the player has guessed too many wrong letters.
     * @return bool
     */
    public function checkForLose()
    {
        return $this->incorrect == $this->lives ? true : false;
    }

    /**
     * This method displays one message if the player wins and another message if they lose. It returns false if the game has not been won or lost.
     * @return string
     */
    public function gameOver()
    {
        if ($this->checkForWin()):
            echo 'The game is over, you WON!';
        elseif ($this->checkForLose()):
            echo 'The game is over, you LOST!';
        endif;
    }

    /**
     * Builds HTML form with each letter being a submit button
     * @return string
     */
    public function displayKeyboard()
    {
        $keyRows = [
            [
                "q",
                "w",
                "e",
                "r",
                "t",
                "y",
                "u",
                "i",
                "o",
                "p"
            ],
            [
                "a",
                "s",
                "d",
                "f",
                "g",
                "h",
                "j",
                "k",
                "l"
            ],
            [
                "z",
                "x",
                "c",
                "v",
                "b",
                "n",
                "m",
            ]
        ];

        $keyBoard = '
    <div id="qwerty" class="section">';
        foreach ($keyRows as $keyRow) {
            $keyBoard .= '<div class="keyrow">';
            foreach ($keyRow as $key) {
                $keyBoard .= $this->buildLetterButton($key);
            }
            $keyBoard .= '</div>';
        }
        $keyBoard .=
            $this->displayResetButton() .
            '</div>';
        return $keyBoard;
    }

    /**
     * Displays the user's current score
     * @return string
     */
    public function displayScore()
    {
        $score = '
    <div id="scoreboard" class="section">
        <ol>';
        if ($this->incorrect === 0):
            $score .= '
        <li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
        <li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
        <li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
        <li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
        <li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>';
        elseif ($this->incorrect === 1):
            $score .= '
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
';
        elseif ($this->incorrect === 2):
            $score .= '
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
';
        elseif ($this->incorrect === 3):
            $score .= '
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
';

        elseif ($this->incorrect === 4):
            $score .= '
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/liveHeart.png" height="35px" width="30px"></li>
';


        elseif ($this->incorrect === 5):
            $score .= '
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
<li class="tries"><img src="images/lostHeart.png" height="35px" width="30px"></li>
';

        endif;
        $score .= '
        </ol>
    </div>';
        return $score;
    }
}