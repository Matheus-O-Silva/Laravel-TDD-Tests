<?php
declare(strict_types=1);

namespace App\Repository\Contracts;

use PhpParser\Node\Expr\Cast\Object_;

interface PaginationInterface
{
    public function items(): array;
    public function total(): int;
    public function currentPage(): int;
    public function perPage(): int;
    public function firstPage(): int;
    public function lastPage(): int;
}
