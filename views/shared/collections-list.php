<?php if (!empty($collections)): ?>
    <ul>
        <?php foreach ($collections as $collection): ?>
            <?php
            $collectionObj = get_db()->getTable('Collection')->find($collection['id']);
            $children = get_db()->getTable('CollectionTree')->getChildCollections($collectionObj->id);
            $attributes = array(
                'data-collection-id' => (string) $collectionObj->id,
            );
            if (!empty($children)) {
                $attributes['class'] = 'collapsible collapsed';
            }
            ?>

            <li <?php echo tag_attributes($attributes); ?>>
                <span class="collection-tree-handle"></span>
                <?php echo link_to_collection(null, array(), 'show', $collectionObj); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
