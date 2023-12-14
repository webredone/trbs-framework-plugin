import TrDefaultFieldsHandler from '../../core/framework-logic/components/block-elements/TrDefaultFieldsHandler'

const EditMain = (props) => {
  const { className } = props

  console.log('inside edit main', props)

  return (
    <div className={`${className}`}>
      <TrDefaultFieldsHandler data={{ ...props }} />
    </div>
  )
}

export default EditMain
