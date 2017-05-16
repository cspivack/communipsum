<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{

    protected $vocab;
    protected $paragraphs = 4;
    protected $words = 50;

    /**
     * Retrieve the vocabulary from the cache, if available
     */
    public function __construct()
    {

        $this->vocab = \Cache::remember('words', 30, function() {
            return include(app()->getConfigurationPath().'words.php');
        });

    }

    /**
     * Return the response
     * @param  Request $request The current request object
     * @return string  The filler text according to the request options
     */
    public function getWords(Request $request)
    {

        $this->validate($request, [
            'paragraphs' => 'integer',
            'words' => 'integer'
        ]);

        $content = [];

        $paragraphs = ($request->has('paragraphs')) ? static::within($request->input('paragraphs'), 1, 99) : $this->paragraphs;
        $words = ($request->has('words')) ? static::within($request->input('words'), 1, 500) : $this->words;

        for($p=0; $p<$paragraphs; $p++)
            $content[] = $this->paragraph($words);

        if($request->input('html')!==FALSE)
            $content = array_map(function($p) {
                return '<p>'.$p.'</p>';
            }, $content);

        $response = implode(PHP_EOL.PHP_EOL, $content);

        return $response;

    }

    /**
     * Restrict a number to a certain range
     * @param  integer $number The input number
     * @param  integer $min    The minimum allowed value
     * @param  integer $max    The maximum allowed value
     * @return integer  The input number after being restricted to the range set by the $min and $max
     */
    protected static function within($number, $min, $max)
    {

        return min(max($number, $min), $max);

    }

    /**
     * Construct a paragraph
     * @param  integer $words The minimum number of words the paragraph should contain
     * @return string  The constructed paragraph
     */
    protected function paragraph($words)
    {

        $paragraph = [];

        $wordCount = 0;

        while($wordCount < $words):
            $sentence = $this->sentence();
            $paragraph[] = ucfirst($sentence['content']);
            $wordCount += $sentence['count'];
        endwhile;

        return implode(' ', $paragraph);

    }

    /**
     * Construct a sentence with a variable number of words in it
     * @return array  The sentence as a string, and a count of the number of words in the sentence
     */
    protected function sentence()
    {

        $words = rand(8, 13);
        $wordCount = 0;

        $sentence = [];

        while($wordCount < $words):
            $word = $this->word($sentence);
            $sentence[] = $word;
            $wordCount++;
        endwhile;

        return [
            'content' => implode(' ', $sentence).'.',
            'count' => count($sentence)
        ];

    }

    /**
     * Pick a random word that isn't already in the sentence
     * @param  array $sentence The currently built sentence
     * @return string  The randomly-picked word
     */
    protected function word($sentence)
    {

        $word = $this->vocab[array_rand($this->vocab, 1)];

        while(in_array($word, $sentence))
            $word = $this->vocab[array_rand($this->vocab, 1)];

        return $word;

    }


}
