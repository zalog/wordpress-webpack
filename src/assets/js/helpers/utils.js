import $ from "jquery";

import Cookies from "js-cookie";

function imgDefer() {
  let imgDefer = document.getElementsByTagName('img');
  for (let i = 0; i < imgDefer.length; i++)
    if (imgDefer[i].getAttribute('data-src'))
      imgDefer[i].setAttribute('src', imgDefer[i].getAttribute('data-src'));
}

function commentsAHref($comments) {
  const a = new RegExp('/' + window.location.host + '/');
  let $links = $comments.find('.comment').find('a');

  $links.map((index, link) => {
    let $link = $(link),
      href = $link.attr('href');
    if (!a.test(href)) {
      $link.click((event) => {
        event.preventDefault();
        event.stopPropagation();
        window.open(href, '_blank');
      });
    }
  });
}

function jumpToAnchor(event) {
  event.preventDefault();

  let $this = $(this),
    $body = $('body'),
    id = $this.data("target") || $this.attr("href") || false,
    $id = $(id),
    target = ($id.length) ? Math.round( $id.offset().top ) : 0;

  $body.animate(
    { scrollTop: target },
    400
  );
}

function cookieBar(content) {
  if (Cookies.get('civicCookieControl')) return;
  import('./bs-alert').then((alert) => {
    let $body = $('body'),
      alertCookieHtml = () => {
        return alert.alertHtml({
          content: content,
          hasWrapContent: true,
          hasClose: true,
          classes: 'alert-warning alert-cookies fixed-bottom'
        });
      };
    $body.append(alertCookieHtml);
    $(alertCookieHtml).on('close.bs.alert', () => {
      Cookies.set('civicCookieControl', 'hide', { expires: 365, path: '/' });
    });
  });
}

function fbApi(appId) {
  /*global FB*/
  window.fbAsyncInit = () => {
    if ( typeof FB === "undefined" ) return;
    FB.init({
      appId: appId,
      xfbml: true,
      version: 'v2.11'
    });
  };
  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
}

function getTheIcon({classes, filename}) {
  /*global settings*/
  filename = ( filename ) || "icons.svg";
  let classesArr = classes.split(" ");

  return `<span class="icon icon-${classes}" role="img"><svg>
    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="${settings['theme-path']}/assets/svg/${filename}#icon-${classesArr[0]}"></use>
  </svg></span>`;
}

export { imgDefer, commentsAHref, jumpToAnchor, cookieBar, fbApi, getTheIcon };
