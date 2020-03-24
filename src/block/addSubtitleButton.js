/*  global wp,ear2words_button_object  */
const backgroundSettings = {
  hasRequest: {
    type: 'boolean',
  },
};

const withInspectorControls = wp.compose.createHigherOrderComponent((BlockEdit) => function
addElement(props) {
  const { Fragment } = wp.element;
  const { InspectorControls } = wp.editor;
  const { PanelBody, Button } = wp.components;
  function onClick() {
    props.setAttributes({ hasRequest: true });
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
            'ERRORE, job non inviato correttamente',
          );
        }
      }
    };
    xhttp.open('POST', ear2words_button_object.ajax_url, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(`action=submitVideo&_ajax_nonce=${ear2words_button_object.ajaxnonce}`);
  }
  if (props.name !== 'core/video') {
    return (
      <Fragment>
        <BlockEdit {...props} />
      </Fragment>
    );
  }
  return (
    <Fragment>
      <BlockEdit {...props} />
      <InspectorControls>
        <PanelBody title='Ear2words'>
          <Button disabled={props.attributes.hasRequest} name='sottotitoli' id={props.attributes.id} isPrimary onClick={onClick}>
            ATTIVA SOTTOTITOLI
          </Button>
        </PanelBody>
      </InspectorControls>
    </Fragment>
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
