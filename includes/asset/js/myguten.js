/*  global wp,my_ajax_object  */
const el = wp.element.createElement;
const { useSelect } = wp.data;
const backgroundSettings = {
  hasRequest: {
    type: 'boolean',
  },
};


const withInspectorControls = wp.compose.createHigherOrderComponent((BlockEdit) => function
addElement(props) {
  const idPost = useSelect((select) => select('core/editor').getCurrentPostId());
  function onClick() {
    props.setAttributes({ hasRequest: true });
    const idAttachment = props.attributes.id;
    const srcAttachment = props.attributes.src;
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function ajax() {
      if (this.readyState === 4 && this.status === 200) {
        const response = JSON.parse(this.response);
        if (response.success) {
          wp.data.dispatch('core/notices').createNotice(
            'success',
            'Job inviato correttamente',
          );
        } else {
          wp.data.dispatch('core/notices').createNotice(
            'error',
            response.data,
          );
        }
      }
    };
    xhttp.open('POST', my_ajax_object.ajax_url, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`action=submitVideo&_ajax_nonce=${my_ajax_object.ajaxnonce}&id_attachment=${idAttachment}&src_attachment=${srcAttachment}&id_post=${idPost}`);
  }

  if (props.name !== 'core/video') {
    return el(
      wp.element.Fragment,
      {},
      el(
        BlockEdit,
        props,
      ),
    );
  }
  return el(
    wp.element.Fragment,
    {},
    el(
      BlockEdit,
      props,
    ),
    el(
      wp.editor.InspectorControls,
      {},
      el(
        wp.components.PanelBody,
        { title: 'Ear2Words' },
        el(
          wp.components.Button,
          {
            disabled: props.attributes.hasRequest,
            name: 'sottotitoli',
            id: props.attributes.id,
            isPrimary: true,
            onClick,
          },
          'ATTIVA SOTTOTITOLI',
        ),
      ),
    ),
  );
}, 'withInspectorControls');

function addAttributes(settings) {
  /*  global lodash  */
  const { assign } = lodash;
  const options = settings;
  options.attributes = assign(settings.attributes, backgroundSettings);
  return options;
}

wp.hooks.addFilter('blocks.registerBlockType', 'ear2words/add-attributes', addAttributes);
wp.hooks.addFilter('editor.BlockEdit', 'ear2words/with-inspector-controls', withInspectorControls);
