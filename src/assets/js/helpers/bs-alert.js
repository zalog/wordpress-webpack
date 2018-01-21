import 'bootstrap/js/dist/alert';

function html({content, hasWrapContent, hasClose, classes}) {
  if (!content) throw new Error("content property is required!");

  hasWrapContent = hasWrapContent || false;
  hasClose = hasClose || false;
  classes = (classes) ? ` ${classes}` : ' alert-info';

  var wrapContentBegin = '',
    wrapContentEnd = '',
    btnClose = '';

  if (hasWrapContent) {
    wrapContentBegin = '<div class="container-fluid">';
    wrapContentEnd = '</div>';
  }

  if (hasClose) {
    classes += ' alert-dismissible';
    btnClose = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
  }

  return `<div class="alert fade show${classes}" role="alert">
    ${wrapContentBegin}
      ${btnClose}
      <p>${content}</p>
    ${wrapContentEnd}
  </div>`;
}

function update({$alert, content}) {
  if (!content) throw new Error("content property is required!");

  $alert.find('p').text(content);
}

export { html, update };
