import TrDefaultFieldsHandler from '../../core/framework-logic/components/block-elements/TrDefaultFieldsHandler'

const EditMain = (props) => {
  const { className } = props

  return (
    <div className={`${className}`}>
      <TrDefaultFieldsHandler data={{ ...props }} />
      Nikola - Edit Main
    </div>
  )
}

export default EditMain
