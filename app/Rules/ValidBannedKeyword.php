<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidBannedKeyword implements Rule
{
    protected $banned_keyword_file = 'keywordbanned.txt';
    protected $banned_keywords = array();
    public $keyword_banned;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->banned_keywords = get_banned_keywords();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        foreach (explode(" ", strtoupper($value)) as $k=>$keyword) {
            if(in_array($keyword, $this->banned_keywords)){
                $this->keyword_banned = $keyword;
                return false;//contain bad words
            }else{
                // print_r($this->banned_keywords);
            }
        }
        return true;//no-contain bad words
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has banned keyword: '.$this->keyword_banned;
    }
}
