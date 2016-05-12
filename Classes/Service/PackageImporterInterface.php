<?php
namespace Neos\MarketPlace\Service;

/*
 * This file is part of the Neos.MarketPlace package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\MarketPlace\Domain\Model\Storage;
use Packagist\Api\Result\Package;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Package Importer Interface
 *
 * @api
 */
interface PackageImporterInterface
{
    /**
     * @param Package $package
     * @param Storage $storage
     * @return NodeInterface
     */
    public function process(Package $package, Storage $storage);

    /**
     * @return array
     */
    public function getProcessedPackages();

    /**
     * @return integer
     */
    public function getProcessedPackagesCount();

    /**
     * Remove local package not preset in the processed packages list
     *
     * @param Storage $storage
     * @param callable $callback function called after the package removal
     * @return integer
     */
    public function cleanupPackages(Storage $storage, callable $callback = null);

    /**
     * Remove vendors without packages
     *
     * @param Storage $storage
     * @param callable $callback function called after the vendor removal
     * @return integer
     */
    public function cleanupVendors(Storage $storage, callable $callback = null);
}
