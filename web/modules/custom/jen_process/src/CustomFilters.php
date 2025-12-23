<?php

namespace Drupal\jen_process;

use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\Entity\Media;
use Drupal\responsive_image\ResponsiveImageStyleInterface;

class CustomFilters {

  public function Card1($matches): string {
    // Custom filter for card1.
    // #\[card-type1:(.*?):img:(.*?):style:(.*?):title:(.*?):text:(.*?):but:(.*?);(.*?):classes:(.*?)\]#i
    $media_id = $matches[2];
//    $image_style_name = $matches[3];
    $responsive_image_style_id = $matches[3];

    $responsive_image_style = \Drupal::entityTypeManager()
      ->getStorage('responsive_image_style')
      ->load($responsive_image_style_id);

    if (!$responsive_image_style instanceof ResponsiveImageStyleInterface) {
      // Handle the case where the responsive image style is not found.
      return '';
    }

    $media = Media::load($media_id);
    if (!$media && $media->hasField('field_media_image')) {
      return '';
    }

    $fid = $media->get('field_media_image')->entity->id();
    $file = File::load($fid);
//    $file_url = ImageStyle::load($image_style_name)->buildUrl($file->getFileUri());


    $responsive_image_render_array = [
      '#theme' => 'responsive_image',
      '#responsive_image_style_id' => $responsive_image_style_id,
      '#uri' => $file->getFileUri(),
      '#alt' => $file->getFilename(),
    ];
    $responsive_image_render_array['#attributes']['class'] =
      [ 'card-img-top', 'rounded-0', 'rounded-top'];

    $img_html = \Drupal::service('renderer')->render($responsive_image_render_array);


    $result =
'<div class="card ' . $matches[8] . '" style="width: ' . $matches[1] . 'rem;">' .
    $img_html .
  '<div class="card-body">
    <h5 class="card-title">' . $matches[4] . '</h5>
    <p class="card-text">' . $matches[5] . '</p>
    <a href="' . $matches[7] . '" class="btn btn-primary">' . $matches[6] . '</a>
  </div>
</div>';
    return $result;
  }

  public function Feature1($matches): string {
//[feature1: icon:$1 :text:$2 :but-text:$3 :but-url:$4 ]

  $result =
'<div class="col-md col-lg feature bg-info-subtle border rounded p-3 m-4">
  <div>' . $matches[1] . '&nbsp;</i></div>' .
     '<p>' . $matches[2] . '</p>' .
     '<a href="' . $matches[3] . '" class="btn btn-primary">' . $matches[4] . '</a>' .
'<p><a class="btn btn-primary" href="' . $matches[4] . '">' . $matches[3] . '</a></p>' .
'</div>';
  return $result;
  }

}
