<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ValueExistsInTwoTables implements ValidationRule
{

    protected $table1;
    protected $table2;
    protected $column;

    public function __construct($table1, $table2, $column)
    {
        $this->table1 = $table1;
        $this->table2 = $table2;
        $this->column = $column;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value exists in the first table
        $existsInTable1 = DB::table($this->table1)
            ->where($this->column, $value)
            ->exists();

        // Check if the value exists in the second table
        $existsInTable2 = DB::table($this->table2)
            ->where($this->column, $value)
            ->exists();

        // If the value does not exist in either table, fail the validation
        if (!$existsInTable1 && !$existsInTable2) {
            $fail("The {$attribute} does not exist in either {$this->table1} or {$this->table2}.");
        }
    }
}
