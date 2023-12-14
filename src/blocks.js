/* import register_block from './register_block'
import setupBlockPreviewImage from './core/framework-logic/setupBlockPreviewImage'

const blocks_array_config = require('./core/blocks_array.json')
const blocks_array = blocks_array_config.blocks_array

console.log('BLOCKS ARRAY: ', blocks_array)

const block_name_prefix_data = require('./core/config.json')
const block_name_prefix = block_name_prefix_data.BLOCK_PREFIX

// START:REMOVE REDUNDANT OPTIONS FROM RICHTEXT TOOLBAR AND ADD UNDERLINE
;(function (wp) {
  var TextUnderlineButton = function (props) {
    return wp.element.createElement(wp.blockEditor.RichTextToolbarButton, {
      icon: 'admin-customizer',
      title: 'Text Underline',
      onClick: function () {
        props.onChange(
          wp.richText.toggleFormat(props.value, {
            type: `${block_name_prefix}/text-underline`,
          }),
        )
      },
    })
  }

  // wp.richText.unregisterFormatType('core/code')
  // wp.richText.unregisterFormatType('core/strikethrough')
  // wp.richText.unregisterFormatType("core/text-color");
  // wp.richText.unregisterFormatType('core/keyboard')
  wp.richText.registerFormatType(`${block_name_prefix}/text-underline`, {
    title: 'Text Underline',
    tagName: 'u',
    className: 'txt-underline',
    edit: TextUnderlineButton,
  })
})(window.wp)
// END:REMOVE REDUNDANT OPTIONS FROM RICHTEXT TOOLBAR AND ADD UNDERLINE
setupBlockPreviewImage()
// Register custom blocks
blocks_array.forEach((block_folder_name) => {
  register_block({ block_folder_name })
})
 */

import register_block from './register_block'
import setupBlockPreviewImage from './core/framework-logic/setupBlockPreviewImage'

const blocks_array_config = require('./core/blocks_array.json')
const blocks_array = blocks_array_config.blocks_array

console.log('BLOCKS ARRAY: ', blocks_array)

const block_name_prefix_data = require('./core/config.json')
const block_name_prefix = block_name_prefix_data.BLOCK_PREFIX

// START:REMOVE REDUNDANT OPTIONS FROM RICHTEXT TOOLBAR AND ADD UNDERLINE
;(function (wp) {
  var TextUnderlineButton = function (props) {
    return wp.element.createElement(wp.blockEditor.RichTextToolbarButton, {
      icon: 'admin-customizer',
      title: 'Text Underline',
      onClick: function () {
        props.onChange(
          wp.richText.toggleFormat(props.value, {
            type: `${block_name_prefix}/text-underline`,
          }),
        )
      },
    })
  }

  // wp.richText.unregisterFormatType('core/code')
  // wp.richText.unregisterFormatType('core/strikethrough')
  // wp.richText.unregisterFormatType("core/text-color");
  // wp.richText.unregisterFormatType('core/keyboard')
  wp.richText.registerFormatType(`${block_name_prefix}/text-underline`, {
    title: 'Text Underline',
    tagName: 'u',
    className: 'txt-underline',
    edit: TextUnderlineButton,
  })
})(window.wp)
// END:REMOVE REDUNDANT OPTIONS FROM RICHTEXT TOOLBAR AND ADD UNDERLINE
setupBlockPreviewImage()
// Register custom blocks
blocks_array.forEach((block_folder_name) => {
  register_block({ block_folder_name })
})
