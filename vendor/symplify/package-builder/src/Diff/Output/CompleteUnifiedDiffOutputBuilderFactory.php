<?php

declare (strict_types=1);
namespace MonorepoBuilder20220517\Symplify\PackageBuilder\Diff\Output;

use MonorepoBuilder20220517\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use MonorepoBuilder20220517\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
/**
 * @api
 * Creates @see UnifiedDiffOutputBuilder with "$contextLines = 1000;"
 */
final class CompleteUnifiedDiffOutputBuilderFactory
{
    /**
     * @var \Symplify\PackageBuilder\Reflection\PrivatesAccessor
     */
    private $privatesAccessor;
    public function __construct(\MonorepoBuilder20220517\Symplify\PackageBuilder\Reflection\PrivatesAccessor $privatesAccessor)
    {
        $this->privatesAccessor = $privatesAccessor;
    }
    /**
     * @api
     */
    public function create() : \MonorepoBuilder20220517\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder
    {
        $unifiedDiffOutputBuilder = new \MonorepoBuilder20220517\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder('');
        $this->privatesAccessor->setPrivateProperty($unifiedDiffOutputBuilder, 'contextLines', 10000);
        return $unifiedDiffOutputBuilder;
    }
}
