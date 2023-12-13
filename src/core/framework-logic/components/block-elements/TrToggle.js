const { ToggleControl, Card, CardHeader, CardBody } = wp.components
const { __ } = wp.i18n

import trUpdateField from '../../helpers/trUpdateField.js'
import TrTooltip from '../TrTooltip.js'

const TrToggle = ({ fieldData }) => {
  const { field_object, meta } = fieldData

  const handleChange = () => {
    const newValue = { ...field_object }
    newValue.checked = !field_object['checked']
    trUpdateField(fieldData, newValue)
  }

  return (
    <Card
      size="small"
      className={`tr-control tr-toggle-control tr-control-name--${meta.field_name}`}
    >
      <CardHeader size="small">
        {__(meta.label)}
        {meta.help && <TrTooltip help tooltip={meta.help} />}
      </CardHeader>
      <CardBody>
        <ToggleControl
          label={__(meta.label)}
          checked={field_object.checked}
          onChange={handleChange}
        />
      </CardBody>
    </Card>
  )
}

export default TrToggle
