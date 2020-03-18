/*  global wp  */
const el = wp.element.createElement;
const backgroundSettings = {
  hasRequest: {
    type: 'boolean',
  },
};

const withInspectorControls = wp.compose.createHigherOrderComponent((BlockEdit) => function
addElement(props) {
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
          'ATTIVA SOTTOTITOLI',
          {
            disabled: props.attributes.hasRequest,
            name: 'sottotitoli',
            id: props.attributes.id,
            onClick() {
              props.setAttributes({ hasRequest: true });
              return el(
                wp.components.notice,
              );
            },
          },
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
