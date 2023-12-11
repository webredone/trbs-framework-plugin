const { __ } = wp.i18n
const { InspectorControls } = wp.blockEditor
const { PanelBody } = wp.components
import TrDefaultFieldsHandlerSidebar from '../../core/framework-logic/components/block-elements/TrDefaultFieldsHandlerSidebar'

const EditSidebar = props => {
  return (
    <InspectorControls>
      <PanelBody
        className="tr-sidebar-fields"
        title={__('Additional Controls', 'tr_blocks')}
      >
        <TrDefaultFieldsHandlerSidebar data={{ ...props }} />
      </PanelBody>
    </InspectorControls>
  )
}

export default EditSidebar
