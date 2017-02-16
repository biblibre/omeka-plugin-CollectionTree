<?php
/**
 * Collection Tree
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package CollectionTree\View\Helper
 */
class CollectionTree_View_Helper_CollectionTreeFullList extends Zend_View_Helper_Abstract
{
    protected $_collections;

    /**
     * Build a nested HTML unordered list of the full collection tree, starting
     * at root collections.
     *
     * @param bool $linkToCollectionShow
     * @return string|null
     */
    public function collectionTreeFullList($linkToCollectionShow = true)
    {
        $rootCollections = get_db()->getTable('CollectionTree')->getRootCollections();
        // Return NULL if there are no root collections.
        if (!$rootCollections) {
            return null;
        }

        $collectionTable = get_db()->getTable('Collection');
        $html = '<div id="collection-tree">';

        $collectionTreeTable = get_db()->getTable('CollectionTree');
        foreach ($rootCollections as &$rootCollection) {
            $rootCollection['children'] = $collectionTreeTable->getDescendantTree($rootCollection['id']);
        }

        $html .= $this->_collectionTreeList($rootCollections, $linkToCollectionShow);
        $html .= '</div>';

        return $html;
    }

    protected function _collectionTreeList($collectionTree, $linkToCollectionShow)
    {
        $html = '<ul>';

        foreach ($collectionTree as $collection) {
            $html .= '<li>';
            if ($linkToCollectionShow) {
                $html .= link_to_collection(null, array(), 'show', $this->_getCollection($collection['id']));
            } else {
                $html .= $collection['name'] ? $collection['name'] : __('[Untitled]');
            }
            $html .= $this->_collectionTreeList($collection['children'], $linkToCollectionShow);
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    protected function _getCollection($collectionId)
    {
        if (!isset($this->_collections)) {
            $collectionTable = get_db()->getTable('Collection');
            $this->_collections = array();
            foreach ($collectionTable->findAll() as $collection) {
                $this->_collections[$collection->id] = $collection;
            }
        }

        if (isset($this->_collections[$collectionId])) {
            return $this->_collections[$collectionId];
        }
    }
}
