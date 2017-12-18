/* global settings */
import $ from 'jquery';
import axios from 'axios';

import { alertHtml, alertUpdate } from './bs-alert';



var $comments = $('#comments');



function send({url, $form}) {
  const config = {
      headers: {'X-WP-NONCE': settings.nonce}
    },
    paramsMap = {
      'comment_post_ID': 'post',
      'comment_parent': 'parent',
      'author': 'author_name',
      'email': 'author_email',
      'url': 'author_url',
      'comment': 'content'
    };

  var params = $form.serialize(),
    params = replaceAll(params, paramsMap),
    $btnSubmit = $form.find('#submit');

  $btnSubmit.attr('disabled', true);

  axios.post(url, params, config)
    .then((response) => {
      appendComment(response.data);
      resetForm($form);
    })
    .catch((error) => {
      let message = error.response.data.message,
        $alert = $form.find('.alert'),
        $btnClose = $form.prev().find('#cancel-comment-reply-link'),
        $commentsList = $comments.find('.list-comments'),
        $linkReply = $commentsList.find('.comment-reply-link');

      if (!$alert.length)
        appendAlert({$form, message});
      else
        alertUpdate({$alert, message});

      if ($btnClose.length) {
        $btnClose.off();
        $btnClose.one('click', () => {
          resetForm($form);
        });
      }

      if ($linkReply.length) {
        $linkReply.off();
        $linkReply.one('click', () => {
          resetForm($form);
        });
      }

      $btnSubmit.attr('disabled', false);
    });
}

function appendAlert({$form, message}) {
  let alert = alertHtml({
    content: message,
    classes: 'alert-warning',
    hasClose: true
  });

  $form.find('#submit').before(alert);
}

function appendComment(comment) {
  let commentAvatar = `<img src="${comment['author_avatar_urls']['48']}" class="avatar">`,
    commentAuthor = comment.author_name,
    $commentsList = $comments.find('.list-comments');

  if (comment.author_url) {
    commentAvatar = `<a href="${comment.author_url}" rel="external nofollow">${commentAvatar}</a>`;
    commentAuthor = `<a href="${comment.author_url}" rel="external nofollow">${commentAuthor}</a>`;
  }

  let commentHtml = `<li id="comment-${comment.id}" class="comment media highlight">
    <div class="mr-3">
      ${commentAvatar}
    </div>
    <div class="media-body" id="div-comment-${comment.id}">
      <h4 class="media-heading">${commentAuthor}</h4>
      <div class="comment-content">${comment.content.rendered}</div>
    </div>
  </li>`;

  if (comment.parent) {
    $commentsList.find('#comment-' + comment.parent).append(`<ol class="children">${commentHtml}</ol>`);
    $commentsList.find('#cancel-comment-reply-link').trigger('click');
  } else {
    if ($commentsList.length)
      $commentsList.append(commentHtml);
    else
      $comments.prepend(`<div class="card-body"><ol class="list-comments">${commentHtml}</ol></div>`);
  }
}

function resetForm($form) {
  $form.find('#comment').val('');
  $form.find('#submit').attr('disabled', false);
  $form.find('.alert').remove();
}

function replaceAll(str, mapObj){
  var re = new RegExp(Object.keys(mapObj).join("|"),"gi");
  return str.replace(re, (matched) => mapObj[matched]);
}

export { send };
