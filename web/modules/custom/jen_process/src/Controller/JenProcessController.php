<?php

declare(strict_types=1);

namespace Drupal\jen_process\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\media\Entity\Media;

/**
 * Returns responses for Jen process routes.
 */
final class JenProcessController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $media_id = 8; // Replace with the actual media entity ID
    $media = Media::load($media_id);
    $display_options = [
      'label' => 'hidden', // Hide the field label
      'type' => 'responsive_image', // Use the responsive image formatter
      'settings' => [
        'responsive_image_style' => 'hero_large', // Replace with your style name
      ],
    ];

    if ($media && $media->hasField('field_media_image')) { // Assuming 'field_media_image' is your image field
      $responsive_image_render_array = $media->field_media_image->view($display_options);
    } else {
      // Handle cases where the media entity or field doesn't exist
      $responsive_image_render_array = [];
    }

    $rendered_html = \Drupal::service('renderer')->render($responsive_image_render_array);

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $rendered_html,
    ];

    return $build;
  }

}
