<?php

declare(strict_types=1);
/*
* This file is part of the Akeneo PIM Enterprise Edition.
*
* (c) 2021 Akeneo SAS (http://www.akeneo.com)
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Akeneo\Pim\Structure\Component\Query\PublicApi\Permission;

interface GetViewableAttributeCodesForUserInterface
{
    public function forAttributeCodes(array $attributeCodes, int $userId): array;
}
