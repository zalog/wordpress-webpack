import $ from "jquery";

import 'bootstrap/js/dist/modal';

function modalHtml({title, content, footerCall2action, autohide, wrapClasses, classes}) {
  title = (title) ? `<h5 class="modal-title">${title}</h5>` : '';
  content = (content) ? `<div class="modal-body">${content}</div>` : '';
  wrapClasses = (wrapClasses) ? ` ${wrapClasses}` : '';
  classes = (classes) ? ` ${classes}` : '';

  let header = '',
    footer = '',
    $output = null;

  if (!content) throw new Error("content property it's required!");

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

  $output = $(`<div class="modal fade${wrapClasses}"><div class="modal-dialog${classes}" role="document"><div class="modal-content">
      ${header}
      ${content}
      ${footer}
    </div></div></div>`)
    .on('hidden.bs.modal', () => {
      $output.remove();
    });

  if (autohide) {
    $output.on('shown.bs.modal', () => {
      window.setTimeout(() => { $output.modal('hide'); }, autohide);
    });
  }

  return $output;
};

export { modalHtml };
