<?php

namespace App\Http\Controllers\sepa;
Class SpecialChars_formatter
{
    private $search;
    private $replace;
    private $preg_match;
    private $signs = array();

    public function __construct()
    {
        $this->signs = array(                         //[0]-string replace    [1][]-array search signs
            array('Ae', array('Ä', 'Ə', 'Æ')),
            array('ae', array('ä', 'ə', 'æ')),
            array('Oe', array('Ö', 'Œ')),
            array('oe', array('ö', 'œ')),
            array('Ue', array('Ü')),
            array('ue', array('ü')),
            array('Dj', array('Đ', 'Ď')),
            array('dj', array('đ', 'ď')),
            array('A', array('Â', 'Ă', 'Ą', 'Ά', 'Å', 'Ā', 'Á', 'Ǎ', 'À')),
            array('a', array('â', 'ă', 'ą', 'ά', 'ā', 'á', 'ǎ', 'à', 'å')),
            array('E', array('Ē', 'Ę', 'Ë', 'Έ', 'É', 'Ě', 'È', 'Ê', 'Ê̄', 'Ế', 'Ê̌')),
            array('e', array('ë', 'ę', 'ē', 'έ', 'é', 'ě', 'è', 'Ề', 'ê', 'ê̄', 'ế', 'ê̌', 'ề')),
            array('I', array('Î', 'Ï', 'Ī', 'Í', 'Ǐ', 'Ì')),
            array('i', array('î', 'ï', 'ī', 'í', 'ǐ', 'ì')),
            array('O', array('Ő', 'Ø', 'Ô', 'Õ', 'Ō', 'Ó', 'Ǒ', 'Ò')),
            array('o', array('ő', 'ø', 'ô', 'õ', 'ō', 'ó', 'ǒ', 'ò')),
            array('U', array('Ű', 'Ů', 'Ŭ', 'Ū', 'Ú', 'Ǔ', 'Ù', 'Û', 'Ǖ', 'Ǘ', 'Ǚ', 'Ǜ', 'Ũ')),
            array('u', array('ű', 'ů', 'ŭ', 'ū', 'ú', 'ǔ', 'ù', 'û', 'ũ', 'ǖ', 'ǘ', 'ǚ', 'ǜ')),
            array('C', array('Ç', 'Ĉ', 'Č', 'Ć')),
            array('c', array('ç', 'ĉ', 'ć', 'č')),
            array('G', array('Ĝ', 'Ģ', 'Ğ', 'G‘')),
            array('g', array('ģ', 'ĝ', 'ğ', 'g‘')),
            array('H', array('Ĥ')),
            array('h', array('ĥ')),
            array('J', array('Ĵ')),
            array('j', array('ĵ')),
            array('K', array('Ķ')),
            array('k', array('ķ')),
            array('L', array('Ļ', 'Ł', 'Ľ', 'Ĺ')),
            array('l', array('ļ', 'ł', 'ľ', 'ĺ')),
            array('N', array('Ñ', 'Ņ', 'Ň')),
            array('n', array('ñ', 'ņ', 'ň')),
            array('R', array('Ř', 'Ŕ')),
            array('r', array('ř', 'ŕ')),
            array('S', array('Ŝ', 'Š', 'Ś', 'Ș', 'Ş')),
            array('s', array('ŝ', 'š', 'ś', 'ș', 'ş')),
            array('T', array('Ț', 'Ţ', 'Ť')),
            array('t', array('ț', 'ţ', 'ť')),
            array('Y', array('Ÿ', 'Ý')),
            array('y', array('ÿ', 'ý')),
            array('Z', array('Ž', 'Ź', 'Ż')),
            array('z', array('ž', 'ź', 'ż'))

        );
        $this->setArrays();

    }

    private function setArrays()
    {
        $this->search     = array();
        $this->replace    = array();
        $this->preg_match = '/[';
        foreach ($this->signs as $letters) {
            foreach ($letters[1] as $sign) {
                array_push($this->search, '/' . $sign . '/');
                array_push($this->replace, $letters[0]);
                $this->preg_match .= $sign;
            }
        }
        $this->preg_match .= ']/';
    }

    /**
     * @param $replace - string
     * @param $serach - array with chars to replace
     */
    public function add_Char($replace, $search)
    {
        array_push($this->signs, array($replace, $search));
        $this->setArrays();
    }

    public function format_to_english($word)
    {
        if (preg_match($this->preg_match, $word)) {
            return preg_replace($this->search, $this->replace, $word);
        } else return $word;
    }

}