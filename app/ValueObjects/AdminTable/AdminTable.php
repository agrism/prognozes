<?php

namespace App\ValueObjects\AdminTable;

class AdminTable
{
    /**
     * @param AdminTableRow[] $rows
     */
    public function __construct(public string $title, public array $rows = [])
    {

    }
}
