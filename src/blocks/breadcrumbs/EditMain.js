const { Fragment } = wp.element

import TrDefaultFieldsHandler from '../../core/framework-logic/components/block-elements/TrDefaultFieldsHandler'

const EditMain = (props) => {
  const { className } = props

  return (
    <Fragment className={`${className}`}>
      <h1>test breadcrumbs</h1>
      <TrDefaultFieldsHandler data={{ ...props }} />
    </Fragment>
  )
}

export default EditMain
