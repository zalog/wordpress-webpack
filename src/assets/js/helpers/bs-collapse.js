import $ from "jquery";

import 'bootstrap/js/dist/collapse';

function collapsedTools(containers) {
  if (!containers) return;

  containers.forEach(($container) => {
    let containerId = '#' + $container.attr('id');

    // close other collapsed
    $(containerId).on('shown.bs.collapse', () => {
      containers.forEach(($container) => {
        let otherId = '#' + $container.attr('id');

        if (containerId != otherId)
          $(otherId).collapse('hide');
      });
    });

    // focus if search
    if (containerId.search('search') !== -1)
      $container.on('shown.bs.collapse', () => {
        $container.find('.form-control').focus();
      });

    // hide if click outside
    window.addEventListener('mouseup', (event) => {
      if (!$(containerId).is(event.target) && $(containerId).has(event.target).length === 0)
        $(containerId).collapse('hide');
    });
  });
}

export { collapsedTools };
