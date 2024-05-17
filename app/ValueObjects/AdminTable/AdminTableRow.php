<?php

namespace App\ValueObjects\AdminTable;

class AdminTableRow
{
    /**
     * @param AdminTableCell[] $cells
     */
    public function __construct(public int $id, public array $cells = [])
    {

    }
}
