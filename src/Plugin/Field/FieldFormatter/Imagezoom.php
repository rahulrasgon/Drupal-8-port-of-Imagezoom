<?php

namespace Drupal\imagezoom\Plugin\Field\FieldFormatter;


use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;


/**
 * @FieldFormatter(
 *  id = "imagezoom",
 *  label = @Translation("Image Zoom"),
 *  field_types = {"image"}
 * )
 */
class Imagezoom extends ImageFormatterBase
{


  /**
   * {@inheritdoc}
   */
  public static function defaultSettings()
  {
    return [
      'imagezoom_zoom_type' => '',
      'imagezoom_display_style' => '',
      'imagezoom_zoom_style' => '',
      'imagezoom_additional' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state)
  {
    $description_link = Link::fromTextAndUrl(
      $this->t('Documentation'),
      Url::fromUri('http://igorlino.github.io/elevatezoom-plus/api.htm')
    );
    $element['imagezoom_zoom_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Zoom type'),
      '#options' => $this->imagezoomZoomTypes(),
      '#default_value' => $this->getSetting('imagezoom_zoom_type'),
    ];

    $image_styles = image_style_options(FALSE);
    $element['imagezoom_display_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Image style'),
      '#options' => $image_styles,
      '#default_value' => $this->getSetting('imagezoom_display_style'),
      '#empty_option' => $this->t('None (original image)'),
    ];

    $element['imagezoom_zoom_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Zoomed Image style'),
      '#default_value' => $this->getSetting('imagezoom_zoom_style'),
      '#empty_option' => $this->t('None (original image)'),
      '#options' => $image_styles,
    ];

    $element['imagezoom_additional'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Additional settings'),
      '#default_value' => $this->getSetting('imagezoom_additional'),
      '#description' => $this->t('Add additional settings. For a list of available options, see the @des. Settings should be added in the following format: <pre>@code</pre>', [
          '@des' => $description_link->toString(),
          '@code' => 'option: value']
      ),
      '#weight' => 20,
      '#element_validate' => array(array($this, 'myElementValidator')),
    ];

    return $element;
  }


  public static function myElementValidator($element, FormStateInterface $form_state)
  {
    // Validate additional setting. Must be in specific format ex. option: value
    $settings_array = explode("\n", $element['#value']);
    foreach ($settings_array as $setting) {
      if (!empty($setting)) {
        if (!preg_match('/^[a-z][a-z0-9-_]*: ?[a-z][a-z0-9-_]*$/i', trim($setting))) {
          $form_state->setErrorByName('imagezoom_additional', t('Additional settings must be in the format "option: value".'));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary()
  {
    $summary = [];
    $image_styles = image_style_options(FALSE);
    unset($image_styles['']);
    $image_zoom_type = $this->getSetting('imagezoom_zoom_type');
    $image_display_setting = $this->getSetting('imagezoom_display_style');
    $image_zoom_setting = $this->getSetting('imagezoom_zoom_style');
    $zoom_types = $this->imagezoomZoomTypes();

    if (isset($image_styles[$image_display_setting])) {
      $summary[] = $this->t('Zoom type: @style', array('@style' => $zoom_types[$image_zoom_type]));
      $summary[] = $this->t('Display image style: @style', array('@style' => $image_styles[$image_display_setting]));
      $summary[] = $this->t('Display zoom style: @style', array('@style' => $image_styles[$image_zoom_setting]));
    } else {
      $summary[] = $this->t('Configure Imagezoom Settings');
    }
    return $summary;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $display_style = $this->getSetting('imagezoom_display_style');
    $zoom_style = $this->getSetting('imagezoom_zoom_style');

    $settings = [
      'zoomType' => $this->getSetting('imagezoom_zoom_type'),
    ];

    $additonal_settings = $this->imagezoomSettingsToArray($this->getSetting('imagezoom_additional'));
    $settings += $additonal_settings;

    $element = [];
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#theme' => 'imagezoom_image',
        '#item' => $item,
        '#display_style' => $display_style,
        '#zoom_style' => $zoom_style,
        '#settings' => $settings,
      ];
      $element['#attached']['drupalSettings']['imagezoom'] = $settings;
    }

    return $element;
  }


  /**
   * Returns an array of available zoom types.
   */
  public function imagezoomZoomTypes()
  {
    $types = array(
      'window' => $this->t('Window'),
      'inner' => $this->t('Inner'),
      'lens' => $this->t('Lens'),
    );

    return $types;
  }

  /**
   * Convert a settings string to an array.
   */
  function imagezoomSettingsToArray($string)
  {
    $settings = array();

    if (!empty($string)) {
      $array = explode("\n", $string);

      foreach ($array as $option) {
        $parts = explode(':', $option);
        if (sizeof($parts) == 2) {
          $key = trim($parts[0]);
          $value = trim($parts[1]);
          $settings[$key] = $value;
        }
      }
    }

    return $settings;
  }
}
