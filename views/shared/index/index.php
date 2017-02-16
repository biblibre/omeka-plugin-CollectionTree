<?php queue_css_file('collection-tree'); ?>
<?php echo head(array('title' => __('Collection Tree'))); ?>

<?php if ($this->rootCollections): ?>
    <div id="collection-tree">
        <?php echo $this->partial('collections-list.php', array('collections' => $this->rootCollections)); ?>
    </div>
<?php else: ?>
    <p><?php echo __('There are no collections.'); ?></p>
<?php endif; ?>

<script>
    (function($) {
        var expandCollection = function(elt) {
            elt.children('ul').show();
            elt.removeClass('collapsed');
            elt.addClass('expanded');
        };
        var collapseCollection = function(elt) {
            elt.children('ul').hide();
            elt.removeClass('expanded');
            elt.addClass('collapsed');
        };

        $(document).ready(function() {
            $('#collection-tree').on('click', '.collapsible .collection-tree-handle', function(e) {
                e.preventDefault();

                var collection = $(this).parent();
                var childrenList = collection.children('ul');
                if (childrenList.length > 0) {
                    if (childrenList.is(':visible')) {
                        collapseCollection(collection);
                    } else {
                        expandCollection(collection);
                    }
                } else {
                    var collectionId = collection.attr('data-collection-id');
                    var url = <?php echo json_encode($this->url('/collection-tree/index/collection-children-list')); ?> + '?id=' + collectionId;
                    $.ajax(url).done(function(childrenHtml) {
                        collection.append(childrenHtml);
                        expandCollection(collection);
                    });
                }
            });
        });
    })(jQuery);
</script>

<?php echo foot(); ?>
