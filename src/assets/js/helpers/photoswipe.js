import $ from 'jquery';

import PhotoSwipe from 'photoswipe';
import PhotoSwipeUI_Default from 'photoswipe/dist/photoswipe-ui-default';

import { getTheIcon } from './utils';

const $body = $('body'),
  photoswipeHtml = `<div id="pswp" class="pswp" tabindex="-1" role="dialog">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
      <div class="pswp__container">
        <div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div>
      </div>
      <div class="pswp__ui pswp__ui--hidden">
        <div class="pswp__top-bar">
          <div class="pswp__counter"></div>
          <button class="pswp__button pswp__button--close" title="Închide (Esc)">${getTheIcon({classes: 'close'})}</button>
          <button class="pswp__button pswp__button--share" title="Share">${getTheIcon({classes: 'share'})}</button>
          <button class="pswp__button pswp__button--fs" title="Fullscreen">${getTheIcon({classes: 'fullscreen'})}</button>
          <button class="pswp__button pswp__button--zoom" title="Zoom">${getTheIcon({classes: 'zoom'})}</button>
          <div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div>
        </div>
        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div>
        <button class="pswp__button pswp__button--arrow--left" title="Înapoi">${getTheIcon({classes: 'arrow-left icon-xl'})}</button>
        <button class="pswp__button pswp__button--arrow--right" title="Înainte">${getTheIcon({classes: 'arrow-right icon-xl'})}</button>
        <div class="pswp__caption"><div class="pswp__caption__center"></div></div>
      </div>
    </div>
  </div>`,
  photoswipeOptions = {
    shareButtons: [
      { id: 'facebook', label: 'Share Facebook', url: 'https://www.facebook.com/sharer/sharer.php?u={{url}}' },
      { id: 'twitter', label: 'Share Twitter', url: 'https://twitter.com/intent/tweet?url={{url}}' },
      { id: 'google-plus', label: 'Share Google+', url: 'https://plus.google.com/share?url={{url}}' }
    ]
  };

function open(event) {
  let $a = $(event.currentTarget),
    href = $a.attr('href');

  // if not *.img
  if ( !isImg(href) ) {
    window.open(href, "_self");
    return;
  }

  let $parentGallery = $a.closest('.gallery'),
    $parentEntry = $(event.delegateTarget),
    $parent = ( $parentGallery.length ) ? $parentGallery : $parentEntry,
    $pictures = $parent.find('a'),
    photoswipeEl = () => {
      if ( document.getElementById('pswp') === null ) $body.append(photoswipeHtml);
      return document.getElementById('pswp');
    },
    photoswipeItems = [],
    gallery = null;

  // gallery id
  $parentEntry.find('.gallery').each((index, gallery) => {
    $(gallery).attr('data-pswp-uid', index + 1);
  });

  // populate photoswipeItems
  $pictures
    .filter((index, a) => isImg(a.href))
    .map((index, a) => {
      let $a = $(a),
        href = $a.attr('href'),
        $size = $a.attr('data-size'),
        size = ($size) ? $size.split('x') : [0, 0],
        title = $a.closest('figure').find('figcaption').html();
      photoswipeItems.push({src: href, w: size[0], h: size[1], title: title});
    });
  if (photoswipeItems.length === 0) throw new Error('photoswipeItems property must not be empty!');

  // populate photoswipeOptions
  photoswipeOptions.index = $pictures.index($a);
  photoswipeOptions.galleryUID = $parent.data('pswp-uid') || 0;

  // gallery
  gallery = new PhotoSwipe(photoswipeEl(), PhotoSwipeUI_Default, photoswipeItems, photoswipeOptions);
  gallery.listen('gettingData', (index, item) => {
    if (item.w == 0 || item.h == 0) {
      let img = new Image();
      img.onload = () => {
        item.w = img.width;
        item.h = img.height;

        gallery.invalidateCurrItems();
        gallery.updateSize(true);
      };

      img.src = item.src;
    }
  });
  gallery.init();
}

function isImg(href) {
  return (href.match(/\.(jpeg|jpg|gif|png)$/) !== null);
}

export { open };
