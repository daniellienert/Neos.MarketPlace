<?php
namespace Neos\MarketPlace\Domain\Model;

/*
 * This file is part of the Neos.MarketPlace package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\MarketPlace\Exception;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\Neos\Domain\Service\ContentContextFactory;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeTemplate;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

/**
 * Storage
 *
 * @api
 */
class Storage
{
    /**
     * @var ContentContextFactory
     * @Flow\Inject
     */
    protected $contextFactory;

    /**
     * @var NodeTypeManager
     * @Flow\Inject
     */
    protected $nodeTypeManager;

    /**
     * @var array
     * @Flow\InjectConfiguration(path="repository")
     */
    protected $repository;

    /**
     * @var string
     */
    protected $workspaceName;

    /**
     * @var NodeInterface
     */
    protected $node;

    /**
     * @param string $workspaceName
     */
    public function __construct($workspaceName = 'live')
    {
        $this->workspaceName = $workspaceName;
    }

    /**
     * @return NodeInterface
     * @throws Exception
     */
    public function node()
    {
        if ($this->node !== null) {
            return $this->node;
        }
        $context = $this->createContext($this->workspaceName);
        $this->node = $context->getNodeByIdentifier($this->repository['identifier']);
        if ($this->node === null) {
            throw new Exception('Repository node not found', 1457507995);
        }
        return $this->node;
    }

    /**
     * @param string $packageKey
     * @return Vendor
     */
    public function getPackageVendor($packageKey)
    {
        $vendor = Slug::create(explode('/', $packageKey)[0]);
        $node = $this->node()->getNode($vendor);
        if ($node !== null) {
            return $node;
        }
        $nodeTemplate = new NodeTemplate();
        $nodeTemplate->setNodeType($this->nodeTypeManager->getNodeType('Neos.MarketPlace:Vendor'));
        $nodeTemplate->setName($vendor);
        $nodeTemplate->setProperty('uriPathSegment', $vendor);
        $nodeTemplate->setProperty('title', $vendor);
        return $this->node()->createNodeFromTemplate($nodeTemplate);
    }

    /**
     * @return string
     */
    public function getIdentifer() {
        if ($this->node === null) {
            $this->node();
        }
        return $this->node->getIdentifier();
    }

    /**
     * Creates a content context for given workspace and language identifiers
     *
     * @param string $workspaceName
     * @return ContentContext
     */
    protected function createContext($workspaceName)
    {
        $contextProperties = [
            'workspaceName' => $workspaceName,
            'invisibleContentShown' => true,
            'inaccessibleContentShown' => true
        ];

        return $this->contextFactory->create($contextProperties);
    }
}
