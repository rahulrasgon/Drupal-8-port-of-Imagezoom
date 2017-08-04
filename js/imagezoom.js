(function($) {

  /**
   * Initialize image zoom functionality.
   */
  Drupal.behaviors.imagezoom = {
    attach: function(context, drupalSettings) {
      $('.imagezoom-image').once('imagezoom').each(function() {
        $(this).ezPlus(drupalSettings.imagezoom);
      });
      $(document).bind('CToolsCloseModalBehaviors', function(context) {
        $('.zoomContainer').remove();
      });
    }
  }

})(jQuery, Drupal);
