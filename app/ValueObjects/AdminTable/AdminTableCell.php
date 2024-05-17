<?php

namespace App\ValueObjects\AdminTable;

readonly class AdminTableCell
{
    public function __construct(
        public ?string $columnName,
        public mixed $value,
        public bool $isSelect = false,
        public bool $isAction = false,
        public array $options = [],
    ) {
    }
}
