import $ from "jquery";

import 'bootstrap/js/dist/dropdown';

import { imgDefer, commentsAHref, jumpToAnchor, cookieBar, fbApi } from '../helpers/utils';
import { collapsedTools } from '../helpers/bs-collapse';



/**
 * global vars
 */
const fbAppId = null,
  alertCookieContent = 'Acest site folosește cookie-uri, prin continuarea navigării sunteți de acord cu <a href="#">politica de utilizare a cookie-urilor</a>.';



/**
 * defer images
 */
imgDefer();

/**
 * bootstrap collapse tools
 */
var $collapsingNavbar = $('#collapsing-navbar'),
  $collapsingSearch = $('#collapsing-search');
collapsedTools([$collapsingNavbar, $collapsingSearch]);

/**
 * photoswipe
 */
$('.entry-content').on('click', 'a', (event) => {
  event.preventDefault();

  import('../helpers/photoswipe').then((photoswipe) => {
    photoswipe.open(event);
  });
});

/**
 * #commentForm ajax
 */
var $commentForm = $('#commentform');
if ($commentForm.length) {
  $commentForm.on('submit', (event) => {
    event.preventDefault();

    import('../helpers/form-comment').then((comment) => {
      let url = '/wp-json/wp/v2/comments/',
        $form = $(event.currentTarget);

      comment.send({url: url, $form: $form});
    });
  });
}

/**
 * #comments _blank for external url
 */
var $comments = $('#comments');
if ($comments.length) commentsAHref($comments);

/**
 * jump to anchor animation
 */
$('#content').on('click', '.scroll', jumpToAnchor);

/**
 * cookie bar
 */
cookieBar(alertCookieContent);

/**
 * facebook api
 */
fbApi(fbAppId);
