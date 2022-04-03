<?php

namespace Tivins\Core\Routing;

use Attribute;
use Tivins\Core\Http\ContentType;
use Tivins\Core\Http\Method as HTTPMethod;
use Tivins\UserPack\Objects\Permission;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    /**
     * @param HTTPMethod[] $methods
     * @param Permission[] $permissions
     * @param ContentType[] $accept
     */
    public function __construct(
        public array $methods = [HTTPMethod::GET],
        public array $permissions = ['public'],
        public ?string $path = null,
        public array $accept = [ContentType::ALL],
    )
    {
    }
}