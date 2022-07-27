<?php

namespace App\Rules;

use App\Models\Anagrafiche\Lotto;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class UniqueLotto implements DataAwareRule, InvokableRule
{
        /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $articolo_id = $this->data['mountedFormComponentActionData']['articolo_id'];
        $lottofield = $this->data['mountedFormComponentActionData']['lotto'];
        $data_lotto = $this->data['mountedFormComponentActionData']['data_lotto'];

        $lotto = Lotto::select(DB::raw("id as id"))
        ->where([
            ['lotti.articolo_id','=',$articolo_id],
            ['lotti.lotto','=',$lottofield],
            ['lotti.data_lotto','=',$data_lotto]
        ])
        ->first();
        if (is_null($lotto) == false) {
            $fail('Esiste già per questo articolo un lotto con stessa data. Modificarlo o chiudere e selzionarlo.');
        }

    }

        /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
