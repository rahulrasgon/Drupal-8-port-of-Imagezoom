<?php

namespace Drupal\imagezoom_gallery\Plugin\Field\FieldFormatter;

use Drupal\imagezoom\Plugin\Field\FieldFormatter\Imagezoom;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldFormatter(
 *  id = "imagezoomgallery",
 *  label = @Translation("Image Zoom Gallery"),
 *  field_types = {"image"}
 * )
 */
class ImagezoomGallery extends Imagezoom
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
      'imagezoom_thumb_style' => '',
      'imagezoom_additional' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state)
  {

    $element = parent::settingsForm($form, $form_state);
    $image_styles = image_style_options(FALSE);
    $element['imagezoom_thumb_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Thumbnail image style'),
      '#options' => $image_styles,
      '#empty_option' => $this->t('None (original image)'),
      '#default_value' => $this->getSetting('imagezoom_thumb_style'),
    ];

    return $element;
  }


  /**
   * {@inheritdoc}
   */
  public function settingsSummary()
  {
    $summary = parent::settingsSummary();
    $image_styles = image_style_options(FALSE);
    // Unset possible 'No defined styles' option.
    unset($image_styles['']);
    // Styles could be lost because of enabled/disabled modules that define
    // their styles in code.
    $summary[] = t('Thumbnail image style: @style', array(
      '@style' => isset($image_styles[$this->getSetting('imagezoom_thumb_style')]) ?
        $image_styles[$this->getSetting('imagezoom_thumb_style')] : 'original',
    ));
    return $summary;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $display_style = $this->getSetting('imagezoom_display_style');
    $zoom_style = $this->getSetting('imagezoom_zoom_style');
    $thumb_style = $this->getSetting('imagezoom_thumb_style');

    $settings = [
      'zoomType' => $this->getSetting('imagezoom_zoom_type'),
      'gallery' => 'imagezoom-thumb-wrapper',
    ];

    $additonal_settings = parent::imagezoomSettingsToArray($this->getSetting('imagezoom_additional'));
    $settings += $additonal_settings;

    $element = [];
    $element = [
      '#theme' => 'imagezoom_gallery',
      '#items' => $items,
      '#display_style' => $display_style,
      '#zoom_style' => $zoom_style,
      '#thumb_style' => $thumb_style,
      '#settings' => $settings,
    ];
    $element['#attached']['drupalSettings']['imagezoom'] = $settings;

    return $element;
  }


}
