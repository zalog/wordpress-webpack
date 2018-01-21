import $ from "jquery";

import 'bootstrap/js/dist/modal';

function show({title, content, footerCall2action, autohide, id, classes, classesDialog}) {
  if (!content) throw new Error("content property is required!");

  title = (title) ? `<h5 class="modal-title">${title}</h5>` : '';
  content = (content) ? `<div class="modal-body">${content}</div>` : '';
  id = (id) ? `${id}` : 'modal-0';
  classes = (classes) ? ` ${classes}` : '';
  classesDialog = (classesDialog) ? ` ${classesDialog}` : '';

  let $getModal = $("#" + id);

  // modal exists
  if ($getModal.length) {
    $getModal.modal('hide');
    return;
  }

  let header = '',
    footer = '',
    $modal = null;

  if (title) {
    header = `<div class="modal-header">
      ${title}
      <button type="button" class="close" data-dismiss="modal" aria-label="Închide"><span aria-hidden="true">&times;</span></button>
    </div>`;
  }
  if (footerCall2action) {
    footer = `<div class="modal-footer">
      <button type="button" class="btn btn-primary submit">${footerCall2action}</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Închide</button>
    </div>`;
  }

  $modal = $(`<div id="${id}" class="modal fade${classes}"><div class="modal-dialog${classesDialog}" role="document"><div class="modal-content">
      ${header}
      ${content}
      ${footer}
    </div></div></div>`)
    .on('hidden.bs.modal', () => {
      $modal.remove();
    });

  if (autohide) {
    $modal.on('shown.bs.modal', () => {
      window.setTimeout(() => { $modal.modal('hide'); }, autohide);
    });
  }

  $modal.modal('show');

  return $modal;
};

export { show };
